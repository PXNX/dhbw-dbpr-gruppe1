<?php
session_start();
include '../common/db.inc.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Patricia Schäle">
    <title>Erfassungsformular</title>
    <!--
      Allgemeine Erläuterung:
      Zur Bestellungserfassung muss der Kunde zunächst einen Markt und die Anzahl der Bestellpositionen auswählen. Dafür steht ihm eine Liste aus
      <code>marktid-marktname</code> für die Marktauswahl und ein Input-Feld für die Anzahl der <code>positionsnr</code>n in der Bestellung zur Verfügung.
    -->
</head>
<body>

<h1 style="text-align:center">Erfassungsformular erstellen</h1>

<!-- Die Inhalte werden per <code>method="post"</code> an die Erfassungsdatei übertragen (Hier findet dann der Erstellungsprozess des Formulars statt) -->
<form action="bestellung/erfassung.php" method="post">

    <!-- Auswahlliste für den Markt. Ein Markt besteht hier aus <code>marktid-marktname</code>.
        <code>extern-marktid</code>: Namensgebung extern, weil es sonst mit id von Mitarbeitern korreliert -->
    <label for="extern-marktid"><b>Markt:</b></label>
    <select id="extern-marktid" name="extern-marktid">
        <?php
        try {
            //Datenbankabfrage die alle Märkte ausliest. Ergebnisse werden in dem Array <code>markt</code> abgelegt
            $statement = $db->query("SELECT * FROM markt");
            $statement->execute([]);
            $markt = $statement->fetchAll(PDO::FETCH_ASSOC);

            //Foreach Schleife legt die <code>marktid</code> jedes in dem Array <code>markt</code> abgelegten Datensatzes in ein <code>marktid</code> Array
            foreach ($markt as $row) {
                $marktid = $row["marktid"];
                echo '<option value="' . $marktid . '">' . $marktid . ' - ' . $row["marktname"] . '</option>';
            }
        } catch (Exception $e) {
            echo 'Es ist ein Fehler aufgetreten.
        Versuchen Sie es noch einmal oder rufen Sie den Support an.';
        }
        ?>
    </select>
    <br>
    <!-- Eingabefeld für die Anzahl der Bestellpositionen. Eingabe erlaubt nur ganze Zahlen >= 1 -->
    <label for="positionsnr"><b>Anzahl Bestellpositionen:</b></label></td>
    <input type="number" min="1" name="positionsnr" size="40"
           value='<?php echo $_SESSION['positionsnr']; ?>' required/><br></td>
    <code></code>
    <br>
    <!-- Daten werden an bestellung_erfassen.php übertragen --->
    <button type="submit" name="erfassen">Erfassungsformular erstellen</button>
    <br>
</form>
</body>
</html>