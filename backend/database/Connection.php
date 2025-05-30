<?php

require_once('ConnConfig.php');

$conn=mysqli_connect($dbhost,$dbuser,$dbpassword,$dbname);

if(!$conn){

    die("sei morto".mysqli_connect_error());

}
?>