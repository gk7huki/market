<?php

  include 'config.php';

  $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
  if (!$conn) {
    die('Could not connect: ' . mysqli_connect_error());
  }

?>
