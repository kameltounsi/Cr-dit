<?php
// Inclure le fichier de configuration pour obtenir la connexion PDO
require_once 'Config.php';

// Obtenir la connexion à la base de données via la fonction GetConnexion()
$pdo = GetConnexion();

// Recevoir les données JSON envoyées via fetch
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données nécessaires sont présentes
if (isset($data['id_user'], $data['id_car'], $data['date_current'], $data['date_debut'], $data['date_fin'], $data['prixtotal'], $data['telephone'], $data['mail'])) {
    
    // Extraire les valeurs du tableau $data
    $id_user = $data['id_user'];
    $id_car = $data['id_car'];
    $date_current = $data['date_current'];
    $date_debut = $data['date_debut'];
    $date_fin = $data['date_fin'];
    $prixtotal = $data['prixtotal'];
    $telephone = $data['telephone'];
    $mail = $data['mail'];

    // Préparer la requête d'insertion
    $sql = "INSERT INTO reservation (id_user, id_car, date_current, date_debut, date_fin, prixtotal, telephone, mail)
            VALUES (:id_user, :id_car, :date_current, :date_debut, :date_fin, :prixtotal, :telephone, :mail)";
    $stmt = $pdo->prepare($sql);

    // Exécuter la requête
    $result = $stmt->execute([
        ':id_user' => $id_user,
        ':id_car' => $id_car,
        ':date_current' => $date_current,
        ':date_debut' => $date_debut,
        ':date_fin' => $date_fin,
        ':prixtotal' => $prixtotal,
        ':telephone' => $telephone,
        ':mail' => $mail
    ]);

    // Répondre à la requête avec un succès ou une erreur
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

} else {
    // Si les données requises ne sont pas présentes
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
}
?>
