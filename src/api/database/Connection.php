<?php

require('ConnConfig.php');

$conn = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
