<?php
session_start();
include '../common/db.inc.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Patricia Schäle">
    <title>Anmeldung Kunde</title>
    <!-- 
Allgemeine Erläuterung:
Reichen die Lagerbestände, werden die Kundendaten erfasst. Hierfür hat der
Kunde die Wahl, einen vorhandenen Kundenaccount über seine Mail-Adresse und ein Kennwort
zu nutzen oder einen neuen Kundenaccount anzulegen.-->
</head>
<body>
<h1 style="text-align:center">Anmeldung: Ihre Bestellung ist möglich!</h1>
<p>Um die Bestellung durchführen zu können, müssen Sie sich <b>einloggen</b>.
        Wenn Sie noch kein Kundenkonto haben, <b>registrieren</b> Sie sich bitte zuerst.</p>

<!-- Formular für E-Mail- und Kennworteingabe -->
<form method="post" action="login.php">

    <label for="mailadresse">E-Mail Adresse:</label>
    <input type="text" id="mailadresse" name="mailadresse" required>
    <br><br>

    <label for="kundenkennwort">Passwort:</label>
    <input id="kundenkennwort" name="kundenkennwort" type="password" required>
    <br><br>

    <input id="kunde-login" type="submit" value="Einloggen">
</form>
<br><br>

<a href="signup.php">Kunde registrieren</a>

<?php
if (isset($_POST['mailadresse']) && isset($_POST['kundenkennwort'])) {
    kundelogin($_POST['mailadresse'], $_POST['kundenkennwort']);

}

//Funktion soll prüfen ob Kunde ein Kundenaccount hat
function kundelogin(string $input_mail, string $input_pass)
{
    include '../common/db.inc.php';
    $query = $db->prepare("SELECT kundenkennwort from kunde where mailadresse= :mailadresse");
    $query->execute([
        ':mailadresse' => $input_mail]);
    $correct_pass = $query->fetchAll(PDO::FETCH_COLUMN)[0];

    //Passwortprüfung
    //Korrekt -> Weiterleitung Bestellabschluss
    //Inkorrekt -> Meldung
    if (password_verify($input_pass, $correct_pass)) {
        $_SESSION["mailadresse"] = $input_mail;
        header('Location: bestellung/abschluss.php', true, 301);
        exit();

    } else {
        echo("<h1>Passwort ist inkorrekt.</h1>");


    }

}

?>
</body>
</html>