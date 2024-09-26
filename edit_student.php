<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the student details
$id = $_GET['id'];
$query = "SELECT * FROM students WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

// Handle the edit student form submission
if (isset($_POST['edit_student'])) {
    $name = $_POST['name'];
    $matric_number = $_POST['matric_number'];
    $class = $_POST['class'];

    // Handle photo upload
    if ($_FILES['photo']['name']) {
        $photo = $_FILES['photo']['name'];
        $target = "upload/" . basename($photo);
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $_SESSION['error'] = "Failed to upload the photo.";
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
            exit();
        }
    } else {
        $photo = $_POST['old_photo'];
    }

    // Update the student record
    $query = "UPDATE students SET name = ?, matric_number = ?, class = ?, photo = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $name, $matric_number, $class, $photo, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Student updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update student.";
    }

    header("Location: view_student.php");
    exit();
}
?>

<!-- Edit Student Form -->
<link rel="stylesheet" type="text/css" href="styles.css">
<div class="container">
    <h1>Edit Student</h1>

    <!-- Check for session messages and display them as alerts -->
    <script>
        <?php if (isset($_SESSION['message'])): ?>
            alert("<?php echo addslashes($_SESSION['message']); ?>");
            <?php unset($_SESSION['message']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            alert("<?php echo addslashes($_SESSION['error']); ?>");
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
        <input type="hidden" name="old_photo" value="<?php echo $student['photo']; ?>">

        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required><br><br>

        <label>Matric Number:</label><br>
        <input type="text" name="matric_number" value="<?php echo htmlspecialchars($student['matric_number']); ?>" required><br><br>

        <label>Class:</label><br>
        <input type="text" name="class" value="<?php echo htmlspecialchars($student['class']); ?>" required><br><br>

        <label>Photo:</label><br>
        <input type="file" name="photo"><br><br>

        <input type="submit" name="edit_student" value="Update Student">
        <a href="index.php" class="back-button">Back to Student List</a>
    </form>
</div>
