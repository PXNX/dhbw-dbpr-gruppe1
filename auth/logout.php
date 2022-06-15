<?php
/**
 * @author Felix Huber
 */
session_destroy();
header('Location: login.php', true, 301);
exit();
?>
