<?php
include('Database.php');

$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reactionType = $_POST['reactionType'];
    $currentJokeId = isset($_POST['currentJokeId']) ? (int)$_POST['currentJokeId'] : 0;

    if ($reactionType == 'like') {
        $db->updateReaction($currentJokeId, 1, 0);
    } elseif ($reactionType == 'not_like') {
        $db->updateReaction($currentJokeId, 0, 1);
    }

    echo json_encode(['status' => 'success']);
    exit;
}
