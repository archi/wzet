<?php
include ("inc/core.php");
include ("inc/functions.php");

/**
 * Check the event title
 */
$title = htmlspecialchars (postOrDie ("name"));

//expected event id:
$eeid = getOrDie ("eeid");

/**
 * Check the total to be a float
 */
$total = postOrDie ("total");
$users = array_keys (postOrDie ("user"));
$payer = postOrDie ("payer");

$total_f = floatval (str_replace (",", ".", $total));
if ($total_f == 0) {
    die ("Fatal: Bad total (not a float)!");
}
$user_c = sizeof ($users);


/**
 * Check specified user IDs vs DB
 */
$q = $_DB->prepare ("SELECT ID, Name FROM users WHERE ID = :1;");

$q->bindParam (":1", $payer);
$r = $q->execute ();
if (!($f = $r->fetchArray ())) {
    die ("Fatal: Bad payer ID!");
}
$payer_name = $f[1];
$q->reset();

/**
 * Get the +X for ATTENDING users and get usernames with appropriate pluses
 */
$plus = postOrDie ("plus");
$pp = 0;
$user_names = array();
foreach ($users as $u) {
    $pp += $plus[$u];
    $q->bindParam(":1", $u);
    if (!($f = $r->fetchArray ())) {
      die ("Fatal: Bad user ID!");
    }
    $user_names[$f[0]] = $f[1];
    if ($plus[$u]) {
      $user_names[$f[0]] .= " +" . $plus[$u];
    }
    $q->reset();
}
$user_c += $pp;

//make sure we have no +X for NON-ATTENDING users:
if (array_sum ($plus) != $pp) {
    die ("Specified a +X for someone who is not attending!");
}

/**
 * Per user cost
 */
$per_user =  ceil(($total_f/(float)$user_c) * 100);

$total_eff = $per_user * $user_c; ?>




Rechnung: <?php print $total ?> &euro; gerundet auf <?php print ($total_eff / 100) ?> &euro;<br>
Bezahlt: <?php print $payer_name ?><br>
Kosten pro Person: <?php print ($per_user/100) ?>&euro;<br>;

Es essen mit:
<ul>

<?php foreach ($users as $u) { ?>
    <li>
      <?php print $user_names[$u]; ?>
    </li>
<?php } ?>
</ul>
Teilnehmer Gesamt: <?php print $user_c; ?>
<br>


<?php $_DB->query ("BEGIN TRANSACTION;");

$attendees = array();
$q = $_DB->prepare ("UPDATE Users SET Konto = Konto - :1 WHERE ID = :2;");
foreach ($users as $u) {
    $q->bindValue (":1", $per_user * (1 + $plus[$u]));
    $q->bindParam (":2", $u);
    $q->execute ();
    $q->reset ();
    array_push ($attendees, "$u+".$plus[$u]);
}

$ins = $_DB->prepare ("INSERT INTO Events (Title, Payer, Attendees) VALUES (:1, :2, :3);");
$ins->bindParam (":1", $title);
$ins->bindParam (":2", $payer);
$ins->bindValue (":3", implode (",", $attendees));

$ins->execute ();

$event_id = $_DB->lastInsertRowID();
$ins->reset ();

$q->bindValue (":1", $total_eff * (-1));
$q->bindParam (":2", $payer);
$q->execute ();
$q->reset ();

if ($event_id != $eeid) {
    print ("<br><b>Achtung: Die erwartete Event ID entspricht nicht der erhaltenen!<br>Entweder eine Kollision, oder du hast F5 gedr&uuml;ckt.<br>Breche SQLite Transaktion ab.</b>");
    $_DB->query ("ROLLBACK TRANSACTION");
    exit ();
}

$q = $_DB->prepare ("INSERT INTO Payed (EventID, UserID, Amount, Reason) VALUES (:1, :2, :3, 'Initial');");
$q->bindParam (":1", $event_id);
$q->bindParam (":2", $payer);
$q->bindParam (":3", $total_eff);
$q->execute ();
$q->reset ();

addToLog ("Added Event", $event_id);

$_DB->query ("COMMIT TRANSACTION");
?>
<b>Event eingetragen!</b>
<?php include ("inc/foot.php");
?>
