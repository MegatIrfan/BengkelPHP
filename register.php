<?php
session_start();
include 'db.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Ensure passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Check if username is taken
        $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Username Already Exist!');</script>";
        } else {
            // Hash password and insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, password, role) VALUES (?, ?, 'user')";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
            mysqli_stmt_execute($stmt);
            echo "<script>alert('Registration Successfully! You Can Login Now');</script>";
            
            
        }
    }
}
?>

<!-- Registration Form -->
<link rel="stylesheet" type="text/css" href="styles.css">
<div class="container">
    <h1>Register</h1>
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required>

        <label>Password:</label><br>
        <input type="password" name="password" required>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required>

        <input type="submit" name="register" value="Register">
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</div>
