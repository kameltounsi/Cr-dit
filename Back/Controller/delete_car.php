<?php
include '../Model/Config.php'; // Inclure votre fichier de configuration

header('Content-Type: application/json'); // Indiquer que la réponse est en JSON

// Vérifiez si l'identifiant de la voiture est passé via la requête POST
if (isset($_POST['id'])) {
    $carId = intval($_POST['id']); // Assurez-vous que l'ID est un entier

    // Obtenez la connexion à la base de données
    $pdo = GetConnexion();

    try {
        // Préparez la requête SQL pour supprimer la voiture
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
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de voiture non spécifié.']);
}
?>
