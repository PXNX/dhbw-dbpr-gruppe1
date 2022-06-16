<?php
include "common/auth.inc.php";
include 'common/db.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Marcel Bitschi">
    <title>Lagerbestanderfassung</title>
</head>
<body>

<h1 style="text-align:center"><p>Lagerbestand erfassen</p></h1>

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
    var_dump($getraenk, $hersteller, $getraenkename, $lagerbestand);
    saveLagerbestand($getraenkename, $hersteller, $lagerbestand);

}

function saveLagerbestand(string $getraenkename, string $hersteller, int $lagerbestand)
{
    include "common/db.inc.php";
    $query = $db->prepare("insert into fuehrt(getraenkename,hersteller,lagerbestand,marktid) values(:getraenkename, :hersteller, :lagerbestand, :marktid) on duplicate key update lagerbestand = :lagerbestand");
    $result = $query->execute([
        ':getraenkename' => $getraenkename,
        ':hersteller' => $hersteller,
        ':lagerbestand' => $lagerbestand,
        ':marktid' => $_SESSION['market-id']]);
    if ($result) {
        echo 'Lagerbestand wurde erfolgreich gespeichert!';
    }
}

// Unnötig?
function getraenk_exists($getraenkename, $hersteller): bool
{
    include "common/db.inc.php";
    $query = $db->prepare("Select * from fuehrt where getraenkename=? and hersteller=? and marktid=?");
    $query->execute([$getraenkename, $hersteller, $_SESSION['market-id']]);
    $result = $query->fetchAll();
    return count($result) !== 0;
}

?>


</body>
</html>