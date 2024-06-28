<?php
include 'db.php';
require 'vendor/autoload.php'; 

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header('Content-Type: application/json');

$db = new Database();

$jwt = null;
$headers = apache_request_headers();
if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    $token = explode(" ", $authHeader);

    if (isset($token[1])) {
        $jwt = $token[1];
    } else {
        http_response_code(403);
        echo json_encode(array("error" => "Token not provided"));
        exit;
    }
} else {
    http_response_code(403);
    echo json_encode(array("error" => "Authorization header not found"));
    exit;
}

$key = $db->secret_key;

try {
    $decoded = JWT::decode($jwt, new key($key,'HS256'));

    $userId = $decoded->data->id;
    $userRole = $decoded->data->role;

    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array("error" => "Unauthorized. Admin role required."));
        exit;
    }

   
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    $sql = "DELETE FROM posts WHERE id=$id";

    if ($db->conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Record deleted successfully"));
    } else {
        echo json_encode(array("error" => $db->conn->error));
    }

} catch (Exception $e) {
    http_response_code(403);
    echo json_encode(array("error" => "Invalid token", "message" => $e->getMessage()));
}

$db->conn->close();
?>

