<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_unset();
session_destroy();
$response = array(
  'success' => true,
  'message' => 'User logged out'
);
header('Content-Type: application/json');
echo json_encode($response);
?>
