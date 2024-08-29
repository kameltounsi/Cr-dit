<?php
require_once 'Config.php'; // Inclure le fichier de connexion PDO
header('Content-Type: application/json');

$response = ['status' => 'error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;

    // Obtenir les données de session de l'utilisateur
    session_start();
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'User not logged in.';
        echo json_encode($response);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $email = $data['modifyEmail'];
    $password = isset($data['modifyPassword']) ? $data['modifyPassword'] : null;
    $confirmPassword = isset($data['confirmModifyPassword']) ? $data['confirmModifyPassword'] : null;

    // Valider les mots de passe
    if ($password && $password !== $confirmPassword) {
        $response['message'] = 'Passwords do not match.';
        echo json_encode($response);
        exit;
    }

    try {
        $pdo = GetConnexion(); // Fonction pour obtenir la connexion PDO

        // Préparer la requête SQL pour mettre à jour l'email
        $updateFields = [];
        if ($email) {
            $updateFields[] = "mail = :email";
        }
        if ($password) {
            $updateFields[] = "password = :password";
        }

        if (count($updateFields) > 0) {
            $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            
            if ($email) {
                $stmt->bindParam(':email', $email);
            }
            if ($password) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt->bindParam(':password', $hashedPassword);
            }
            $stmt->bindParam(':id', $userId);

            $stmt->execute();
        }

        $response['status'] = 'success';
        $response['message'] = 'Profile updated successfully.';
    } catch (PDOException $e) {
        $response['message'] = 'Error updating profile: ' . $e->getMessage();
    }
}

echo json_encode($response);
?>
