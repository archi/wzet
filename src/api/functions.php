<?php
function addToLog ($msg, $data) {
    global $_DB;
    global $_USER;
    $q = $_DB->prepare ("INSERT INTO MessageLog (By, Msg, Data) VALUES (:1, :2, :3);");
    $q->bindParam (":1", $_USER['ID']);
    $q->bindParam (":2", $msg);
    $q->bindParam (":3", $data);
    $q->execute ();
}

function getOrDie ($x) {
    if (!isset ($_GET[$x])) {
        die ("Expected GET field not found!");
    }

    return $_GET[$x];
}

function postOrDie ($x) {
    if (!isset ($_POST[$x])) {
        die ("Expected POST field not found!");
    }

    return $_POST[$x];
}

function userFlag ($x) {
    global $_USER;
    $f = $_USER['Flags'];
    $f &= $x;
    return $f == $x;
}
?>
