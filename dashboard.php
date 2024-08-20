<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'conf.php'; // Include database connection

// Fetch user details
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT name, email, age, course FROM tb_user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($name, $email, $age, $course);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 200px;
            background-color: #f4f4f4;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .button-container a {
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
        }
        .button-container a:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .form-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <div class="button-container">
            <a href="?page=students">Students</a>
            <a href="?page=parents">Parents</a>
            <a href="?page=staff">Staff Members</a>
        </div>
        <a href="logout.php">Logout</a>
    </div>
    <div class="content">
        <?php
        // Display content based on the selected page
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        if ($page == 'students') {
            include 'students.php';
        } elseif ($page == 'parents') {
            echo '<h2>Parents Section</h2><p>Content for Parents section.</p>';
        } elseif ($page == 'staff') {
            echo '<h2>Staff Members Section</h2><p>Content for Staff Members section.</p>';
        } else {
            echo '<h2>Welcome to Your Dashboard</h2>';
            echo '<p>Name: ' . htmlspecialchars($name) . '</p>';
            echo '<p>Username: ' . htmlspecialchars($username) . '</p>';
            echo '<p>Email: ' . htmlspecialchars($email) . '</p>';
            echo '<p>Age: ' . htmlspecialchars($age) . '</p>';
            echo '<p>Course: ' . htmlspecialchars($course) . '</p>';
        }
        ?>
    </div>
</body>
</html>
