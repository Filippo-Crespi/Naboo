<?php

$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "naboo";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$conn) {
  die("sei morto" . mysqli_connect_error());
}
