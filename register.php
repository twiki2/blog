<?php
include 'db.php';

header('Content-Type: application/json');

$db = new Database();

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);
$role = $data['role'];

$sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";

if ($db->conn->query($sql) === TRUE) {
    echo json_encode(array("message" => "New user registered successfully"));
} else {
    echo json_encode(array("error" => $db->conn->error));
}

$db->conn->close();
?>
