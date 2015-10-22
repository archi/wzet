<?php
setcookie ("kochen_uid", 0, -1, "/~sebastian/");
setcookie ("kochen_sid", 0, -1, "/~sebastian/");
unset ($_COOKIE['kochen_uid']);
unset ($_COOKIE['kochen_sid']);
print ("Ausgeloggt!<br><br>");
include ('api/core.php');
?>
