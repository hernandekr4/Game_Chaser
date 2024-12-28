<?php

session_start();

include 'db_connect.php';


// Get list of genres for the dropdown
$genreOptions = [];
$genreQuery = "SELECT GenreID, GenreName FROM genre";
$genreResult = $conn->query($genreQuery);

if ($genreResult->num_rows > 0) {
    while ($row = $genreResult->fetch_assoc()) {
        $genreOptions[] = $row;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $genreInput = (int) $_POST['genre']; // Genre ID from the dropdown
    $genreInput = $conn->real_escape_string($genreInput); // Prevent SQL injection

    // SQL query to retrieve video games under the specified genre
    $sql = "
        SELECT v.GameID, v.Name, v.NumberofPlayers, v.ImageURL, g.GenreName
        FROM videogame v
        INNER JOIN videogame_genre vg ON v.GameID = vg.GameID
        INNER JOIN genre g ON vg.GenreID = g.GenreID
        WHERE g.GenreID = '$genreInput'
    ";

    $result = $conn->query($sql);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Video Games by Genre</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin-top: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        label {
            font-size: 18px;
            color: #333;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .blue-button {
            background-color: #007bff; /* Blue background */
            color: white; /* White text */
            border: none; /* Remove border */
            padding: 10px 20px; /* Add padding */
            font-size: 16px; /* Font size */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s ease; /* Smooth hover effect */
        }

        .blue-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .blue-button:active {
            background-color: #003f7f; /* Even darker blue when clicked */
        }
        .results {
            margin-top: 20px;
        }
        .results ul {
            list-style-type: none;
            padding: 0;
        }
        .results li {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .results img {
            border-radius: 5px;
            max-width: 150px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
    <?php
    if ($_SESSION['role'] === 'admin') {
        echo '<button class="blue-button" onclick="window.location.href=\'videogame_admin.html\'">Back</button>';
    } elseif ($_SESSION['role'] === 'user') {
        echo '<button class="blue-button" onclick="window.location.href=\'videogame_user.html\'">Back</button>';
    } else {
        echo '<p>Unknown role. Please contact support.</p>';
    }
    ?>
        <h1>Search Video Games by Genre</h1>
        <form method="POST" action="">
            <label for="genre">Select Genre:</label>
            <select id="genre" name="genre" required>
                <option value="">-- Select Genre --</option>
                <?php
                foreach ($genreOptions as $option) {
                    echo "<option value='{$option['GenreID']}'>{$option['GenreName']}</option>";
                }
                ?>
            </select>
            <button type="submit">Search</button>
        </form>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $result->num_rows > 0): ?>
            <div class="results">
                <h2>Video Games under Genre: 
                    <?php
                    foreach ($genreOptions as $option) {
                        if ($option['GenreID'] == $genreInput) {
                            echo $option['GenreName'];
                            break;
                        }
                    }
                    ?>
                </h2>
                <ul>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>";
                        echo "<strong>Name:</strong> " . $row["Name"] . "<br>";
                        echo "<strong>Number of Players:</strong> " . $row["NumberofPlayers"] . "<br>";
                        echo "<img src='" . $row["ImageURL"] . "' alt='" . $row["Name"] . "'><br>";
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $result->num_rows == 0): ?>
            <div class="results">
                <p>No video games found for the selected genre.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
