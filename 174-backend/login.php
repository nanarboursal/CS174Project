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
    $password = $data['password'];

    // Validate the user's input
    if (empty($username) || empty($password)) {
        $response = ['success' => false, 'message' => 'Please enter your username and password'];
        echo json_encode($response);
        exit;
    }

    // Check if the username and password combination is correct
    $sql = "SELECT * FROM users WHERE username='$username' AND pwd='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // Login successful
        $user = $result->fetch_assoc();
        $_SESSION['user_name'] = $user['username'];
        $response = ['success' => true];
        echo json_encode($response);
        exit;
    } else {
        // Login failed
        $response = ['success' => false, 'message' => 'Invalid username or password'];
        echo json_encode($response);
        exit;
    }
}
?>