<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'connected',
        'user' => [
            'pdp' => $_SESSION['user_pdp']
        ]
    ]);
} else {
    echo json_encode([
        'status' => 'not_connected'
    ]);
}
?>
