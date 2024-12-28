<?php
// Database connection
include 'db_connect.php';


$message = "";

// Handle form submission for deleting a company
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_company'])) {
    $companyID = $_POST['company_id'];

    // Delete the company record
    $sql = "DELETE FROM company WHERE CompanyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $companyID);

    if ($stmt->execute()) {
        $message = "Company deleted successfully.";
    } else {
        $message = "Error deleting company: " . $stmt->error;
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
    <title>Delete Company</title>
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
            color: #e74c3c;
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
            background: #e74c3c;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #c0392b;
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
        <h1>Delete Company</h1>
        <!-- Form to select a company for deletion -->
        <form method="POST" action="delete_company.php">
            <label for="company_id">Select a Company to Delete:</label>
            <select name="company_id" id="company_id" required>
                <option value="">-- Select Company --</option>
                <?php
                if ($companies->num_rows > 0) {
                    while ($company = $companies->fetch_assoc()) {
                        echo "<option value='{$company['CompanyID']}'>{$company['CompanyName']}</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="delete_company" value="Delete Company">
        </form>

        <!-- Display message -->
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
    </div>
</body>
</html>
