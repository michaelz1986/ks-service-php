<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data.php';
require_once ROOT . '/vendor/phpmailer/PHPMailer.php';
require_once ROOT . '/vendor/phpmailer/SMTP.php';
require_once ROOT . '/vendor/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_notification(string $subject, string $body): bool {
    $s = get_settings();
    if (empty($s['smtpEnabled']) || !$s['smtpHost']) return false;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $s['smtpHost'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $s['smtpUser'];
        $mail->Password   = $s['smtpPass'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)$s['smtpPort'];
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($s['smtpFrom'] ?: $s['smtpUser'], $s['smtpFromName']);
        $mail->addAddress($s['smtpTo'] ?: $s['email']);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->isHTML(false);
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
