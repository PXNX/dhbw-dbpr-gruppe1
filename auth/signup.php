<?php 
include "auth.inc.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Felix Huber">
    <title>Neuen Markt anlegen</title>
</head>
<body>

  <h1>Neuen Markt anlegen</h1>
<form method="post" action="signup.php">

<label for="market-id">Markt ID:</label>
<input type="text" id="market-id" name="market-id" required><br><br>

  <label for="market-name">Markt Name:</label>
<input  id="market-name" name="market-name" required><br><br>

<label for="market-pass">Markt Passwort:</label>
<input  id="market-pass" name="market-pass" type="password" required><br><br>

<input  id="market-signup"  type="submit" value="registrieren"> 
</form></br></br><a href="login.php">Mitarbeiter anmelden</a>


<?php
include "../include/db.inc.php";

if(isset($_POST['market-id']) && isset($_POST['market-name']) && isset( $_POST['market-pass']) ){

  $input_id =   $_POST['market-id'];
  $input_name = $_POST['market-name'];
  $input_pass = password_hash( $_POST['market-pass'], PASSWORD_DEFAULT);

  if(market_id_exists($input_id)){
    echo("<h1>Ein Markt mit ID ". $input_id . " existiert bereits. Bitte wählen Sie eine andere ID.");
    return;
  }

  if(market_name_exists($input_name)){
    echo("<h1>Ein Markt mit Name ". $input_name . " existiert bereits. Bitte wählen Sie einen anderen Namen.");
    return;
  }

  $query = $db->prepare("INSERT into markt (marktid,marktname,marktkennwort) values(?,?,?)");
  $query->execute([ $input_id,$input_name,$input_pass]);
  $test= $query->fetchAll();
  var_dump($test);

  $_SESSION["market-id"] = $input_id;



  header('Location:../intern.php', true, 301);
  exit();


 


}else{

if( empty($_SERVER['HTTP_REFERER']) ||
  preg_match( '$'. (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/'.  $_SERVER['REQUEST_URI'] . '$',
  $_SERVER['HTTP_REFERER'])
){
 echo("<h1>Für die Registrierung Ihres Marktes werden eine von Ihnen gewählte, nicht bereits existierende Markt-Id, sowie einer frei wählbarer Marktname und Passwort benötigt.</h1>");
}
 }





//muss ne sql function werden meh
 function  market_name_exists($market_name):bool{
  include "../include/db.inc.php";
  $query = $db->prepare("SElect * from markt m where m.marktname=?");
  $query->execute([$market_name]);
  $result= $query->fetchAll();
  return count($result) !== 0;
 }

 function  market_id_exists($market_id):bool{
  include "../include/db.inc.php";
  $query = $db->prepare("SElect * from markt m where m.marktid=?");
  $query->execute([$market_id]);
  $result= $query->fetchAll();
  return count($result) !== 0;
 }

?>
</body>
</html>