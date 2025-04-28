<?php
session_start();
include 'config.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle deletion of a user
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

    // Delete the user from users, feedback, and user_goals tables
    $delete_user_query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($delete_user_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $delete_feedback_query = "DELETE FROM feedback WHERE user_id = ?";
    $stmt = $conn->prepare($delete_feedback_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $delete_goals_query = "DELETE FROM user_goals WHERE user_id = ?";
    $stmt = $conn->prepare($delete_goals_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Redirect back to the admin dashboard
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch all users
$users_query = "SELECT * FROM users";
$users_result = $conn->query($users_query);

// Fetch all feedback
$feedback_query = "SELECT * FROM feedback";
$feedback_result = $conn->query($feedback_query);

// Fetch all user goals
$user_goals_query = "SELECT * FROM user_goals";
$user_goals_result = $conn->query($user_goals_query);

// Fetch all messages from msgs table
$msgs_query = "SELECT * FROM msgs";
$msgs_result = $conn->query($msgs_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | EliteFit Club</title>
  <style>
    /* Same styles as login page */
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-image: url('./images/loginbg.jpeg');
      background-size: cover;
      background-position: center;
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

    .admin-dashboard {
      padding: 3rem;
      background-color: rgba(255, 255, 255, 0.95);
      margin: 2rem;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    table {
      width: 100%;
      margin-bottom: 2rem;
      border-collapse: collapse;
    }

    th, td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f1c40f;
      color: #2c3e50;
    }

    td {
      background-color: #ecf0f1;
    }

    .delete-btn {
      color: #e74c3c;
      cursor: pointer;
    }

    footer {
      background-color: rgba(44, 62, 80, 0.95);
      color: #ecf0f1;
      padding: 1rem;
      text-align: center;
      margin-top: auto;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <h2>EliteFit Club - Admin Dashboard</h2>
  <ul>
    <li><a href="admin_dashboard.php">Dashboard</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<!-- Admin Dashboard -->
<div class="admin-dashboard">
  <h2>Manage Users</h2>

  <!-- Users Table -->
  <h3>All Users</h3>
  <table>
    <thead>
      <tr>
        <th>Username</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $users_result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($user['username']); ?></td>
          <td><?php echo htmlspecialchars($user['full_name']); ?></td>
          <td><?php echo htmlspecialchars($user['email']); ?></td>
          <td><a href="admin_dashboard.php?delete_user=<?php echo $user['user_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Feedback Table -->
  <h3>User Feedback</h3>
  <table>
    <thead>
      <tr>
        <th>User</th>
        <th>Feedback</th>
        <th>Submitted At</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($feedback = $feedback_result->fetch_assoc()): ?>
        <tr>
          <td>
            <?php
              $user_id = $feedback['user_id'];
              $user_query = "SELECT username FROM users WHERE user_id = ?";
              $stmt = $conn->prepare($user_query);
              $stmt->bind_param("i", $user_id);
              $stmt->execute();
              $user_result = $stmt->get_result();
              $user = $user_result->fetch_assoc();
              echo htmlspecialchars($user['username']);
            ?>
          </td>
          <td><?php echo nl2br(htmlspecialchars($feedback['feedback_text'])); ?></td>
          <td><?php echo htmlspecialchars($feedback['submitted_at']); ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- User Goals Table -->
  <h3>User Goals</h3>
  <table>
    <thead>
      <tr>
        <th>User</th>
        <th>Goal</th>
        <th>Age</th>
        <th>Height</th>
        <th>Weight</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($goal = $user_goals_result->fetch_assoc()): ?>
        <tr>
          <td>
            <?php
              $user_id = $goal['user_id'];
              $user_query = "SELECT username FROM users WHERE user_id = ?";
              $stmt = $conn->prepare($user_query);
              $stmt->bind_param("i", $user_id);
              $stmt->execute();
              $user_result = $stmt->get_result();
              $user = $user_result->fetch_assoc();
              echo htmlspecialchars($user['username']);
            ?>
          </td>
          <td><?php echo htmlspecialchars($goal['fitness_goal']); ?></td>
          <td><?php echo htmlspecialchars($goal['age']); ?></td>
          <td><?php echo htmlspecialchars($goal['height']); ?> cm</td>
          <td><?php echo htmlspecialchars($goal['weight']); ?> kg</td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Anonymous Messages Table -->
  <h3>Contact Messages</h3>
  <table>
    <thead>
      <tr>
        <th>Sender Name</th>
        <th>Sender Email</th>
        <th>Message</th>
        <th>Sent At</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($msg = $msgs_result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($msg['fullname']); ?></td>
          <td><?php echo htmlspecialchars($msg['email']); ?></td>
          <td><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>
          <td><?php echo htmlspecialchars($msg['created_at']); ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</div>

<!-- Footer -->
<footer>
  &copy; <?php echo date("Y"); ?> EliteFit Club. All rights reserved.
</footer>

</body>
</html>
