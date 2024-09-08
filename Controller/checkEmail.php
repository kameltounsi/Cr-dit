<?php
require_once '../Model/Config.php'; // Inclure le fichier de connexion PDO

header('Content-Type: application/json');

$response = ['emailExists' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['email'])) {
        $email = $data['email'];

        try {
            $pdo = GetConnexion(); // Fonction pour obtenir la connexion PDO
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE mail = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $emailExists = $stmt->fetchColumn() > 0;

            $response['emailExists'] = $emailExists;
        } catch (PDOException $e) {
            $response['error'] = 'Error checking email: ' . $e->getMessage();
        }
    }
}

echo json_encode($response);
?>
