<?php
include "../common/auth.inc.php";
include '../common/db.inc.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Felix Huber">
    <title>Auswertungen der Bestellungen</title>
</head>
<body>
<h1 style="text-align:center">Auswertungen der Bestellungen</h1>

<form method="post" action="auswertung.php">

    <select name="kategorie">
        <option value="">Alle Kategorien</option>
        <option value="wasser">Wasser</option>
        <option value="saft">Saft</option>
        <option value="limonade">Limonade</option>
        <option value="wein">Wein</option>
        <option value="bier">Bier</option>
        <option value="sonstiges">Sonstiges</option>
    </select>

    <br><br>

    <?php
    $curr_date = date("Y-m-d");
    echo("<input type='date' id='start' name='start-date' max='$curr_date' required>");
    ?>
    <br><br>

    <input id="market-auswertung" type="submit" value="Auswertungen erzeugen">
</form>
<br><br>

<?php


if (isset($_POST["start-date"]) && isset($_POST["kategorie"])) {

    $start_date = $_POST["start-date"];
    $kategorie = empty($_POST["kategorie"]) ? null : $_POST["kategorie"];

    include "../common/auswertung.inc.php";
    $auswertung = new Auswertung($_SESSION["marktid"], $start_date, $kategorie);
//  $auswertung->calculateKennzahlen($start_date, $kategorie);

    $bestellung = $auswertung->getBestellung();
    $gesamtumsatz = $auswertung->getGesamtumsatz();
    $standardabweichung = $auswertung->getStandardabweichung();
    $median = $auswertung->getMedian();

    echo("Gesamtumsatz der Woche: $bestellung EUR<br>
  Umsatz der größten Bestellung der Woche: $gesamtumsatz EUR<b<br>
  Voraussichtlicher Umsatz diese Woche: ...<br>
  Voraussichtlicher Umsatz nächste Woche: ...<br>
  Standardabweichung der Umsätze aller Bestellungen der Woche: $standardabweichung EUR<br>
  Median der Umsätze aller Bestellungen der Woche: $median EUR");

}

?>

</body>
</html>