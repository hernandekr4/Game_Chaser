<?php
session_start();
// Database connection
include 'db_connect.php';


// Fetch video games for dropdown
$games = $conn->query("SELECT GameID, Name FROM videogame");
if (!$games) {
    die("Error fetching video games: " . $conn->error);
}

// Fetch awards for dropdown
$awards = $conn->query("SELECT AwardID, AwardName FROM awards");
if (!$awards) {
    die("Error fetching awards: " . $conn->error);
}
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gameID = htmlspecialchars($_POST['game_id']);
    $awardID = htmlspecialchars($_POST['award_id']);

    // Insert the relationship into the awarded table
    $sql = "INSERT INTO awarded (AwardID, GameID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $awardID, $gameID);

    if ($stmt->execute()) {
        echo "<p>Link successfully created between Video Game and Award!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Video Game and Award</title>
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container for the form */
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Style for the form elements */
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="text"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
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
        .form-group {
            margin-bottom: 15px;
        }

        /* Message styles */
        .success {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Optional: Responsive design */
        @media (max-width: 600px) {
            .form-container {
                width: 90%;
            }
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
        <h2>Enter Link from Videogame to Award</h2>

        <!-- Display success or error message -->
        <?php if (isset($message)): ?>
            <div class="<?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
        <label for="game_id">Select Video Game:</label>
        <select id="game_id" name="game_id" required>
            <option value="">-- Select a Video Game --</option>
            <?php while ($game = $games->fetch_assoc()) { ?>
                <option value="<?php echo $game['GameID']; ?>">
                    <?php echo htmlspecialchars($game['Name']); ?>
                </option>
            <?php } ?>
        </select>
</div>

<div class="form-group">
        <label for="award">Select Award:</label>
        <select id="award_id" name="award_id" required>
            <option value="">-- Select an Award --</option>
            <?php while ($award = $awards->fetch_assoc()) { ?>
                <option value="<?php echo $award['AwardID']; ?>">
                    <?php echo htmlspecialchars($award['AwardName']); ?>
                </option>
            <?php } ?>
        </select>
        </div>

        <input type="submit" value="Link Award">
       

    </form>
    </div>
</body>
</html>