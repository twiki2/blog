<?php

require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

include 'db.php';

header('Content-Type: application/json');

$db = new Database();
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $password = $data['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $db->conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $key = $db->secret_key;
            $payload = array(
                "iss" => "http://localhost",
                "aud" => "http://localhost",
                "iat" => time(),
                "nbf" => time(),
                "data" => array(
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "role" => $user['role']
                )
            );

            $jwt = JWT::encode($payload, $key,'HS256');
            echo json_encode(array("message" => "Login successful", "token" => $jwt));
        } else {
            http_response_code(401);
            echo json_encode(array("error" => "Invalid password"));
        }
    } else {
        http_response_code(404);
        echo json_encode(array("error" => "User not found"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Username and password are required"));
}

$db->conn->close();
?>

