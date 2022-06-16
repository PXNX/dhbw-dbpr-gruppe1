<!DOCTYPE html>
<html>
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

<h1 style="text-align:center"><p>Erfassungsformular erstellen</p></h1>

<!-- Die Inhalte werden per form-Tag an die Erfassungsdatei übertragen (Hier findet dann der Erstellungsprozess des Formulars statt) -->
<form action="extern/bestellung_erfassen.php" method="post">

    <!-- Auswahlliste für den Markt. Ein Markt besteht hier aus Marktid-Marktname. -->
    <label for="market-name"><b>Markt:</b></label>
    <select id="market-name" name="marktid">
        <?php

        // Warum nochmals include genau hier?
        // Was bringt das include genau (Verbindung zur Datenbank?)

        //Datenbankabfrage die alle Märkte ausliest. Ergebnisse werden in dem Array $markt abgelegt
        //PDOStatement::fetchAll gibt alle Zeilen aus der Ergebnismenge zurück. Der Parameter PDO::FETCH_ASSOC weist PDO an, das Ergebnis als assoziatives Array zurückzugeben. Die Array-Schlüssel entsprechen Ihren Spaltennamen.
        include 'include/db.inc.php';
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
            <td><label for="position"><b>Anzahl Bestellpositionen:</b></label></td>
            <td><input type="number" min="1" name="position" size="40" maxlength="5"
                       value='<?php echo $_SESSION['position']; ?>' required/><br></td>
        </tr>
    </table>
    <br>
    <!-- Daten werden an bestellung_erfassen.php übertragen --->
    <button type="submit" name="erfassen">Erfassungsformular erstellen</button>
    </br>
</form>
</body>
</html>

