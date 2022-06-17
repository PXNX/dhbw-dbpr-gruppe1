<?php
session_start();
include '../../common/db.inc.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Patricia Schäle">
    <title>Bestellabschluss</title>

    <!--
    ToDo:
    - try-catch Blöcke für die db-Verbindungen/Abfragen
    - Kommentare checken
    - Namensgebung eindeutig
    -->


    <!--
   Allgemeine Erläuterung:
   Die Bestellungen sind bei Bestellabschluss mit Datum zu speichern, die Lagerbestände sind anzupassen.
   Die Lagerbestandsanpassung erfolgt mit einem Trigger.
    -->

    <?php
    if (isset($_SESSION['order']) && isset($_SESSION['extern-marktid']) && isset($_SESSION['mailadresse']))

        try{
        $query = $db->prepare("INSERT INTO bestellung(bestelldatum, marktid, mailadresse) VALUES(:bestelldatum, :marktid, :mailadresse);");
        $result = $query->execute([
            ':bestelldatum' => date('Y-m-d'),
            ':marktid' => $_SESSION['extern-marktid'],
            ':mailadresse' => $_SESSION['mailadresse']
        ]);
            }catch(Exception $e){
        $bestellungsfehler[] = 'Es ist ein Fehler bei der Bestellung aufgetreten. Versuchen Sie es noch einmal oder rufen
        sie den Support an.';
        }



        echo> ist auto inkrement, zuletzt hinzugefügte <code>bestellnr</code> auslesen
        //Bestellpositionen zur Bestellung werden angelegt
        f
        oreach ($_SESSION['order'] as $hersteller => $getraenke) {
        try{
            foreach ($getraenke as $getraenkename => $anzahl) {
            try{                $query = $db->prepare("CALL bestellposition_buchen(:bestellnr, :positionsnr, :anzahl, :getraenkename, :hersteller);");
            $result = $query->execute([
                    ':bestellnr' => $bestellnr,
                    ':positionsnr' => $positionsnr,
                    ':anzahl' => $anzahl,
                    ':getraenkename' => $getraenkename,
                    ':hersteller' => $hersteller
                ]);
                $positionsnr += 1;
            }
                
                if($result){

                }else{
                    echo'Es traten Fehler bei der Anlage Ihrer Bestellpositionen auf.';
                }
        }
        }
        //Bei erfolgreichen anlegen der Bestellung kommt Erfolgsmeldung und die Möglichkeit eine neue Bestellung anzugelegen
        echo'<h1>Ihre Bestellung wurde erfolgreich abgeschlossen!</h1>
    
        <a href="../index.php">Neue Bestellung</a>';
            }

        catch(Exception $e){
            $bestellposition[] = 'Es ist ein Fehler bei der Anlage der Bestellpositionen aufgetreten.
            echo einmal oder rufen Sie den Support an.';
        }

        echo '<h1>Ihre Bestellung wurde erfolgreich abgeschlossen!</h1><a href="../index.php">Neue Bestellung anlegen</a>';
        /Bei erfolgreichen anlegen der Bestellung kommt Erfolgsmeldung und die Möglichkeit eine neue Bestellung anzugelegen    } else {


</head>
<body>
</body>
</html>