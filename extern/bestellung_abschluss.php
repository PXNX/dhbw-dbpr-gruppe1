<?php
include "../auth/auth.inc.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8"> 
    <meta name="author" content="Patricia Schäle">
    <title>Bestellabschluss</title>
     <!--
    Allgemeine Erläuterung:
    Die Bestellungen sind bei Bestellabschluss mit Datum zu speichern, die Lagerbestände sind anzupassen.
     -->

<?php
     if(isset($_SESSION['order'])){

      
      
      include_once "../include/db.inc.php";
      
// Problemstelle
//Fatal error: Uncaught PDOException: SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`getraenkeverwaltung`.`bestellposition`, CONSTRAINT `bestellposition_ibfk_1` FOREIGN KEY (`bestellnr`) REFERENCES `bestellung` (`bestellnr`)) in C:\xampp\htdocs\extern\bestellung_abschluss.php:33 Stack trace: #0 C:\xampp\htdocs\extern\bestellung_abschluss.php(33): PDOStatement->execute(Array) #1 {main} thrown in C:\xampp\htdocs\extern\bestellung_abschluss.php on line 33
      $bestellnr = $db->lastInsertId();
      $positionsnr = 0;
      foreach($_SESSION['order'] as $hersteller => $getraenke){
            foreach($getraenke as $getraenkename => $anzahl){
                    $query = $db->prepare("CALL bestellposition_buchen(:bestellnr, :positionsnr, :anzahl, :getraenkename, :hersteller);");
      $result = $query->execute([
        ':bestellnr' => $bestellnr,
        ':positionsnr' =>$positionsnr,
        ':anzahl' => $anzahl,
        ':getraenkename' => $getraenkename,
        ':hersteller' => $hersteller
      ]);
                  $positionsnr += 1;
            }


   

      }
      
      }

      

?>
     

  </head>
  <body>
</body>
</html>