<?php
// Récupérer les données POST
$data = json_decode(file_get_contents('php://input'), true);
$model = isset($data['model']) ? $data['model'] : '';
$ville = isset($data['ville']) ? $data['ville'] : '';
$nom = isset($data['nom']) ? $data['nom'] : '';

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'car_database');
if ($conn->connect_error) {
    die('Erreur de connexion: ' . $conn->connect_error);
}

// Construire la requête SQL avec les filtres
$sql = "SELECT * FROM cars WHERE 1=1";

if (!empty($model)) {
    $sql .= " AND model LIKE '%" . $conn->real_escape_string($model) . "%'";
}

if (!empty($ville)) {
    $sql .= " AND ville LIKE '%" . $conn->real_escape_string($ville) . "%'";
}

if (!empty($nom)) {
    $sql .= " AND nom LIKE '%" . $conn->real_escape_string($nom) . "%'";
}

$result = $conn->query($sql);

$cars = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}

echo json_encode($cars);

$conn->close();
?>
