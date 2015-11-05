<?php
include ("inc/core.php");
include ("inc/functions.php");


if (!userFlag (1)) {
    die ("You are not an admin!");
}

if (!isset ($_GET['sure'])) {
    die ("<a href='reset.php?sure=1'>Are you sure?</a>");
}

print ("Resetting values to 0...");

$_DB->query ("UPDATE Users SET Konto = 0;");
$_DB->query ("DELETE FROM MessageLog;");
$_DB->query ("DELETE FROM Payed;");
$_DB->query ("DELETE FROM Events;");

addToLog ("Reset Database to 0", "");
