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

// Fetch user goal information from the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user_goals WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Check if the important fields are missing
if (empty($user_data['fitness_goal']) || empty($user_data['height']) || empty($user_data['weight'])) {
    echo "<script>alert('Please set your fitness goal, height, and weight first so that I can analyze and suggest tips!'); window.location.href = 'home.php';</script>";
    exit();
}

// Prepare a prompt for AI
$user_goal = $user_data['fitness_goal']; 
$user_height = $user_data['height'];
$user_weight = $user_data['weight'];
$user_age = isset($user_data['age']) ? $user_data['age'] : '25'; // Default age if not set

$prompt = "Give 5 simple, friendly nutrition tips for a person whose goal is '$user_goal', height is $user_height cm, weight is $user_weight kg, and age is $user_age years. Keep tips short and easy to understand.";

// Call OpenAI API
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

if ($err) {
    // If there is an error, fallback to default tips
    $nutrition_tips = [
        "Eat a balanced diet with a variety of foods from all the food groups.",
        "Incorporate more fruits and vegetables into your meals for essential vitamins and minerals.",
        "Drink plenty of water to stay hydrated and support digestion.",
        "Avoid processed and sugary foods to maintain a healthy weight.",
        "Include healthy fats in your diet, such as those found in avocados, nuts, and olive oil."
    ];
} else {
    $result = json_decode($response, true);
    $ai_text = $result['choices'][0]['message']['content'];

    // Split the tips into an array
    $nutrition_tips = preg_split('/\d+\.\s/', $ai_text, -1, PREG_SPLIT_NO_EMPTY);
}

// Now $nutrition_tips array is ready and can be used in HTML
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Tips</title>
    <!-- Include Chartist.js Library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chartist/dist/chartist.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chartist/dist/chartist.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-image: url('./images/headerbg.jpeg');
            color: white;
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
        .nutrition-tips {
            padding: 2rem;
            text-align: left;
            background-color: white;
            
            font-size: 14px;
        }

        .nutrition-tips h3 {
            color:  rgba(44, 62, 80, 0.9);
            margin-bottom: 1rem;
            font-size: 21px;
        }

        .nutrition-tips ul {
            list-style: none;
            padding: 0;
        }

        .nutrition-tips ul li {
            font-size: 1.1rem;
            color: rgba(44, 62, 80, 0.9);
            margin-bottom: 1rem;
        }

        .chart-container {
            margin: 2rem auto;
            max-width: 600px;
            height: 400px;
            color: rgba(44, 62, 80, 0.9);
            background-color: white;
            border-radius: 7px;
        }

        .chart-container .ct-chart {
            height: 100% !important;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>Fitness App</h2>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="nutrition-tips">
        <h3>AI Supported Nutrition Tips Based On Your Profile</h3><br>
        <ul>
            <?php foreach ($nutrition_tips as $tip): ?>
                <li><?php echo $tip; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Chart Section -->
    <div class="chart-container">
        <div class="ct-chart" id="user-chart"></div>
    </div>

    <script>
        // Data for the chart (User's height and weight)
        var userHeight = <?php echo $user_data['height']; ?>;
        var userWeight = <?php echo $user_data['weight']; ?>;

        // Chartist.js Configuration
        var data = {
            labels: ['Height (cm)', 'Weight (kg)'],
            series: [
                [userHeight, userWeight]
            ]
        };

        var options = {
            width: '100%',
            height: '100%',
            axisX: {
                showGrid: true
            },
            axisY: {
                showGrid: true
            }
        };

        // Create the chart
        new Chartist.Bar('#user-chart', data, options);

        // Adding the labels to the bars (Height and Weight)
        var chart = new Chartist.Bar('#user-chart', data, options);
        chart.on('draw', function(data) {
            if(data.type === 'bar') {
                data.element.attr({
                    'title': data.value.y  // Show the value on hover
                });
                data.group.append(new Chartist.Svg('text', {
                    x: data.x + data.width / 2,
                    y: data.y - 10
                }, 'ct-label').text(data.value.y));
            }
        });
    </script>

</body>
</html>
