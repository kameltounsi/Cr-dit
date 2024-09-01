<?php
require 'Config.php'; // Include the configuration file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $model = $_POST['model'];
    $ville = $_POST['ville'];
    $errors = [];

    // Handle file uploads
    $uploadDir = '../uploads/';
    $filePaths = [];

    foreach (['img1', 'img2', 'img3'] as $img) {
        if (isset($_FILES[$img]) && $_FILES[$img]['error'] == UPLOAD_ERR_OK) {
            $fileName = basename($_FILES[$img]['name']);
            $filePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES[$img]['tmp_name'], $filePath)) {
                // Save the relative path to the file
                $filePaths[$img] = $filePath; // Store the relative file path
            } else {
                $errors[] = "Failed to upload $img.";
            }
        }
    }

    if (empty($errors)) {
        try {
            // Get PDO connection
            $conn = GetConnexion();

            // Prepare and execute the SQL statement
            $stmt = $conn->prepare("INSERT INTO voitures (nom, model, img1, img2, img3, ville) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $model, $filePaths['img1'], $filePaths['img2'], $filePaths['img3'], $ville]);

            echo json_encode(['success' => true, 'message' => 'Car added successfully']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to add car: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    }
}
?>
