<?php
function GetConnexion() {
    $host = 'localhost'; // Changez cela si nécessaire
    $db = 'crédit'; // Changez cela avec le nom de votre base de données
    $user = 'root'; // Changez cela avec votre nom d'utilisateur
    $pass = ''; // Changez cela avec votre mot de passe

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}
?>