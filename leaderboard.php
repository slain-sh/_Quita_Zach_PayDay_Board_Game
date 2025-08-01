<?php
require_once("DBconn.php");
$conn = DBconnection();

$sql = "SELECT player_name, score FROM leaderboard ORDER BY score DESC LIMIT 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
</head>
<body>

<h1>Top 10 Payday Players</h1>

<table border="1">
    <tr>
        <th>Rank</th>
        <th>Player Name</th>
        <th>Score</th>
    </tr>
    <?php
    $rank = 1;
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rank++ . "</td>";
            echo "<td>" . htmlspecialchars($row['player_name']) . "</td>";
            echo "<td>" . $row['score'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No scores yet.</td></tr>";
    }
    ?>
</table>

<a href="reset.php"><button>Play Again?</button></a>

</body>
</html>
