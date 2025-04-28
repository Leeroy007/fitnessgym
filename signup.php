<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Signup | EliteFit Club</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-image: url('./images/loginbg.jpeg');
      background-size: cover;
      background-position: center;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: rgba(44, 62, 80, 0.9);
      padding: 2rem 2rem;
      color: #ecf0f1;
    }

    .navbar h2 {
      font-size: 1.8rem;
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
      font-weight: 500;
      font-size: 18px;
    }

    .navbar ul li a:hover {
      color: #f1c40f;
    }

    /* Signup Form */
    .signup-wrapper {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .signup-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 1rem 1rem;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      width: 95%;
      max-width: 40%;
      text-align: center;
    }

    .signup-box h2 {
      margin-bottom: 1.5rem;
      color: #2c3e50;
      font-size: 1.8rem;
    }

    .signup-box input, select {
      width: 100%;
      padding: 0.85rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 12px;
      font-size: 1rem;
    }

    .signup-box button {
      width: 100%;
      padding: 0.85rem;
      border: none;
      background-color: #f1c40f;
      color: #2c3e50;
      font-weight: bold;
      border-radius: 12px;
      font-size: 1.1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .signup-box button:hover {
      background-color: #d4ac0d;
    }

    .signup-box .links {
      margin-top: 1rem;
      font-size: 0.95rem;
    }

    .signup-box .links a {
      color: #2980b9;
      text-decoration: none;
      font-weight: 500;
    }

    .signup-box .links a:hover {
      text-decoration: underline;
    }

    /* Footer */
    footer {
      background-color: rgba(44, 62, 80, 0.95);
      color: #ecf0f1;
      padding: 1rem;
      text-align: center;
    }

    @media screen and (max-width: 768px) {
      .navbar ul {
        flex-direction: column;
        gap: 0.5rem;
        text-align: right;
      }

      .signup-box {
        padding: 2rem;
      }
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar">
    <h2>EliteFit Club</h2>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li><a href="login.php">Login</a></li>
      <li><a href="signup.php">Signup</a></li>
    </ul>
  </nav>
 <br> <br>
  <!-- Signup Form -->
  <div class="signup-wrapper">
    <div class="signup-box">
      <h2>Create Your Account</h2>
      <form method="post" action="process_signup.php">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>

        <label for="fitness_goal">Fitness Goal:</label>
        <select name="fitness_goal" id="fitness_goal" required>
          <option value="">-- Select Your Goal --</option>
          <option value="Lose Weight">Lose Weight</option>
          <option value="Gain Strength">Gain Strength</option>
          <option value="Stay Active">Stay Active</option>
          <option value="Improve Flexibility">Improve Flexibility</option>
          <option value="Just Move Around">Just Move Around</option>
        </select>

        <label for="workout_preferences">Workout Preference:</label>
        <select name="workout_preferences" id="workout_preferences" required>
          <option value="">-- Select Preferred Workout --</option>
          <option value="Light Yoga">Light Yoga</option>
          <option value="Walking">Walking</option>
          <option value="Stretching">Stretching</option>
          <option value="Chair Exercises">Chair Exercises</option>
          <option value="Low-Impact Cardio">Low-Impact Cardio</option>
        </select>

        <label for="dietary_restrictions">Dietary Restrictions:</label>
        <select name="dietary_restrictions" id="dietary_restrictions" required>
          <option value="">-- Select Dietary Option --</option>
          <option value="None">None</option>
          <option value="Diabetic-Friendly">Diabetic-Friendly</option>
          <option value="Low Sodium">Low Sodium</option>
          <option value="Vegetarian">Vegetarian</option>
          <option value="Low Sugar">Low Sugar</option>
        </select>

        <label for="prior_experience">Prior Fitness Experience:</label>
        <select name="prior_experience" id="prior_experience" required>
          <option value="">-- Select Experience Level --</option>
          <option value="Beginner">Beginner</option>
          <option value="Some Experience">Some Experience</option>
          <option value="Regular in Past">Regular in Past</option>
          <option value="Inconsistent">Inconsistent</option>
          <option value="First Time">First Time</option>
        </select>

        <button type="submit">Signup</button>
      </form>

      <div class="links">
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <p>Are you an Admin? <a href="admin_login.php">Login as Admin</a></p>
      </div>
    </div>
  </div>
  <br> <br>
  <!-- Footer -->
  <footer>
    &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
  </footer>

</body>

</html>