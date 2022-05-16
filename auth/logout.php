<?php
session_destroy();
header('Location: login.php', true, 301);
exit();
?>
