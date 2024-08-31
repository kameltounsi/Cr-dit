<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Inclure le fichier Config.php
include '../Config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] === 'delete') {
        $userId = $_POST['id'];

        if (!empty($userId)) {
            try {
                // Obtenir une connexion à la base de données
                $conn = GetConnexion();

                $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to execute the delete query']);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'PDOException: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User ID or action not provided']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
