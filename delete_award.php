<?php
session_start();

include 'db_connect.php';

$message = "";

// Handle deletion of award
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_award'])) {
    $awardID = $_POST['award_id'];

    // Delete award
    $sql = "DELETE FROM awards WHERE AwardID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $awardID);

    if ($stmt->execute()) {
        $message = "Award deleted successfully.";
    } else {
        $message = "Error deleting award: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all awards for dropdown
$awards = $conn->query("SELECT AwardID, AwardName FROM awards ORDER BY AwardName");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Award</title>
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
        select {
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
            background: #ff4444;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #cc0000;
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
        .message {
            margin-top: 20px;
            font-size: 14px;
            color: #ffcc00;
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

        <h1>Delete Award</h1>
        <!-- Form to delete a award -->
        <form method="POST" action="delete_award.php">
            <label for="award_id">Select a Award to Delete:</label>
            <select name="award_id" id="award_id" required>
                <option value="">-- Select Award --</option>
                <?php
                if ($awards->num_rows > 0) {
                    while ($award = $awards->fetch_assoc()) {
                        echo "<option value='{$award['AwardID']}'>{$award['AwardName']}</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="delete_award" value="Delete Award">
        </form>

        <!-- Display message -->
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
    </div>
</body>
</html>
