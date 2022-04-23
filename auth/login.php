<?php 
include "auth.inc.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta author="Felix Huber">
    <title>Anmeldung Mitarbeiter</title>
</head>
<body>
  <h1>Anmeldung Mitarbeiter</h1>

  <form method="post" action="intern.php">
<label for="market-id">Markt ID:</label>
<input type="text" id="market-id" name="market-id"></br>
<label for="market-pass">Markt Passwort:</label>
<input  id="market-pass" name="market-pass" type="password"></br>
<input  id="market-login"  type="submit" value="Einloggen"> 
</form></br></br><a href="signup.php">Markt registrieren</a>

<?php

include "../include/db.inc.php";



$query = $db->prepare("SELECT * from markt where marktid=? and marktkennwort=?");
$query->execute([$_POST['market-id'], $_POST['market-pass']]);
$test= $query->fetchAll();
var_dump($test);



?>
</body>
</html>
