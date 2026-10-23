<?php
require_once 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['annID']) && isset($_SESSION['userID'])) {
        $annID = $conn->real_escape_string($input['annID']);
        $userID = $_SESSION['userID'];

        $sql = "DELETE FROM announcement WHERE annID = '$annID' AND userID = '$userID'";

        if ($conn->query($sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
