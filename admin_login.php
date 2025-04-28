<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | EliteFit Club</title>
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
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

    /* Login Form */
    .login-wrapper {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 3rem 3rem;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      width: 95%;
      max-width: 40%;
      text-align: center;
    }

    .login-box h2 {
      margin-bottom: 1.5rem;
      color: #2c3e50;
      font-size: 1.8rem;
    }

    .login-box input {
      width: 100%;
      padding: 0.85rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 12px;
      font-size: 1rem;
    }

    .login-box button {
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

    .login-box button:hover {
      background-color: #d4ac0d;
    }

    .login-box .links {
      margin-top: 1rem;
      font-size: 0.95rem;
    }

    .login-box .links a {
      color: #2980b9;
      text-decoration: none;
      font-weight: 500;
    }

    .login-box .links a:hover {
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

      .login-box {
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

<!-- Admin Login Form -->
<div class="login-wrapper">
  <div class="login-box">
    <h2>Admin Login</h2>
    <form method="post" action="process_admin_login.php">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <div class="links">
      <p>Not an Admin? <a href="login.php">Login as User</a></p>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>

</body>
</html>
