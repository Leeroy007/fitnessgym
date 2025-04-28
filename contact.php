<?php
include 'config.php'; // Include your database connection

// Processing form submission
$message_sent = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    // Insert into msgs table
    $sql = "INSERT INTO msgs (fullname, email, message) VALUES ('$fullname', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        $message_sent = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - EliteFit Club</title>
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(to right, rgb(188, 215, 242), rgb(252, 248, 248));
        }
        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            padding: 2rem;
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
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 900px;
            width: 90%;
            margin: 40px auto;
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
        }
        input, textarea {
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        button {
            background-color: #f1c40f;
            color: #2c3e50;
            padding: 12px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #e1b400;
        }
        footer {
            width: 100%;
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 2rem;
            text-align: center;
        }

        @media screen and (max-width: 768px) {
            .navbar ul {
                flex-direction: column;
                gap: 0.5rem;
                text-align: right;
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

<!-- Main Content -->
<div class="container">
    <h1>Contact Us</h1>

    <?php if ($message_sent): ?>
        <div class="success">
            Thank you! Your message has been sent successfully.
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <textarea name="message" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
    </form>
</div>

<!-- Footer -->
<footer>
    &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>
</body>
</html>
