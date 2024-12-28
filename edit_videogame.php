<?php
session_start();
// Database connection
include 'db_connect.php';


$message = "";

// Initialize variables
$gameID = "";
$gameName = "";
$numberOfPlayers = "";
$companyID = "";
$imageURL = "";

// Handle form submission for updating video game details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_game'])) {
    $gameID = $_POST['game_id'];
    $gameName = $_POST['game_name'];
    $numberOfPlayers = $_POST['number_of_players'];
    $companyID = $_POST['company_id'];
    $imageURL = $_POST['image_url'];

    $sql = "UPDATE videogame 
            SET Name = ?, NumberofPlayers = ?, CompanyID = ?, ImageURL = ?
            WHERE GameID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdisi", $gameName, $numberOfPlayers, $companyID, $imageURL, $gameID);

    if ($stmt->execute()) {
        $message = "Video game details updated successfully.";
    } else {
        $message = "Error updating video game: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch selected video game details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select_game'])) {
    $gameID = $_POST['game_id'];
    $sql = "SELECT * FROM videogame WHERE GameID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gameID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $gameName = $row['Name'];
        $numberOfPlayers = $row['NumberofPlayers'];
        $companyID = $row['CompanyID'];
        $imageURL = $row['ImageURL'];
    }
    $stmt->close();
}

// Fetch all video games for dropdown
$games = $conn->query("SELECT GameID, Name FROM videogame ORDER BY Name");

// Fetch all companies for the CompanyID dropdown
$companies = $conn->query("SELECT CompanyID, CompanyName FROM company ORDER BY CompanyName");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Video Game Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: rgba(0, 0, 0, 0.85);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #00c3ff;
            text-shadow: 2px 2px 4px #000;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: #2e4158;
            color: white;
            font-size: 16px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 20px 0;
            border: none;
            border-radius: 5px;
            background: #00c3ff;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #01455a;
        }
        .message {
            margin-top: 20px;
            font-size: 14px;
            color: #ffcc00;
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
    </style>
</head>
<body>
    <div class="form-container">
    <?php
    if ($_SESSION['role'] === 'admin') {
        echo '<button class="blue-button" onclick="window.location.href=\'videogame_admin.html\'">Back</button>';
    } elseif ($_SESSION['role'] === 'user') {
        echo '<button class="blue-button" onclick="window.location.href=\'videogame_user.html\'">Back</button>';
    } else {
        echo '<p>Unknown role. Please contact support.</p>';
    }
    ?>
        <h1>Edit Video Game Details</h1>
        <!-- Form to select a video game -->
        <form method="POST" action="edit_videogame.php">
            <label for="game_id">Select a Video Game:</label>
            <select name="game_id" id="game_id" required>
                <option value="">-- Select Game --</option>
                <?php
                if ($games->num_rows > 0) {
                    while ($game = $games->fetch_assoc()) {
                        $selected = ($gameID == $game['GameID']) ? "selected" : "";
                        echo "<option value='{$game['GameID']}' $selected>{$game['Name']}</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="select_game" value="Load Details">
        </form>

        <!-- Form to edit video game details -->
        <?php if ($gameID): ?>
        <form method="POST" action="edit_videogame.php">
            <input type="hidden" name="game_id" value="<?php echo $gameID; ?>">

            <label for="game_name">Game Name:</label>
            <input type="text" name="game_name" id="game_name" value="<?php echo $gameName; ?>" required>

            <label for="number_of_players">Number of Players:</label>
            <input type="number" step="1" name="number_of_players" id="number_of_players" value="<?php echo $numberOfPlayers; ?>" required>

            <label for="company_id">Company:</label>
            <select name="company_id" id="company_id" required>
                <option value="">-- Select Company --</option>
                <?php
                if ($companies->num_rows > 0) {
                    while ($company = $companies->fetch_assoc()) {
                        $selected = ($companyID == $company['CompanyID']) ? "selected" : "";
                        echo "<option value='{$company['CompanyID']}' $selected>{$company['CompanyName']}</option>";
                    }
                }
                ?>
            </select>

            <label for="image_url">Image URL:</label>
            <input type="text" name="image_url" id="image_url" value="<?php echo $imageURL; ?>">

            <input type="submit" name="update_game" value="Update Game Details">
        </form>
        <?php endif; ?>

        <!-- Display message -->
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
    </div>
</body>
</html>