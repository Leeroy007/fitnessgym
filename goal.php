<?php
session_start();
include 'config.php'; // Make sure this line is included

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Fetching the user ID from the session

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $fitness_goal = $_POST['fitness_goal'];

    // First, check if the user already has a goal in the database
    $stmt = $conn->prepare("SELECT * FROM user_goals WHERE user_id = ?");
    $stmt->bind_param("i", $user_id); // Bind the user ID
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the user already has a goal, update the existing record
        $stmt = $conn->prepare("UPDATE user_goals SET age = ?, height = ?, weight = ?, fitness_goal = ? WHERE user_id = ?");
        $stmt->bind_param("iiisi", $age, $height, $weight, $fitness_goal, $user_id); // Bind the parameters

        if ($stmt->execute()) {
            // Redirect to the home page or show a success message
            header("Location: home.php");
            exit();
        } else {
            echo "Error updating goal: " . $stmt->error;
        }
    } else {
        // If no goal exists, insert a new record
        $stmt = $conn->prepare("INSERT INTO user_goals (user_id, age, height, weight, fitness_goal) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $user_id, $age, $height, $weight, $fitness_goal); // Bind the parameters

        if ($stmt->execute()) {
            // Redirect to the home page or show a success message
            header("Location: home.php");
            exit();
        } else {
            echo "Error inserting goal: " . $stmt->error;
        }
    }

    $stmt->close(); // Close the statement
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Set Fitness Goal | EliteFit Club</title>
  <style>
    /* General Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-image: url('./images/feedback.jpeg');
      color: #2c3e50;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background-position: center;
      background-size: cover;
    }

    main {
      flex: 1;
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

    .form-container {
      background-color: #ffffff;
      padding: 3rem;
      max-width: 600px;
      margin: 3rem auto;
      border-radius: 10px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
      text-align: center;
      font-size: 2rem;
      margin-bottom: 2rem;
      color: #2c3e50;
    }

    .form-container label {
      display: block;
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
      color: #34495e;
    }

    .form-container input,
    .form-container select,
    .form-container button {
      width: 100%;
      padding: 0.8rem;
      margin-bottom: 1.5rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .form-container button {
      background-color: #f1c40f;
      color: #2c3e50;
      font-weight: bold;
      cursor: pointer;
      border: none;
      transition: background-color 0.3s;
    }

    .form-container button:hover {
      background-color: #d4ac0d;
    }

    footer {
      background-color: rgba(44, 62, 80, 0.95);
      color: #ecf0f1;
      padding: 1rem;
      text-align: center;
    }

    /* Responsive Styles */
    @media screen and (max-width: 768px) {
      .form-container {
        padding: 2rem;
        margin: 1.5rem auto;
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
    <li><a href="goal.php">Set Goal</a></li>
    <li><a href="feedback.php">Feedback</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<main>
  <!-- Goal Setting Form -->
  <div class="form-container">
    <h2>Set Your Fitness Goal</h2>
    <form action="goal.php" method="POST">
      <!-- Age Dropdown -->
      <label for="age">Age</label>
      <select id="age" name="age" required>
        <?php for ($i = 40; $i <= 100; $i++) { echo "<option value=\"$i\">$i</option>"; } ?>
      </select>

      <!-- Height Input -->
      <label for="height">Height (in cm)</label>
      <input type="number" id="height" name="height" required min="140" max="250" step="0.01">

      <!-- Weight Input -->
      <label for="weight">Weight (in kg)</label>
      <input type="number" id="weight" name="weight" required min="30" max="200" step="0.1">

      <!-- Fitness Goal Dropdown -->
      <label for="fitness_goal">Fitness Goal</label>
      <select id="fitness_goal" name="fitness_goal" required>
        <option value="Weight Loss">Weight Loss</option>
        <option value="Muscle Gain">Muscle Gain</option>
        <option value="Endurance Building">Endurance Building</option>
        <option value="Flexibility Improvement">Flexibility Improvement</option>
        <option value="Maintain Current Fitness Level">Maintain Current Fitness Level</option>
      </select>

      <!-- Submit Button -->
      <button type="submit">Save Goal</button>
    </form>
  </div>
</main>

<footer>
  &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>

</body>
</html>
