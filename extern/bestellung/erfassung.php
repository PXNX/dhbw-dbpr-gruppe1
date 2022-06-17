<?php
//Datenbankverbindung und Start der Session
session_start();
include '../../common/db.inc.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Patricia Schäle">
    <title>Erfassungsformular</title>
    <!--
    Allgemeine Erläuterung:
    Durch die Eingabe vom Kunden wird ein Erfassungsformular erstellt, das genau so viele Eingabezeilen hat,
    wie zuvor als Anzahl an Positionen eingegeben wurde. In jeder
    Zeile kann ein Getränk ausgewählt werden, wobei nur Getränke angeboten werden, die beim
    ausgewählten Markt einen positiven Lagerbestand haben. Auch kann bei jedem Getränk die zu
    bestellende Anzahl eingegeben werden. Es gibt einen Prüfbutton der prüft, ob die Lagerbestände des
    ausgewählten Markts reichen.
     -->
</head>
<body>

<h1 style="text-align:center">Bestellung erfassen</h1>

<?php

?>
<!-- Zur Formatierung des Formulars wurde eine Tabellenform gewählt. Positionsnummer, Getränk und Anzahl sollen die Spaltennamen darstellen. -->
<form action="pruefung.php" method="post">
    <table>
        <tr>
            <td>Positionsnummer</td>
            <td>Getränk</td>
            <td>Anzahl</td>
        </tr>
        <!-- Wurde ein  Markt und eine Anzahl eingegeben, werden sie in der Session abgelegt. -->
        <?php include '../../common/db.inc.php';
        if (isset($_POST['extern-marktid']) && isset($_POST['positionsnr'])){
            $_SESSION['extern-marktid'] = $_POST['extern-marktid'];
            $_SESSION['positionsnr'] = $_POST['positionsnr'];
        }

        //Der if-Block dient zur Erstellung des Tabelleninhaltes.
        //Dazu werden zuerst die in den Session abgelegten Daten in variablen abgelegt.
        if(isset($_SESSION['extern-marktid']) && isset($_SESSION['positionsnr'])){

            $marktid = $_SESSION['extern-marktid'];
            $positionsnr = $_SESSION['positionsnr'];

            //Datenbankabfrage soll positiven Lagerbestand bei den Getränken der Märkte sicherstellen
            //Marktid wird dabei als variable in der Abfrage gewählt
            //In der Ausführung nimmt die variable marktid den Sessionwert des jeweiligen marktes
            //Direkte Abfrage nach den Getränken eines spezifischen Marktes mit positivem Lagerbestand
            $query = $db->prepare("SELECT * FROM getraenk g, fuehrt f WHERE g.getraenkename=f.getraenkename and g.hersteller=f.hersteller and f.marktid = :marktid and f.lagerbestand>0");
            $query->execute([
                ':marktid' => $marktid]);
            $getraenk = $query->fetchAll();


            //Mit der for-Schleife werden die Felder für das Getränk und die Mengenangabe erzeugt
            //Dies geschieht mit Hilfe der in der Session abgelegten Positionsnummeranzahl.
            for ($i = 1;$i <= $positionsnr;$i++) {

                echo '<tr><td>'.$i.'</td><td><select id="getraenk" name="getraenk'.$i.'" required>';

                //Für jedes Getränk weißt ein Schlüssel auf eine hersteller-getraenkename Kombination
                foreach($getraenk as $key=> $row) {
                    $hersteller = $row["hersteller"];
                    $getraenkename = $row["getraenkename"];
                    echo'<option value="'.$key.'">'.$hersteller.' - '.$getraenkename.'</option>';
                }

                // Eingabefeld für die Bestellmengenangabe der jeweiligen hersteller-getraenkenamen
                echo'   </select></td>
            
            <td><input name="anzahl'.$i.'" size="10" maxlength="5" value="" required/></td>
        </tr>';
            }
        }
        ?>
    </table>

    <!-- Über post werden die Daten an die Lagerbestandsprüfung weitergeleitet um
    zu prüfen ob die ausgewählte Bestellmenge mit dem Lagerbestand vereinbar ist --->
    <button type="submit" name="pruefen">Lagerbestand prüfen</button>
</form>
</body>
</html>