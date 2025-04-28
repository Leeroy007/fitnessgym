<?php
// about_us.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Elderly Fitness Hub</title>
    <link rel="stylesheet" href="styles.css"> <!-- Assuming you have a central CSS file -->
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
    /* Navbar */
    .navbar {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #2c3e50;
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
    .container {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        max-width: 900px;
        width: 90%;
        margin: 40px auto;
        text-align: center;
        flex: 1; /* important to push footer down */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    h1 {
        margin-bottom: 20px;
        color: #2c3e50;
    }
    p {
        font-size: 18px;
        color: #555;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    a.button {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 25px;
        background-color: #f1c40f;
        color: #2c3e50;
        text-decoration: none;
        border-radius: 8px;
        transition: background-color 0.3s;
    }
    a.button:hover {
        background-color: #e1b400;
    }
    /* Footer */
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
        <li><a href="#">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="signup.php">Signup</a></li>
    </ul>
</nav>

<!-- Main Content -->
<div class="container">
    <h1>About Us</h1>
    <p>Welcome to Elderly Fitness Hub, a platform dedicated to promoting health and wellness among elderly individuals. We understand the unique challenges that come with aging, and our mission is to provide a safe, supportive, and engaging environment where seniors can pursue their fitness and wellness goals with confidence. Our community is built around the belief that staying active, eating well, and maintaining mental wellness are essential components of a fulfilling life at any age.</p>

    <p>At Elderly Fitness Hub, we offer personalized workout plans, tailored dietary advice, and goal-tracking features designed specifically for the elderly population. Users can set their fitness goals, receive customized feedback, and share their experiences with a caring community. Our platform also provides insightful resources, regular wellness tips, and ongoing motivation to help our users achieve and maintain a healthier lifestyle in their golden years.</p>

    <a href="index.php" class="button">Back to Home</a>
</div>
<!-- Footer -->
<footer>
    &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>
</body>
</html>
