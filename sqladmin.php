<?php

/**
 * This is a crude hack to protect the phpliteadmin from unauthenticated users.
 *
 * You should also set a password in phpliteadmin, else all of your users can manipulate the database!!
 *
 * This is a dev feature and you don't need this for productive environments!
 */

if (true) {
    include ("inc/config.php");

    if (!isset ($_CONFIG["SECRET"]) || $_CONFIG["SECRET"] == "") {
        die ("No SECRET set in inc/config.php!");
    }

    if (!isset ($_COOKIE['kochen_sid']) || !isset ($_COOKIE['kochen_uid'])) {
        die ("Cookies not set, no access to sqladmin!");
    }

    $x = hash ("sha512", $_CONFIG["SECRET"] . $_COOKIE['kochen_uid']);

    if ($x != $_COOKIE['kochen_sid']) {
        die ("Nicht eingeloggt (Cookie nicht OK).");
    }

    unset ($x);
}

print ("You should add the phpliteadmin source to this file!");
?>
