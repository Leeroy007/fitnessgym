<?php
session_start();
include 'config.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user goal info
$stmt = $conn->prepare("SELECT * FROM user_goals WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_goal = $result->fetch_assoc();

// Check if goal data is missing (e.g., fitness goal, weight, height)
if (
    !$user_goal || 
    empty($user_goal['fitness_goal']) || 
    empty($user_goal['weight']) || 
    empty($user_goal['height'])
) {
    echo "<script>alert('Please set your goal, weight, and height before proceeding.'); window.location.href='home.php';</script>";
    exit();
}

// Handle form submit
$report = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exercise = $_POST['exercise'];
    $time_spent = $_POST['time_spent'];
    $current_weight = $_POST['current_weight'];
    $current_height = $_POST['current_height'];

    $prompt = "Based on the following fitness details, generate a fitness progress report with numerical data to show progress:
    - Previous Goal: " . $user_goal['fitness_goal'] . "
    - Exercise done: $exercise
    - Time spent: $time_spent minutes
    - Previous weight: " . $user_goal['weight'] . " kg
    - Previous height: " . $user_goal['height'] . " cm
    - Current weight: $current_weight kg
    - Current height: $current_height cm

    Please provide a numerical report with:
    1. Weight change in kg
    2. BMI change
    3. Time vs calories burnt estimation
    in JSON format like:
    {
      \"weight_change\": -1.5,
      \"bmi_change\": -0.4,
      \"calories_burned\": 320
    }";

    // Send to ChatGPT API
    $apiKey = "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"; 
    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "user", "content" => $prompt]
        ]
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $resultData = json_decode($response, true);
    $gptContent = $resultData['choices'][0]['message']['content'];

    $reportData = json_decode($gptContent, true);
    if ($reportData) {
        $report = $reportData;
    } else {
        $report = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Progress Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-image: url('./images/profilebg.jpg');
    background-size: cover;
    background-position: center;
    color: #2f3640;
    margin: 0;
    padding: 0;
    height: 100%;
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
    width: 50%;
    margin: 50px auto;
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
    flex-grow: 1; /* This will make the container grow and push the footer down */
}


      h2, h3 {
          text-align: center;
          color: #2f3640;
      }

      form {
          display: flex;
          flex-direction: column;
      }

      label {
          margin-top: 15px;
          font-weight: bold;
          color: #2f3640;
      }

      input, textarea {
          margin-top: 5px;
          padding: 10px;
          border: 1px solid #ccc;
          border-radius: 6px;
      }

      button {
          margin-top: 20px;
          padding: 12px;
          background-color: #f1c40f;
          color: #2c3e50;
          font-size: 16px;
          border: none;
          border-radius: 6px;
          cursor: pointer;
      }

      button:hover {
          background-color: #d4ac0d;
      }

      .charts {
          margin-top: 40px;
      }

      footer {
    background-color: #2c3e50;
    padding: 15px;
    text-align: center;
    color: white;
    margin-top: 50px;
    width: 100%;
}
  </style>
</head>
<body>

<div class="navbar">
    <h2>Fitness App</h2>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="updateprofile.php">Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="container">
    <h2>Your Progress Dashboard</h2>
    <p><strong>Your Goal:</strong> <?php echo htmlspecialchars($user_goal['fitness_goal']); ?></p>

    <form method="POST" action="">
        <label>Exercise Description:</label>
        <textarea name="exercise" required></textarea>

        <label>Time Spent (daily minutes):</label>
        <input type="text" name="time_spent" placeholder="10 minutes daily from 3 months" required>

        <label>Current Weight (kg):</label>
        <input type="number" name="current_weight" required>

        <label>Current Height (cm):</label>
        <input type="number" name="current_height" required>

        <button type="submit">Generate Report</button>
    </form>

    <?php if ($report): ?>
    <div class="charts">
        <h3>Progress Report</h3>

        <canvas id="weightChart" width="400" height="200"></canvas>
        <canvas id="bmiChart" width="400" height="200" style="margin-top:30px;"></canvas>
        <canvas id="caloriesChart" width="400" height="200" style="margin-top:30px;"></canvas>
        <canvas id="comparisonChart" width="400" height="200" style="margin-top:30px;"></canvas> <!-- NEW Line Chart -->

    </div>

    <script>
        const report = <?php echo json_encode($report); ?>;
        const previousWeight = <?php echo (float) $user_goal['weight']; ?>;
        const previousHeight = <?php echo (float) $user_goal['height']; ?>;
        const currentWeight = previousWeight + report.weight_change;
        const previousBMI = (previousWeight / ((previousHeight/100) * (previousHeight/100))).toFixed(2);
        const currentBMI = (currentWeight / ((previousHeight/100) * (previousHeight/100))).toFixed(2);

        // Weight Change Chart
        new Chart(document.getElementById('weightChart'), {
            type: 'bar',
            data: {
                labels: ['Weight Change'],
                datasets: [{
                    label: 'Kg',
                    data: [report.weight_change],
                    backgroundColor: ['#007bff']
                }]
            }
        });

        // BMI Change Chart
        new Chart(document.getElementById('bmiChart'), {
            type: 'bar',
            data: {
                labels: ['BMI Change'],
                datasets: [{
                    label: 'BMI',
                    data: [report.bmi_change],
                    backgroundColor: ['#28a745']
                }]
            }
        });

        // Calories Burned Chart
        new Chart(document.getElementById('caloriesChart'), {
            type: 'bar',
            data: {
                labels: ['Calories Burned'],
                datasets: [{
                    label: 'Calories',
                    data: [report.calories_burned],
                    backgroundColor: ['#ffc107']
                }]
            }
        });

        // Line Chart: Old vs New Progress (Weight & BMI)
        new Chart(document.getElementById('comparisonChart'), {
            type: 'line',
            data: {
                labels: ['Previous', 'Current'],
                datasets: [
                    {
                        label: 'Weight (kg)',
                        data: [previousWeight, currentWeight],
                        borderColor: '#007bff',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'BMI',
                        data: [previousBMI, currentBMI],
                        borderColor: '#28a745',
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Comparison: Old vs New Progress'
                    }
                }
            }
        });
    </script>

    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p><strong>Failed to generate report. Please try again.</strong></p>
    <?php endif; ?>

</div>

<footer>
    &copy; <?php echo date('Y'); ?> Fitness Progress Tracker. All Rights Reserved.
</footer>

</body>
</html>
