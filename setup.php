<?php
session_start();

$emoji_options = ['🦊', '🐸', '🐵', '🐱', '🐯', '🐼', '🐧', '🐷', '🐤', '🗿'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If the start_game button has been pressed
    if (isset($_POST['start_game'])) {
        $player_count = intval($_POST['player_count']);
        $players = [];

        for ($i = 0; $i < $player_count; $i++) {
            $name = trim($_POST["name_$i"]);
            $icon = $_POST["icon_$i"];
            $players[] = [
                'name' => $name ?: "Player " . ($i + 1),
                'icon' => $icon,
                'position' => 0,
                'money' => 10000,
                'turns' => 0,
                // 'path' => null,            // college or career
                // 'loops' => 0,              // for tracking big events in the game (college graduation, spouse, children, etc.)
                // 'career' => null,          // filled later
                // 'career_salary' => 0       // salary from chosen job
            ];
        }

        $_SESSION['players'] = $players;
        $_SESSION['turn_index'] = 0;
        $_SESSION['last_roll'] = null;
        $_SESSION['event_message'] = null;

        header('Location: board.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Setup PayDay</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h1>🎮 Setup PayDay Game</h1>
<form method="POST" class="setup-form">
    <label>Number of Players:</label>
    <select onchange="this.form.submit()" name="player_count">
        <?php for ($i = 2; $i <= 4; $i++): ?>
            <option value="<?= $i ?>" <?= ($_POST['player_count'] ?? 2) == $i ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>

    <?php
    $count = $_POST['player_count'] ?? 2;
    for ($i = 0; $i < $count; $i++):
    ?>
        <fieldset>
            <legend>Player <?= $i + 1 ?></legend>
            <label>Name:</label>
            <input type="text" name="name_<?= $i ?>" required>

            <label>Icon:</label>
            <select name="icon_<?= $i ?>" required>
                <?php foreach ($emoji_options as $emoji): ?>
                    <option value="<?= $emoji ?>"><?= $emoji ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>
    <?php endfor; ?>

    <button type="submit" name="start_game">Start Game</button>
</form>
</body>
</html>
