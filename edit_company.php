<?php
session_start();
// Database connection
include 'db_connect.php';


$message = "";

// Fetch company data
$companyID = "";
$companyName = "";
$producer = "";
$developers = "";
$publisher = "";
$designers = "";

// Handle form submission for editing company
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_company'])) {
    $companyID = $_POST['company_id'];
    $companyName = $_POST['company_name'];
    $producer = $_POST['producer'];
    $developers = $_POST['developers'];
    $publisher = $_POST['publisher'];
    $designers = $_POST['designers'];

    $sql = "UPDATE company 
            SET CompanyName = ?, Producer = ?, Developers = ?, Publisher = ?, Designers = ? 
            WHERE CompanyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $companyName, $producer, $developers, $publisher, $designers, $companyID);

    if ($stmt->execute()) {
        $message = "Company information updated successfully.";
    } else {
        $message = "Error updating company: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch selected company details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select_company'])) {
    $companyID = $_POST['company_id'];
    $sql = "SELECT * FROM company WHERE CompanyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $companyID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $companyName = $row['CompanyName'];
        $producer = $row['Producer'];
        $developers = $row['Developers'];
        $publisher = $row['Publisher'];
        $designers = $row['Designers'];
    }
    $stmt->close();
}

// Fetch all companies for dropdown
$companies = $conn->query("SELECT CompanyID, CompanyName FROM company ORDER BY CompanyName");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company</title>
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
        <h1>Edit Company</h1>
        <!-- Form to select a company -->
        <form method="POST" action="edit_company.php">
            <label for="company_id">Select a Company:</label>
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
            <input type="submit" name="select_company" value="Load Details">
        </form>

        <!-- Form to edit company details -->
        <?php if ($companyID): ?>
        <form method="POST" action="edit_company.php">
            <input type="hidden" name="company_id" value="<?php echo $companyID; ?>">
            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" id="company_name" value="<?php echo $companyName; ?>" required>

            <label for="producer">Producer:</label>
            <input type="text" name="producer" id="producer" value="<?php echo $producer; ?>">

            <label for="developers">Developers:</label>
            <input type="text" name="developers" id="developers" value="<?php echo $developers; ?>">

            <label for="publisher">Publisher:</label>
            <input type="text" name="publisher" id="publisher" value="<?php echo $publisher; ?>">

            <label for="designers">Designers:</label>
            <input type="text" name="designers" id="designers" value="<?php echo $designers; ?>">

            <input type="submit" name="update_company" value="Update Company">
        </form>
        <?php endif; ?>

        <!-- Display message -->
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
    </div>
</body>
</html>
