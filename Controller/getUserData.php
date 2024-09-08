<?php
require_once '../Model/Config.php'; // Inclure le fichier de connexion PDO
header('Content-Type: application/json');

$response = ['status' => 'error'];

session_start();
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $pdo = GetConnexion(); // Fonction pour obtenir la connexion PDO

    $stmt = $pdo->prepare("SELECT mail AS email, pdp AS profilePhoto FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $response['status'] = 'success';
        $response['data'] = $user;
    } else {
        $response['message'] = 'User not found.';
    }
} catch (PDOException $e) {
    $response['message'] = 'Error fetching user data: ' . $e->getMessage();
}

echo json_encode($response);
?>
