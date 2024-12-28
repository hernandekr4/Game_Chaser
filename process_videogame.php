<?php
$host = "localhost";
$user = "root";
$password = "root"; // Replace with your database password
$database = "game_chaser"; // Replace with your database name

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $players = $_POST['players'];
    $companyID = $_POST['company'];
    $imageURL = $_POST['image'];

    $stmt = $conn->prepare("INSERT INTO videogame (Name, NumberofPlayers, CompanyID, ImageURL) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $name, $players, $companyID, $imageURL);

    if ($stmt->execute()) {
        echo "New videogame added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
