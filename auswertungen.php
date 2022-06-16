<?php
session_start();
include 'common/db.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Felix Huber">
    <title>Auswertungen der Bestellungen</title>
</head>
<body>
<h1 style="text-align:center"><p>Auswertungen der Bestellungen</p></h1>

<form method="post" action="auswertung.php">

    <label for="market-id">Markt ID:</label>
    <input type="text" id="market-id" name="market-id" required>
    <br><br>

    <label for="market-pass">Markt Passwort:</label>
    <input id="market-pass" name="market-pass" type="password" required>
    <br><br>

    <input id="market-login" type="submit" value="Einloggen">
</form>
<br><br>


</body>
</html>