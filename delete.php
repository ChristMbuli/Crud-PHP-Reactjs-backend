<?php
// Require du fichier de connexion sécurisé
require("connexion.php");

$obj = new DbConnect;
$conn = $obj->connect();

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$sql = "DELETE FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    $response = ['status' => 1, 'message' => 'Record deleted successfully.'];
} else {
    $response = ['status' => 0, 'message' => 'Failed to delete record.'];
}
echo json_encode($response);