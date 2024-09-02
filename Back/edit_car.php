<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $carId = $_POST['car_id'];
    $nom = $_POST['nom'];
    $model = $_POST['model'];
    $ville = $_POST['ville'];
    $prix = $_POST['prix'];

    // Gestion des fichiers d'images
    $img1 = $_FILES['img1']['name'];
    $img2 = $_FILES['img2']['name'];
    $img3 = $_FILES['img3']['name'];

    // Récupérer les connexions à la base de données
    $pdo = GetConnexion();

    try {
        // Commencer une transaction
        $pdo->beginTransaction();

        // Préparer la requête SQL de mise à jour
        $sql = "UPDATE voitures SET nom = :nom, model = :model, ville = :ville, prix = :prix";

        // Si une nouvelle image est téléchargée, ajouter à la requête SQL
        if (!empty($img1)) {
            $img1Path = '../uploads/' . $img1;
            $sql .= ", img1 = :img1";
            move_uploaded_file($_FILES['img1']['tmp_name'], $img1Path);
        }
        if (!empty($img2)) {
            $img2Path = '../uploads/' . $img2;
            $sql .= ", img2 = :img2";
            move_uploaded_file($_FILES['img2']['tmp_name'], $img2Path);
        }
        if (!empty($img3)) {
            $img3Path = '../uploads/' . $img3;
            $sql .= ", img3 = :img3";
            move_uploaded_file($_FILES['img3']['tmp_name'], $img3Path);
        }

        $sql .= " WHERE id = :carId";

        // Préparer la requête
        $stmt = $pdo->prepare($sql);

        // Associer les valeurs aux paramètres
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':carId', $carId, PDO::PARAM_INT);

        // Si les images sont téléchargées, les associer aux paramètres avec le chemin relatif
        if (!empty($img1)) {
            $stmt->bindParam(':img1', $img1Path);
        }
        if (!empty($img2)) {
            $stmt->bindParam(':img2', $img2Path);
        }
        if (!empty($img3)) {
            $stmt->bindParam(':img3', $img3Path);
        }

        // Exécuter la requête
        $stmt->execute();

        // Commit la transaction
        $pdo->commit();

        // Envoyer une réponse de succès
        echo "Success: Car updated successfully.";
    } catch (Exception $e) {
        // Rollback en cas d'erreur
        $pdo->rollBack();
        // Envoyer une réponse d'erreur
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: Invalid request method.";
}
?>
