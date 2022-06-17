<?php
include '../common/auth.inc.php';
include '../common/db.inc.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Marcel Bitschi">
    <title>Lagerbestanderfassung</title>
    <!--Allgemeine Erläuterung:
    Hier kann der Lagerstand zu einem Getränk erfasst werden.
    -->
</head>
<body>

<h1 style="text-align:center">Lagerbestand erfassen</h1>

<form method="post" action="lagerbestand.php">
    <table>
        <!--Dropdown der möglichen Getränke-->
        <label for="getraenk"><b>Getränke:</b></label>
        <select id="getraenk" name="getraenk">

            <?php
            // DB Abfrage um die möglichen Getränke anzuzeigen
            try{
            $query = $db->query("SELECT * from getraenk");
            $getraenk = $query->fetchAll();
            } catch (Exception $e) {
                echo 'Es ist ein Fehler aufgetreten! Es können keine Getränke abgerufen werden.';
            }
            
            foreach ($getraenk as $row) {
                $hersteller = $row["hersteller"];
                $getraenkename = $row["getraenkename"];
                echo '<option value="' . $hersteller . '$$' . $getraenkename . '">' . $hersteller . ' - ' . $getraenkename . '</option>';

            }
            ?>
        </select>
        <br>

        <tr>
            <!--Eingabefeld für den neuen Lagerbestand -->
            <label for="lagerbestand"><b>Anzahl:</b></label>
            <input id="lagerbestand" name="lagerbestand" type="number" min="0">
        </tr>
    </table>
    <br>
    <input type="submit" value="erfassen"/>
</form>

<?php
// Wenn Getränk ausgewählt und Lagerbestand eingetragen sind, wird der neue Lagerbestand des Getränks in der Db gespeichert.
if (isset($_POST['getraenk']) && isset($_POST['lagerbestand'])) {
    $getraenk = explode("$$", $_POST['getraenk']);
    $hersteller = $getraenk[0];
    $getraenkename = $getraenk[1];
    $lagerbestand = $_POST['lagerbestand'];

    saveLagerbestand($getraenkename, $hersteller, $lagerbestand);
}

// Funktion um den Lagerbestand zu speichern
function saveLagerbestand(string $getraenkename, string $hersteller, int $lagerbestand) {
    include '../common/db.inc.php';
    try {
        // Insert Statement 
        $query = $db->prepare("insert into fuehrt(getraenkename,hersteller,lagerbestand,marktid) values(:getraenkename, :hersteller, :lagerbestand, :marktid) on duplicate key update lagerbestand = :lagerbestand");
        $result = $query->execute([
            'getraenkename' => $getraenkename,
            'hersteller' => $hersteller,
            'lagerbestand' => $lagerbestand,
            'marktid' => $_SESSION['marktid']]);
    } catch (Exception $e) {
       echo 'Es ist ein Fehler aufgetreten! Der Lagerbestand kann nicht erfasst werden.';
    }
     
   // Erfolgs- oder Misserfolgsmeldung
    if ($result) {
        echo 'Lagerbestand wurde erfolgreich gespeichert!';
    } else {
        echo 'Lagerbestand wurde nicht gespeichert!';
    }
}

?>
</body>
</html>