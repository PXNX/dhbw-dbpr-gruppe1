<?php
include "../common/auth.inc.php";
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Felix Huber">
    <title>Anmeldung Mitarbeiter</title>
    <!--
    Allgemeine Erläuterung:
    Auf dieser Seite finden sich Links zu den für Mitarbeiter zugänglichen Informationen. Ist man nicht eingeloggt, so wird man automatisch zum Login weitergeleitet.
    -->
</head>
<body>

<h1>intern Mitarbeiter</h1>
<ul>
    <li><a href="getraenk.php">Getränkeerfassung</a></li>
    <li><a href="lagerbestand.php">Lagerbestand</a></li>
    <li><a href="auswertung.php">Auswertung</a></li>
</ul>
<br><br>
<a href="logout.php">Logout</a>
</body>
</html>
