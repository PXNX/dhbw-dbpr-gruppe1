<html>
  <head>
    <meta charset="UTF-8"> 
    <meta author="Patricia Schäle">
    <title>Bestellung prüfen</title>
  </head>
  <body>

<h1 style="text-align:center">Bestellung prüfen</h1>

<?php

include '../include/db.inc.php';
//if (isset($_POST['marktid']) && isset($_POST['position'])){
$markt = 69;//$_POST['marktid'];
$pos = 2;//$_POST['position'];

//Statt * -> Auch g.getraenkename, g.hersteller und f.lagerbestand
$query = $db->prepare("SELECT * FROM getraenk g, fuehrt f WHERE g.getraenkename=f.getraenkename and g.hersteller=f.hersteller and f.marktid=? and f.lagerbestand>0");
$query->execute([$markt]);
$getraenk = $query->fetchAll();

$sumgetraenk = array();

for ($i = 1;$i <= $pos;$i++) {
    var_dump($_POST['getraenk'.$i]);
    var_dump($_POST['anz'.$i]);
if(isset($_POST['getraenk'.$i]) && isset($_POST['anz'.$i]) && $_POST['anz'.$i]>0){
    $a_getraenk=$getraenk[$_POST['getraenk'.$i]];
    $sumgetraenk[$a_getraenk[0]][$a_getraenk[1]]+= $_POST['anz'.$i];
    var_dump($sumgetraenk[$a_getraenk[0]][$a_getraenk[1]]);
  

var_dump($sumgetraenk[$_POST['getraenk'.$i]]);
$sumgetraenk[$_POST['getraenk'.$i]] += $_POST['anz'.$i];
}
var_dump($sumgetraenk);


?>
<form action="bestellung_pruefen.php" method="post">
    <table>
        <tr>
            <td>Positionsnummer</td>
            <td>Getränk</td>
            <td>Anzahl</td>
        </tr>
        <tr>
            <?php
            echo '<td>'.$i.'</td>';
            ?>
            <td><select id="getraenk" name="getraenk".$i>
                    <?php

                    foreach($getraenk as $key=> $row) {
                        $hersteller = $row["hersteller"];
                        $getraenkename = $row["getraenkename"];
                        echo'<option value="'.$key.'">'.$hersteller.' - '.$getraenkename.'</option>';
                      }


                    ?>
                </select></td>
            
            <td><input name="anz".$i size="10" maxlength="5" value=""/></td>
        </tr>
    </table>
    <button type="submit" name="pruefen">Lagerbestand prüfen</button></br>
    <?php
    }
    //}
    ?>
</form>
</body>

</html>