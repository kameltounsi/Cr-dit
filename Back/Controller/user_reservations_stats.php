<?php
session_start();
header('Content-Type: application/json');

// Vérifiez si l'utilisateur est connecté et a les autorisations nécessaires
if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== 'Admin' && $_SESSION['user_role'] !== 'Agent de location')) {
    http_response_code(403);
    echo json_encode(['message' => 'Forbidden']);
    exit;
}

include('../Model/Config.php'); // Incluez le fichier de connexion à la base de données

// Obtenez la connexion PDO
$pdo = GetConnexion();

// Préparez et exécutez la requête SQL
$sql = "SELECT users.mail, COUNT(reservation.id) AS reservation_count 
        FROM users 
        LEFT JOIN reservation ON users.id = reservation.id_user 
        GROUP BY users.id";

try {
    $stmt = $pdo->query($sql);
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($stats);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Database query failed: ' . $e->getMessage()]);
}

$pdo = null;
