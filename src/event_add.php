<?php
include ("api/core.php");
include ("api/functions.php");

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
 * Get the +X for ATTENDING users!
 */
$plus = postOrDie ("plus");
$pp = 0;
foreach ($users as $u) {
    $pp += $plus[$u];
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

/**
 * Check specified user IDs vs DB
 */
$q = $_DB->prepare ("SELECT ID, Name FROM users WHERE ID = :1;");

$q->bindParam (":1", $payer);
$r = $q->execute ();
if (!($f = $r->fetchArray ())) {
    die ("Fatal: Bad payer ID!");
}
$total_eff = $per_user * $user_c;
print ("Rechung: $total &euro; gerundet auf ". ($total_eff / 100) . "&euro;<br>\n");
print ("Bezahlt: " . $f[1] . "<br>\n");
print ("Kosten pro Person: ".($per_user/100)."&euro;<br>\n");

$q->reset ();
print ("Es essen mit:<ul>\n");
foreach ($users as $u) {
    $q->bindParam (":1", $u);
    $r = $q->execute ();
    if (!($f = $r->fetchArray ())) {
        die ("Fatal: Bad user ID!");
    }
    print ("<li>".$f[1]);
    if ($plus[$u] != 0) {
        print (" +".$plus[$u]);
    }
    print ("</li>\n");
    $q->reset ();
}
print ("</ul>");
print ("Teilnehmer Gesamt: $user_c<br>\n");
$_DB->query ("BEGIN TRANSACTION;");

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

print ("<b>Event eingetragen!</b>");
include ("api/foot.php");
?>
