<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $fitness_goal = $_POST['fitness_goal'];
    $workout_preferences = $_POST['workout_preferences'];
    $dietary_restrictions = $_POST['dietary_restrictions'];
    $prior_experience = $_POST['prior_experience'];

    $update_stmt = $conn->prepare("UPDATE users SET username = ?, full_name = ?, email = ?, fitness_goal = ?, workout_preferences = ?, dietary_restrictions = ?, prior_experience = ? WHERE user_id = ?");
    if (!$update_stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $update_stmt->bind_param("sssssssi", $username, $full_name, $email, $fitness_goal, $workout_preferences, $dietary_restrictions, $prior_experience, $user_id);

    if ($update_stmt->execute()) {
        header("Location: profile.php?update=success");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile - Fitness App</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('./images/homebg.jpeg');
            background-size: cover;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(44, 62, 80, 0.9);
            padding: 2rem 2rem;
            color: #ecf0f1;
        }
        .navbar h2 {
            font-size: 2rem;
            color: #f1c40f;
        }
        .navbar ul {
            display: flex;
            gap: 1.5rem;
            list-style: none;
        }
        .navbar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
        }
        .navbar ul li a:hover {
            color: #f1c40f;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 500px;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input, textarea {
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 10px;
            margin-top: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #f1c40f;
            color: #2c3e50;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        footer {
            background-color: rgba(44, 62, 80, 0.95);
            color: #ecf0f1;
            padding: 1rem;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Fitness App</h2>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="updateprofile.php">Update Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="container">
    <h1>Update Your Profile</h1>

    <?php if (isset($error)) { echo '<div class="error">' . $error . '</div>'; } ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($user['username']); ?>">
        <input type="text" name="full_name" placeholder="Full Name" required value="<?php echo htmlspecialchars($user['full_name']); ?>">
        <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($user['email']); ?>">
        <input type="text" name="fitness_goal" placeholder="Fitness Goal" value="<?php echo htmlspecialchars($user['fitness_goal']); ?>">
        <input type="text" name="workout_preferences" placeholder="Workout Preferences" value="<?php echo htmlspecialchars($user['workout_preferences']); ?>">
        <input type="text" name="dietary_restrictions" placeholder="Dietary Restrictions" value="<?php echo htmlspecialchars($user['dietary_restrictions']); ?>">
        <textarea name="prior_experience" placeholder="Prior Experience"><?php echo htmlspecialchars($user['prior_experience']); ?></textarea>

        <button type="submit">Update Profile</button>
    </form>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>

</body>
</html>
