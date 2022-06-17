<?php
/**
 * Durch Zerstören der Session geht die dort gespeicherte <code>marktid</code> verloren. Man muss sich event einloggen.
 * @author Felix Huber
 */
session_destroy();
header('Location: login.php', true, 301);
exit();
?>
