<?php
session_start();
$user_role = $_SESSION['user_role'] ?? '';
if ($user_role !== 'Admin' && $user_role !== 'Agent de location') {
    header("Location: ../index.html");
    exit;
}

// Include PHPMailer files
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
require_once 'Config.php';
$pdo = GetConnexion();

// Fetch the reservation data
$stmt = $pdo->prepare('
    SELECT reservation.*, voitures.nom AS car_nom, voitures.model AS car_model
    FROM reservation
    JOIN voitures ON reservation.id_car = voitures.id
');
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to send confirmation email using PHPMailer
function sendConfirmationEmail($to, $reservation_id) {
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Serveur SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'coolandbnin@gmail.com'; // Votre adresse Gmail
        $mail->Password = 'nnijwlklduqjcivf'; // Votre mot de passe ou mot de passe d'application Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Expéditeur et destinataire
        $mail->setFrom('coolandbnin@gmail.com', 'Votre Nom');
        $mail->addAddress($to);

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Reservation Approved';
        $mail->Body    = "Votre réservation (ID: $reservation_id) a été approuvée !";
        $mail->AltBody = "Votre réservation (ID: $reservation_id) a été approuvée !";

        // Envoi de l'email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur d'envoi d'email à $to : " . $mail->ErrorInfo);
        return false;
    }
}

// Function to update reservation status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'];
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'delete') {
            // Suppression de la réservation
            $deleteStmt = $pdo->prepare('DELETE FROM reservation WHERE id = ?');
            $deleteStmt->execute([$reservation_id]);
            echo json_encode(['status' => 'success']);
        } else {
            // Mise à jour du statut
            $new_status = $_POST['status'];

            $updateStmt = $pdo->prepare('UPDATE reservation SET status = ? WHERE id = ?');
            $updateStmt->execute([$new_status, $reservation_id]);

            // Envoi de l'email de confirmation si la réservation est acceptée
            if ($new_status === 'accepté') {
                $emailStmt = $pdo->prepare('SELECT mail FROM reservation WHERE id = ?');
                $emailStmt->execute([$reservation_id]);
                $emailRow = $emailStmt->fetch(PDO::FETCH_ASSOC);
                if ($emailRow) {
                    sendConfirmationEmail($emailRow['mail'], $reservation_id);
                }
            }

            echo json_encode(['status' => 'success']);
        }
        http_response_code(200);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        http_response_code(500);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cars - List</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="/car_rent/img/top-logo1.png" type="image/png">
    <link rel="stylesheet" href="reservation_back.css"></head>
    <link rel="stylesheet" href="voitures.css"></head>

    <link href="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons/ionicons.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<body>
<div class="container">
        <div class="navigation">

            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="logo-apple"></ion-icon>
                        </span>
                        <span class="title">Brand Name</span>
                    </a>
                </li>
                <li>
                    <a href="index.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <?php if ($user_role === 'Admin') { ?>
                    <li>
                        <a href="customers.php">
                            <span class="icon">
                                <ion-icon name="people-outline"></ion-icon>
                            </span>
                            <span class="title">Customers</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($user_role === 'Agent de location') { ?>
                    <li>
                        <a href="../back/voitures.php">
                            <span class="icon">
                                <ion-icon name="car-sport-outline"></ion-icon>
                            </span>
                            <span class="title">Cars</span>
                        </a>
                    </li>
                    <li>
                        <a href="reservation_back.php">
                            <span class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </span>
                            <span class="title">Reservations</span>
                        </a>
                    </li>
                <?php } ?>
                <li>
                    <a href="../index.html">
                        <span class="icon">
                            <ion-icon name="business-outline"></ion-icon>
                        </span>
                        <span class="title">Go to Front</span>
                    </a>
                </li>
                <li>
                    <a href="#" id="signOutLink">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
        <!-- Main Content -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="search">
    <label>
        <input type="text" id="search-input" placeholder="Search...">
        <select id="search-type" class="custom-select">
            <option value="nom">Search by Name</option>
            <option value="model">Search by Model</option>
            <option value="ville">Search by City</option>
        </select>
    </label>
</div>

                <div class="user">
                    <img src="assets/imgs/customer01.jpg" alt="">
                </div>
            </div>

    <div class="main">
        <div class="topbar">
            <!-- Topbar code here -->
        </div>
        <div class="details">
            <div class="reservationTable">
                <div class="cardHeader">
                    <h2>Reservation List</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Car</th>
                            <th>Date Current</th>
                            <th>Total Price (DT)</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reservation-table-body">
                        <?php foreach ($reservations as $reservation) { ?>
                            <tr>
                                <td><?= $reservation['id'] ?></td>
                                <td><?= $reservation['id_user'] ?></td>
                                <td><?= $reservation['car_nom'] . ' ' . $reservation['car_model'] ?></td>
                                <td><?= $reservation['date_current'] ?></td>
                                <td><?= $reservation['prixtotal'] ?></td>
                                <td><?= $reservation['date_debut'] ?></td>
                                <td><?= $reservation['date_fin'] ?></td>
                                <td><?= $reservation['telephone'] ?></td>
                                <td><?= $reservation['mail'] ?></td>
                                <td><span class="<?= strtolower($reservation['status']) ?>"><?= $reservation['status'] ?></span></td>
                                <td>
                                    <?php if ($reservation['status'] === 'en cours') { ?>
                                        <form method="POST" class="status-form" data-action="approve">
                                            <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                            <input type="hidden" name="status" value="accepté">
                                            <button type="submit" class="approve-btn" style="background-color: green; color: white">Approve</button>
                                        </form>
                                        <form method="POST" class="status-form" data-action="reject">
                                            <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                            <input type="hidden" name="status" value="refusé">
                                            <button type="submit" class="reject-btn" style="background-color: red; color: white;">Reject</button>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.status-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const action = form.dataset.action;
        const formData = new FormData(form);

        formData.append('action', action === 'reject' ? 'delete' : '');

        fetch('', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Success',
                    text: action === 'approve' ? 'Reservation approved!' : 'Reservation rejected!',
                    icon: 'success'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Something went wrong!',
                    icon: 'error'
                });
            }
        }).catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Unable to process the request.',
                icon: 'error'
            });
        });
    });
});
</script>
</body>
</html>
