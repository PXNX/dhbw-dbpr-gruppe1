<?php
include '../common/auth.inc.php';
include '../common/db.inc.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Marcel Bitschi">
    <title>Getränkeerfassung</title>
    <!--
      Hier können Getränke mit ihrem Namen, einem Preis, dem Hersteller und der zugehörigen Kategorie gespeichert werden.
-->
</head>
<body>

<h1 style="text-align:center">Getränk erfassen</h1>

<form method="post" action="getraenk.php">
    <table>

        <!-- Eingabefeld Getränkename-->
        <tr>
            <td><label for="getraenkename"><b>Getränkename:</b></label></td>
            <td><input id="getraenkename" name="getraenkename" size="40" maxlength="60" value="" required/><br></td>
        </tr>

        <!-- Eingabefeld Getränkepreis-->
        <tr>
            <td><label for="preis"><b>Preis (in EUR):</b></label></td>
            <td><input id="preis" name="preis" type="number" min="0" step=".01" size="40" maxlength="5" value=""
                       required/><br></td>
        </tr>

        <!-- Eingabefeld Getränkehersteller-->
        <tr>
            <td><label for="hersteller"><b>Hersteller:</b></label></td>
            <td><input id="hersteller" name="hersteller" size="40" maxlength="40" value=""/><br></td>
        </tr>

        <!-- Enum der Getränkekategorien. Hartkodiert -->
        <br>
        <form>
            <label for="kategorie"><b>Kategorie:</b></label>
            <select id="kategorie" name="kategorie">
                <option value="wasser">Wasser</option>
                <option value="saft">Saft</option>
                <option value="limonade">Limonade</option>
                <option value="wein">Wein</option>
                <option value="bier">Bier</option>
                <option value="sonstiges">Sonstiges</option>
            </select>
            <br>
            <input type="submit" value="erfassen"/>

        </form>
        <?php

        // Überprüfung, ob alle Werte eingetragen sind, dann Einfügen des Getränks in die DB
        if (isset($_POST['getraenk']) && ($_POST['preis']) && ($_POST['hersteller']) && ($_POST['kategorie'])) {
            $getraenkename = $_POST['getraenkename'];
            $preis = $_POST['preis'];
            $hersteller = $_POST['hersteller'];
            $kategorie = $_POST['kategorie'];


            try {
                $query = $db->prepare("INSERT into getraenk (getraenkename,hersteller,preis,kategorie) values(:getraenkename,:hersteller,:preis,:kategorie);");
         $result =   $query->execute(['getraenkename' => $getraenkename,
               'hersteller' => $hersteller,
                               'preis' => $preis,
               'kategorie' => $kategorie]);
            if($result){
               echo 'Das Getränk wurde erfolgreich angelegt.';
            }
            } catch (Exception $e) {
                echo 'Es ist ein Fehler aufgetreten! Bitte die Werte überprüfen und erneut versuchen.';
            }
            
        }
        ?>
    </table>
</body>
</html>