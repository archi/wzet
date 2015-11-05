<?php
include ("inc/core.php");

$q = $_DB->query ("SELECT ID,Login FROM Users;");
$users = array ();
while ($u = $q->fetchArray ()) {
    $users[$u[0]] = $u[1];
}

function user ($id) {
    global $users;
    if (isset ($users[$id]))
        return $users[$id];

    return "&lt;Unbekannt: $id&gt;";
}

$q = $_DB->query ("SELECT * FROM MessageLog;");

print <<<EOP
<table>
<tr><th>Nutzer</th><th>Wann</th><th>Log</th></tr>
EOP;

while ($data = $q->fetchArray ()) {
    $u = user ($data['By']);
    $d = $data['When'];
    $m = $data['Msg'];
    print <<<EOP
<tr>
 <td>$u</td>
 <td>$d</td>
 <td>$m</td>
</tr>
EOP;
}

print ("</table>");

include ("inc/foot.php");
?>
