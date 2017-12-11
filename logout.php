<?php
include ('inc/config.php');
setcookie ("kochen_uid", 0, -1, $_CONFIG["COOKIE_PATH"]);
setcookie ("kochen_sid", 0, -1, $_COOKIE["COOKIE_PATH"]);
unset ($_COOKIE['kochen_uid']);
unset ($_COOKIE['kochen_sid']);
print ("Ausgeloggt!<br><br>");
include ('inc/core.php');
?>
