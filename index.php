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

if ($act == "check")
{
  $username=$_POST['username'];
  $password=$_POST['password'];
  $code = mysqli_real_escape_string($cid,$_POST['code']);

 $clean_username = strip_tags(strtolower(stripslashes(mysqli_real_escape_string($cid,$username))));

  $sql = "SELECT * FROM users where email='$clean_username'";
  $rs = mysqli_query($cid, $sql);
  if (mysqli_num_rows($rs) == 1)
  {
    $row = mysqli_fetch_assoc($rs);
    if ($password == $row["passcode"])
    {
      # echo "Matched passcode.";
      $_SESSION['sessionkey'] = "4444";
      $_SESSION['id'] = $row["id"];
      logmessage($cid, "Log in for " . $username);
      $_SESSION['username'] = $clean_username;
      header("location:index.php");
      exit();
    }
    else
    {
      header("location:login.html");
      exit();
    }
  }
  else
  {
    header("location:login.html");
    exit();    
  }
}

if ($act == "logout")
{
  session_destroy();
  header("location:login.html");
  exit();
}

if ($act == "viewlog")
{
  $ln = $_GET["ln"];
  // echo "View log for $ln<br>";
  # show_log($cid, $ln);
  exit();
}

# echo "Action is " . $act . "<br>";



if ($act == "upload")
{
  handle_upload($cid);
}

echo "The thumbs-up/down links is live.  If you submitted project 1, you should be able to see eight other randomly selected projects from the class.  Give a point to about the \"top half\" of what you see, and then upload your rankings.<br>\n";
echo "<a href=\"index.php\">Refresh</a><br>\n";
echo "<a href=\"index.php?action=logout\">Log out.</a><br>\n";
echo "<a href=\"submit.php\">Submit a program for grading</a><br>\n";
echo "<a href=\"review.php\">Review/Vote on Program Submissions</a><br>\n";
echo "<a href=\"bonus.php\">Presentation Review and Scoring!</a><br>\n";

if ($_SESSION["id"] == 1)
{
  echo "<a href=\"grader.php\">Grade Review</a><br>\n";  
}


if ($act == "view")
{
  if ($_SESSION["id"] == 1)
  {
    $entry = $_GET["entry"];
    $entry = mysqli_real_escape_string($cid,$entry);
    show_submission($cid, $entry, 0);
  }
  exit();
}

show_existing($cid, $_SESSION["id"]);


?>

<br>
