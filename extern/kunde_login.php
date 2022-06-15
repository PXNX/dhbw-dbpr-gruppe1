<?php
include "../auth/auth.inc.php";
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8"> 
    <meta author="Patricia Schäle">
    <title>Anmeldung Kunde</title>
    </head>
    <body>
    <h1 style="text-align:center"><p>Anmeldung: Ihre Bestellung ist möglich!!!</p></h1>
    <h3 style="text-align:center"><p>Um die Bestellung durchführen zu können, müssen Sie sich <b>einloggen</b>.
      Wenn Sie noch kein Kundenkonto haben, <b>registrieren</b> Sie sich bitte zuerst.</p></h3>
<form method="post" action="bestellung_abschluss.php">

<label for="e-mail">E-Mail Adresse:</label>
<input type="text" id="e-mail" name="e-mail" required>
<br><br>

<label for="kunde-pass">Passwort:</label>
<input  id="kunde-pass" name="kunde-pass" type="password" required>
<br><br>

<input  id="kunde-login"  type="submit" value="Einloggen"> 
</form><br><br>

<a href="kunde_signup.php">Kunde registrieren</a>

<?php
if(isset($_POST['e-mail']) && isset( $_POST['kunde-pass']) ){
 kundelogin($_POST['e-mail'],  $_POST['kunde-pass']);
}

function kundelogin(String $input_mail, String $input_pass){
    include_once "../include/db.inc.php";
    $query = $db->prepare("SELECT kundenkennwort from kunde where mailadresse=?");
    $query->execute([$input_mail]);
    $correct_pass= $query->fetchAll(PDO::FETCH_COLUMN)[0];
   
    if(  password_verify($input_pass, $correct_pass)){
      $_SESSION["e-mail"] = $input_mail;
   
    header('Location: bestellung_abschluss.php', true, 301);
    exit();
  
    }else{
      echo("<h1>Passwort ist inkorrekt.</h1>");

      



    }
  
  }
?>
</body>
</html>
