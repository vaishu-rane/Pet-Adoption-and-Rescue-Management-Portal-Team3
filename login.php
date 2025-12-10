<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$email = trim($_POST['email']);
$password = $_POST['password'];

$sql = "SELECT * FROM registration WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {

    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row['password'])) {

        $_SESSION['user'] = $row['fullname'];
        $_SESSION['email'] = $row['email'];

        header("Location: index.html");
        exit();
    } else {
        echo "<script>alert('Incorrect Password'); window.location='login.html';</script>";
    }
} else {
    echo "<script>alert('Email Not Found'); window.location='login.html';</script>";
}
?>
