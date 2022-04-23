<?php 
include "auth.inc.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Felix Huber">
    <title>Neuen Markt anlegen</title>
</head>
<body>

  <h1>Neuen Markt anlegen</h1>
<form method="post" action="signup.php">
<label for="market-id">Markt ID:</label>
<input type="text" id="market-id" name="market-id"></br>
  <label for="market-name">Markt Name:</label>
<input  id="market-name" name="market-name" ></br>
<label for="market-pass">Markt Passwort:</label>
<input  id="market-pass" name="market-pass" type="password"></br>
<input  id="market-signup"  type="submit" value="registrieren"> 
</form></br></br><a href="login.php">Mitarbeiter anmelden</a>


<?php
include "../include/db.inc.php";



$query = $db->prepare("INSERT into markt (marktid,marktname,marktkennwort) values(?,?,?)");
$query->execute([$_POST['market-id'],$_POST['market-name'],$_POST['market-pass']]);
$test= $query->fetchAll();
var_dump($test);



?>
</body>
</html>