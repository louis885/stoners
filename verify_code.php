<?php
session_start();

// Get the email and verification code from the POST request
$email = $_POST['email'];
$enteredCode = $_POST['code'];

// Log the entered code and email for debugging
error_log("Verification attempt for email: $email, entered code: $enteredCode");

// Check if the email and code match the values stored in the session
if ($_SESSION['email'] === $email && $_SESSION['verification_code'] == $enteredCode) {
    // Code is correct
    error_log("Verification successful for email: $email");
    echo json_encode(["status" => "success", "message" => "Verification successful."]);
} else {
    // Code is incorrect
    error_log("Verification failed for email: $email. Incorrect code entered.");
    echo json_encode(["status" => "error", "message" => "Invalid verification code."]);
}
?>
