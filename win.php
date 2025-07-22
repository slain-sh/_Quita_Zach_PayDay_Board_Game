<?php
session_start();
$winner = $_SESSION['winner'] ?? 'Unknown';
?>

<!DOCTYPE html>
<html>
<head>
    <title>🎉 Winner!</title>
</head>
<body>
<h1>🏆 <?= htmlspecialchars($winner) ?> wins the game!</h1>
<p>Congratulations on being the richest player!</p>
<a href="setup.php">Play Again</a>
</body>
</html>
