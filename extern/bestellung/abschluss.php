<?php
session_start();
include '../common/db.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Patricia Schäle">
    <title>Bestellabschluss</title>

    <!--
    ToDo:
    - try-cath Blöcke für die db-Verbindungen/Abfragen
    - Kommentare checken
    - Namensgebung eindeutig
    -->


    <!--
   Allgemeine Erläuterung:
   Die Bestellungen sind bei Bestellabschluss mit Datum zu speichern, die Lagerbestände sind anzupassen.
   Die Lagerbestandsanpassung erfolgt mit einem Trigger.
    -->

    <?php
    if(isset($_SESSION['order']) && isset($_SESSION['extern-marktid']) && isset($_SESSION['mailadresse'])){

        include_once "../common/db.inc.php";


        //Bestellung wird angelegt mit aktuellem Datum
        $query = $db->prepare("INSERT INTO bestellung(bestelldatum, marktid, mailadresse) VALUES(:bestelldatum, :marktid, :mailadresse);");
        $result = $query->execute([
            ':bestelldatum' => date('Y-m-d'),
            ':marktid' => $_SESSION['extern-marktid'],
            ':mailadresse' =>$_SESSION['mailadresse']
        ]);


        //Bestellpositionen zur Bestellung werden angelegt
        $bestellnr = $db->lastInsertId();
        $positionsnr = 1;
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

        echo'<h1>Ihre Bestellung wurde erfolgreich abgeschlossen!</h1>';

    }

    ?>

</head>
<body>
</body>
</html>