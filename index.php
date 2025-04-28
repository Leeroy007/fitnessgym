<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EliteFit Club</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f6fa;
            color: #2f3640;
        }

        /* Navbar */
        .navbar {
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

        /* Hero Section */
        .hero {
            height: 90vh;
            background-image: url('./images/headerbg.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            color: #fff;
            padding: 2rem;
            transition: background-image 1s ease-in-out;
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 5px #000;
        }

        .hero p {
            font-size: 1.4rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 3px #000;
        }

        .hero a.button {
            background-color: #f1c40f;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-size: 1rem;
            color: #2c3e50;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .hero a.button:hover {
            background-color: #d4ac0d;
        }

        /* Feedback Section */
        .feedback-section {
            padding: 3rem 2rem;
            background-color: #fff;
        }

        .feedback-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #2c3e50;
        }

        .feedback-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .feedback-card {
            background: #fdfefe;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 1.5rem;
        }

        .feedback-card p {
            font-style: italic;
            color: #34495e;
        }

        /* Footer */
        footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 2rem;
            text-align: center;
        }

        @media screen and (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero p { font-size: 1rem; }
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

<!-- Hero Banner -->
<section class="hero">
    <h1>EliteFit Club</h1>
    <p>Your Journey to Fitness Begins Here!</p>
    <a class="button" href="login.php">Start My Gym</a>
</section>

<!-- Feedback Section -->
<section class="feedback-section">
    <div class="feedback-title">What Our Members Say</div>
    <div class="feedback-cards">
        <?php
        $sql = "SELECT feedback_text FROM feedback ORDER BY submitted_at DESC LIMIT 6";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="feedback-card"><p>"' . htmlspecialchars($row['feedback_text']) . '"</p></div>';
            }
        } else {
            echo '<p style="text-align:center;">No feedbacks yet.</p>';
        }
        ?>
    </div>
</section>

<!-- Footer -->
<footer>
    &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>

<!-- JavaScript Slideshow -->
<script>
    const images = [
        './images/headerbg.jpeg',
        './images/feedback.jpeg',
        './images/profilebg.jpg',
        
    ];

    let currentImage = 0;
    const heroSection = document.querySelector('.hero');

    setInterval(() => {
        currentImage = (currentImage + 1) % images.length;
        heroSection.style.backgroundImage = `url('${images[currentImage]}')`;
    }, 3000); // every 4 seconds
</script>

</body>
</html>
