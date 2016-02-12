<?php
//don't print an overview
$in_index = 1;

include ('inc/core.php');
include ('inc/functions.php');

print <<<EOP
<h1>Wer ist dran mit bezahlen?</h1>
<a href='event.php' >Neues Kochevent</a><br>
<a href='users.php' >Nutzerliste</a><br>
<a href='log.php'   >Protokoll</a><br>
<a href='logout.php'>Ausloggen</a><br>
EOP;

if (userFlag(1)) {
    print ("<hr>"
        . "Admin:<br>"
        . "<a href='sqladmin.php'>SQLite</a><br>"
        . "<a href='reset.php'>RESET DB</a><br>"
    );

    if ($_CONFIG["MAIL_PREVIEW_KEY"] != "") {
        print ("<a href='mail.php?m=".($_CONFIG["MAIL_PREVIEW_KEY"])."'>Mail Vorschau</a>");

        if ($_CONFIG["MAIL_CRON_KEY"] != "")
            print ("(<a href='mail.php?m=".($_CONFIG["MAIL_CRON_KEY"])."'>Senden</a>)");

        print ("<br>");
    }
}

include ("inc/foot.php");
?>
