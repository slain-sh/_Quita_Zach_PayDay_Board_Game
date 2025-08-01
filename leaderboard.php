<?php
require_once("DBconn.php");
$conn = DBconnection();

$sql = "SELECT player_name, score, turns FROM leaderboard ORDER BY score DESC, turns ASC LIMIT 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<h1>Top 10 Payday Players</h1>

<table border="1">
    <tr>
        <th>Rank</th>
        <th>Player Name</th>
        <th>Score</th>
        <th>Turns</th>
    </tr>
    <?php
    $rank = 1;
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rank++ . "</td>";
            echo "<td>" . htmlspecialchars($row['player_name']) . "</td>";
            echo "<td>" . $row['score'] . "</td>";
            echo "<td>" . $row['turns'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No scores yet.</td></tr>";
    }
    ?>
</table>

<a href="reset.php"><button>Play Again?</button></a>

</body>
</html>
