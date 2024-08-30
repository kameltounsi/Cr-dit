<?php
// Inclure le fichier de configuration
require '/car_rent/config.php';

try {
    // Obtenir la connexion à la base de données
    $pdo = GetConnexion();

    // Préparer et exécuter la requête SQL pour récupérer les utilisateurs
    $stmt = $pdo->prepare("SELECT mail, role, pdp FROM users");
    $stmt->execute();

    // Récupérer tous les résultats sous forme de tableau associatif
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les données au format JSON
    header('Content-Type: application/json');
    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode(['error' => "Erreur: " . $e->getMessage()]);
}
?>
