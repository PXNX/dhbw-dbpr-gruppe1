<?php
include "auth/auth.inc.php";
include 'include/db.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta author="Marcel Bitschi">
    <title>Lagerbestanderfassung</title>
</head>
<body>
  
  <h1 style="text-align:center"><p>Lagerbestand erfassen</p></h1>

  <form method="post" action="lagerbestand.php">
    
  <table>
    <label for="getraenkename"><b>Getränk:</b></label>
  <select id="getraenkename" name="getraenkename"> 
  <?php
  
  $getraenke = $db->query("SELECT * from getraenk");
  var_dump($getraenke);
  foreach($getraenke as $row) {
    $getraenkename = $row["getraenkename"];
    echo "<option value='$getraenkename'>$getraenkename</option>";
  }
  ?>
 
  </select>
  <br> 
  <table>
    <label for="hersteller"><b>Hersteller:</b></label>
  <select id="hersteller" name="hersteller"> 
  <?php
  
  $hersteller = $db->query("SELECT * from Getraenk");
  var_dump($hersteller);
  foreach($hersteller as $row) {
    $hersteller = $row["hersteller"];
    echo "<option value='$hersteller'>$hersteller</option>";
  }
  ?>
 
  </select>
  <br> 

 <tr>
    <label for="lagerbestand"><b>Anzahl:</b></label>
    <input id="lagerbestand" name="lagerbestand" type="number" min="0">
  </tr>
 </table><br>
    <input type="submit" value="erfassen"/>
  </form>

<?php

if(isset($_POST['getraenkename']) && isset($_POST['lagerbestand']) && isset($_POST['hersteller'])){
  $getraenkename = $_POST['getraenkename'];
  $hersteller = $_POST['hersteller'];
  $lagerbestand = $_POST['lagerbestand'];
  
  saveLagerbestand($getraenkename,$hersteller,$lagerbestand);

}

function saveLagerbestand(string $getraenkename,string $hersteller,int $lagerbestand){
  include "include/db.inc.php";
  $query = $db->prepare("insert into fuehrt(getraenkename,hersteller,lagerbestand,marktid) values(:getraenkename, :hersteller, :lagerbestand, :marktid) on duplicate key update lagerbestand = :lagerbestand");
  $result = $query->execute([
    ':getraenkename'=>$getraenkename,
    ':hersteller'=>$hersteller,
    ':lagerbestand'=>$lagerbestand,
    ':marktid'=>$_SESSION['market-id']]);
if($result) {
  echo'Lagerbestand wurde erfolgreich gespeichert!';
} 
}

 // Unnötig?
 function getraenk_exists($getraenkename,$hersteller):bool{
  include "include/db.inc.php";
  $query = $db->prepare("Select * from fuehrt where getraenkename=? and hersteller=? and marktid=?");
  $query->execute([$getraenkename,$hersteller,$_SESSION['market-id']]);
  $result= $query->fetchAll();
  return count($result) !== 0;
 }

?>


</body>
</html>