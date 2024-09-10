<?php
include '../Model/Config.php'; // Inclure votre fichier de configuration

header('Content-Type: application/json'); // Indiquer que la réponse est en JSON

// Vérifiez si l'identifiant de la voiture est passé via la requête POST
if (isset($_POST['id'])) {
    $carId = intval($_POST['id']); // Assurez-vous que l'ID est un entier

    // Obtenez la connexion à la base de données
    $pdo = GetConnexion();

    try {
        // Vérifiez d'abord s'il existe une réservation future pour cette voiture
        $sqlCheckReservation = "SELECT COUNT(*) FROM reservation WHERE id_car = :id AND date_fin > CURDATE()";
        $stmtCheck = $pdo->prepare($sqlCheckReservation);
        $stmtCheck->bindParam(':id', $carId, PDO::PARAM_INT);
        $stmtCheck->execute();
        $reservationCount = $stmtCheck->fetchColumn();

        // Si une réservation future existe, on empêche la suppression
        if ($reservationCount > 0) {
            echo json_encode(['success' => false, 'message' => 'Cette voiture est réservée pour une date future et ne peut pas être supprimée.']);
        } else {
            // Si aucune réservation future, on peut supprimer la voiture
            $sql = "DELETE FROM voitures WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            // Liez l'ID de la voiture au paramètre
            $stmt->bindParam(':id', $carId, PDO::PARAM_INT);

            // Exécutez la requête
            $stmt->execute();

            // Préparez la réponse JSON
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'La voiture a été supprimée avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Aucune voiture trouvée avec cet ID.']);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de voiture non spécifié.']);
}
?>
