<?php

$db = new SQLite3(__DIR__ . '/../db/progression_game.sqlite');

function generateProgression() {
    $start = rand(1, 20);
    $step = rand(1, 10);
    $progression = [];
    for ($i = 0; $i < 10; $i++) {
        $progression[] = $start + ($i * $step);
    }
    $missingIndex = rand(0, 9);
    $missingNumber = $progression[$missingIndex];
    $progression[$missingIndex] = '.';

    return [
        'progression' => $progression,
        'missingIndex' => $missingIndex,
        'missingNumber' => $missingNumber
    ];
}

function saveResult($db, $playerName, $correct, $missingNumber, $progression) {
    $correctedProgression = str_replace('.', $missingNumber, $progression);

    $stmt = $db->prepare("INSERT INTO game_results (player_name, correct, missing_number, progression) VALUES (:player_name, :correct, :missing_number, :progression)");
    $stmt->bindValue(':player_name', $playerName, SQLITE3_TEXT);
    $stmt->bindValue(':correct', $correct ? 1 : 0, SQLITE3_INTEGER);
    $stmt->bindValue(':missing_number', $missingNumber, SQLITE3_INTEGER);
    $stmt->bindValue(':progression', $correctedProgression, SQLITE3_TEXT);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerName = $_POST['name'];
    $playerAnswer = $_POST['answer'];
    $progressionData = json_decode($_POST['progressionData'], true);

    $isCorrect = $playerAnswer == $progressionData['missingNumber'];

    saveResult($db, $playerName, $isCorrect, $progressionData['missingNumber'], implode(' ', $progressionData['progression']));

    if ($isCorrect) {
        $message = "<h2>Поздравляем!!! Вы правильно нашли число!</h2>";
    } else {
        $correctedProgression = str_replace('.', $progressionData['missingNumber'], $progressionData['progression']);
        $message = "<h2>Неправильный ответ! Вот правильная прогрессия:</h2><p>" . implode(' ', $correctedProgression) . "</p>";
    }
    include 'view.php';
    exit;
}

$progressionData = generateProgression();
$progressionJson = json_encode($progressionData);
include 'view.php';