<?php
session_start();
$act = $_GET['action'];

if(!isset($_SESSION['id'])){
  if (($act != "check") && ($act != "register") && ($act != "reset"))
  {
    header("location:login.html");
    exit();
  }
}
if ($_SESSION['id'] != 1)
{
  echo "Admin page restricted.";
  exit();
}

echo "<h1>Admin</h1>\n";
include 'db.php';
include 'util.php';



?>
