<?php
include "../common/auth.inc.php";
include '../common/db.inc.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Marcel Bitschi">
    <title>Lagerbestanderfassung</title>
</head>
<body>

<h1 style="text-align:center">Lagerbestand erfassen</h1>

<form method="post" action="lagerbestand.php">
    <table>
        <label for="getraenk"><b>Getränke:</b></label>
        <select id="getraenk" name="getraenk">

            <?php
            $query = $db->query("SELECT * from getraenk");
            $getraenk = $query->fetchAll();

            foreach ($getraenk as $row) {
                $hersteller = $row["hersteller"];
                $getraenkename = $row["getraenkename"];
                echo '<option value="' . $hersteller . '$$' . $getraenkename . '">' . $hersteller . ' - ' . $getraenkename . '</option>';

            }
            ?>
        </select>
        <br>

        <tr>
            <label for="lagerbestand"><b>Anzahl:</b></label>
            <input id="lagerbestand" name="lagerbestand" type="number" min="0">
        </tr>
    </table>
    <br>
    <input type="submit" value="erfassen"/>
</form>

<?php

if (isset($_POST['getraenk']) && isset($_POST['lagerbestand'])) {
    $getraenk = explode("$$", $_POST['getraenk']);
    $hersteller = $getraenk[0];
    $getraenkename = $getraenk[1];
    $lagerbestand = $_POST['lagerbestand'];

    saveLagerbestand($getraenkename, $hersteller, $lagerbestand);
}

function saveLagerbestand(string $getraenkename, string $hersteller, int $lagerbestand)
{
    include "../common/db.inc.php";
    $query = $db->prepare("insert into fuehrt(getraenkename,hersteller,lagerbestand,marktid) values(:getraenkename, :hersteller, :lagerbestand, :marktid) on duplicate key update lagerbestand = :lagerbestand");
    $result = $query->execute([
        ':getraenkename' => $getraenkename,
        ':hersteller' => $hersteller,
        ':lagerbestand' => $lagerbestand,
        ':marktid' => $_SESSION['marktid']]);
    if ($result) {
        echo 'Lagerbestand wurde erfolgreich gespeichert!';
    }
}

?>
</body>
</html>