<?php
/**
 * Verbindung zur Datenbank. Die Verbindung kann in anderen Dateien verwendet werden.
 * @author Felix Huber
 */
try {
    //alternativ via public static
    $db = new PDO('mysql:host=localhost;port=3306;dbname=getraenkeverwaltung', 'root', 'root');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>