<?php
session_start();
include '../common/db.inc.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Felix Huber">
    <title>Neuen Markt anlegen</title>
</head>
<body>

<h1 style="text-align:center">Neuen Markt anlegen</h1>
<form method="post" action="signup.php">

    <label for="marktid">Markt ID:</label>
    <input type="text" id="marktid" name="marktid" required><br><br>

    <label for="marktname">Markt Name:</label>
    <input id="marktname" name="marktname" required><br><br>

    <label for="marktkennwort">Markt Passwort:</label>
    <input id="marktkennwort" name="marktkennwort" type="password" required><br><br>

    <input id="market-signup" type="submit" value="registrieren">
</form>
<br><br>
<a href="login.php">Mitarbeiter anmelden</a>
<?php
if (isset($_POST['marktid']) && isset($_POST['marktname']) && isset($_POST['marktkennwort'])) {

    $marktid = $_POST['marktid'];
    $marktname = $_POST['marktname'];
    $marktkennwort = password_hash($_POST['marktkennwort'], PASSWORD_DEFAULT);

    if (marktid_exists($marktid)) {
        echo("<h1>Ein Markt mit ID $marktid existiert bereits. Bitte wählen Sie eine andere ID.");
        return;
    }

    if (marktname_exists($marktname)) {
        echo("<h1>Ein Markt mit Name $marktname existiert bereits. Bitte wählen Sie einen anderen Namen.");
        return;
    }

    try {
        $query = $db->prepare("INSERT into markt (marktid,marktname,marktkennwort) values(:marktid, :marktname, :marktkennwort)");
        $result = $query->execute([
            'marktid' => $marktid,
            'marktname' => $marktname,
            'marktkennwort' => $marktkennwort]);

        if ($result) {
            $_SESSION['marktid'] = $marktid;

            header('Location: index.php', true, 301);
            exit();
        }
    }catch (Exception $e){
        echo 'Registrierung war fehlerhaft.';
    }

    echo 'Registrierung konnte nicht durchgeführt werden.';
} else {

//Damit bei erstmaligem Seitenaufruf keine Warnung wegen inkorrekter Passworteingabe kommt. Ist keine AF, sondern eher
//ein Experiment.
    if (empty($_SERVER['HTTP_REFERER']) ||
        preg_match('$' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI'] . '$',
            $_SERVER['HTTP_REFERER'])
    ) {
        echo('<h1>Für die Registrierung Ihres Marktes werden eine von Ihnen gewählte, nicht bereits existierende Markt-Id, sowie einer frei wählbarer Marktname und Passwort benötigt.</h1>');
    }
}


/**
 * Prüft, ob Markt mit gegebenem Namen bereits existiert.
 * @author Felix Huber
 */
function marktname_exists($marktname): bool
{
    include '../common/db.inc.php';
    $query = $db->prepare("Select * from markt m where m.marktname=:marktname");
    $query->execute([
        ':marktname' => $marktname]);
    $result = $query->fetchAll();
    return count($result) !== 0;
}

/**
 * Prüft, ob Markt mit gegebener Id bereits existiert.
 * @author Felix Huber
 */
function marktid_exists($marktid): bool
{
    include '../common/db.inc.php';
    $query = $db->prepare("Select * from markt m where m.marktid=:marktid");
    $query->execute(['marktid' => $marktid]);
    $result = $query->fetchAll();
    return count($result) !== 0;
}

?>
</body>
</html>