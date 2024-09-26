<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch students from the database
$query = "SELECT * FROM students";
$students = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <title>Student List</title>
    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable(); // Initialize DataTable
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to logout?');">Logout</a><br>

        <?php
        // Display success or error messages
        if (isset($_SESSION['message'])) {
            echo "<div class='success'>" . htmlspecialchars($_SESSION['message']) . "</div>";
            unset($_SESSION['message']);
        }

        if (isset($_SESSION['error'])) {
            echo "<div class='error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <h2>Student List</h2>
        <table id="studentsTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Matric Number</th>
                    <th>Class</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $list = 1;
                while ($student = mysqli_fetch_assoc($students)): ?>
                    <tr>
                        <td><?php echo $list++; ?></td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['matric_number']); ?></td>
                        <td><?php echo htmlspecialchars($student['class']); ?></td>
                        <td><img src="upload/<?php echo htmlspecialchars($student['photo']); ?>" width="50" alt="Student Photo"></td>
                        <td>
                            <a href="edit_student.php?id=<?php echo $student['id']; ?>">Edit</a> | 
                            <a href="delete_student.php?id=<?php echo $student['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <a href="add_student.php" class="button">Add New Student</a>
    </div>
</body>
</html>
