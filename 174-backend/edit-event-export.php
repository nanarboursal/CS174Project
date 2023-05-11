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

    
    $sql = "SELECT duedate from calendar WHERE username='$username' AND title='$title'";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();

    $duedate = $row['duedate'];

    $sql = "INSERT into tasks (username, description, duedate) VALUES ('$username', '$title', '$duedate')";
    $result = $conn->query($sql);

    if ($result === TRUE) {
        $response = ['success' => true];
        echo json_encode($response);
        exit;
    } else {
        $response = ['success' => false, 'message' => 'Export event unsuccessful'];
        echo json_encode($response);
        exit;
    }
}
?>