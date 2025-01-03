<?php
// Start the session
session_start();

// Include the database connection (and initialization logic)
include 'db_connect.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect based on user role
    if ($_SESSION['role'] === 'admin') {
        header("Location: videogame_admin.html");
    } else {
        header("Location: videogame_user.html");
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Chaser</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            color: #4caf50;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px #000;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            color: #00c3ff;
            text-decoration: none;
            font-size: 20px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Welcome to Game Chaser</h1>
    <ul>
        <li><a href="register.html">Register</a></li>
        <li><a href="videogame_login.html">Login</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
    </ul>
</body>
</html>
