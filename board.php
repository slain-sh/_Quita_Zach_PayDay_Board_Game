<?php
session_start();

$cols = 9;
$rows = 5;
$tile_map = [];

// Build 28-tile perimeter
for ($x = 0; $x < $cols; $x++) $tile_map[] = [$x, 0];
for ($y = 1; $y < $rows - 1; $y++) $tile_map[] = [$cols - 1, $y];
for ($x = $cols - 1; $x >= 0; $x--) $tile_map[] = [$x, $rows - 1];
for ($y = $rows - 2; $y > 0; $y--) $tile_map[] = [0, $y];

$total_tiles = count($tile_map);

$events = [
    "You got sick! Medicine's getting expensive these days. Pay ₱3,000" => -3000,
    "Car repair! Pay ₱2,000" => -2000,
    "Unpaid taxes 😬 Pay ₱3,500" => -3500,
    "It's your great grandmother's 101st birthday! Pay ₱2,000" => -2000,
    "Island Adventure! You go on a 3 day cruise. Pay ₱3,000" => -3000,
    "So kind! You donated to the \"Free The Bees\" movement. Pay ₱2,000" => -2000,
    "Jollibog cravings hitting hard! Got hungry for some chicken and spaghetti. Pay ₱500" => -500,
    "Got scammed! Free Data 200 text scam, even gave your birthday and mother's maiden name. Pay ₱2,000" => -2000,
    "Haircut promo GONE WRONG! You came out bald. Pay ₱500" => -500,
    "Reminder for next time... You used \"Pay Later\" on Lazaddy and forgot about it. Pay ₱2,500" => -2500,
    "Ugh! Your dog chewed your earphones again, time for some new ones. Pay ₱1,000" => -1000,
    "Kuya, bayad mo 'yan. You spilled coffee on someone's laptop. Pay ₱5,000" => -5000,
    "But they're cute tho! You tipped the barista too much kasi cute. Pay ₱500" => -500,
    "Oh no! Accidentally tapped Boop card 5 times on the NRT. Pay ₱500" => -500,
    "Defeat. You were challenged to eat some unli-wings, but your stomach lost. Pay ₱2,500" => -2500,
    "Woah! You flipped thrifted clothes and sold them online, ukay-ukay master! Claim ₱4,500" => 4500,
    "Tita in Canada randomly sent you padala! Claim ₱2,000" => 2000,
    "You found money on the street! Claim ₱1,000" => 1000,
    "Your investment paid off! Claim ₱4,000" => 4000,
    "Birthday gift! Claim ₱2,000" => 2000,
    "You helped the old lady cross the street! Claim ₱1,000" => 1000,
    "Your Shofee livestream comment got picked for a giveaway! Claim ₱2,000" => 2000,
    "Whenever I see girls and boys~ You started carolling to the neighbors! Claim ₱1,000" => 1000,
    "Pambansang Alaga! Your dog won 'Cutest Pet' in the barangay pet day! Claim ₱2,000" => 2000,
    "I'll take it! You won an e-raffle for load convertible to CCash! Claim ₱1,500" => 1500,
    "Quick Cash! You collected and sold old newspapers and bottles to the junk shop. Claim ₱500" => 500,
    "Hardcore Stan! You sold your extra BuTaS album to a fan! Claim ₱2,000" => 2000,
    "Gaming is life! You joined a \"Mobile Leggings:Beng Beng\" tournament and got 2nd place! Claim ₱3,000" => 3000,
    "You helped your grandma fix her WiFi twice! Claim ₱2,500 and a granny smooch" => 2500,
    "For the money! You won a bet of calling your ex to tell them you still love them. Claim ₱2,000" => 2000
]
$college_events = [
    "You got sick! Medicine's getting expensive these days. Pay ₱3,000" => -3000,
    "McDollibee midnight snack feels! Got hungry for some chicken nuggets and sundae. Pay ₱500" => -500,
    "Got scammed! Free Data 200 text scam, even gave your birthday and mother's maiden name. Pay ₱2,000" => -2000,
    "Gastos lang ambag! You contributed money for materials AND snacks for your group project. Pay ₱1,000" => -1000,
    "Instant regret... You joined 5 orgs. Paid all the fees. Regretted everything. Pay ₱1,500" => -1500,
    "You sold some of your homemade crinkles to your classmates! Claim ₱1,000" => 1000,
    "Leaked Math quiz answers reached the whole section! Claim ₱3,000" => 3000,
    "Birthday gift! Claim ₱2,000" => 2000,
    "You helped the old lady cross the street! Claim ₱1,000" => 1000,
    "Basta 'di ako bumagsak! You offered to do your bestie's thesis formatting. Claim ₱2,000" => 2000
];

// Process move
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    if ($_SESSION['players'][$i]['money'] >= 1000000) {
        $_SESSION['winner'] = $_SESSION['players'][$i]['name'];
        header('Location: board.php');
        exit;
    }

    $_SESSION['turn_index'] = ($i + 1) % count($_SESSION['players']);
}

$current = $_SESSION['players'][$_SESSION['turn_index']]['name'];
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
                <button type="submit">Next Turn</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<h1>💸 PayDay</h1>
<h2>Current Turn: <?= htmlspecialchars($current) ?></h2>
<?php if ($last_roll): ?>
    <p>Last Roll: <?= $last_roll ?></p>
<?php endif; ?>

<form method="POST">
    <button type="submit" class="roll-btn">Roll Dice</button>
</form>

<div class="board">
<?php
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
