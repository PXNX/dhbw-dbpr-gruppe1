<?php
/**
 * @author Felix Huber
 */
session_start();

if (!isset($_SESSION['market-id'])) {
    header("Location: ../auth/login.php", true, 301);
    exit();
}
?>