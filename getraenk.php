<?php
include "include/auth.inc.php";
include 'include/db.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Marcel Bitschi">
    <title>Getränkeerfassung</title>
</head>
<body>

<h1 style="text-align:center">Getränk erfassen</h1>

  <form method="post" action="getraenk.php">
    <table>
  <tr>
    <td><label for="getränk"><b>Getränk:</b></label></td>
    <td><input id="getraenk" name="getränk" size="40" maxlength="60" value="" required/><br></td>
  </tr>
  <tr>
    <td><label for="preis"><b>Preis:</b></label> </td>
    <td><input id="preis" name="preis" type="number" min="0" step=".01" size="40" maxlength="5" value="" required/><br></td>
  </tr>
 <tr>
    <td><label for="hersteller"><b>Hersteller:</b></label> </td>
    <td><input id="hersteller" name="hersteller" size="40" maxlength="40" value=""/><br></td>
  </tr> 
  </table><br> 
<form> 
<b>Kategorie:</b>
<select name="kategorie">
     <option value="wasser">Wasser</option>
     <option value="saft">Saft</option>
     <option value="limonade">Limonade</option>
     <option value="wein">Wein</option>
     <option value="bier">Bier</option>
     <option value="sonstiges">Sonstiges</option>
  </select> <br>  
  <input type="submit" value="erfassen"/>
  </form> 
<?php

if(isset($_POST['getränk']) && ($_POST['preis']) && ($_POST['hersteller']) && ($_POST['kategorie'])) {
$input_getraenk = $_POST['getränk'];
$input_preis = $_POST['preis'];
$input_hersteller = $_POST['hersteller'];
$input_kategorie = $_POST['kategorie'];


  $query = $db->prepare("INSERT into getraenk (getraenkename,hersteller,preis,kategorie) values(?,?,?,?);");
  $query->execute([$input_getraenk,$input_hersteller,$input_preis,$input_kategorie]);
  $test= $query->fetchAll();
}
?>

</body>
</html>