<?php
session_start();
include '../common/db.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Felix Huber">
    <title>Anmeldung Mitarbeiter</title>
</head>
<body>
<h1 style="text-align:center">Anmeldung Mitarbeiter</h1>

<form method="post" action="login.php">

    <label for="marktid">Markt ID:</label>
    <input type="text" id="marktid" name="marktid" required>
    <br><br>

    <label for="marktkennwort">Markt Passwort:</label>
    <input id="marktkennwort" name="marktkennwort" type="password" required>
    <br><br>

    <input id="market-login" type="submit" value="Einloggen">
</form>
<br><br>

<a href="signup.php">Markt registrieren</a>

<?php
if (isset($_POST['marktid']) && isset($_POST['marktkennwort'])) {
    login($_POST['marktid'], $_POST['marktkennwort']);
}

/**
 * Nutzer anhand der Zugangsdaten einloggen.
 * @author Felix Huber
 */

function login(string $marktid, string $marktkennwort)
{
    include '../common/db.inc.php';
    $query = $db->prepare("SELECT marktkennwort from markt where marktid=:marktid");
    $query->execute([
        ':marktid' => $marktid]);
    $correct_pass = $query->fetchAll(PDO::FETCH_COLUMN)[0];

    if (password_verify($marktkennwort, $correct_pass)) {
        $_SESSION["marktid"] = $marktid;

        /*
        Hier könnte man auch direkt auf die zuvor angeforderte Url weiterleiten, dies ist aber keine AF.
        Deshalb nur auf die Startseite für interne Mitarbeiter.
        */
        header('Location: index.php', true, 301);
        exit();

    } else {
        echo("<h1>Passwort ist inkorrekt.</h1>");
    }

}

?>
</body>
</html>

