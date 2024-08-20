<?php
require 'conf.php'; // Include database connection

// Check if the form is submitted
if (isset($_POST["submit"])) {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST["name"]));
    $username = htmlspecialchars(trim($_POST["username"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $password_confirm = $_POST["confirm_password"];
    $age = intval($_POST["age"]);
    $course = htmlspecialchars(trim($_POST["course"]));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format');</script>";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT * FROM tb_user WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Username or Email already exists');</script>";
        } else {
            if ($password === $password_confirm) {
                // Hash the password before storing
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Use a prepared statement to insert data
                $stmt = $conn->prepare("INSERT INTO tb_user (name, username, email, password, age, course) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $name, $username, $email, $hashed_password, $age, $course);

                if ($stmt->execute()) {
                    echo "<script>alert('Registration Successful'); window.location.href='login.php';</script>";
                } else {
                    echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Passwords do not match');</script>";
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
            box-sizing: border-box;
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
        input[type="text"], input[type="email"], input[type="password"], input[type="number"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
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
    <div class="form-container">
        <h2>Register</h2>
        <form action="registration.php" method="post" autocomplete="off">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" placeholder="Name" required>
            
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Username" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Password" required>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
            
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" placeholder="Age" required>
            
            <label for="course">Course:</label>
            <input type="text" name="course" id="course" placeholder="Course" required>
            
            <button type="submit" name="submit">Register</button>
        </form>
        
        <div class="links">
            <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
