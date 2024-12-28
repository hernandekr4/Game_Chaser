<?php
session_start();
// Database connection
include 'db_connect.php';


$message = "";

// Fetch user data
$userID = "";
$userName = "";
$userEmail= "";
$userRole = "";

// Handle form submission for editing user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $userID = $_POST['user_id'];
    $userName = $_POST['user_name'];
    $userEmail = $_POST['user_email'];
    $userRole = $_POST['user_role'];

    $sql = "UPDATE users 
            SET UserName = ?, UserEmail = ?, UserRole = ?
            WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $userName, $userEmail, $userRole, $userID);

    if ($stmt->execute()) {
        $message = "User information updated successfully.";
    } else {
        $message = "Error updating user: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch selected user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select_user'])) {
    $userID = $_POST['user_id'];
    $sql = "SELECT * FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userName = $row['UserName'];
        $user_email = $row['UserEmail'];
        $userRole = $row['UserRole'];
    }
    $stmt->close();
}

// Fetch all companies for dropdown
$companies = $conn->query("SELECT UserID, UserName FROM users ORDER BY UserName");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
        <h1>Edit User</h1>
        <!-- Form to select a user -->
        <form method="POST" action="edit_user.php">
            <label for="user_id">Select a User:</label>
            <select name="user_id" id="user_id" required>
                <option value="">-- Select User --</option>
                <?php
                if ($companies->num_rows > 0) {
                    while ($user = $companies->fetch_assoc()) {
                        $selected = ($userID == $user['UserID']) ? "selected" : "";
                        echo "<option value='{$user['UserID']}' $selected>{$user['UserName']}</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="select_user" value="Load Details">
        </form>

        <!-- Form to edit user details -->
        <?php if ($userID): ?>
        <form method="POST" action="edit_user.php">
            <input type="hidden" name="user_id" value="<?php echo $userID; ?>">
            <label for="user_name">User Name:</label>
            <input type="text" name="user_name" id="user_name" value="<?php echo $userName; ?>" required>

            <label for="user_email">User Email:</label>
            <input type="text" name="user_email" id="user_email" value="<?php echo $userEmail; ?>">

            <label for="user_role">User Role:</label>
            <input type="text" name="user_role" id="user_role" value="<?php echo $userRole; ?>">

            <input type="submit" name="update_user" value="Update User">
        </form>
        <?php endif; ?>

        <!-- Display message -->
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
    </div>
</body>
</html>
