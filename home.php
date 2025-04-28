<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome | EliteFit Club</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: whitesmoke;
      background-size: cover;
      color: #2c3e50;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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

    .welcome {
      text-align: center;
      padding: 2rem;
    }

    .welcome h1 {
      font-size: 2.5rem;
      color: #34495e;
      margin-bottom: 0.5rem;
    }

    .welcome p {
      font-size: 1.3rem;
      color: rgb(55, 58, 59);
      margin-bottom: 1rem;
    }

    .highlight {
      font-size: 1.1rem;
      color: rgb(20, 28, 37);
      font-weight: bold;
    }

    /* SLIDER */
    .slider-container {
      max-width: 100%;
      margin: 0 auto;
      overflow: hidden;
  
    }

    .slider {
      display: flex;
      transition: transform 1s ease-in-out;
    }

    .slider img {
      width: 100%;
      height: 70vh;
      object-fit: cover;
    }

    /* For Mobile View */
    @media screen and (max-width: 768px) {
      .slider-container {
        max-width: 100%;
      }

      .slider {
        display: flex;
      }

      .slider img {
        width: 100%;
      }
    }

    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      padding: 2rem;
      max-width: 1200px;
      margin: auto;
    }

    .card {
      background-color:rgb(238, 224, 224);
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card h3 {
      font-size: 1.8rem;
      margin-bottom: 1rem;
      color: #2c3e50;
    }

    .card p {
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
      color: #7f8c8d;
    }

    .card button {
      padding: 0.75rem 1.5rem;
      background-color: #f1c40f;
      color: #2c3e50;
      border: none;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
      font-size: 1rem;
    }

    .card button:hover {
      background-color: #d4ac0d;
    }

    footer {
      background-color: rgba(44, 62, 80, 0.95);
      color: #ecf0f1;
      padding: 1rem;
      text-align: center;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <h2>EliteFit Club</h2>
  <ul>
    <li><a href="home.php">Home</a></li>
    <li><a href="about.php">About</a></li>
    <li><a href="contact.php">Contact</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<main>
  <!-- Welcome Section -->
  <div class="welcome">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Your personalized fitness journey starts here.</p>
    <p class="highlight">EliteFit Club is specially designed for elderly individuals to promote healthy aging, gentle exercises, and balanced nutrition tailored to their unique needs.</p>
  </div>

  <!-- Slider -->
  <div class="slider-container">
    <div class="slider">
      <img src="images/loginbg.jpeg" alt="Fitness Image 1">
      <img src="images/homebg.jpeg" alt="Fitness Image 2">
      <img src="images/headerbg.jpeg" alt="Fitness Image 3">
    </div>
  </div>

  <!-- Cards -->
  <div class="card-container">
    <div class="card">
      <h3>My Profile</h3>
      <p>View and update your fitness preferences and health info.</p>
      <button onclick="location.href='profile.php'">Go to Profile</button>
    </div>

    <div class="card">
      <h3>Fitness Goals</h3>
      <p>Set and track your personal goals for a healthy lifestyle.</p>
      <button onclick="location.href='goal.php'">Set Goals</button>
    </div>

    <div class="card">
      <h3>AI Workouts</h3>
      <p>Get custom workout plans tailored just for you using AI.</p>
      <button onclick="location.href='aiworkouts.php'">View Workouts</button>
    </div>

    <div class="card">
      <h3>Nutrition Tips</h3>
      <p>Receive diet suggestions according to your profile.</p>
      <button onclick="location.href='nutritiontip.php'">See Nutrition</button>
    </div>

    <div class="card">
      <h3>Progress Dashboard</h3>
      <p>Track your performance and see how far you’ve come.</p>
      <button onclick="location.href='progress.php'">View Progress</button>
    </div>

    <div class="card">
      <h3>Feedback</h3>
      <p>Share your experience and help us improve your journey.</p>
      <button onclick="location.href='feedback.php'">Give Feedback</button>
    </div>
  </div>
</main>

<!-- Footer -->
<footer>
  &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>

<script>
  let currentSlide = 0;
  const slides = document.querySelectorAll('.slider img');
  const totalSlides = slides.length;

  function showSlide(index) {
    const slider = document.querySelector('.slider');
    const offset = -index * 100; // Slide width = 100%
    slider.style.transform = `translateX(${offset}%)`;
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides; // Loop to first slide after last
    showSlide(currentSlide);
  }

  setInterval(nextSlide, 2000); // Change slide every 5 seconds
</script>

</body>
</html>
