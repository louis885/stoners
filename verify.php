<?php
session_start();
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $otp = trim($_POST['otp']);

    if (empty($email) || empty($otp)) {
        die("Email and OTP are required.");
    }

    // Check if session email exists
    if ($_SESSION['email'] !== $email) {
        die("Email mismatch.");
    }

    // Check if the entered OTP matches the OTP stored in session
    if ($_SESSION['otp'] == $otp) {
        // OTP is correct, mark the user as verified by removing OTP or updating status
        $update = $conn->prepare("UPDATE users SET otp = NULL WHERE email = ?");
        $update->bind_param("s", $email);
        if ($update->execute()) {
            // Successfully verified
            // You can store the verification status in the session, if needed
            $_SESSION['verified'] = true; // Store session variable to mark the user as verified
            echo "<script>alert('Verification Successful!'); window.location.href='dashboard.html';</script>"; // Redirect to PHP dashboard
            exit();
        } else {
            echo "<script>alert('Failed to update OTP status.'); window.location.href='verify.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Incorrect OTP! Please try again.'); window.location.href='verify.html';</script>";
        exit();
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
