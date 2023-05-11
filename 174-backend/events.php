<?php

session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];

$sql = "SELECT cid, title, duedate FROM calendar where username='$username'";
$result = $conn->query($sql);

// Get events as an array
$events = array();
while ($row = $result->fetch_assoc()) {
    $event = array(
        'id' => $row['cid'],
        'title' => $row['title'],
        'start' => $row['duedate']
    );
    array_push($events, $event);
}

header('Content-Type: application/json');
echo json_encode($events);

?>
