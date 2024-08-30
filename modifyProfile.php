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
    $email = isset($data['modifyEmail']) ? trim($data['modifyEmail']) : null;
    $password = isset($data['modifyPassword']) ? trim($data['modifyPassword']) : null;
    $confirmPassword = isset($data['confirmModifyPassword']) ? trim($data['confirmModifyPassword']) : null;

    // Valider les mots de passe
    if ($password && $password !== $confirmPassword) {
        $response['message'] = 'Passwords do not match.';
        echo json_encode($response);
        exit;
    }

    try {
        $pdo = GetConnexion(); // Fonction pour obtenir la connexion PDO

        // Préparer la requête SQL pour mettre à jour l'email et/ou le mot de passe
        $updateFields = [];
        $params = [];

        if ($email) {
            $updateFields[] = "mail = :email";
            $params[':email'] = $email;
        }
        if ($password) {
            $updateFields[] = "password = :password";
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $params[':password'] = $hashedPassword;
        }

        if (count($updateFields) > 0) {
            $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = :id";
            $params[':id'] = $userId;

            $stmt = $pdo->prepare($sql);

            // Lier les paramètres
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }

            $stmt->execute();

            $response['status'] = 'success';
            $response['message'] = 'Profile updated successfully.';
        } else {
            $response['message'] = 'No changes were made.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Error updating profile: ' . $e->getMessage();
    }
}

echo json_encode($response);
?>
