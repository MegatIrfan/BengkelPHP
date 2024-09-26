<?php
// Database connection file
$conn = mysqli_connect("localhost", "root", "", "student_crud");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
