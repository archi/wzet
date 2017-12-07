<?php
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (-1);

include ("inc/core.php");

$r = $_DB->query ("SELECT seq FROM SQLITE_SEQUENCE WHERE name='Events'")->fetchArray ();
$expected_event_id = $r[0] + 1;
?>

<h1>F&uuml;ge ein Event hinzu:</h1>

<div id='nojs'>
<!-- <input type='submit' value='Submit ohne JS check' id='nojs'> -->
<b>Bitte JS f&uuml;r Eingabechecks aktivieren ;-)</b>
</div>
<br>

<form method='post' action='event_add.php?eeid=<?php print $expected_event_id ?>' id='f0'>
<table>
    <tr>
    <td>Event:</td>
    <td><input type='text' name='name' width='40' value='Kochen'></td>
    </tr>
    <tr>
    <td>Rechung:</td>
    <td><input type='text' name='total' id='total' size='5' value='13,37'>&euro;</td>
    </tr>
</table>

<br>

<div class='block'>
  <div class='fl'><img src='img/arrow-down.svg' class='icon14_16'>Teilnehmer</div>
  <div class='fr'>Bezahler<img src='img/arrow-down.svg' class='icon14_16'></div>
<br>

<?php
// Get top 3 whoms turn it is
$res = $_DB->query ("SELECT ID FROM Users ORDER BY Konto ASC Limit 3;");
$next = array ();
while ($user = $res->fetchArray ()) {
    array_push ($next, $user[0]);
}

$res = $_DB->query ("SELECT ID, Name, Konto FROM Users ORDER BY Name;");
while ($user = $res->fetchArray ()) {
    $ba = "";
    $bb = "";
    if (in_array ($user[0], $next)) {
        $ba = "<b>";
        $bb = "</b>";
    } 
    $checked = "";
    if ($user[0] == $_USER['ID']) {
      $checked = "checked";
    }?>
    <div class='block'>
      <label>
        <input type='checkbox' name='user[<?php print $user[0]?>]' class='user'>
          <?php print $ba . $user[1]. " (".($user[2]/100)."&euro;)" . $bb; ?>
      </label>
      <div class='fr'>
        &nbsp;+<input type='text' name='plus[<?php print $user[0] ?>]' size='1' value='0'>
        <input type='radio' value='<?php print $user[0] ?>' class='pay_radio' name='payer' <?php print $checked ?>>
      </div>
    </div>
    <br>
<?php
}
?>
</div>
<br>
</form>

<div id='js'>
</div>

<script>
function check () {
    var p=-1;
    var radios = document.getElementsByClassName('pay_radio');
    for (var i=0; i < radios.length; i++) {
        if (radios[i].checked) {
            p = radios[i].value;
        }
    }

    if (p == -1) {
        alert ("Bitte einen Bezahler anlegen!");
        return false;
    }

    var users = document.getElementsByClassName ('user');
    var at = 0;
    for (var i=0; i < users.length && at <= 2; i++) {
        if (users[i].checked)
            at++;
    } 

    if (at <= 2) {
        alert ("Es sollten wenigstens zwei Personen Mitessen!");
//TODO enable this line!        return false;
    }

    var pcb = document.getElementsByName ('user['+p+']')[0];
    if (!pcb.checked && !confirm("Der Bezahler isst nicht mit - ist das richtig?"))
        return false;

    var t = document.getElementById ('total');
    if (t.value == "13,37" && !confirm ("Ist der Rechnungsbetrag wirklich 13,37 Euro?"))
        return false;

    document.getElementById("f0").submit ();
    return true;
}

    var s = document.getElementById('nojs');
    s.parentElement.removeChild (s);

    document.getElementById ('js').innerHTML="<input type='submit' onclick='check()'>";
</script>

<?php include ("inc/foot.php"); ?>
