<?php
// parents.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parents</title>
</head>
<body>
    <h2>Parents Section</h2>
    <p>Content for Parents section will go here.</p>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
