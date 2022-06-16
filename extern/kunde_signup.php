<?php
include "../auth/auth.inc.php";
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta author="Patricia Schäle">
    <title>Kunde Registrieren</title>
</head>
<body>

<h1 style="text-align:center"><p>Registrieren</p></h1>

<form method="post" action="kunde_signup.php">

    <label for="e-mail">E-Mail Adresse:</label>
    <input type="text" id="e-mail" name="e-mail" required>
    <br><br>

    <label for="kunde-pass">Passwort:</label>
    <input id="kunde-pass" name="kunde-pass" type="password" required>
    <br><br>

    <label for="kunde-name">Name:</label>
    <input id="kunde-name" name="kunde-name" required>
    <br><br>

    <label for="kunde-adresse">Adresse:</label>
    <input id="kunde-adresse" name="kunde-adresse" required>
    <br><br>

    <input id="kunde-signup" type="submit" value="Registrieren">
</form>
</br></br><a href="kunde_login.php">Anmelden</a>

<?php
include "../include/db.inc.php";

if (isset($_POST['e-mail']) && isset($_POST['kunde-pass']) && isset($_POST['kunde-name'])
    && isset($_POST['kunde-adresse'])) {

    $input_mail = $_POST['e-mail'];
    $input_pass = password_hash($_POST['kunde-pass'], PASSWORD_DEFAULT);
    $input_name = $_POST['kunde-name'];
    $input_adresse = $_POST['kunde-adresse'];

    if (mail_exists($input_mail)) {
        echo("<h1>Ein Kunde mit Mail-Adresse " . $input_mail . " existiert bereits. Bitte wählen Sie eine andere Mail-Adresse.");
        return;
    }

    $query = $db->prepare("INSERT into kunde (kundenadresse,kundenkennwort,kundenname, mailadresse) values(:kundenadresse, :kundenkennwort, :kundenname ,:mailadresse)");
    $query->execute([
        ':kundenadresse' => $input_adresse,
        ':kundenkennwort' => $input_pass,
        'kundenname' => $input_name,
        'mailadresse' => $input_mail]);
    $test = $query->fetchAll();

    $_SESSION["e-mail"] = $input_mail;

    header('Location: kunde_login.php', true, 301);
    exit();

}

function mail_exists($input_mail): bool
{
    include "../include/db.inc.php";
    $query = $db->prepare("SELECT * from kunde k where k.mailadresse=:mailadresse");
    $query->execute([
        ':mailadresse' => $input_mail]);
    $result = $query->fetchAll();
    return count($result) !== 0;
}


//Evtl. if empty abfrage


?>

</body>
</html>