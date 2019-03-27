<?php

include "Parsedown/Parsedown.php";
$Parsedown = new Parsedown();

function handle_upload($cid)
{
  logmessage($cid, "Uploading assignment from " . $_SESSION["username"] . " " . $_SESSION["id"]);
  logmessage($cid, "Upload " . $title . " for " . $_SESSION["username"]);

  $project = mysqli_real_escape_string($cid, $_POST['project']);

  if ($project < 3)
  {
   echo "<b>Submissions only on projects 3 and above right now!</b><br>";
   return;
  }

  $dest = "submissions";
  # echo "CID is $cid";
  # echo "File is " . $_FILES['file1']['name'];
  # echo "Type is " . $_FILES['file1']['type'];
  $a = imageblob($_FILES['file1']['name'], $_FILES['file1']['tmp_name']);
  $a = addslashes($a);

  $b = imageblob($_FILES['file2']['name'], $_FILES['file2']['tmp_name']);
  $b = addslashes($b);

  $c = imageblob($_FILES['file3']['name'], $_FILES['file3']['tmp_name']);
  $c = addslashes($c);

  $title = mysqli_real_escape_string($cid,$_POST['title']);
  $git = mysqli_real_escape_string($cid, $_POST['git']);
  $project = mysqli_real_escape_string($cid, $_POST['project']);
  $collab1 = mysqli_real_escape_string($cid, $_POST['collab1']);
  $collab2 = mysqli_real_escape_string($cid, $_POST['collab2']);
  if ($_FILES['gitlog']['tmp_name'] != NULL)
    $gitlog = mysqli_real_escape_string($cid, file_get_contents($_FILES['gitlog']['tmp_name']));
  else
    $gitlog = "File not read";
  $gitlog = preg_replace('/[\x00-\x05\x7F-\xFF]/', '', $gitlog);

  if ($_FILES['markdown']['tmp_name'] != NULL)
    $markdown = mysqli_real_escape_string($cid, file_get_contents($_FILES['markdown']['tmp_name']));
  else
    $gitlog = "File not read";
  $markdown = preg_replace('/[\x00-\x05\x7F-\xFF]/', '', $markdown);
  $classnumber = mysqli_real_escape_string($cid, $_POST['classnumber']);

  $id = $_SESSION["id"];

  $sql = "SELECT entry from submissions WHERE id='$id' AND project='$project'";
  # echo "UPDATE QUERY:";
  # echo $sql;

  $rs = mysqli_query($cid, $sql);
  if (mysqli_num_rows($rs) == 0)
  {
    $sql = "INSERT INTO submissions (title,id,project,classnumber,collab1,collab2,git,data1,data2,data3,markdown,gitlog) VALUES ('$title','$id','$project','$classnumber','$collab1','$collab2','$git','$a', '$b', '$c','$markdown','$gitlog')";
    $rs = mysqli_query($cid, $sql);
    echo "Uploaded a new project<br>\n";
    logmessage($cid, "Upload " . $title . " for " . $_SESSION["username"]);
  } else {
    $row = mysqli_fetch_assoc($rs);

    echo "Updated a prior submission " . $row["entry"] . "<br>\n";
    sql_replace($cid, "title", $title, $row["entry"]);
    sql_replace($cid, "classnumber", $classnumber, $row["entry"]);
    sql_replace($cid, "collab1", $collab1, $row["entry"]);
    sql_replace($cid, "collab2", $collab2, $row["entry"]);
    sql_replace($cid, "git", $git, $row["entry"]);
    sql_replace($cid, "data1", $a, $row["entry"]);
    sql_replace($cid, "data2", $b, $row["entry"]);
    sql_replace($cid, "data3", $c, $row["entry"]);
    sql_replace($cid, "markdown", $markdown, $row["entry"]);
    sql_replace($cid, "gitlog", $gitlog, $row["entry"]);
    logmessage($cid, "Revise " . $title . " for " . $_SESSION["username"]);    
  }

  # echo $sql;
}

function sql_replace($cid, $field, $newval, $entry)
{
  # echo "Replace $field";

  $sql = "UPDATE submissions SET $field = '$newval' WHERE entry=$entry";
  $rs = mysqli_query($cid, $sql);

  # echo $sql;

}

