<?php
session_start();
include '../Model/Config.php'; // Fichier qui contient la fonction GetConnexion()

header('Content-Type: application/json'); // Assurez-vous que le contenu est renvoyé en JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifiez si l'utilisateur est déjà connecté
    if (isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'success', 'user' => ['pdp' => $_SESSION['user_pdp'], 'role' => $_SESSION['user_role']]]);
        exit();
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifiez que les champs email et password existent dans les données reçues
    if (isset($data['email']) && isset($data['password'])) {
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $password = $data['password'];

        // Vérifiez que l'email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
            exit();
        }

        try {
            // Obtenir la connexion à la base de données
            $conn = GetConnexion();

            // Requête pour récupérer l'utilisateur avec cet email
            $stmt = $conn->prepare("SELECT * FROM users WHERE mail = ?");
            $stmt->bindParam(1, $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Fermez la requête
            $stmt->closeCursor();

            if ($user && password_verify($password, $user['password'])) {
                // Mot de passe correct, démarrez une session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['mail'];
                $_SESSION['user_pdp'] = $user['pdp']; // chemin de l'image de profil
                $_SESSION['user_role'] = $user['role']; // Rôle de l'utilisateur

                // Réponse JSON pour une connexion réussie
                echo json_encode(['status' => 'success', 'user' => ['pdp' => $user['pdp'], 'role' => $user['role']]]);
            } else {
                // Erreur d'authentification
                echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
            }
        } catch (PDOException $e) {
            // En cas d'erreur avec la requête ou la connexion à la base de données
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        } finally {
            // Fermez la connexion
            $conn = null;
        }
    } else {
        // Données manquantes
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
    }
} else {
    // Mauvaise méthode HTTP
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
