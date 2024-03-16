<?php
require("connexion.php");

$obj = new DbConnect;
$conn = $obj->connect();

try {
    $row = json_decode(file_get_contents("php://input"));

    if (!$row || !isset($row->fname) || !isset($row->lname) || !isset($row->country)) {
        throw new Exception("Invalid or incomplete data received");
    }

    $sql = "INSERT INTO users (fname, lname, country, created_at) VALUES (:fname, :lname, :country, :created_at)";
    $stmt = $conn->prepare($sql);
    $created_at = date("Y-m-d");
    $stmt->bindParam(":fname", $row->fname);
    $stmt->bindParam(":lname", $row->lname);
    $stmt->bindParam(":country", $row->country);
    $stmt->bindParam(":created_at", $created_at);

    if ($stmt->execute()) {
        $res = ['status' => 200, 'message' => 'Record created successfully'];
    } else {
        $res = ['status' => 404, 'message' => 'Failed to create record'];
    }

    echo json_encode($res);
} catch (Exception $e) {
    $res = ['status' => 500, 'message' => $e->getMessage()];
    echo json_encode($res);
}