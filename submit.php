<?php
session_start();
$act = $_GET['action'];

if(!isset($_SESSION['sessionkey'])){
  if (($act != "check") && ($act != "register") && ($act != "reset"))
  {
    header("location:login.html");
    exit();
  }
}

?>

<h2>Submissions are now open for Project 4</h2>

Upload your program.  After it uploads, you should see it on your `home page,' with your readme, the screen shots, and the git log.  Your git log will be parsed to calculate the number of commits and days you hacked -- if these numbers don't look right, check to see if you have some crazy unicode characters, or something like that (the parser is expecting regular ASCII).
<br>
On Thursday morning, review assignments will be available -- doing the review is a small part of your grade.  Try to get your reviews done by Thursday evening (say 7pm?), and then we'll have the top ranked projects do demos on Friday.


<form action="index.php?action=upload" method="post" enctype="multipart/form-data">
Program Title: <input type="text" name="title" /><br>
Git URL: <input type="text" name="git" /><br>
Project Number: <input type="number" name="project" min="4" max="4" value="4" /><br>
<input type="radio" name="classnumber" value="0"> 8:30am Class<br>
<input type="radio" name="classnumber" value="1" checked> 9:40am Class<br>
Screenshot 1: <input type="file" name="file1" /><br>
Screenshot 2: <input type="file" name="file2" /><br>
Screenshot 3: <input type="file" name="file3" /><br>
Teammate: <input type="text" name="collab1"><br>
Teammate: <input type="text" name="collab2"><br>
Readme.md file: <input type="file" name="markdown" /><br>
git log --stat file:  <input type="file" name="gitlog" /><br>
<button type="submit" name="Upload">Upload</button>
</form>

