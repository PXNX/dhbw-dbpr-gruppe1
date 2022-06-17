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
   $daten[]=date('W',strtotime($row["start_date"]));
   
    $umseatze[] = $row["total"];
}

//$counter = count($daten);

//for($c = 1; $c <= $counter; $c++){
//   $wochen[]= $c;
//}

//$xArray = $wochen;
//$yArray = $umseatze;

$xArray = $daten;
$yArray = $umseatze;

if(count($xArray)!==count($yArray)){
    echo'Es ist ein Fehler aufgetreten.';
}

//$xArray = [50,60,70,80,90,100,110,120,130,140,150];
//$yArray = [7,8,8,9,9,9,10,11,14,14,15];

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
// b = Ay - (Ax * m)
$intercept = ($ySum / $count) - ($slope * $xSum) / $count;

$dieseWoche = date('W');
$vorhersage1=($dieseWoche*$slope);
$nächsteWoche = $dieseWoche+1;
$vorhersage2 =($nächsteWoche*$slope);
var_dump($vorhersage1); echo'</br>';
var_dump($vorhersage2);

//Bei Auswertung Format anpassen
//$foo = "105";
//echo number_format((float)$foo, 2, '.', ''); // Outputs -> 105.00
?>
</body>
</html>