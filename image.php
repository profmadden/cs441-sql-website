<?php
include_once 'db.php';

$id = mysqli_real_escape_string($cid, $_GET['id']);
$im = $_GET['image'];
$entry = "data1";
if ($im == "2")
  $entry = "data2";
if ($im == "3")
  $entry = "data3";

$query = "SELECT * FROM submissions WHERE entry=$id";
$result = mysqli_query($cid, $query);

if (mysqli_num_rows($result) != 1)
  header("location:battery.png");
else
  {
    // list($id, $file, $type, $size,$content) = $result->fetch_array(MYSQLI_NUM);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $len = strlen($row[$entry]);
    if ($len < 10)
      header("location:battery.png");
    else
      {
        header("Content-length: " . strlen($row[$entry]));
        header("Content-type: image/png");
        header("Content-Disposition: attachment; filename=image.png");
        ob_clean();
        flush();
        echo $row[$entry];
      }
  }
mysqli_close($cid);
exit;
?>
