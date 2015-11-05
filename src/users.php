<?php
include ("inc/core.php");

$q = $_DB->query ("SELECT ID,Name,Login,Konto FROM Users;");

print <<<EOP
<table>
<tr><th>User</th><th>Login</th><th>Konto</th></tr>
EOP;

while ($data = $q->fetchArray ()) {
    $u = $data['Name'];
    $d = $data['Login'];
    $m = $data['Konto']/100;
    print <<<EOP
<tr>
 <td>$u</td>
 <td>$d</td>
 <td>$m &euro;</td>
</tr>
EOP;
}

print ("</table>");

include ("inc/foot.php");
?>
