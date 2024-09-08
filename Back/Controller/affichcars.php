<?php
header('Content-Type: application/json');

include '../Model/Config.php'; // Include the Config.php file to use the GetConnexion function

try {
    // Get the PDO instance from the Config.php
    $pdo = GetConnexion();

    // Query to fetch car records
    $stmt = $pdo->query("SELECT id, nom, model, img1, img2, img3, ville, prix FROM voitures");

    // Fetch all rows as an associative array
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the results as JSON
    echo json_encode($cars);

} catch (PDOException $e) {
    // Handle errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
