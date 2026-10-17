<?php
require '../vendor/autoload.php'; 

use SendGrid\Mail\Mail;

function sendResetEmail($to, $link) {
    $email = new Mail();
    $email->setFrom("souxfiane201999@gmail.com", "AGHARAS");
    $email->setSubject("Réinitialisation de votre mot de passe");
    $email->addTo($to);
    $email->addContent("text/plain", "   Cliquez sur ce lien pour réinitialiser votre mot de passe: $link");
    $email->addContent("text/html", "<strong>Cliquez ici pour réinitialiser votre mot de passe: <a href='$link'>Réinitialiser</a></strong>");
    
    $sendgrid = new \SendGrid("SG.YOUR_API_KEY_HERE");
    try {
        $response = $sendgrid->send($email);
        return $response->statusCode() == 202;
    } catch (Exception $e) {
        error_log('SendGrid Error: ' . $e->getMessage());
        return false;
    }
}
?>
