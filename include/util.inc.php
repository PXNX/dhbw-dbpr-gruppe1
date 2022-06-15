<?php
/**
 * @author Felix Huber
 */

 function redirect($url): void{
     ob_start();
    header("Location: $url", true, 301);
    ob_end_flush();
    die();
}

?>