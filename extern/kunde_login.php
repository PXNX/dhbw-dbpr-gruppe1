<?php 
include "auth.inc.php";
?>
<html>
    <head>
    <meta charset="UTF-8"> 
    <meta author="Patricia Schäle">
    <title>Anmeldung Kunde</title>
    </head>
    <body>
    <h1 style="text-align:center"><p>Schritt 3: Anmeldung</p></h1>
<form method="post" action="kunde_login.php">

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
 kundelogin($_POST['e-mail'],  $_POST['kunden-pass']);
}

function kundelogin(String $input_mail, String $input_pass){
    include_once "../include/db.inc.php";
    $query = $db->prepare("SELECT kundenkennwort from kunde where mailaddresse=?");
    $query->execute([$input_mail]);
    $correct_pass= $query->fetchAll(PDO::FETCH_COLUMN)[0];
   
    if(  password_verify($input_pass, $correct_pass)){
      $_SESSION["e-mail"] = $input_mail;
   
    header('Location: ../extern.php', true, 301);
    exit();
  
    }else{
      echo("<h1>Passwort ist inkorrekt.</h1>");
    }
  
  }


?>
</body>
</html>