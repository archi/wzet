<?php
$k = "";
if (isset ($_GET['m']))
    $k = $_GET['m'];

$key = "#### CRON E-MAIL SECRET KEY HERE ####";
$tk = "#### E-MAIL PREVIEW KEY HERE ####";

if ($k != $key && $k != $tk) {
    die ("Bad key!");
}

ini_set ('display_errors', 1); 
ini_set ('display_startup_errors', 1); 
error_reporting (-1);


$db = new SQLite3 ("api/db/kochen.sqlite");
$q = $db->query ("SELECT Name,Konto FROM Users ORDER BY Konto ASC;");

function row () {
    global $q;
    $data = $q->fetchArray ();
    return $data[0] . ": " . ($data[1]/100);
}

$mail = "Hallo Kochgruppe,\n"
    . "hier die aktuellen Topkandidaten:\n"
    . "\n"
    . "Platz 1: " . row () . "\n"
    . "Platz 2: " . row () . "\n"
    . "Platz 3: " . row () . "\n"
    . "\n"
    . "Einigt euch bitte, wer einkaufen faehrt und wer bezahlt.\n"
    . "\n\n"
    . "Derzeitiger Stand:\n";

while ($data = $q->fetchArray ()) {
    $mail .= $data[0] . ": " . $data[1];
}

if ($k == $tk) {
    print ("<pre>$mail</pre>");
    exit ();
}

print ("Not implemented, yet!");
?>
