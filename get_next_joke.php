<?php
include('Database.php');

$db = new Database();
$conn = $db->connect();

$currentJokeId = isset($_GET['currentJokeId']) ? (int)$_GET['currentJokeId'] : 0;

$joke = $db->getNextJoke($currentJokeId);

if ($joke) {
    echo json_encode(['joke' => $joke['content'], 'nextJokeId' => $joke['id']]);
} else {
    echo json_encode(['joke' => 'No jokes available.']);
}
