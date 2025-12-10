<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid Request");
}

$fullname  = trim($_POST['fullname']);
$email     = trim($_POST['email']);
$mobile    = trim($_POST['mobile']);
$address   = trim($_POST['address']);
$password  = $_POST['password'];
$cpassword = $_POST['cpassword'];

// Check password match
if ($password !== $cpassword) {
    echo "<script>alert('Passwords do not match!'); window.location='register.html';</script>";
    exit;
}

// Check if email exists
$check = "SELECT * FROM registration WHERE email='$email'";
$result = mysqli_query($conn, $check);

if (mysqli_num_rows($result) > 0) {
    echo "<script>alert('Email already exists!'); window.location='register.html';</script>";
    exit;
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$sql = "INSERT INTO registration (fullname, email, mobile, address, password)
        VALUES ('$fullname', '$email', '$mobile', '$address', '$hashed_password')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Registration Successful! Please Login.'); window.location='login.html';</script>";
} else {
    echo "<script>alert('Error during registration.'); window.location='register.html';</script>";
}
?>
