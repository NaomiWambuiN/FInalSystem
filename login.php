<?php
require 'conf.php'; // Include database connection

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Check if the username exists
    $stmt = $conn->prepare("SELECT password FROM tb_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
        // Username exists, fetch the hashed password
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start a session and redirect
            session_start();
            $_SESSION['username'] = $username;
            echo "<script>alert('Login Successful'); window.location.href='dashboard.php';</script>";
        } else {
            // Password is incorrect
            echo "<script>alert('Invalid Password');</script>";
        }
    } else {
        // Username does not exist
        echo "<script>alert('Username not found');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        h2 {
            margin-top: 0;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 5px 0;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .links {
            text-align: center;
            margin-top: 10px;
        }
        .links a {
            color: #007bff;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="post" autocomplete="off">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Password" required>
            
            <button type="submit" name="login">Login</button>
        </form>
        
        <div class="links">
            <a href="registration.php">Register</a>
        </div>
    </div>
</body>
</html>
