<?php
session_start();
// Database connection
$host = "localhost";
$user = "root";
$password = "root"; // Replace with your database password
$database = "game_chaser"; // Replace with your database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission (AJAX request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    $name = $_POST['name'];
    $players = $_POST['players'];
    $companyID = $_POST['company'];
    $imageURL = $_POST['image'];

    $stmt = $conn->prepare("INSERT INTO videogame (Name, NumberofPlayers, CompanyID, ImageURL) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $name, $players, $companyID, $imageURL);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'New videogame added successfully!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $stmt->error;
    }

    $stmt->close();
    echo json_encode($response); // Send JSON response
    exit();
}

// Fetch companies for the dropdown
$companyQuery = "SELECT CompanyID, CompanyName FROM company";
$companyResult = $conn->query($companyQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videogame Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
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
        .success {
            color: green;
            margin-top: 10px;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <h1>Add Videogame</h1>
        <form id="videogameForm">
            <div class="form-group">
                <label for="name">Game Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="players">Number of Players</label>
                <input type="number" id="players" name="players" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="company">Company</label>
                <select id="company" name="company" required>
                    <option value="">Select a company</option>
                    <?php
                    if ($companyResult->num_rows > 0) {
                        while ($row = $companyResult->fetch_assoc()) {
                            echo "<option value='" . $row['CompanyID'] . "'>" . htmlspecialchars($row['CompanyName']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No companies available</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="text" id="image" name="image">
            </div>
            <input type="submit" value="Add Game">
            <div id="response"></div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $("#videogameForm").on("submit", function (e) {
                e.preventDefault(); // Prevent form from refreshing the page
                
                $.ajax({
                    url: "", // Submits to the same script
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        let res = JSON.parse(response);
                        if (res.status === 'success') {
                            $("#response").html(res.message).css("color", "green");
                            $("#videogameForm")[0].reset(); // Clear the form
                        } else {
                            $("#response").html(res.message).css("color", "red");
                        }
                    },
                    error: function () {
                        $("#response").html("An error occurred. Please try again.").css("color", "red");
                    }
                });
            });
        });
    </script>
</body>
</html>
