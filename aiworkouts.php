<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// OpenAI API Key
$openai_api_key = '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';

// Fetch user profile
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user_goals WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Check if user goal, height, or weight is not set
if (
    !$user_data || 
    empty($user_data['fitness_goal']) || 
    empty($user_data['height']) || 
    empty($user_data['weight'])
) {
    echo "<script>alert('First set your goal, height, and weight kindly so that I can analyze.'); window.location.href='home.php';</script>";
    exit();
}

$user_goal = $user_data['fitness_goal'];
$user_height = $user_data['height'];
$user_weight = $user_data['weight'];
$user_age = isset($user_data['age']) ? $user_data['age'] : '45'; // Assume 45 if missing

// Prepare a prompt for AI
$prompt = "Generate 5 different workout plans for a 40+ aged person whose goal is '$user_goal'.
For each workout, provide only:
- Workout Title
- 2-line Description
- Effectiveness (score out of 10)

Keep formatting very clear like:
Workout Title: XYZ
Description: ABC
Effectiveness: 8/10

Separate each workout with numbering 1. 2. 3. 4. 5.";

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "user", "content" => $prompt]
        ],
        "temperature" => 0.7
    ]),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $openai_api_key"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

// Initialize workouts array
$workouts = [];

if (!$err) {
    $result = json_decode($response, true);
    $ai_text = $result['choices'][0]['message']['content'];

    // Split workouts based on numbering
    $raw_workouts = preg_split('/\d+\.\s/', $ai_text, -1, PREG_SPLIT_NO_EMPTY);

    foreach ($raw_workouts as $workout_text) {
        preg_match('/Workout Title\:\s*(.*?)\n/', $workout_text, $title_match);
        preg_match('/Description\:\s*(.*?)\n/', $workout_text, $desc_match);
        preg_match('/Effectiveness\:\s*(.*?)\n/', $workout_text, $eff_match);

        $workouts[] = [
            'title' => isset($title_match[1]) ? trim($title_match[1]) : '',
            'description' => isset($desc_match[1]) ? trim($desc_match[1]) : '',
            'effectiveness' => isset($eff_match[1]) && trim($eff_match[1]) !== '' ? trim($eff_match[1]) : '7/10',
        ];
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Workouts</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-image: url('./images/homebg.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .main-content {
            min-height: calc(100vh - 70px); /* Adjust if footer height changes */
            display: flex;
            flex-direction: column;
        }

        .content-area {
            flex: 1;
            padding: 20px;
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
            margin: 0;
            padding: 0;
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

        h1 {
            color: #333;
        }

        .workout-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: rgb(238, 224, 224);
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            width: 320px;
            padding: 20px;
            text-align: left;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-top: 0;
            color: #333;
            font-size: 21px;
        }

        .card p {
            margin: 10px 0;
            color: #555;
            font-size: 17px;
        }

        .effectiveness {
            font-weight: bold;
            color: green;
            font-size: 18px;
        }

        .loading {
            font-size: 24px;
            color: #555;
            margin-top: 50px;
        }

        footer {
            background-color: rgba(44, 62, 80, 0.95);
            color: #ecf0f1;
            padding: 1rem;
            text-align: center;
            height: 70px;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("loading").style.display = 'none';
            document.getElementById("workouts").style.display = 'flex';
        });
    </script>
</head>
<body>
<div class="main-content">
    <div class="navbar">
        <h2>Fitness App</h2>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="content-area">
        <h1>Your AI Personalized Workouts</h1>

        <div id="loading" class="loading">
            Loading your workouts, please wait...
        </div>

        <div id="workouts" class="workout-container" style="display:none;">
            <?php if (!empty($workouts)): ?>
                <?php foreach ($workouts as $workout): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($workout['title']); ?></h3>
                        <p><?php echo htmlspecialchars($workout['description']); ?></p>
                        <p class="effectiveness">Effectiveness: <?php echo htmlspecialchars($workout['effectiveness']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Sorry, could not fetch workouts at the moment. Please try again later.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
  &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>

</body>
</html>
