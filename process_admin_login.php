<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Prepare query to fetch admin by username
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Check if the password matches
        if ($password === $admin['password_hash']) {
            $_SESSION['admin_id'] = $admin['admin_id']; // Store admin ID
            $_SESSION['admin_username'] = $admin['username']; // Store admin username

            // Redirect to admin dashboard or another page
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='admin_login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid username!'); window.location.href='admin_login.php';</script>";
        exit();
    }
}
?>
