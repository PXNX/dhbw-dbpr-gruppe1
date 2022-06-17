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
      Zur Bestellungserfassung muss der Kunde zunächst einen Markt und die Anzahl der Bestellpositionen auswählen.
      Dafür steht ihm eine Liste aus Marktid-Marktname für die Marktauswahl und ein Input-Feld für die Positionsanzahl zur verfügung.
    -->
</head>
<body>

<h1 style="text-align:center">Erfassungsformular erstellen</h1>

<!-- Die Inhalte werden per form-Tag an die Erfassungsdatei übertragen (Hier findet dann der Erstellungsprozess des Formulars statt) -->
<form action="bestellung/erfassung.php" method="post">

    <!-- Auswahlliste für den Markt. Ein Markt besteht hier aus Marktid-Marktname.
        extern-marktid: extern, weil es sonst mit id von Mitarbeitern korrelliert -->
    <label for="extern-marktid"><b>Markt:</b></label>
    <select id="extern-marktid" name="extern-marktid">
        <?php

        //Datenbankabfrage die alle Märkte ausliest. Ergebnisse werden in dem Array $markt abgelegt
        //PDOStatement::fetchAll gibt alle Zeilen aus der Ergebnismenge zurück. Der Parameter PDO::FETCH_ASSOC weist PDO an, das Ergebnis als assoziatives Array zurückzugeben. Die Array-Schlüssel entsprechen Ihren Spaltennamen.
        $statement = $db->query("SELECT * FROM markt");
        $statement->execute([]);
        $markt = $statement->fetchAll(PDO::FETCH_ASSOC);

        //Foreach Schleife legt die Marktid jedes in dem Array $markt abgelegt Datensatzes in ein $marktid Array
        foreach ($markt as $row) {
            $marktid = $row["marktid"];
            echo '<option value="' . $marktid . '">' . $marktid . ' - ' . $row["marktname"] . '</option>';
        }
        ?>
    </select>

    <!-- Eingabefeld für die Anzahl der Bestellpositionen. Eingabe erlaubt nur ganze Zahlen >= 1 -->
    <table>
        <tr>
            <td><label for="positionsnr"><b>Anzahl Bestellpositionen:</b></label></td>
            <td><input type="number" min="1" name="positionsnr" size="40"
                       value='<?php echo $_SESSION['positionsnr']; ?>' required/><br></td>
        </tr>
    </table>
    <br>
    <!-- Daten werden an bestellung_erfassen.php übertragen --->
    <button type="submit" name="erfassen">Erfassungsformular erstellen</button>
    <br>
</form>
</body>
</html>