<?php
session_start();
include 'config.php';

$message = '';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username']; // Make sure to store this during login

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback = trim($_POST['feedback']);

    if (!empty($feedback)) {
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, feedback_text) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $feedback);
        if ($stmt->execute()) {
            $message = "Thank you for your feedback!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Please write something before submitting.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Feedback | EliteFit Club</title>
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      /* background-color: #f4f6f7; */
      background-image: url('./images/feedback.jpeg');
      background-position: center;
      background-size: cover;
      color: #2c3e50;
      min-height: 100vh;
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

    .container {
       
      width: 40%;
      max-width: 700px;
      margin: 2rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .container h1 {
      color: #34495e;
      margin-bottom: 0.5rem;
    }

    .welcome {
      font-size: 1.1rem;
      color: #2980b9;
      margin-bottom: 1rem;
    }

    textarea {
      width: 100%;
      height: 150px;
      padding: 1rem;
      border: 1px solid #ccc;
      border-radius: 10px;
      resize: none;
      font-size: 1rem;
      margin-bottom: 1rem;
    }

    button {
      background-color: #f1c40f;
      color: #2c3e50;
      border: none;
      padding: 0.7rem 2rem;
      font-size: 1.1rem;
      border-radius: 10px;
      cursor: pointer;
    }

    button:hover {
      background-color: #d4ac0d;
    }

    .message {
      margin-top: 1rem;
      color: green;
      font-weight: bold;
    }

    footer {
      background-color: rgba(44, 62, 80, 0.95);
      color: #ecf0f1;
      padding: 1rem;
      text-align: center;
      margin-top: auto;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <h2>EliteFit Club</h2>
  <ul>
    <li><a href="home.php">Home</a></li>
    <li><a href="profile.php">My Profile</a></li>
    <li><a href="feedback.php">Feedback</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<!-- Feedback Form -->
<div class="container">
  <div class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</div>
  <h1>We value your feedback</h1>
  <form action="" method="post">
    <textarea name="feedback" placeholder="Write your feedback here..."></textarea>
    <br>
    <button type="submit">Submit Feedback</button>
  </form>
  <?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
</div>

<!-- Footer -->
<footer>
  &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>

</body>
</html>
