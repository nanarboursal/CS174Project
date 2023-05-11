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
    $description = $data['description'];
    $duedate = $data['duedate'];


    $sql = "DELETE from tasks WHERE username='$username' AND description='$description' AND duedate='$duedate'";
    $result = $conn->query($sql);
    if ($result === TRUE) {
        $sql = "DELETE from calendar WHERE username='$username' AND title='$description' AND duedate='$duedate'";
        $result = $conn->query($sql);
        if ($result == TRUE) {
            $response = ['success' => true];
            echo json_encode($response);
            exit;
        } else {
            $response = ['success' => false, 'message' => 'Complete task unsuccessful'];
            echo json_encode($response);
            exit;
        }
    } else {
        $response = ['success' => false, 'message' => 'Complete task unsuccessful'];
        echo json_encode($response);
        exit;
    }
}
?>