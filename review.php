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

echo "<h1>Project $projnum Reviews</h1>\n";
echo "Take a look through the following, and give a score from 1 to 5.  1 is not good, 5 is great.  Try to evaluate based on how much effort was put in; a semi-broken program that was built from scratch is more interesting than a slightly modified tutorial from the web.<br>Did the author put in a real effort, learn something, become a better programmer?  Give them some points!<br>\nAfter putting in scores, <b>make sure you hit submit at the bottom of the page</b> -- that uploads your scores to the web site.<br><hr>\n";
# echo "Reviewing for program 2 is done!<br>";
# exit();


include 'db.php';
include 'util.php';


$id = $_SESSION['id'];
$sql = "SELECT * FROM review where reviewid = '$id' AND project=$projnum";
$rs = mysqli_query($cid, $sql);
echo "<a href=\"index.php\">Home</a><br>\n";
echo "<a href=\"review.php\">Refresh</a><br>\n";
echo "There are " . mysqli_num_rows($rs) . " assignments to look at.<br>\n";

echo "<form action=\"reviewfile.php\" method=\"post\">";

$counter = 0;

while ($assignment = mysqli_fetch_array($rs, MYSQLI_ASSOC))
{
  echo "<hr>\n";
  # echo "Review project " . $assignment["entry"] . " " . $assignment["authorid"] . "<br>\n";
  $value = $assignment["score"];
  echo "<b>Score (1 is low, 5 is amazing) : </b>";
  echo "<input type=\"number\" name=\"$counter\" value=\"$value\" min=\"1\" max=\"5\">\n";
  show_submission($cid, $assignment["entry"], 1);
  $counter = $counter + 1;
}

echo "<button type=\"submit\" name=\"Submit\">Upload</button>\n";


?>