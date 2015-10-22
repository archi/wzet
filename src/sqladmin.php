<?php

if (true) {
    $_SECRET = "#### SECRET HERE ####";
    if (!isset ($_COOKIE['kochen_sid']) || !isset ($_COOKIE['kochen_uid'])) {
        die ("Cookies not set!");
    }
    $x = hash ("sha512", $_SECRET . $_COOKIE['kochen_uid']);

    if ($x != $_COOKIE['kochen_sid']) {
        die ("Nicht eingeloggt (Cookie nicht OK).");
    }
    unset ($x);
    unset ($_SECRET);
}

print ("You should add the phpliteadmin source to this file!");
?>
