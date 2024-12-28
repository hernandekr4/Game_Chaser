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

// Query to fetch data
$platformName = "Nintendo";
$query = "
    SELECT 
        vg.Name AS 'Videogame Name', 
        c.CompanyName, 
        p.PlatformName 
    FROM 
        videogame vg
    JOIN 
        company c ON vg.CompanyID = c.CompanyID
    JOIN 
        runson r ON vg.GameID = r.GameID
    JOIN 
        platform p ON r.PlatformID = p.PlatformID
    WHERE 
        p.PlatformName = ?
         order by c.CompanyName
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $platformName);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videogame Query Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .table-container {
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .no-results {
            text-align: center;
            color: #666;
            margin: 20px 0;
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
    </style>
</head>
<body>
    <div class="table-container">
    <?php
    if ($_SESSION['role'] === 'admin') {
        echo '<button class="blue-button" onclick="window.location.href=\'videogame_admin.html\'">Back</button>';
    } elseif ($_SESSION['role'] === 'user') {
        echo '<button class="blue-button" onclick="window.location.href=\'videogame_user.html\'">Back</button>';
    } else {
        echo '<p>Unknown role. Please contact support.</p>';
    }
    ?>

        <h1>Names of all companies that develop video games for Nintendo platform</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>                        
                        <th>Company Name</th>
                        <th>Video Game Name</th>
                        <th>Platform Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['CompanyName']); ?></td>
                            <td><?php echo htmlspecialchars($row['Videogame Name']); ?></td>                           
                            <td><?php echo htmlspecialchars($row['PlatformName']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-results">No results found for platform "<?php echo htmlspecialchars($platformName); ?>".</p>
        <?php endif; ?>
        <?php $stmt->close(); ?>
        <?php $conn->close(); ?>
    </div>
</body>
</html>
