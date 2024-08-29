<?php
require_once 'user.php'; // Inclure la classe User
require_once 'Config.php'; // Inclure le fichier de connexion PDO
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'An error occurred.'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $mail = $_POST['registerEmail'];
    $password = $_POST['registerPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Gestion de l'upload de fichier
    $pdp = ''; // Valeur par défaut
    $uploadFileDir = './uploads/';
    
    // Créer le répertoire si nécessaire
    if (!is_dir($uploadFileDir)) {
        if (!mkdir($uploadFileDir, 0755, true)) {
            $response['message'] = 'Failed to create upload directory.';
            echo json_encode($response);
            exit;
        }
    }
    
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
                $pdp = $dest_path; // Chemin du fichier
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
    
    $role = 'user'; // Définir un rôle par défaut ou récupérez-le d'un autre endroit

    // Validation
    if ($password !== $confirmPassword) {
        $response['message'] = "Passwords do not match.";
        echo json_encode($response);
        exit;
    }

    // Hacher le mot de passe avant de le stocker
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Préparer l'insertion dans la base de données
    try {
        $pdo = GetConnexion(); // Fonction pour obtenir la connexion PDO
        $stmt = $pdo->prepare("INSERT INTO users (mail, password, pdp, role) VALUES (:mail, :password, :pdp, :role)");
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':pdp', $pdp);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        
        $response['status'] = 'success';
        $response['message'] = 'User registered successfully!';
    } catch (PDOException $e) {
        $response['message'] = "Error: " . $e->getMessage();
    }
    
    echo json_encode($response);
}

?>
