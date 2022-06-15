
<html>
  <head>
    <meta charset="UTF-8"> 
    <meta author="Patricia Schäle">
    <title>Erfassungsformular</title>
  </head>
  <body>

<h1 style="text-align:center">Bestellung erfassen</h1>

<?php

?>
<form action="bestellung_pruefen.php" method="post">
    <table>
        <tr>
            <td>Positionsnummer</td>
            <td>Getränk</td>
            <td>Anzahl</td>
        </tr>
        
            <?php include '../include/db.inc.php';
if (isset($_POST['marktid']) && isset($_POST['position'])){
$markt = $_POST['marktid'];
$pos = $_POST['position'];

//Statt * -> Auch g.getraenkename, g.hersteller und f.lagerbestand
$query = $db->prepare("SELECT * FROM getraenk g, fuehrt f WHERE g.getraenkename=f.getraenkename and g.hersteller=f.hersteller and f.marktid=? and f.lagerbestand>0");
$query->execute([$markt]);
$getraenk = $query->fetchAll();

for ($i = 1;$i <= $pos;$i++) {

            echo '<tr><td>'.$i.'</td><td><select id="getraenk" name="getraenk'.$i.'">';
            

                    foreach($getraenk as $key=> $row) {
                        $hersteller = $row["hersteller"];
                        $getraenkename = $row["getraenkename"];
                        echo'<option value="'.$key.'">'.$hersteller.' - '.$getraenkename.'</option>';
                      }
                    
            echo'   </select></td>
            
            <td><input name="anz'.$i.'" size="10" maxlength="5" value=""/></td>
        </tr>';
                    }
                }
    ?>
    </table>
  
    <button type="submit" name="pruefen">Lagerbestand prüfen</button>
</form>
</body>
</html>