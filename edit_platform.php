<?php
session_start();
// Database connection
include 'db_connect.php';


$message = "";

// Fetch platform data
$platformID = "";
$platformName = "";

// Handle form submission for editing platform
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_platform'])) {
    $platformID = $_POST['platform_id'];
    $platformName = $_POST['platform_name'];

    $sql = "UPDATE platform 
            SET PlatformName = ?
            WHERE PlatformID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $platformName, $platformID);

    if ($stmt->execute()) {
        $message = "Platform information updated successfully.";
    } else {
        $message = "Error updating platform: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch selected platform details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select_platform'])) {
    $platformID = $_POST['platform_id'];
    $sql = "SELECT * FROM platform WHERE PlatformID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $platformID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $platformName = $row['PlatformName'];
    }
    $stmt->close();
}

// Fetch all companies for dropdown
$companies = $conn->query("SELECT PlatformID, PlatformName FROM platform ORDER BY PlatformName");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Platform</title>
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
        input[type="text"], select {
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
        .blue-button {
            background-color: #00c3ff; /* Blue background */
            color: white; /* White text */
            border: none; /* Remove border */
            padding: 10px 20px; /* Add padding */
            font-size: 16px; /* Font size */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s ease; /* Smooth hover effect */
        }

        .blue-button:hover {
            background-color: #01455a; /* Darker blue on hover */
        }

        .blue-button:active {
            background-color: #003f7f; /* Even darker blue when clicked */
        }
        .message {
            margin-top: 20px;
            font-size: 14px;
            color: #ffcc00;
        }
        .error {
            color: #ff4444;
            font-size: 14px;
            margin-top: 10px;
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
        <h1>Edit Platform</h1>
        <!-- Form to select a platform -->
        <form method="POST" action="edit_platform.php">
            <label for="platform_id">Select a Platform:</label>
            <select name="platform_id" id="platform_id" required>
                <option value="">-- Select Platform --</option>
                <?php
                if ($companies->num_rows > 0) {
                    while ($platform = $companies->fetch_assoc()) {
                        $selected = ($platformID == $platform['PlatformID']) ? "selected" : "";
                        echo "<option value='{$platform['PlatformID']}' $selected>{$platform['PlatformName']}</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="select_platform" value="Load Details">
        </form>

        <!-- Form to edit platform details -->
        <?php if ($platformID): ?>
        <form method="POST" action="edit_platform.php">
            <input type="hidden" name="platform_id" value="<?php echo $platformID; ?>">
            <label for="platform_name">Platform Name:</label>
            <input type="text" name="platform_name" id="platform_name" value="<?php echo $platformName; ?>" required>

            <input type="submit" name="update_platform" value="Update Platform">
        </form>
        <?php endif; ?>

        <!-- Display message -->
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
    </div>
</body>
</html>