function imageblob($filename, $tmpname)
{
  $ext = pathinfo($filename, PATHINFO_EXTENSION);

  # echo "Filename is " . $filename . "<br>\n";
  # echo "Temp name is " . $tmpname . "<br>\n";
  # echo "Ext is " . $ext . "<br>\n";

  switch ($ext)
  {
  case "png":
  case "PNG":
    $source = imagecreatefrompng($tmpname);
    break;
  case "jpg":
  case "jpeg":
  case "JPG":
    $source = imagecreatefromjpeg($tmpname);
    break;
  case "gif":
  case "GIF":
    $source = imagecreatefromgif($tmpname);
    break;
  default:
  }

  $imsize = 800;

  if ($source)
  {
    list($width, $height) = getimagesize($tmpname);
    $dest = "profile_images";
    $newwidth = $width;
    $newheight = $height;
    if ($width > $imsize)
    {
      $scale = $imsize/$width;
      $newwidth = $width * $scale;
      $newheight = $height * $scale;
    }
    if ($newheight > $imsize)
    {
      $scale = $imsize/$newheight;
      $newwidth = $newwidth * $scale;
      $newheight = $newheight * $scale;
    }

    # echo "Scale " . $width . " by " . $height . " to " . $newwidth . " by " . $newheight;


    $thumb = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    ob_start();
    imagepng($thumb);
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
  }

  return 0;
}

function user_submissions()
{
  $sql = "SELECT entry from submissions where id='$id'";
  $rs = mysqli_query($cid, $sql);
}

function show_log($cid, $entry)
{
  $entry = mysqli_real_escape_string($cid,$entry);
  $sql = "SELECT * from submissions where entry='$entry'";
  # echo $sql;
  # echo "<br>";
  $rs = mysqli_query($cid, $sql);
  if (mysqli_num_rows($rs) == 1)
  {
    $row = mysqli_fetch_assoc($rs);
    # echo "Here is the log file for $entry<br>";
    echo "<pre>\n";
    echo htmlentities($row["gitlog"]);
    echo "</pre>\n";
    # echo "Done<br>";
  }
}

function show_submission($cid, $entry, $mode)
{
  $entry = mysqli_real_escape_string($cid,$entry);
  $sql = "SELECT * from submissions where entry='$entry'";
  # echo $sql;
  # echo "<br>";
  $rs = mysqli_query($cid, $sql);
  if (mysqli_num_rows($rs) == 1)
  {
    $row = mysqli_fetch_assoc($rs);
    echo "<h1>Program " . $row["project"] . ": " . $row["title"] . "</h1><br>\n";

    if ($mode == 0)
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
      echo "Total number of points: " . $count . " ($rated ratings, average $avg)<br>\n";
      echo "<b>Points for in-class voting and presentations yet to be computed!</b><br>\n";

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

    $url = $row["git"];
    if ($mode == 0)
        echo "<a href=\"$url\">GIT Repository: $url</a></br>\n";

    # echo "<pre>\n";
    # echo htmlentities($row["markdown"]);
    if (!$Parsedown)
      $Parsedown = new Parsedown();
    echo $Parsedown->text($row["markdown"]);
    # echo "\n</pre>\n<br>\n";

    echo "<img src=\"image.php?id=$entry\">\n";
    echo "<img src=\"image.php?id=$entry&image=2\">\n";
    echo "<img src=\"image.php?id=$entry&image=3\"><br>\n";

    $count = substr_count($row["gitlog"], "Date:");
    echo "$count check-ins<br>\n";
    # Patterns like Date: Wed Jan 30
    $gla = preg_split('/$\R?^/m', $row["gitlog"]);
    $glines = count($gla);
    # echo "GIT log has $glines lines<br>\n";
    $i = 0;
    $prevdate = "";
    $uniq = 0;
    foreach ($gla as $line)
    {
      $n = sscanf($line, "Date: %s %s %d", $day, $month, $date);
      # echo "Line $line<br>\nDates $dates<br>\n";
      if ($n == 3)
      {
        # echo "Saw date $day $month $date<br>\n";
	if (strcmp($prevdate, $date) != 0)
        {
	  $uniq = $uniq + 1;
          # echo "Unique<br>\n";
        }
        $prevdate = $date;
      }
    }

    echo "$uniq different days of coding.<br>\n";
    # echo "<a href=\"index.php?action=viewlog&ln=$entry\">View the log</a><br>\n";
    if ($mode == 0)
    {
      echo "\n<pre>\n";
      echo htmlentities($row["gitlog"]);
      echo "\n</pre>\n";
    }
  }

}

function show_existing($cid, $id)
{
  echo "Submitted programs<br>";
  $sql = "SELECT entry from submissions where id='$id' order by entry desc";
  $rs = mysqli_query($cid, $sql);
  while (list($entry) = mysqli_fetch_array($rs))
  {
    show_submission($cid, $entry, 0);
    echo "\n<hr>\n";
  }
  

}

function logmessage($cid, $message)
{
  $message = mysqli_real_escape_string($cid, $message);
  $sql = "INSERT INTO log (log) VALUES ('$message')";
  mysqli_query($cid, $sql);
}
