<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if an ID is provided to delete
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Prepare and execute the deletion query
    $query = "DELETE FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $student_id);

    if (mysqli_stmt_execute($stmt)) {
        // Output a JavaScript alert for successful deletion
        echo "<script>alert('Student deleted successfully.'); window.location.href='view_student.php';</script>";
        exit();
    } else {
        // Output a JavaScript alert for failed deletion
        echo "<script>alert('Failed to delete student.'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    // Redirect back if no ID is provided
    header("Location: index.php");
    exit();
}
?>
