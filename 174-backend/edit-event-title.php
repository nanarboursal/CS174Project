<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $title = $data['title'];
    $newtitle = $data['newtitle'];

    // Validate the user's input
    if (empty($newtitle)) {
        $response = ['success' => false, 'message' => 'Please enter a new title'];
        echo json_encode($response);
        exit;
    }

    
    $sql = "UPDATE calendar SET title='$newtitle' WHERE username='$username' AND title='$title'";
    $result = $conn->query($sql);

    if ($result === TRUE) {
        $response = ['success' => true];
        echo json_encode($response);
        exit;
    } else {
        $response = ['success' => false, 'message' => 'Edit event unsuccessful'];
        echo json_encode($response);
        exit;
    }
}
?>