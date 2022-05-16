<?php



include "util.inc.php";

ob_start();
session_start();

if(!isset($_SESSION['market-id'])){
  redirect("../auth/login.php");
}
?>