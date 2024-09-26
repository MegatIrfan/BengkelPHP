<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle adding student request
if (isset($_POST['add_student'])) {
    $name = htmlspecialchars($_POST['name']);
    $matric_number = htmlspecialchars($_POST['matric_number']);
    $class = htmlspecialchars($_POST['class']);
    $photo = $_FILES['photo'];

    // Validate file upload
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $allowed_extensions = ['jpeg', 'jpg', 'png', 'gif'];
    $file_type = mime_content_type($photo['tmp_name']);
    $file_extension = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION)); // Ensure lowercase
    $max_file_size = 5000000; // 5MB
    
    if (in_array($file_type, $allowed_types) && in_array($file_extension, $allowed_extensions) && $photo['size'] <= $max_file_size) {
        $new_filename = uniqid() . '.' . $file_extension;
        $target = "upload/" . $new_filename;

        if (move_uploaded_file($photo['tmp_name'], $target)) {
            $query = "INSERT INTO students (name, matric_number, class, photo) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $matric_number, $class, $new_filename);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "Student added successfully.";
            } else {
                $_SESSION['error'] = "Database Error: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['error'] = "Failed to upload photo.";
        }
    } else {
        if (!in_array($file_type, $allowed_types) || !in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        } elseif ($photo['size'] > $max_file_size) {
            $_SESSION['error'] = "File size exceeds 5MB limit.";
        } else {
            $_SESSION['error'] = "Unknown file upload error.";
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Add Student</title>
    <script>
        // Display PHP session messages as JavaScript alerts
        window.onload = function() {
            <?php if (isset($_SESSION['message'])): ?>
                alert("<?= htmlspecialchars($_SESSION['message']); ?>");
                <?php unset($_SESSION['message']); ?>
                setTimeout(function() {
                    window.location.href = 'view_student.php'; // Redirect after alert
                }, 1000); // Wait 2 seconds before redirecting
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                alert("<?= htmlspecialchars($_SESSION['error']); ?>");
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Add Student</h1>

        <form method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="matric_number">Matric Number:</label>
            <input type="text" name="matric_number" id="matric_number" required>

            <label for="class">Class:</label>
            <input type="text" name="class" id="class" required>

            <label for="photo">Photo:</label>
            <input type="file" name="photo" id="photo" required>

            <input type="submit" name="add_student" value="Add Student">
        </form>
        <br>
        <a href="view_student.php" class="back-button">Back to Student List</a>
    </div>
</body>
</html>
