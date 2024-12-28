<?php
// Database connection settings
$servername = "localhost";
$username = "rootuser";       // Replace with your MySQL username
$password = "rootpass";   // Replace with your MySQL password

// Connect to MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load SQL script file
$sqlFile = 'initialize_game_chaser.sql'; // The file name of your SQL script
if (!file_exists($sqlFile)) {
    die("Error: SQL file '$sqlFile' not found.");
}

$sql = file_get_contents($sqlFile);

// Split SQL statements by semicolon for execution
$sqlCommands = explode(';', $sql);

// Execute each SQL command
foreach ($sqlCommands as $command) {
    $command = trim($command);
    if (!empty($command)) {
        if (strpos($command, 'INSERT INTO users') !== false) {
            // Custom handling for the users table to hash passwords
            $stmt = $conn->prepare("INSERT INTO users (UserName, UserPassword, UserRole) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashedPassword, $role);

            // Insert admin
            $username = 'admin';
            $hashedPassword = password_hash('adminpass', PASSWORD_DEFAULT);
            $role = 'admin';
            $stmt->execute();

            // Insert user
            $username = 'user1';
            $hashedPassword = password_hash('userpass', PASSWORD_DEFAULT);
            $role = 'user';
            $stmt->execute();

            $stmt->close();
        } else {
            if ($conn->query($command) === TRUE) {
                echo "Executed: " . substr($command, 0, 50) . "...<br>";
            } else {
                echo "Error executing: " . $conn->error . "<br>";
            }
        }
    }
}

echo "Database initialized successfully!";

$conn->close();
?>
