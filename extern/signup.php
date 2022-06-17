<?php
session_start();
include '../common/db.inc.php';
?>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Patricia Schäle">
    <title>Kunde Registrieren</title>
</head>
<body>

<h1 style="text-align:center">Registrieren</h1>

<form method="post" action="signup.php">
    <label for="mailadresse">E-Mail Adresse:</label>
    <input type="text" id="mailadresse" name="mailadresse" required>
    <br><br>
    <label for="kundenkennwort">Passwort:</label>
    <input id="kundenkennwort" name="kundenkennwort" type="password" required>
    <br><br>
    <label for="kundenname">Name:</label>
    <input id="kundenname" name="kundenname" required>
    <br><br>
    <label for="strassenname">Straßenname:</label>
    <input id="strassenname" name="strassenname" required>
    <br><br>
    <label for="hausnummer">Hausnummer:</label>
    <input type="number" min="0" id="hausnummer" name="hausnummer" size="6" required>
    <br><br>
    <label for="plz">PLZ:</label>
    <input type="number" min="0" id="plz" name="plz" size="6" required>
    <br><br>
    <label for="ort">Ort:</label>
    <input id="ort" name="ort" required>
    <br>
    <input id="kunde-signup" type="submit" value="Registrieren">
</form>
<br><br>
<a href="login.php">Anmelden</a>

<?php

if (isset($_POST['mailadresse']) && isset($_POST['kundenkennwort'])
    && isset($_POST['kundenname']) && isset($_POST['strassenname'])
    && isset($_POST['hausnummer']) && isset($_POST['plz'])
    && isset($_POST['ort'])) {

    $mailadresse = $_POST['mailadresse'];
    $kundenkennwort = password_hash($_POST['kundenkennwort'], PASSWORD_DEFAULT);
    $kundenname = $_POST['kundenname'];
    $strassenname = $_POST['strassenname'];
    $hausnummer = $_POST['hausnummer'];
    $plz = $_POST['plz'];
    $ort = $_POST['ort'];

    if (mail_exists($mailadresse)) {
        echo("<h1>Ein Kunde mit Mail-Adresse " . $mailadresse . " existiert bereits. Bitte wählen Sie eine andere Mail-Adresse.");
        return;
    }

    $query = $db->prepare("INSERT into kunde (hausnummer, kundenkennwort, kundenname, mailadresse, ort, plz, strassenname) values(:hausnummer, :kundenkennwort, :kundenname, :mailadresse, :ort, :plz, :strassenname)");
    $query->execute([
        'hausnummer' => $hausnummer,
        'kundenkennwort' => $kundenkennwort,
        'kundenname' => $kundenname,
        'mailadresse' => $mailadresse,
        'ort' => $ort,
        'plz' => $plz,
        'strassenname' => $strassenname]);

    $_SESSION["mailadresse"] = $mailadresse;

    header('Location: login.php', true, 301);
    exit();

}

function mail_exists($mailadresse): bool
{
    include "../common/db.inc.php";
    $query = $db->prepare("SELECT * from kunde k where k.mailadresse=:mailadresse");
    $query->execute([
        ':mailadresse' => $mailadresse]);
    $result = $query->fetchAll();
    return count($result) !== 0;
}

?>

</body>
</html>