<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $fitness_goal = $_POST["fitness_goal"];
    $workout_preferences = $_POST["workout_preferences"];
    $dietary_restrictions = $_POST["dietary_restrictions"];
    $prior_experience = $_POST["prior_experience"];

    // Basic password match validation
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match'); window.location.href='signup.php';</script>";
        exit();
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists'); window.location.href='signup.php';</script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $insert_stmt = $conn->prepare("INSERT INTO users 
        (username, password_hash, full_name, email, fitness_goal, workout_preferences, dietary_restrictions, prior_experience) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$insert_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $insert_stmt->bind_param(
        "ssssssss",
        $username,
        $hashed_password,
        $full_name,
        $email,
        $fitness_goal,
        $workout_preferences,
        $dietary_restrictions,
        $prior_experience
    );

    if ($insert_stmt->execute()) {
        echo "<script>alert('Account created successfully'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error creating account: " . $insert_stmt->error . "'); window.location.href='signup.php';</script>";
    }

    $stmt->close();
    $insert_stmt->close();
    $conn->close();
} else {
    header("Location: signup.php");
    exit();
}
?>
