<?php
$conn = mysqli_connect("localhost", "root", "", "rumah_sakit");

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
