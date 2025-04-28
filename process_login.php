<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Prepare query to fetch user by email or username
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Correct column name for password hash
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id']; // Use correct column name
            $_SESSION['username'] = $user['username'];

            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid username or email!'); window.location.href='login.php';</script>";
        exit();
    }
}
?>
