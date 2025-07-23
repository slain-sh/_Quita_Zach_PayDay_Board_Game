<?php
session_start();

$all_chose_path = true;
foreach ($_SESSION['players'] as $player) {
    if ($player['path'] == null) {
        $all_chose_path = false;
        break;
    }
}

if (!$all_chose_path) {
    $i = $_SESSION['turn_index'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['career_path'])
        && $_SESSION['players'][$i]['path'] === null) {
        $career_choice = $_POST['career_path'];
        $_SESSION['players'][$i]['path'] = $career_choice;

        if ($career_choice == 'career') {
            header('Location: board.php');

        } elseif ($career_choice == 'college') {
            header('Location: board.php');

        }

        $_SESSION['turn_index'] = ($i+1) % count($_SESSION['players']);
    }

    echo '<form method="POST" class="modal">';
    echo '<div class="modal-content">';
    echo "<h1>👣CHOOSE YOUR PATH👣</h1>";
    echo "<h3>{$_SESSION['players'][$i]['name']}, you may either: </h3>";
    echo "<p>💼 Start working now for decent money OR 🎓 go to college for higher future income!</p>";
    echo '<button type="submit" name="career_path" value="career">Start with a Job</button>';
    echo '<button type="submit" name="career_path" value="college">Go to College</button>';
    echo '</div>';
    echo '</form>';
}

// $all_chose_path = true;
// foreach ($_SESSION['players'] as $player) {
//     if ($player['path'] === null) {
//         $all_chose_path = false;
//         break;
//     }
// }

// if (!$all_chose_path) {
//     $current = $_SESSION['turn_index'];
//     if ($_SESSION['players'][$current]['path'] === null && $_SERVER['REQUEST_METHOD'] === 'POST') {
//         $choice = $_POST['career_path'];
//         $_SESSION['players'][$current]['path'] = $choice;

//         if ($choice === 'career') {
//             $_SESSION['players'][$current]['job_choices'] = array_rand($career_jobs, num: 3); // pick 3 job indexes
//             $_SESSION['players'][$current]['stage'] = 'career_pick1'; // stage tracking
//         }
//         // $_SESSION['turn_index'] = ($current + 1) % count($_SESSION['players']);
//         $_SESSION['turn_index'] = ($current + 1);

//     }

//     echo '<form method="POST" class="modal">';
//     echo '<div class="modal-content">';
//     echo $_SESSION['turn_index'];
//     echo "<h1>👣CHOOSE YOUR PATH👣</h3>";
//     echo "<h3>{$_SESSION['players'][$current]['name']}, you may either: </h3>";
//     echo "<p>💼 Start working now for decent money OR 🎓 go to college for higher future income!</p>";
//     echo '<button type="submit" name="career_path" value="career">Start with a Job</button>';
//     echo '<button type="submit" name="career_path" value="college">Go to College</button>';
//     echo '</div>';
//     echo '</form>';
// }
?>