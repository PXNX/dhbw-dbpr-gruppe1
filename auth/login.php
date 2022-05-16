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

  <form method="post" action="login.php">

<label for="market-id">Markt ID:</label>
<input type="text" id="market-id" name="market-id" required>
<br><br>

<label for="market-pass">Markt Passwort:</label>
<input  id="market-pass" name="market-pass" type="password" required>
<br><br>

<input  id="market-login"  type="submit" value="Einloggen"> 
</form><br><br>

<a href="signup.php">Markt registrieren</a>

<?php
if(isset($_POST['market-id']) && isset( $_POST['market-pass']) ){
 login($_POST['market-id'],  $_POST['market-pass']);
}

function login(String $input_id, String $input_pass){
  include_once "../include/db.inc.php";
  $query = $db->prepare("SELECT marktkennwort from markt where marktid=?");
  $query->execute([$input_id]);
  $correct_pass= $query->fetchAll(PDO::FETCH_COLUMN)[0];
 
  if(  password_verify($input_pass, $correct_pass)){
    $_SESSION["market-id"] = $input_id;
 
    /*
    Hier könnte man auch direkt auf die zuvor angeforderte Url weiterleiten, dies ist aber keine AF.
    Deshalb nur auf die Startseite für interne Mitarbeiter.
    */
  header('Location: ../intern.php', true, 301);
  exit();

  }else{
    echo("<h1>Passwort ist inkorrekt.</h1>");
  }

}

?>
</body>
</html>

