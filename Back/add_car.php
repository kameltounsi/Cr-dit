<?php
require 'Config.php'; // Inclure le fichier de configuration

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $model = $_POST['model'];
    $ville = $_POST['ville'];
    $prix = $_POST['prix'];
    $errors = [];

    // Vérification que le prix est bien un nombre
    if (!is_numeric($prix)) {
        $errors[] = "Le prix doit être un nombre.";
    }

    // Gestion des téléchargements de fichiers
    $uploadDir = '../uploads/';
    $filePaths = [
        'img1' => null,
        'img2' => null,
        'img3' => null
    ];

    foreach (['img1', 'img2', 'img3'] as $img) {
        if (isset($_FILES[$img]) && $_FILES[$img]['error'] == UPLOAD_ERR_OK) {
            $fileName = basename($_FILES[$img]['name']);
            $filePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES[$img]['tmp_name'], $filePath)) {
                // Enregistrer le chemin relatif vers le fichier
                $filePaths[$img] = $filePath; // Stocker le chemin relatif du fichier
            } else {
                $errors[] = "Échec du téléchargement de $img.";
            }
        }
    }

    // Convertir les chemins null en chaînes vides pour SQL
    foreach ($filePaths as &$path) {
        $path = $path ?? '';
    }

    if (empty($errors)) {
        try {
            // Obtenir la connexion PDO
            $conn = GetConnexion();

            // Préparer et exécuter la requête SQL
            $stmt = $conn->prepare("INSERT INTO voitures (nom, model, img1, img2, img3, ville, prix) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $model, $filePaths['img1'], $filePaths['img2'], $filePaths['img3'], $ville, $prix]);

            echo json_encode(['success' => true, 'message' => 'Voiture ajoutée avec succès']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Échec de l\'ajout de la voiture : ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    }
}
?>
