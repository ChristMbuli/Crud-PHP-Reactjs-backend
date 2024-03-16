<?php
require("connexion.php");

$obj = new DbConnect;
$conn = $obj->connect();

$sql = "SELECT * FROM users";
$path = explode('/', $_SERVER['REQUEST_URI']);

if (isset($path[3]) && is_numeric($path[3])) {
    $sql .= " WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', (int)$path[3], PDO::PARAM_INT);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$users) {
        http_response_code(404);
        exit();
    }
} else {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($users);