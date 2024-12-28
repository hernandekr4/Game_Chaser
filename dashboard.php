<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Welcome message
echo "Welcome, " . htmlspecialchars($_SESSION['role']) . "!<br>";

// Redirect based on role
if ($_SESSION['role'] == 'admin') {
    header("Location: videogame_admin.html");
    exit();
} else {
    header("Location: videogame_user.html");
    exit();
}
?>
