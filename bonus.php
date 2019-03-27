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

include 'projnum.php';

echo "<h1>Project $projnum Presentations</h1>\n";

if ($groupnum == -1)
{
  echo "Reviewing for program $projnum will start shortly!<br>";
  exit();
}

echo "Rate each of the presentations (1 is low, 5 is high).<br>\n";


include 'db.php';
include 'util.php';


$id = $_SESSION['id'];
$sql = "SELECT * FROM bonus where groupnum=$groupnum order by entry";
$rs = mysqli_query($cid, $sql);
echo "<a href=\"index.php\">Home</a><br>\n";
echo "<a href=\"bonus.php\">Refresh</a><br>\n";
echo "There are " . mysqli_num_rows($rs) . " assignments to look at.<br>\n";

echo "<form action=\"bonusfile.php\" method=\"post\">";

$counter = 0;

while ($assignment = mysqli_fetch_array($rs, MYSQLI_ASSOC))
{
  echo "<hr>\n";
  # echo "Review project " . $assignment["entry"] . " " . $assignment["authorid"] . "<br>\n";
  $entry = $assignment["entry"];
  $id = $_SESSION["id"];
  $sql = "select * from bonuspoints where entry=$entry and reviewid=$id";
  $scored = mysqli_query($cid, $sql);
  if (mysqli_num_rows($scored) == 1)
  {
    $current = mysqli_fetch_array($scored, MYSQLI_ASSOC);
    $value = $current["score"];
  }
  else
    $value = 0;
  echo "<b>Score (1 is low, 5 is amazing) : </b>";
  echo "<input type=\"number\" name=\"$counter\" value=\"$value\" min=\"1\" max=\"5\">\n";
  show_submission($cid, $assignment["entry"], 1);
  $counter = $counter + 1;
}

echo "<button type=\"submit\" name=\"Submit\">Upload</button>\n";


?>