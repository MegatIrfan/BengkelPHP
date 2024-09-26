<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for username in database
    $query = "SELECT * FROM users WHERE username = ? ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // Validate password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit();
    } else {
        // Set an error message in a session variable
        $_SESSION['login_error'] = "Invalid login credentials.";
        header("Location: login.php"); // Redirect to the same page
        exit();
    }
}
?>


<!-- Login Form -->

<head>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet"  href="https://megatirfan.com/cdn/styles.css">

</head>

<div class="container">
    <h1>Login</h1>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" name="login" value="Login">
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
   

    <?php
// Display SweetAlert for login error
if (isset($_SESSION['login_error'])) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: '" . htmlspecialchars($_SESSION['login_error']) . "',
            confirmButtonText: 'OK'
        });
    </script>";
    unset($_SESSION['login_error']); // Clear the error message after displaying
}
?>
</div>