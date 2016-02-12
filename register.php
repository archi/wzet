<?php
$k = "";
if (isset ($_GET['k']))
    $k = $_GET['k'];

include ("inc/config.php");

if ($_CONFIG["REGISTER_KEY"] == "-1" || !isset ($_CONFIG["REGISTER_KEY"])) {
    die ("Registration is disabled in inc/config.php!");
}

if ($k != $_CONFIG["REGISTER_KEY"]) {
    die ("Bad key!");
}

if ($_CONFIG["SHOW_ERRORS"]) {
    ini_set ('display_errors', 1); 
    ini_set ('display_startup_errors', 1); 
    error_reporting (-1);
}

$user = "";
$name = "";
$mail = "";

$ok = true;
if (isset ($_POST['user'])) {
    $user = $_POST['user'];
    $pw0 = $_POST['pw0'];
    $pw1 = $_POST['pw1'];
    $name = $_POST['name'];
    $mail = $_POST['mail'];

    if ($pw0 != $pw1) {
        print ("Passw&ouml;rter stimmen nicht &uuml;berein!<br>");
        $ok = false;
    }

    if (strlen ($user) < 2) {
        print ("Login ist zu kurz (min. 2 Zeichen)!<br>");
        $ok = false;
    }

    if (strlen ($mail) < 7) {
        print ("E-Mail ist zu kurz (min. 7 Zeichen)!<br>");
        $ok = false;
    }

    if (strlen ($pw0) < 6) {
        print ("Passwort ist zu kurz (min. 6 Zeichen)!<br>");
        $ok = false;
    }

    if (strlen ($name) < 5) {
        print ("Name ist zu kurz (min. 5 Zeichen; @17: Ich kanns in der DB anpassen ;))<br>");
        $ok = false;
    }
    
    $db = new SQLite3 ($_CONFIG["DATABASE"]);
    $q = $db->prepare ("SELECT ID FROM Users WHERE Login = :1");
    $q->bindParam (":1", $user);
    $r = $q->execute ();
    if ($r->fetchArray ()) {
        print ("Login ist bereits in Verwendung!");
        $ok = false;
    }
    $q->reset ();

    if ($ok) {
        require_once ("inc/functions.php");
        $hash = hashPassword ($pw0); 

        $q = $db->prepare ("INSERT INTO Users (Login, Password, Mail, Name, Konto) VALUES (:1, :2, :3, :4, :5);");
        $q->bindParam (":1", $user);
        $q->bindParam (":2", $hash);
        $q->bindParam (":3", $mail);
        $q->bindParam (":4", $name);
        $q->bindValue (":5", 0);
        $q->execute ();

        print ("Nutzer angelegt. <a href='index.php'>Zum Login</a>");
    } else {
        print ("<b>Mindestens ein Fehler!<b><br>");
    }
} else {
    $ok = false;
}
if (!$ok) {
    print <<<EOP
<form method='post' action='register.php?k=$key'>
<table>
<tr><td>Login:</td><td><input type='text' name='user' value='$user'></td><td>(Min. 2 Zeichen)</td></tr>
<tr><td>Passwort:</td><td><input type='password' name='pw0'></td><td>(Min. 6 Zeichen)</td></tr>
<tr><td>Passwort (nochmal):</td><td><input type='password' name='pw1'><td></td></td></tr>
<tr><td>Name:</td><td><input type='text' name='name' value='$name'></td><td>(Min. 5 Zeichen)</td></tr>
<tr><td>E-Mail:</td><td><input type='text' name='mail' value='$mail'></td><td>(Min. 7 Zeichen)</td></tr>
</table>

<input type='submit'>
</form>
<b>Passwort wird sicher gehasht (16 Runden SHA512 mit 64 Bit Salt):</b>
<pre>
    \$random = openssl_random_pseudo_bytes (8);
    \$random = base64_encode (\$random);
    \$hash = \$passwort . \$random;
    for (\$i = 0; \$i < 16; \$i++) {
        \$hash = hash("sha512", \$hash);
    }
    \$store_in_database = \$random . ":" . \$hash;
</pre>
EOP;
}
?>
