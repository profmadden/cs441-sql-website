<?php
//DB Connection information
$h = gethostname();

if (strcasecmp($h, "local_testing_machine")==0)
{
  $dbname="your_database";
  $dbuser="your_user";
  $dbpwd="your_password";
  $host="localhost";
  // Connect to the database
  $cid = mysqli_connect($host,$dbuser,$dbpwd,$dbname);
}
else
{
  $dbname="deploy_database";
  $dbuser="deploy_user";
  $dbpwd= file_get_contents("../../../.mysql_access2");
  $host="deploy_machine.com";
  // Connect to the database
  $cid = mysqli_connect($host,$dbuser,$dbpwd,$dbname);

}
if (!$cid)
  {
    print "ERROR: " . mysqli_error() . "n";
    exit();
  }
else
  {

  }
?>
