<?php
require("connexion.php");

$obj = new DbConnect;
$conn = $obj->connect();

// Récupérer les données JSON


$input = json_decode(file_get_contents('php://input'));

// Vérifier et nettoyer les données d'entrée
$id = filter_var($input->id, FILTER_VALIDATE_INT);
$fname = htmlspecialchars($input->fname, ENT_QUOTES, 'UTF-8');
$lname = htmlspecialchars($input->lname, ENT_QUOTES, 'UTF-8');
$country = htmlspecialchars($input->country, ENT_QUOTES, 'UTF-8');

// Vérifier si les données sont valides
if ($id !== false && $fname !== null && $lname !== null && $country !== null) {
    // Préparer la requête SQL avec des paramètres
    $sql = "UPDATE users SET fname= :fname, lname= :lname, country= :country, updated_at = :updated_at WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $updated_at = date("Y-m-d");

    // Liaison des paramètres avec leurs valeurs
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
    $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
    $stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);

    // Exécution de la requête
    if ($stmt->execute()) {
        $res = ['status' => 200, 'message' => 'Record updated successfully'];
    } else {
        $res = ['status' => 403, 'message' => 'Failed to update record'];
    }
} else {
    $res = ['status' => 400, 'message' => 'Invalid input data'];
}

// Encoder la réponse en JSON et renvoyer
header('Content-Type: application/json');
echo json_encode($res);