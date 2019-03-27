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

if ($_SESSION['id'] != 1) {
    header("location:login.html");
    exit();
}

echo "<h1>Project Grading</h1>\n";

function show_points($cid, $entry)
{
      $sql = "SELECT * from review where entry=$entry";
      $rs2 = mysqli_query($cid, $sql);
      $count = 0;
      $rated = 0;
      while ($reviews = mysqli_fetch_array($rs2, MYSQLI_ASSOC))
      {
        # echo "Review of " . $reviews["score"] . "<br>\n";
	$count = $count + $reviews["score"];
        if ($reviews["score"] > 0)
          $rated = $rated + 1;
      }
      if ($rated > 0)
        $avg = $count / $rated;
      else
        $avg = "---";
      echo "Points: " . $count . " ($rated ratings, average $avg)<br>\n";


      $sql = "SELECT * from points where entry=$entry";
      $points = mysqli_query($cid, $sql);
      $pt = mysqli_fetch_assoc($points);
      echo "Readme (10 points):" . $pt["readme"] . "<br>\n";
      echo "Screen shots (10 points):" . $pt["screenshots"] . "<br>\n";
      echo "Git Log (10 points):" . $pt["gitlog"] . "<br>\n";
      echo "Different-day checkin (40 points):" . $pt["commits"] . "<br>\n";
      echo "Voted on other projects (10 points):" . $pt["voted"] . "<br>\n";
      echo "Points from other voters (10 points):" . $pt["classpoints"] . "<br>\n";
      echo "Presentation (10 points):" . $pt["presentation"] . "<br>\n";
      echo "Project total (out of 100):" . $pt["total"] . "<br>\n";
}


include 'db.php';
include 'util.php';

if (isset($_GET['projnum']))
  $_SESSION['projnum'] = $_GET['projnum'];
if (!isset($_SESSION['projnum']))
  $_SESSION['projnum'] = 3;

$projnum = $_SESSION['projnum'];

echo "<a href=\"grader.php?projnum=1\">Project 1</a><br>\n";
echo "<a href=\"grader.php?projnum=2\">Project 2</a><br>\n";
echo "<a href=\"grader.php?projnum=3\">Project 3</a><br>\n";
echo "<a href=\"grader.php?projnum=4\">Project 4</a><br>\n";


$sql = "SELECT * FROM users";
$userlist = mysqli_query($cid, $sql);
while ($user = mysqli_fetch_array($userlist, MYSQLI_ASSOC))
{
  $uid = $user["id"];
  $name = $user["fn"] . " " . $user["ln"] . " " . $uid;
  $email = $user["email"];


  echo "<br>User " . $name . "<a href=\"mailto:$email\">"  . $user["email"] . "</a><br>\n";

  $sql2 = "SELECT entry, title from submissions where id=$uid and project=$projnum";
  $proj = mysqli_query($cid, $sql2);
  $projinfo = mysqli_fetch_array($proj, MYSQLI_ASSOC);
  $entrynum = $projinfo["entry"];
  
  $title = $projinfo["title"];
  echo "<a href=\"index.php?action=view&entry=$entrynum\">";
  echo "Project: " . $projinfo["entry"] . " " . $title . "</a><br>\n";
  if (!is_null($projinfo["entry"]))
    show_points($cid, $projinfo["entry"]);
}

echo "<hr>\n";

$sql = "SELECT * FROM users";
$userlist = mysqli_query($cid, $sql);
while ($user = mysqli_fetch_array($userlist, MYSQLI_ASSOC))
{
  $name = $user["fn"] . " " . $user["ln"];
  $email = $user["email"];

  # echo "<br>User " . $name . "  <a href=\"mailto:$email\">"  . $user["email"] . "</a><br>\n";
  $uid = $user["id"];
  $sql2 = "SELECT entry, title from submissions where id=$uid and project=$projnum";
  $proj = mysqli_query($cid, $sql2);
  $projinfo = mysqli_fetch_array($proj, MYSQLI_ASSOC);
  $entrynum = $projinfo["entry"];
  
  $title = $projinfo["title"];
  
  if (!is_null($projinfo["entry"]))
    echo "$uid $entrynum<br>\n";
    
}

?>
