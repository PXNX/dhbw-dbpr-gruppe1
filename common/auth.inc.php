<?php
/**
 * @author Felix Huber
 */
session_start();

if (!isset($_SESSION['marktid'])) {
    header("Location: ../intern/login.php", true, 301);
    exit();
}
?>