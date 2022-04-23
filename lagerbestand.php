<?php
include "auth/auth.inc.php";
check_already_logged_in();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta author="Marcel Bitschi">
    <title>Lagerbestanderfassung</title>
</head>
<body>

  <?php echo '<h1 style="text-align:center"><p>Lagerbestand erfassen</p></h1>';

include 'include/db.inc.php';

$query = $db->prepare("SELECT * from markt where marktid=? and marktkennwort=?");
$query->execute([$_POST['market-id'], $_POST['market-pass']]);
$test= $query->fetchAll();
var_dump($test);

echo '
  <form method="post" action="http://vorlesungen.kirchbergnet.de/inhalte/DB-PR/output_posted_vars.php">
    
  <table>
  <tr>
    <td><label for="getränk"><b>Getränk:</b></label></td>
    <td><input name="getränk" size="40" maxlength="60" value="" /><br></td>
  </tr>
  <tr>
    <td><label for="hersteller"><b>Hersteller:</b></label></td>
    <td><input name="hersteller" size="40" maxlength="60" value="" /><br></td>
  </tr>
 <tr>
  //Todo: Anzahl muss mind. 0 sein

    <td><label for="anzahl"><b>Anzahl:</b></label></td>
    <td><input name="anzahl" size="5" maxlength="5" value="" /><br></td>
  </tr>
 </table><br>
    <center><input type="submit" value="erfassen"/></center>
  </form>

'
?>

  
</body>
</html>