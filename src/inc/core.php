<?php
/**
 * Open database
 */

ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (-1);

$_DB = new SQLite3 ("api/db/kochen.sqlite");

if ($_DB->lastErrorCode () != 0) {
    die ("Fatal DB Error!");
}

$_SECRET = "#### SECRET HERE ####";

$ok = false;
$msg = "keine";
$_USER = false;

if (isset($_POST['user']) and isset ($_POST['pass'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    $q = $_DB->prepare ("SELECT * FROM users WHERE Login = :1;");
    $q->bindValue (":1", $user);

    $r = $q->execute ();

    if ($r && $_USER = $r->fetchArray ()) {
        $pw = explode (":", $_USER['Password']);
        $hash = $pw[0];
        $stored = $pw[1];
        
        $hash = $pass . $pw[0];
        for ($i = 0; $i < 16; $i++) {
            $hash = hash("sha512", $hash);
        }

        if ($stored == $hash) {
            $ok = true;
        } else {
            $_USER = false;
        }
    }
   
    if (!$ok) { 
        $random = openssl_random_pseudo_bytes (8);
        $random = base64_encode ($random);
        $hash = $pass . $random;
        for ($i = 0; $i < 16; $i++) {
            $hash = hash("sha512", $hash);
        }
        $hash = $random . ":" . $hash;
        $msg = "Konnte nicht authentifizieren! <!-- Benutze diesen Hash f&uuml;r einen neuen Account: $hash -->";
    } else {

        $x = hash ("sha512", $_SECRET . $_USER['ID']);
        $tout = 60*60*24*30 + time ();
        setcookie ('kochen_uid', $_USER['ID'], $tout, "/~sebastian/");
        setcookie ('kochen_sid', $x, $tout, "/~sebastian/");
        unset ($x);
    }
} else if (isset ($_COOKIE['kochen_sid']) && isset ($_COOKIE['kochen_uid'])) {
    $x = hash ("sha512", $_SECRET . $_COOKIE['kochen_uid']);

    if ($x == $_COOKIE['kochen_sid']) {
        $q = $_DB->prepare ("SELECT * FROM users WHERE ID = :1;");
        $q->bindValue (":1", $_COOKIE['kochen_uid']);
        $r = $q->execute ();
        if ($r && $_USER = $r->fetchArray ()) {
            $ok = true; 
        } else {
            setcookie ("kochen_uid", null, -1, "/~sebastian/");
            setcookie ("kochen_sid", null, -1, "/~sebastian/");
            unset ($_COOKIE['kochen_uid']);
            unset ($_COOKIE['kochen_sid']);
            $_USER = false;
            $msg = "Cookie OK, aber interner Fehler beim Laden der Userdaten aus der DB";
        }
    } else {
        $msg = "Nicht eingeloggt (Cookie nicht OK).";
    }
} else {
    $msg = "Nicht eingeloggt (kein Cookie).";
}

if (!$ok) {
    print <<<EOHTML
<form method='post' action='index.php'>
Bitte Einloggen:<br>
<input type='text' name='user' size='32'><br>
<input type='password' name='pass' size='32'><br>
<input type='submit'>
</form>
EOHTML;
print ("Login Meldung: <b>$msg</b>");
exit ();
}

unset ($_USER["Password"]);
unset ($_USER[2]);
unset ($ok);
unset ($msg);
unset ($_SECRET);

global $_USER;
global $_DB;

?>

<html>
  <head>
    <title>Kochen</title>
    <link rel='stylesheet' href='base.css'>
  </head>
  <body>

<?php
 if (!isset($in_index))
     print("<a href='index.php'>Zur &Uuml;bersicht</a><hr>");
?>
<!-- end of core.php -->
