<?php
// Database connection settings
include 'db_connect.php';


// Fetch video games and awards for dropdown options
$games = $conn->query("SELECT GameID, Name FROM videogame");
$awards = $conn->query("SELECT AwardID, AwardName FROM awards");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gameID = htmlspecialchars($_POST["game_id"]);
    $awardID = htmlspecialchars($_POST["award_id"]);
    $awardDate = htmlspecialchars($_POST["award_date"]);

    // Insert data into the awarded table
    $sql = "INSERT INTO awarded (GameID, AwardID, AwardDate) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $gameID, $awardID, $awardDate);

    if ($stmt->execute()) {
        echo "<p>Link added successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Video Game and Award</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 500px;
            margin: auto;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        input[type="submit"] {
            margin-top: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Link Video Game to Award</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="game_id">Select Video Game:</label>
        <select id="game_id" name="game_id" required>
            <option value="">-- Select a Video Game --</option>
            <?php while ($game = $games->fetch_assoc()) { ?>
                <option value="<?php echo $game['GameID']; ?>">
                    <?php echo $game['Name']; ?>
                </option>
            <?php } ?>
        </select>
        
        <label for="award_id">Select Award:</label>
        <select id="award_id" name="award_id" required>
            <option value="">-- Select an Award --</option>
            <?php while ($award = $awards->fetch_assoc()) { ?>
                <option value="<?php echo $award['AwardID']; ?>">
                    <?php echo $award['AwardName']; ?>
                </option>
            <?php } ?>
        </select>
        
        <label for="award_date">Award Date:</label>
        <input type="date" id="award_date" name="award_date">
        
        <input type="submit" value="Link Award">
    </form>
</body>
</html>
