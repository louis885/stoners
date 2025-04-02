<?php
session_start();
include('connection.php'); // Ensure this file contains the correct database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $dob = trim($_POST['dob']);
    $location = trim($_POST['location']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $gender = trim($_POST['gender']);

    // Validation
    if (empty($firstname) || empty($lastname) || empty($dob) || empty($location) || empty($email) || empty($password) || empty($phoneNumber) || empty($gender)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $email = mysqli_real_escape_string($conn, $email);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Email already registered.");
    }
    $stmt->close();

    // Generate 4-digit OTP
    $otp = rand(1000, 9999);

    // Insert user data into database
    $stmt = $conn->prepare("INSERT INTO users (firstname, middlename, lastname, dob, location, email, password, phoneNumber, gender, otp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssi", $firstname, $middlename, $lastname, $dob, $location, $email, $hashed_password, $phoneNumber, $gender, $otp);

    if ($stmt->execute()) {
        $_SESSION['email'] = $email;
        $_SESSION['otp'] = $otp; // Store OTP in session
        echo "<script>alert('Registration successful! Your OTP is: $otp'); window.location.href='verify.html';</script>";
        exit();
    } else {
        die("Could not register: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
?>
