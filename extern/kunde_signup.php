<?php
session_start();
?>
<html>
    <head>
    <meta charset="UTF-8"> 
    <meta author="Patricia Schäle">
    <title>Kunde Registrieren</title>
    </head>
    <body>

    <h1 style="text-align:center"><p>Schritt 3: Registrieren</p></h1>

<form method="post" action="kunde_signup.php">

<label for="e-mail">E-Mail Adresse:</label>
<input type="text" id="e-mail" name="e-mail" required>
<br><br>

<label for="kunde-pass">Passwort:</label>
<input  id="kunde-pass" name="kunde-pass" type="password" required>
<br><br>

<label for="kunde-name">Name:</label>
<input  id="kunde-name" name="kunde-name" required>
<br><br>

<label for="kunde-adresse">Adresse:</label>
<input  id="kunde-adresse" name="kunde-adresse" required>
<br><br>

<input  id="kunde-signup"  type="submit" value="Registrieren"> 
</form></br></br><a href="kunde_login.php">Anmelden</a>

<?php
include "../include/db.inc.php";

if(isset($_POST['e-mail']) && isset( $_POST['kunde-pass']) && isset($_POST['kunde-name']) 
&& isset($_POST['kunde-adresse'])){
    
$input_mail =   $_POST['e-mail'];
$input_pass = password_hash( $_POST['kunde-pass'], PASSWORD_DEFAULT);
$input_name = $_POST['kunde-name'];
$input_adresse = $_POST['kunde-adresse'];

if(mail_exists($input_mail)){
    echo("<h1>Ein Kunde mit Mail-Adresse ". $input_mail . " existiert bereits. Bitte wählen Sie eine andere Mail-Adresse.");
    return;
}

$query = $db->prepare("INSERT into kunde (kundenaddresse,kundenkennwort,kundenname, mailaddresse) values(?,?,?,?)");
$query->execute([ $input_adresse,$input_pass,$input_name,$input_mail]);
$test= $query->fetchAll();
var_dump($test);

$_SESSION["e-mail"] = $input_mail;



header('Location:../extern.php', true, 301);
exit();

}

//Evtl. if empty abfrage

//function mailaddresse_exists

?>

</body>
</html>