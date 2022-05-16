<?php
include "auth/auth.inc.php";
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

  <select name="kategorie">
  <option value="wasser">Alle Kategorien</option>
     <option value="wasser">Wasser</option>
     <option value="saft">Saft</option>
     <option value="limonade">Limonade</option>
     <option value="wein">Wein</option>
     <option value="bier">Bier</option>
     <option value="sonstiges">Sonstiges</option>
  </select>

<br><br>

<?php
$curr_date =  date("Y-m-d");
echo ("<input type='date' id='start' name='start-date' max='$curr_date' required>");

?>
<br><br>

<input  id="market-auswertung"  type="submit" value="Auswertungen erzeugen"> 
</form><br><br>

<?php


if(isset($_POST["start-date"]) && isset($_POST["kategorie"])){

  $start_date = $_POST["start-date"];
  $kategorie = $_POST["kategorie"];

  include "include/auswertung.inc.php";
  $auswertung = new Auswertung();
//  $auswertung->calculateKennzahlen($start_date, $kategorie);

  $bestellung = $auswertung->getBestellung();
  $gesamtumsatz = $auswertung->getGesamtumsatz();
  $standardabweichung = $auswertung->getStandardabweichung();
  $median = $auswertung->getMedian();

  echo ("Gesamtumsatz der Woche: $bestellung EUR<br>
  Umsatz der größten Bestellung der Woche: $gesamtumsatz EUR<br>
  Standardabweichung der Umsätze aller Bestellungen der Woche:  $standardabweichung EUR<br>
  Median der Umsätze aller Bestellungen der Woche: $median EUR");

}


?>

</body>
</html>