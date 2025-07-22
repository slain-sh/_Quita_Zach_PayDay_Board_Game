<?php

function get_tile_event($pos) {
    $events = [
        0 => 'payday',
        5 => 'bonus',
        10 => 'investment',
        15 => 'danger',
        20 => 'random'
    ];

    return $events[$pos] ?? 'blank';
}

function apply_event($type, &$player) {
    switch ($type) {
        case 'payday':
            $player['money'] += 50000;
            return ['message' => "Pay Day! You earned ₱50,000"];
        case 'bonus':
            $gain = rand(10000, 30000);
            $player['money'] += $gain;
            return ['message' => "Bonus event! You gained ₱" . number_format($gain)];
        case 'investment':
            $chance = rand(0, 1);
            $gain = rand(20000, 50000);
            $amount = $chance ? $gain : -$gain;
            $player['money'] += $amount;
            return ['message' => $chance ? "Your investment gained ₱$gain!" : "Your investment lost ₱" . abs($amount)];
        case 'danger':
            $loss = rand(15000, 30000);
            $player['money'] -= $loss;
            return ['message' => "Loan Shark! You lost ₱$loss"];
        case 'random':
            $money = rand(-20000, 20000);
            $player['money'] += $money;
            return ['message' => $money >= 0 ? "Lucky! Gained ₱$money" : "Unlucky! Lost ₱" . abs($money)];
        default:
            return ['message' => "Nothing happened."];
    }
}

function render_board($players) {
    $tile_map = [];
    foreach ($players as $p) {
        $tile_map[$p['position']][] = $p['emoji'];
    }

    echo '<div class="grid">';

    // Top Row (0–8)
    for ($i = 0; $i < 9; $i++) {
        echo render_tile($i, $tile_map);
    }

    // Middle Rows
    for ($row = 1; $row < 4; $row++) {
        echo render_tile(25 - $row, $tile_map); // Left side
        for ($i = 0; $i < 7; $i++) {
            echo '<div class="tile empty"></div>';
        }
        echo render_tile(9 + $row - 1, $tile_map); // Right side
    }

    // Bottom Row (17–25)
    for ($i = 17; $i <= 25; $i++) {
        echo render_tile($i, $tile_map);
    }

    echo '</div>';
}

function render_tile($index, $tile_map) {
    $content = isset($tile_map[$index]) ? implode('', $tile_map[$index]) : '';
    return "<div class='tile'>$index<br>$content</div>";
}
