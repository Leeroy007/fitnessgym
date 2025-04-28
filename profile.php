<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT username, full_name, email, fitness_goal, workout_preferences, dietary_restrictions, prior_experience 
          FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile | EliteFit Club</title>
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    html, body {
      height: 100%;
      /* background-color: #f9f9f9; */
      background-image: url('./images/profilebg.jpg');
      color: #2c3e50;
      display: flex;
      flex-direction: column;
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

    .main-content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem 1rem;
    }

    .container {
      width: 100%;
      max-width: 800px;
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .container h1 {
      text-align: center;
      margin-bottom: 0.5rem;
      font-size: 2.2rem;
      color: #34495e;
    }

    .welcome {
      text-align: center;
      font-size: 1.1rem;
      margin-bottom: 2rem;
      color: #7f8c8d;
    }

    .profile-info {
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 1rem 2rem;
    }

    .profile-info div {
      padding: 0.5rem 0;
      font-size: 1.1rem;
    }

    .label {
      font-weight: bold;
      color: #7f8c8d;
    }

    footer {
      background-color: rgba(44, 62, 80, 0.95);
      color: #ecf0f1;
      padding: 1rem;
      text-align: center;
    }

    @media screen and (max-width: 600px) {
      .profile-info {
        grid-template-columns: 1fr;
      }

      .container {
        margin: 1rem;
        padding: 1.5rem;
      }

      .container h1 {
        font-size: 1.7rem;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <h2>EliteFit Club</h2>
  <ul>
    <li><a href="home.php">Home</a></li>
    <li><a href="updateprofile.php">Update Profile</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<!-- Profile Content -->
<div class="main-content">
  <div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h1>
    <p class="welcome">Wishing you a fantastic fitness journey ahead! 💪</p>
    <div class="profile-info">
      <div class="label">Username:</div>
      <div><?php echo htmlspecialchars($user['username']); ?></div>

      <div class="label">Full Name:</div>
      <div><?php echo htmlspecialchars($user['full_name']); ?></div>

      <div class="label">Email:</div>
      <div><?php echo htmlspecialchars($user['email']); ?></div>

      <div class="label">Fitness Goal:</div>
      <div><?php echo htmlspecialchars($user['fitness_goal']); ?></div>

      <div class="label">Workout Preferences:</div>
      <div><?php echo htmlspecialchars($user['workout_preferences']); ?></div>

      <div class="label">Dietary Restrictions:</div>
      <div><?php echo htmlspecialchars($user['dietary_restrictions']); ?></div>

      <div class="label">Prior Experience:</div>
      <div><?php echo htmlspecialchars($user['prior_experience']); ?></div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>

</body>
</html>
