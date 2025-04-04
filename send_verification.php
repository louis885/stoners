<?php
// Set the content-type to send HTML emails
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log incoming POST request data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST data received: " . json_encode($_POST)); // Log the received email data
}

// Get the email from the AJAX POST request
$email = $_POST['email'] ?? null;

// Check if email is provided
if (!$email) {
    error_log("No email provided in the POST request."); // Log missing email
    echo json_encode(["status" => "error", "message" => "Email is required"]);
    exit;
}

// Generate a random 6-digit verification code
$verificationCode = rand(100000, 999999);

// Start the session and store the verification code
session_start();
$_SESSION['verification_code'] = $verificationCode;
$_SESSION['email'] = $email;

// Log session data for debugging
error_log("Session data set: " . json_encode($_SESSION));

// Configure the email settings (using PHP's mail function)
$subject = "Email Verification Code";
$message = "Your email verification code is: $verificationCode";
$headers = "From: no-reply@yourdomain.com";

// Attempt to send email using PHP's mail() function
$mailSent = mail($email, $subject, $message, $headers);

if ($mailSent) {
    error_log("Verification email sent using mail().");
    echo json_encode(["status" => "success", "message" => "Verification code sent successfully."]);
} else {
    error_log("Failed to send verification email using mail().");

    // Log the attempt to send with PHPMailer
    require 'vendor/autoload.php';

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Set the SMTP server to send email
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com';  // Your Gmail address
        $mail->Password = 'your-email-password';  // Your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'Mailer');
        $mail->addAddress($email);  // Recipient email address

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification Code';
        $mail->Body    = "Your verification code is: $verificationCode";

        // Send email
        $mail->send();
        error_log("Verification email sent using PHPMailer.");
        echo json_encode(["status" => "success", "message" => "Verification code sent successfully via PHPMailer."]);
    } catch (Exception $e) {
        // Log PHPMailer error details
        error_log("Mailer Error: {$mail->ErrorInfo}");
        echo json_encode(["status" => "error", "message" => "Mailer Error: {$mail->ErrorInfo}"]);
    }
}
?>
