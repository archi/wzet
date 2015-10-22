<?php
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (-1);

$in_index = 1;

include ('api/core.php');
include ('api/functions.php');
?>
<h1>Wer ist dran mit bezahlen?</h1>
<a href='event.php' >Neues Kochevent</a><br>
<a href='users.php' >Nutzerliste</a><br>
<a href='log.php'   >Protokoll</a><br>
<a href='logout.php'>Ausloggen</a><br>

<?php
if (userFlag(1)) {
    print <<<EOP
<hr>
Admin:<br>
<a href='sqladmin.php'>SQLite</a><br>
<a href='reset.php'>RESET DB</a><br>
<a href='mail.php?m=#### E-MAIL PREVIEW KEY HERE ####'>Mail Vorschau</a> (<a href='mail.php?m=#### CRON E-MAIL SECRET KEY HERE ####'>Senden</a>)<br>
EOP;
}
include ("api/foot.php");
?>
