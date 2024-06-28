<?php
include 'db.php';

header('Content-Type: application/json');

$db = new Database();


$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $db->conn->query($sql);

$posts = array();

if ($result->num_rows>0){
    while($row = $result->fetch_assoc()){
        $posts[] = $row;
    }
} 

echo json_encode($posts);

$db->conn->close();
?>

