<?php
# header("location:login.html");
# exit();

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

$id = $_SESSION['id'];
$sql = "SELECT * FROM review where reviewid = '$id' and project=$projnum";
$rs = mysqli_query($cid, $sql);

$counter = 0;

while ($assignment = mysqli_fetch_array($rs, MYSQLI_ASSOC))
{
  # echo "<br>Review for " . $assignment["entry"] . " is " . $_POST[$counter] . "<br>\n";
  $value = $_POST[$counter];
  if (strlen($value) < 1)
    $value = 0;

  $rid = $assignment["rid"];
  $sql = "update review set score=$value where rid=$rid";
  # echo $sql . "<br><br>";
  $rs2 = mysqli_query($cid, $sql);
  $counter = $counter + 1;
}

header("location:index.php");
exit();
?>
