<?php
$servername = "localhost";
$username = "rootuser";       // Replace with your MySQL username
$password = "rootpass";   // Replace with your MySQL password
$dbname = "game_chaser";  // Define your database name

// Connect to MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the database exists
if ($conn->query("CREATE DATABASE IF NOT EXISTS $dbname") === TRUE) {
    $conn->select_db($dbname); // Switch to the database
} else {
    die("Error creating database: " . $conn->error);
}

// Check if tables are already initialized
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows == 0) {
    // Tables are not initialized; load and execute the initialization script
    $sqlFile = 'initialize_game_chaser.sql'; // SQL script to create and populate tables
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        $commands = explode(';', $sql); // Split SQL script into individual commands

        foreach ($commands as $command) {
            $command = trim($command);
            if (!empty($command)) {
                if ($conn->query($command) === FALSE) {
                    die("Error executing command: " . $conn->error);
                }
            }
        }
        echo "Database initialized successfully!";
    } else {
        die("Error: Initialization script 'initialize_game_chaser.sql' not found.");
    }
}else {
    // Only show message on login or register pages
    if (basename($_SERVER['PHP_SELF']) === 'videogame_login.html' || 
        basename($_SERVER['PHP_SELF']) === 'register.html') {
        echo "Database and tables already exist.<br>";
    }
}


?>
