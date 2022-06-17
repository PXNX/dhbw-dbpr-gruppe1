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
   Allgemeine Erläuterung:
   Die Bestellungen sind bei Bestellabschluss mit Datum zu speichern, die Lagerbestände sind anzupassen.
   Die Lagerbestandsanpassung erfolgt mit einem Trigger. Bei erfolgreichen anlegen der Bestellung kommt Erfolgsmeldung
    und die Möglichkeit eine neue Bestellung anzuglegen.
    -->
</head>
<body>
<?php
/**
 *Anlegen der Bestellung. Die bestellnr ist auto inkrement, zuletzt hinzugefügte <code>bestellnr</code> auslesen.
 * @author Patricia Schäle
 */
function bestellung_anlegen(): int
{
    include '../../common/db.inc.php';
    try {
        $query = $db->prepare("INSERT INTO bestellung(bestelldatum, marktid, mailadresse) VALUES(:bestelldatum, :marktid, :mailadresse);");
        $result = $query->execute([
            'bestelldatum' => date('Y-m-d'),
            'marktid' => $_SESSION['extern-marktid'],
            'mailadresse' => $_SESSION['mailadresse']
        ]);
        if ($result) {
            return $db->lastInsertId();
        }
    } catch (Exception $e) {
        echo "Anlegen der Bestellung war fehlerhaft.";
    }
    echo "Die Bestellung konnte nicht gespeichert werden.";
    exit();
}

if (isset($_SESSION['order']) && isset($_SESSION['extern-marktid']) && isset($_SESSION['mailadresse'])) {

    $bestellnr = bestellung_anlegen();

    $positionsnr = 1;
    $fehler_aufgetreten = false;

//Bestellpositionen zur Bestellung werden angelegt
    foreach ($_SESSION['order'] as $hersteller => $getraenke) {
        foreach ($getraenke as $getraenkename => $anzahl) {
            try {
                $query = $db->prepare("CALL bestellposition_buchen(:bestellnr, :positionsnr, :anzahl, :getraenkename, :hersteller);");
                $result = $query->execute([
                    'bestellnr' => $bestellnr,
                    'positionsnr' => $positionsnr,
                    'anzahl' => $anzahl,
                    'getraenkename' => $getraenkename,
                    'hersteller' => $hersteller
                ]);
                $positionsnr += 1;

                if (!$result) {
                    $fehler_aufgetreten = true;
                }

            } catch (Exception $e) {
                echo 'Es ist ein Fehler bei der Anlage der Bestellpositionen aufgetreten';
                exit();
            }


            if ($fehler_aufgetreten) {
                echo 'Es traten Fehler bei der Anlage Ihrer Bestellpositionen auf.';
            } else {
                echo '<h1>Ihre Bestellung wurde erfolgreich abgeschlossen!</h1><br><a href="../index.php">Neue Bestellung</a>';
            }
        }
    }
} else {
    echo "Bestellung, Marktid oder Mailadresse wurden nicht übergeben.";
}

?>
<body>
</html>