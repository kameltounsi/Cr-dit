<?php
require_once 'Config.php'; // Inclure le fichier de configuration
session_start();

// Initialiser une réponse par défaut
$response = ['success' => false, 'message' => 'Unknown error'];

try {
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'You must be logged in to cancel a reservation.';
        echo json_encode($response);
        exit;
    }

    // Vérifier si l'ID de la réservation est passé en paramètre
    if (!isset($_GET['id'])) {
        $response['message'] = 'No reservation specified.';
        echo json_encode($response);
        exit;
    }

    $user_id = $_SESSION['user_id']; // Récupérer l'ID de l'utilisateur connecté
    $reservation_id = intval($_GET['id']); // Récupérer l'ID de la réservation

    // Connexion à la base de données
    $pdo = GetConnexion();

    // Commencer une transaction pour sécuriser l'opération
    $pdo->beginTransaction();

    // Vérifier si la réservation appartient à l'utilisateur connecté et peut être annulée
    $stmt = $pdo->prepare('
        SELECT id, date_debut 
        FROM reservation 
        WHERE id = :reservation_id AND id_user = :user_id AND date_debut > CURDATE()
    ');
    $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        $response['message'] = 'Reservation not found or cannot be cancelled.';
        $pdo->rollBack();
        echo json_encode($response);
        exit;
    }

    // Annuler la réservation
    $stmt = $pdo->prepare('DELETE FROM reservation WHERE id = :reservation_id');
    $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
    $stmt->execute();

    // Valider la transaction
    $pdo->commit();

    // Réponse en cas de succès
    $response['success'] = true;
    $response['message'] = 'Reservation cancelled successfully.';
    echo json_encode($response);
} catch (Exception $e) {
    // En cas d'erreur, annuler la transaction et renvoyer une erreur
    $pdo->rollBack();
    $response['message'] = 'Error cancelling the reservation: ' . $e->getMessage();
    echo json_encode($response);
}
?>
