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

include 'db.php';
include 'util.php';
include 'projnum.php';

if ($groupnum == -1)
{
  header("location:index.html");
  exit();
}


$id = $_SESSION['id'];
$sql = "SELECT * FROM bonus where groupnum=$groupnum order by entry";
$rs = mysqli_query($cid, $sql);

$counter = 0;

while ($assignment = mysqli_fetch_array($rs, MYSQLI_ASSOC))
{
  # echo "<br>Review for " . $assignment["entry"] . " is " . $_POST[$counter] . "<br>\n";
  $value = $_POST[$counter];
  if (strlen($value) < 1)
    $value = 0;

  $entry = $assignment["entry"];
  $id = $_SESSION['id'];
  $sql = "delete from bonuspoints where reviewid=$id and entry=$entry";
  mysqli_query($cid, $sql);

  $sql = "insert into bonuspoints(reviewid, entry, score) values($id, $entry, $value)";
  # echo $sql . "<br><br>";
  $rs2 = mysqli_query($cid, $sql);
  $counter = $counter + 1;
}

header("location:bonus.php");
exit();
?>
