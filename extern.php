<html>
  <head>
    <meta charset="UTF-8"> 
    <meta author="Patricia Schäle">
    <title>Erfassungsformular</title>
  </head>
  <body>

<h1 style="text-align:center"><p>Erfassungsformular erstellen</p></h1>
<form action="extern/bestellung_erfassen.php" method="post">
    
  
    <label for="market-name"><b>Markt:</b></label>
    <select id="market-name" name="marktid">  
<?php
include 'include/db.inc.php';
$statement = $db->query("SELECT * FROM markt");
$statement->execute([]);
$markt = $statement->fetchAll(PDO::FETCH_ASSOC); 

foreach($markt as $row) {
  $marktid = $row["marktid"];
  echo'<option value="'. $marktid.'">'.$marktid.' - '.$row["marktname"].'</option>';
}
?>
</select>
<table>
  <tr>
    <td><label for="position"><b>Anzahl Bestellpositionen:</b></label> </td>
    <td><input type="number" name="position" size="40" maxlength="5" value='<?php echo $_SESSION['position']; ?>' required/><br></td>
  </tr>
  </table><br>
    <button type="submit" name="erfassen" >Erfassungsformular erstellen</button></br>
  </form>

 </body>
</html>

