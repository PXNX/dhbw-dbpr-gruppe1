<?php
include "../auth/auth.inc.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Patricia Schäle">
    <title>Bestellung prüfen</title>
    <!--
   Allgemeine Erläuterung:
   Prüfung, ob die Lagerbestände des gewählten Markts ausreichen. Ist dies nicht der Fall,
   bekommt der Kunde eine Meldung und die Bestellung kann dann angepasst werden.
    -->
</head>
<body>

<h1 style="text-align:center">Bestellung prüfen</h1>

<?php

/**
 * @author Felix Huber
 */
function sum_amounts($pos, $getraenk): array
{

    $sumgetraenk = array();

    for ($i = 1; $i <= $pos; $i++) {

        if (isset($_POST['getraenk' . $i]) && isset($_POST['anzahl' . $i]) && $_POST['anzahl' . $i] > 0) {
            $a_getraenk = $getraenk[$_POST['getraenk' . $i]];
            $hersteller = $a_getraenk['hersteller'];
            $name = $a_getraenk['getraenkename'];

            //Wir setzen zu einem Hersteller zu einem bestimmten Getränkenamen erhöhen wir die Anzahl i oder wenn noch nichts da ist haben wir 0+Anzahl
            $sumgetraenk[$hersteller][$name] = ($sumgetraenk[$hersteller][$name] ?? 0) + $_POST['anzahl' . $i];
        }


    }
    return $sumgetraenk;
}

/**
 * @author Felix Huber
 */
function check_amounts($getraenk, array $sumgetraenk): bool
{
    $is_correct = true;

    foreach ($getraenk as $a_getraenk) {
        $hersteller = $a_getraenk['hersteller'];
        $name = $a_getraenk['getraenkename'];
        $lagerbestand = $a_getraenk['lagerbestand'];

        if (isset($sumgetraenk[$hersteller]) && isset($sumgetraenk[$hersteller][$name])) {

            $anzahl = $sumgetraenk[$hersteller][$name]; //?? 0
            if ($lagerbestand < $anzahl) {
                $is_correct = false;
                echo '<p>Das Getränk ' . $name . ' vom Hersteller ' . $hersteller . ' 
        ist nur begrenzt auf Lager. Der Lagerbestand beträgt ' . $lagerbestand . '. 
        Ihre Bestellmenge von ' . $anzahl . ' ist zu hoch.</p>';
            }
        }
    }
    return $is_correct;
}

if (isset($_SESSION['extern-marktid']) && isset($_SESSION['positionsnr'])) {
    include '../include/db.inc.php';
    $marktid = $_SESSION['extern-marktid'];
    $position = $_SESSION['positionsnr'];

//Statt * -> Auch g.getraenkename, g.hersteller und f.lagerbestand
    $query = $db->prepare("SELECT g.hersteller, g.getraenkename, f.lagerbestand FROM getraenk g, fuehrt f WHERE g.getraenkename=f.getraenkename and g.hersteller=f.hersteller and f.marktid= :marktid and f.lagerbestand>0");
    $query->execute([
        ':marktid' => $marktid]);
    $getraenk = $query->fetchAll();


//Wir summieren die Anzahl auf, falls jemand zweimal dasselbe Getränk gewählt hat an mehreren Bestellpositionen
    $sumgetraenk = sum_amounts($position, $getraenk);

//Passen die Anzahlen zum Lagerbestand?

    if (check_amounts($getraenk, $sumgetraenk)) {
        $_SESSION["order"] = $sumgetraenk;
        include "../include/util.inc.php";
        redirect("kunde_login.php");
    } else {
        echo '<a href="bestellung_erfassen.php">Bestellung überarbeiten</a>';
    }

}

?>

</form>
</body>

</html>