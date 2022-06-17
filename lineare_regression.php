<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Patricia Schäle">
    <title>LR Test</title>
</head>
<body>

<?php

$kategorie = 'Limonade';
$startdatum = '2022-01-01';
$marktid = 69;
include_once "common/db.inc.php";
    $query = $db->prepare("call sp_gesamt(:kategorie, :startdatum, :marktid);");
    $query->execute([
        ':kategorie' =>$kategorie,
        ':startdatum' =>$startdatum,
        ':marktid' =>$marktid,
    ]);
$result = $query->fetchAll();

foreach($result as $row){
   $daten[]=date('W', strtotime($row["start_date"]));
   $umsaetze[] = $row["total"];
}

echo $result;

//date('d.m.Y', strtotime('-1 week')); // Jetzt minus 1 Woche, als Datum
//Startwoche bis vorletzte Woche
//Startwoche+1 bis letzte Woche
//$bb_week = date('W') -2;
//$b_week = date('W') -1;
//$s_week = date('W', strtotime($startdatum));

//var_dump($bb_week);
//var_dump($b_week);
//var_dump($s_week);

/*$count=0;

for($i=$s_week;$i<=$bb_week;$i++){
    if(in_array($i, $daten)){
        $count+=$count;
    }
}

var_dump($count);*/

$zeitraum = new \DatePeriod($start, $interval,$ende);

foreach ($zeitraum as $date) {
    echo $date->format('d.m.Y') . '<br>';
}



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

//echo number_format($vorhersage1,2,'.',',');
//echo number_format($vorhersage2,2,'.',',');



?>
</body>
</html>