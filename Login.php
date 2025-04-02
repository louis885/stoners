<?php
session_start();
include('connection.php'); // Ensure this file connects to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "<script>alert('Username and Password are required!'); window.location.href='login.html';</script>";
        exit();
    }

    // Check if the user exists
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            echo "<script>alert('Login Successful!'); window.location.href='dashboard.html';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect Password! Please try again.'); window.location.href='login.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Email does not exist! Please sign up.'); window.location.href='registration.html';</script>";
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
