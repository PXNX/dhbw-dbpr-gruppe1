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

    <label for="mailadresse">E-Mail Adresse:</label>
    <input type="text" id="mailadresse" name="mailadresse" required>
    <br><br>

    <label for="kundenkennwort">Passwort:</label>
    <input  id="kundenkennwort" name="kundenkennwort" type="password" required>
    <br><br>

    <input  id="kunde-login"  type="submit" value="Einloggen">
</form><br><br>

<a href="kunde_signup.php">Kunde registrieren</a>

<?php
if(isset($_POST['mailadresse']) && isset( $_POST['kundenkennwort']) ){
    kundelogin($_POST['mailadresse'],  $_POST['kundenkennwort']);
}

function kundelogin(String $input_mail, String $input_pass){
    include_once "../include/db.inc.php";
    $query = $db->prepare("SELECT kundenkennwort from kunde where mailadresse= :mailadresse");
    $query->execute([
        ':mailadresse' => $input_mail]);
    $correct_pass= $query->fetchAll(PDO::FETCH_COLUMN)[0];

    if(  password_verify($input_pass, $correct_pass)){
        $_SESSION["mailadresse"] = $input_mail;

        header('Location: bestellung_abschluss.php', true, 301);
        exit();

    }else{
        echo("<h1>Passwort ist inkorrekt.</h1>");





    }

}
?>
</body>
</html>