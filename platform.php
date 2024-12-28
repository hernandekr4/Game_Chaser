<?php
session_start();
// Insert the genre data into the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    include 'db_connect.php';

    // Retrieve the form data
    $PlatformName = $_POST['PlatformName'];

    // Insert data into the database
    $sql = "INSERT INTO platform (PlatformName)
            VALUES ('$PlatformName')";

    if ($conn->query($sql) === TRUE) {
        $message = "New platform record created successfully!";
        $messageType = "success";
    } else {
        $message = "Error: " . $conn->error;
        $messageType = "error";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Form</title>
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
            font-weight: bold;
            color: #333;
            display: block;
            margin: 10px 0 5px;
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
        <h2>Enter Platform Information</h2>

        <!-- Display success or error message -->
        <?php if (isset($message)): ?>
            <div class="<?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Form for input -->
        <form action="" method="POST">
            <label for="PlatformName">Platform Name:</label>
            <input type="text" id="PlatformName" name="PlatformName" required>

            <input type="submit" value="Submit">
        </form>
    </div>

</body>
</html>