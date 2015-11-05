<?php
$k = "";
if (isset ($_GET['m']))
    $k = $_GET['m'];

include "inc/config.php";

/**
 * Check key
 */
if ($k != $_CONFIG["MAIL_CRON_KEY"]
 && $k != $_CONFIG["MAIL_PREVIEW_KEY"]) {
    die ("Bad key!");
}

if ($k == "") {
    die ("Functionality disabled in inc/config.php!");
}

$preview = false;
if ($k == $_CONFIG["MAIL_PREVIEW_KEY"]) {
    $preview = true;
}

/**
 * Try to show errors
 */
if ($_CONFIG["SHOW_ERRORS"]) {
    ini_set ('display_errors', 1); 
    ini_set ('display_startup_errors', 1); 
    error_reporting (-1);
}

$db = new SQLite3 ($_CONFIG["DATABASE"]);
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
    $mail .= $data[0] . ": " . $data[1] . "\n";
}

if ($k == $tk) {
    print ("<pre>$mail</pre>");
    exit (0);
}

print ("Not implemented, yet!");
?>
