<?php
require_once '../Model/Config.php'; // Inclure le fichier de connexion PDO
header('Content-Type: application/json');

$response = ['status' => 'error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;

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

    if ($password && $password !== $confirmPassword) {
        $response['message'] = 'Passwords do not match.';
        echo json_encode($response);
        exit;
    }

    // Définir le chemin du répertoire de téléchargement et le créer s'il n'existe pas
    $uploadFileDir = 'uploads/';
    if (!is_dir($uploadFileDir)) {
        if (!mkdir($uploadFileDir, 0755, true)) {
            $response['message'] = 'Failed to create upload directory.';
            echo json_encode($response);
            exit;
        }
    }

    $imagePath = null;
    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profilePhoto']['tmp_name'];
        $fileName = $_FILES['profilePhoto']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Vérifiez les extensions autorisées (ajustez selon vos besoins)
        $allowedExts = array('jpg', 'jpeg', 'png');
        if (in_array($fileExtension, $allowedExts)) {
            // Définir le chemin du fichier uploadé
            $dest_path = $uploadFileDir . uniqid() . '.' . $fileExtension;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $imagePath = $dest_path; // Chemin du fichier
            } else {
                $response['message'] = 'Error moving the uploaded file.';
                echo json_encode($response);
                exit;
            }
        } else {
            $response['message'] = 'Invalid file extension.';
            echo json_encode($response);
            exit;
        }
    }

    try {
        $pdo = GetConnexion();

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
        if ($imagePath) {
            $updateFields[] = "pdp = :profilePhoto";
            $params[':profilePhoto'] = $imagePath;
        }

        if (count($updateFields) > 0) {
            $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = :id";
            $params[':id'] = $userId;

            $stmt = $pdo->prepare($sql);

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
