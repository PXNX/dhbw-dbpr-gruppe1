<?php
/**
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