<html>
    <head>
    <meta charset="UTF-8"> 
    <meta author="Patricia Schäle">
    <title>Bestellung erfassen</title>
    </head>
    <body>
    <h1 style="text-align:center"><p>Schritt 2: Bestellung erfassen</p></h1>

    <?php
include 'include/db.inc.php';
$statement = $db->query("SELECT * FROM getraenk");
$statement->execute([]);
$getraenk = $statement->fetchAll(); 

if (isset($_POST['erfassen']) && isset($_POST['marktid']) && isset($_POST['anzahl'])){

$marktid=$_POST['marktid']; 
$anzahl=$_POST['anzahl'];

  for ($i = 1; $i <= $anzahl; $i++) {

echo'<form action="kunde_login.php" method="post">
<table>
<tr>
<td></td>
<td>Hersteller</td>
<td>Getränkename</td>
<td>Anzahl</td>
</tr>
  <tr>
  <td><label for="pos"><b>Getränk ' .$i.':</b></label></td>
    <td><select id="herst" name="herst">'; 

    foreach($getraenk as $row) {
      $hersteller = $row["hersteller"];
      echo'<option selected value="'.$hersteller.'">'.$hersteller.'</option>';
    }  

      // Einträge auslesen
      //$stmt = $db->prepare("SELECT hersteller, getraenkename FROM getraenk
      //                                    WHERE hersteller = :herst
      //                                    AND getraenkename = :getraenk
      //                                    );
      //$stmt->execute([":herst" => $_POST["herst"],
      //                         ":getraenk" => $_POST["suche"]]);
      //$results = $stmt->fetch(); 

  echo'</select></td>
    <td><select id="getraenke" name="suche">';
    foreach($getraenk as $row) {
      $getraenkename = $row["getraenkename"];
      echo'<option value="'.$getraenkename.'">'.$getraenkename.'</option>';
  }

    echo'</select></td>';
    ?>
    <td><input name="pos" size="10" maxlength="5" value="" /></td>
    <?php
  
;
  echo'</tr>
</table>';
}

//if(isset($_POST['getraenke'])){
echo'</br><input type="submit" value="Lagerbestand prüfen" /></form>';
//if(isset($_POST['Lagerbestand prüfen'])){
  
//}


//function checkInventory(string $getraenkename, string $hersteller, int $lagerbestand){
//}


}





?>

</body>
</html>