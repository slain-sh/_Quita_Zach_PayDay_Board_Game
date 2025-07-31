<?php
session_start();
require_once("job_pools.php");
require_once("events.php");

$cols = 9;
$rows = 5;
$tile_map = [];

// Build 24-tile perimeter
for ($x = 0; $x < $cols; $x++) $tile_map[] = [$x, 0];
for ($y = 1; $y < $rows - 1; $y++) $tile_map[] = [$cols - 1, $y];
for ($x = $cols - 1; $x >= 0; $x--) $tile_map[] = [$x, $rows - 1];
for ($y = $rows - 2; $y > 0; $y--) $tile_map[] = [0, $y];

$total_tiles = count($tile_map);


// Process dice roll to move
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roll_btn'])) {
    $i = $_SESSION['turn_index'];
    $roll = rand(1, 6);
    $_SESSION['last_roll'] = $roll;

    $_SESSION['players'][$i]['position'] += $roll;
    $_SESSION['players'][$i]['position'] %= $total_tiles;

    // Trigger random event
    $event_keys = array_keys($events);
    $random_key = $event_keys[array_rand($event_keys)];
    $amount = $events[$random_key];
    $_SESSION['event_message'] = $random_key;
    $_SESSION['players'][$i]['money'] += $amount;

    // Check for win
    if ($_SESSION['players'][$i]['money'] >= 500000) {
        $_SESSION['winner'] = $_SESSION['players'][$i]['name'];
        header('Location: board.php');
        exit;
    }

    $_SESSION['turn_index'] = ($i + 1) % count($_SESSION['players']);
}

// unsets the event message when next turn is pressed, returns to updated board 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['next_turn'])) {
    unset($_SESSION['event_message']);
}


$current_name = $_SESSION['players'][$_SESSION['turn_index']]['name'];
$last_roll = $_SESSION['last_roll'];
$event_message = $_SESSION['event_message'] ?? null;
$winner = $_SESSION['winner'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>PayDay Board</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php require_once("career_path_modal.php"); ?>

<?php if ($winner): ?>
    <div class="modal">
        <div class="modal-content">
            <h2>🎉 <?= htmlspecialchars($winner) ?> Wins!</h2>
            <a href="reset.php">Play Again</a>
        </div>
    </div>
<?php endif; ?>

<?php if ($event_message): ?>
    <div class="modal">
        <div class="modal-content">
            <p><?= htmlspecialchars($event_message) ?></p>
            <form method="POST">
                <button type="submit" name="next_turn">Next Turn</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<h1>💸 PayDay</h1>
<h2>Current Turn: <?= htmlspecialchars($current_name) ?></h2>
<?php if ($last_roll): ?>
    <p>Last Roll: <?= $last_roll ?></p>
<?php endif; ?>

<form method="POST">
    <button type="submit" name="roll_btn" class="roll-btn">Roll Dice</button>
</form>

<div class="board">
<?php
// render the board
for ($y = 0; $y < $rows; $y++) {
    for ($x = 0; $x < $cols; $x++) {
        $index = array_search([$x, $y], $tile_map);
        if ($index === false) {
            echo "<div class='tile empty'></div>";
            continue;
        }

        echo "<div class='tile'>";
        echo "<span class='tile-num'>" . ($index + 1) . "</span>";

        echo "<div class='tokens'>";
        foreach ($_SESSION['players'] as $p) {
            if ($p['position'] === $index) {
                echo "<div class='token'>" . $p['icon'] . "</div>";
            }
        }
        echo "</div>";

        echo "</div>";
    }
}
?>
</div>

<h3>Player Status</h3>
<ul>
    <?php foreach ($_SESSION['players'] as $p): ?>
        <li><?= $p['icon'] ?> <?= htmlspecialchars($p['name']) ?> – ₱<?= number_format($p['money']) ?></li>
    <?php endforeach; ?>
</ul>

<a href="reset.php">🔄 Reset Game</a>
</body>
</html>
