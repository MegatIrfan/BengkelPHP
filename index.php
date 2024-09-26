<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    
    <title>Student List</title>
   
</head>
<body>
    <div class="container">
        <h1>Welcome! <?php echo $_SESSION['username'] ?></h1>
        <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to logout?');">Logout</a>

        <h2>Student List</h2>
       

        <!-- Buttons to add and view students -->
        <div class="button-group">
            <a href="add_student.php" class="button">Add Student</a>
            <a href="view_student.php" class="button">View Students</a>
        </div>
    </div>
</body>
</html>
