<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure les fichiers manuellement
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendConfirmationEmail($to, $reservation_id) {
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
        $mail->SMTPAuth = true; 
        $mail->Username = 'coolandbnin@gmail.com'; // Votre email Gmail
        $mail->Password = 'nnijwlklduqjcivf'; // Mot de passe ou mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Expéditeur et destinataire
        $mail->setFrom('coolandbnin@gmail.com', 'Cool Car Rentals'); // Adresse d'expédition
        $mail->addAddress($to); // Destinataire

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Reservation Approved';
        $mail->Body    = "Your reservation (ID: $reservation_id) has been approved!";
        $mail->AltBody = "Your reservation (ID: $reservation_id) has been approved!";

        // Envoi de l'email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error sending email: " . $mail->ErrorInfo);
        return false;
    }
}

?>
