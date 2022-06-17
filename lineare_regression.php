<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Patricia Schäle">
    <title>LR Test</title>
</head>
<body>

<?php

$kategorie = '%';
$startdatum = '2022-01-01';
$marktid = 69;
include_once "common/db.inc.php";
$query = $db->prepare("call sp_gesamt(:kategorie, :startdatum, :marktid);");
$query->execute([
    ':kategorie' =>$kategorie,
    ':startdatum' =>$startdatum,
    ':marktid' =>$marktid,
]);
$umsaetze = $query->fetchAll();

/*foreach($result as $row){
   $daten[]=date('W', strtotime($row["start_date"]));
   $umsaetze[] = $row["total"];
}*/

$x_values=array();
$y_values=array();

for($i = 1; $i<count($umsaetze)-1; $i++){
    $x_values += $umsaetze[$i-1];
    $y_values += $umsaetze[$i];
}
var_dump($x_values);

var_dump($y_values);



/*
$xArray = $daten;
$yArray = $umsaetze;

if(count($xArray)!==count($yArray)){
    echo'Es ist ein Fehler aufgetreten.';
}

// Calculate Sums
$xSum=0;
$ySum=0;
$xxSum=0;
$xySum=0;

$count = count($xArray);
for ($i = 0; $i < $count; $i++) {
    $xSum += $xArray[$i];
    $ySum += $yArray[$i];
    $xxSum += $xArray[$i] * $xArray[$i];
    $xySum += $xArray[$i] * $yArray[$i];
}

// Calculate slope (Steigung) and intercept(Verschiebung)
// n = Anzahl
// S = Summe
//Slope = (nSxy - SxSy) / (nSxx - SxSx)
$slope = ($count * $xySum - $xSum * $ySum) / ($count * $xxSum - $xSum * $xSum);

// A = Mittelwert von...
$intercept = ($ySum / $count) - ($slope * $xSum) / $count;

$dieseWoche = date('W');
$vorhersage1=(($dieseWoche*$slope)+$intercept);
number_format($vorhersage1,2,'.', ',');
$nächsteWoche = $dieseWoche+1;
$vorhersage2 =(($nächsteWoche*$slope)+$intercept);

//echo number_format($vorhersage1,2,'.',','); //Vorhersage aktuelle Woche
//echo number_format($vorhersage2,2,'.',','); //Vorhersage folgende Woche

*/
?>
</body>
</html>