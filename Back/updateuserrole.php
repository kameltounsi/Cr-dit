<?php
require_once '../Config.php'; // Include your database connection file

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';

    // Validate input
    if (empty($id) || empty($role) || !in_array($role, ['User', 'Admin', 'Agent de location'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    try {
        // Establish database connection
        $pdo = GetConnexion();

        // Prepare and execute the SQL query to update the user's role
        $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE id = :id");
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Role updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update role']);
        }
    } catch (PDOException $e) {
        // Handle PDO exception
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Handle invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
