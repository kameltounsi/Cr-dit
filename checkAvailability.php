<?php
header('Content-Type: application/json');
require_once 'Config.php'; // Assurez-vous que le chemin est correct

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$carId = isset($data['carId']) ? $data['carId'] : null;
$dateDebut = isset($data['dateDebut']) ? $data['dateDebut'] : null;
$dateFin = isset($data['dateFin']) ? $data['dateFin'] : null;

if (!$carId || !$dateDebut || !$dateFin) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

try {
    $pdo = GetConnexion(); // Utilisation de la fonction GetConnexion
    $query = 'SELECT COUNT(*) FROM reservation WHERE id_car = :carId AND ((date_debut <= :dateFin AND date_fin >= :dateDebut))';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':carId', $carId);
    $stmt->bindParam(':dateDebut', $dateDebut);
    $stmt->bindParam(':dateFin', $dateFin);
    $stmt->execute();

    $reservationCount = $stmt->fetchColumn();
    $response = ['available' => $reservationCount == 0];
} catch (PDOException $e) {
    $response = ['error' => 'Database error: ' . $e->getMessage()];
}

echo json_encode($response);
?>
