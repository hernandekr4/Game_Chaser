<?php
session_start();
// Database connection
include 'db_connect.php';


$message = "";

// Handle deletion of user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $userID = $_POST['user_id'];

    // Delete user
    $sql = "DELETE FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        $message = "User deleted successfully.";
    } else {
        $message = "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all users for dropdown
$users = $conn->query("SELECT UserID, UserName FROM users ORDER BY UserName");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
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

        <h1>Delete User</h1>
        <!-- Form to delete a user -->
        <form method="POST" action="delete_user.php">
            <label for="user_id">Select a User to Delete:</label>
            <select name="user_id" id="user_id" required>
                <option value="">-- Select User --</option>
                <?php
                if ($users->num_rows > 0) {
                    while ($user = $users->fetch_assoc()) {
                        echo "<option value='{$user['UserID']}'>{$user['UserName']}</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="delete_user" value="Delete User">
        </form>

        <!-- Display message -->
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
    </div>
</body>
</html>
