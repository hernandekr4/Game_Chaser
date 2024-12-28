<?php
session_start();
// Database connection
include 'db_connect.php';

// Query to fetch data
$username = "george.wolbrecht";
$platform = "XBOX";
$query = "
    SELECT v.Name AS GameName, g.GenreName, pf.PlatformName
    FROM videogame v
    JOIN videogame_genre vg ON v.GameID = vg.GameID
    JOIN genre g ON vg.GenreID = g.GenreID
    JOIN runson r ON v.GameID = r.GameID
    JOIN platform pf ON r.PlatformID = pf.PlatformID
    JOIN plays p ON v.GameID = p.GameID
    JOIN users u ON p.UserID = u.UserID
    WHERE u.UserName = ? AND pf.PlatformName = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username, $platform);
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

        <h1>Video game Name and Genre that a specific user, George Wolbrecht, plays on the Xbox Platform</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Game Name</th>
                        <th>Genre</th>
                        <th>Platform</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['GameName']); ?></td>
                            <td><?php echo htmlspecialchars($row['GenreName']); ?></td>
                            <td><?php echo htmlspecialchars($row['PlatformName']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-results">No results found for user "<?php echo htmlspecialchars($username); ?>" on platform "<?php echo htmlspecialchars($platform); ?>".</p>
        <?php endif; ?>
        <?php $stmt->close(); ?>
        <?php $conn->close(); ?>
    </div>
</body>
</html>
