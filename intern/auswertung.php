<?php
include '../common/auth.inc.php';
include '../common/db.inc.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Felix Huber">
    <title>Auswertungen der Bestellungen</title>
    <!--
    Allgemeine Erläuterung:
    Auf dieser Seite findet der Mitarbeiter die Auswertungen zu seinem Markt, einschränkbar durch Startdatum und
    Kategorie. Es werden wochenweise Tabelle mit Gesamtumsatz, Umsatz der größten Bestellung, Median,
    Standardabweichung und lineare Regression gezeigt.
-->
</head>
<body>
<h1 style="text-align:center">Auswertungen der Bestellungen</h1>

<form method="post" action="auswertung.php">

    <label for="kategorie"><b>Kategorie:</b></label>
        <select id="kategorie" name="kategorie">
            <option value="">Alle Kategorien</option>
            <option value="wasser">Wasser</option>
            <option value="saft">Saft</option>
            <option value="limonade">Limonade</option>
            <option value="wein">Wein</option>
            <option value="bier">Bier</option>
            <option value="sonstiges">Sonstiges</option>
        </select>

    <br><br>

    <label for="startdatum"><b>Startdatum:</b></label>
    <?php
    $curr_date = date("Y-m-d");
    echo("<input type='date' id='startdatum' name='startdatum' max='$curr_date' required>");
    ?>
    <br><br>

    <input id="market-auswertung" type="submit" value="Auswertungen erzeugen">
</form>
<br><br>



<?php


if (isset($_POST["startdatum"]) && isset($_POST["kategorie"])) {

    $startdatum = $_POST["startdatum"];
    //Wenn keine Kategorie hinterlegt, also <code>value=""</code>, dann <code>null</code> damit in DB dann Platzhalter zu tragen kommt.
    //Alternativ könnte man <code>value="%"</code> nutzen, aber <code>null</code> ist aussagekräftiger und DB-unabhängig.
    $kategorie = empty($_POST["kategorie"]) ? null : $_POST["kategorie"];

    include "../common/auswertung.inc.php";
    $auswertung = new Auswertung($_SESSION["marktid"], $startdatum, $kategorie);

    // Ergebnisse jeweils als einzelne Tabellen ausgeben. In der ersten Spalte steht bei Kalenderwoche noch das Jahr dabei, da sich der Zeitraum
    //ja durchaus über mehrere Jahre hinweg erstrecken könnte.
    echo"<table>
    <tr>
        <td><b>KW (Jahr)</b></td>
        <td><b>Gesamtumsatz der Woche (in EUR)</b></td>
    </tr>";
    $gesamtumsatz = $auswertung->getGesamtumsatz();
    foreach ($gesamtumsatz as  $row){
        echo "<tr><td>". date("W (Y)",strtotime($row["start_date"])) . "</td><td>" . $row["total"] . "</td></tr>";
    }
    echo "</table><br><hr>";

    echo"<table>
    <tr>
        <td><b>KW (Jahr)</td>
        <td><b>größte Bestellung der Woche (in EUR)</b></td>
    </tr>";
    $groesste = $auswertung->getGroesste();
    foreach ($groesste as $row){
        echo "<tr><td>". date("W (Y)",strtotime($row['start_date'])) . "</td><td>" . $row['total'] . "</td></tr>";
    }
    echo "</table><br><hr>";

    echo"<table>
    <tr>
        <td><b>KW (Jahr)</b></td>
        <td><b>Median der Bestellungen der Woche (in EUR)</b></td>
    </tr>";
    $median = $auswertung->getMedian();
    foreach ($median as $row){
        echo "<tr><td>". date("W (Y)",strtotime($row['start_date'])) . "</td><td>" . $row['total'] . "</td></tr>";
    }
    echo "</table><br><hr>";

    $standardabweichung = $auswertung->getStandardabweichung();

    echo"<table>
    <tr>
        <td><b>KW (Jahr)</b></td>
        <td><b>Standardabweichung der Bestellungen der Woche (in EUR)</b></td>
    </tr>";
    foreach ($standardabweichung as $row){
        echo "<tr><td>". date("W (Y)",strtotime($row['start_date'])) . "</td><td>" . $row['total'] . "</td></tr>";
    }
    echo "</table><br><hr>";

    echo "---------";
    //var_dump($standardabweichung);

/*

    echo("Gesamtumsatz der Woche: $bestellung EUR<br>
  Umsatz der größten Bestellung der Woche: $gesamtumsatz EUR<b<br>
  Voraussichtlicher Umsatz diese Woche: ...<br>
  Voraussichtlicher Umsatz nächste Woche: ...<br>
  Standardabweichung der Umsätze aller Bestellungen der Woche: $standardabweichung EUR<br>
  Median der Umsätze aller Bestellungen der Woche: $median EUR");
*/
    //var_dump($gesamtumsatz);




    echo "-------------";


   //var_dump($gesamtumsatz);


}



?>
</table>
</body>
</html>