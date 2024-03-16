<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

include 'DbConnect.php';
$objDb = new DbConnect;
$conn = $objDb->connect();

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "GET":
        $sql = "SELECT * FROM users";
        $path = explode('/', $_SERVER['REQUEST_URI']);
        if (isset($path[3]) && is_numeric($path[3])) {
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->execute();
            $users = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode($users);
        break;
    case "POST":
        $user = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO users(id, name, email, mobile, created_at) VALUES(null, :name, :email, :mobile, :created_at)";
        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d');
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':created_at', $created_at);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record created successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create record.'];
        }
        echo json_encode($response);
        break;

    case "PUT":
        $user = json_decode(file_get_contents('php://input'));
        $sql = "UPDATE users SET name= :name, email =:email, mobile =:mobile, updated_at =:updated_at WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $updated_at = date('Y-m-d');
        $stmt->bindParam(':id', $user->id);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':updated_at', $updated_at);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record updated successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to update record.'];
        }
        echo json_encode($response);
        break;


    case "DELETE":
        $sql = "DELETE FROM users WHERE id = :id";
        $path = explode('/', $_SERVER['REQUEST_URI']);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[3]);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record deleted successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to delete record.'];
        }
        echo json_encode($response);
        break;
}

//-------------------------------
// require("connexion.php");

// $obj = new DbConnect;
// $conn = $obj->connect();

// Récupérer les données JSON
// $input = json_decode(file_get_contents('php://input'));

// Vérifier et nettoyer les données d'entrée
// $id = filter_var($input->id, FILTER_VALIDATE_INT);
// $fname = htmlspecialchars($input->fname, ENT_QUOTES, 'UTF-8');
// $lname = htmlspecialchars($input->lname, ENT_QUOTES, 'UTF-8');
// $country = htmlspecialchars($input->country, ENT_QUOTES, 'UTF-8');

// Vérifier si les données sont valides
// if ($id !== false && $fname !== null && $lname !== null && $country !== null) {
    // Préparer la requête SQL avec des paramètres
    // $sql = "UPDATE users SET fname= :fname, lname= :lname, country= :country, updated_at = :updated_at WHERE id = :id";
    // $stmt = $conn->prepare($sql);
    // $updated_at = date("Y-m-d");

    // Liaison des paramètres avec leurs valeurs
    // $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    // $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
    // $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
    // $stmt->bindParam(':country', $country, PDO::PARAM_STR);
    // $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);

    // Exécution de la requête
//     if ($stmt->execute()) {
//         $res = ['status' => 200, 'message' => 'Record updated successfully'];
//     } else {
//         $res = ['status' => 403, 'message' => 'Failed to update record'];
//     }
// } else {
//     $res = ['status' => 400, 'message' => 'Invalid input data'];
// }

// Encoder la réponse en JSON et renvoyer
// header('Content-Type: application/json');
// echo json_encode($res);