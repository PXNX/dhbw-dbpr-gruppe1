<?php
/**
 * Startet die Session. Wenn keine <code>marktid</code> hinterlegt, bspw. wenn nicht eingeloggt, dann Weiterleitung zum Login.
 * Dies kann in anderen Dateien verwendet werden. Da der Header einer HTML-Anfrage manipuliert wird, muss das <code>include</code>
 * dieser Datei vor dem Body der Seite vorkommen.
 * @author Felix Huber
 */

session_start();

if (!isset($_SESSION['marktid'])) {
    header("Location: ../intern/login.php", true, 301);
    exit();
}
?>