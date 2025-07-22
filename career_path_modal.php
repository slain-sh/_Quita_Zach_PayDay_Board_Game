<?php
$all_chose_path = true;
foreach ($_SESSION['players'] as $player) {
    if ($player['path'] === null) {
        $all_chose_path = false;
        break;
    }
}

if (!$all_chose_path) {
    $current = $_SESSION['turn_index'];
    if ($_SESSION['players'][$current]['path'] === null && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $choice = $_POST['career_path'];
        $_SESSION['players'][$current]['path'] = $choice;

        if ($choice === 'career') {
            $_SESSION['players'][$current]['job_choices'] = array_rand($job_jobs, 3); // pick 3 job indexes
            $_SESSION['players'][$current]['stage'] = 'career_pick1'; // stage tracking
        }
        $_SESSION['turn_index'] = ($current + 1) % count($_SESSION['players']);
        header("Location: board.php");
        exit;
    }

    echo '<form method="POST" class="modal">';
    echo "<h1>👣CHOOSE YOUR PATH👣</h3>";
    echo "<h3>{$_SESSION['players'][$current]['name']}, you may either: </h3>";
    echo "<p>💼 Start working now for decent money OR 🎓 go to college for higher future income!</p>";
    echo '<button name="career_path" value="career">Start with a Job</button>';
    echo '<button name="career_path" value="college">Go to College</button>';
    echo '</form>';
    exit;
}
?>


<!-- <div class="modal">
    <div class="modal-content">
        <div class ="choice-container"></div>
            <h2>College or Job</h2>
            <p>After graduating senior-high, you are faced with a major choice:
                Go to college, or get a job. Getting a job will provide you with an early source of income.
                Graduating from college takes 1 whole loop around the board, but will provide you with better job
                opportunities later on.
            </p>
            <div class="card">
                <h2>College</h2>
                <p>Get a headstart and earn early, choosing randomly from a pool of decent paying jobs</p>
            </div>
            <div class="card">
                <h2>Job</h2>
                <p>Get a headstart and earn early, choose randomly from a pool of decent paying jobs</p>
                <p>Get a headstart and earn early, choose randomly from a pool of decent paying jobs</p>
            </div>
    </div>
</div> -->