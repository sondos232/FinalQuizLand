<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        die('Please fill all fields.');
    }

    if ($password !== $confirmPassword) {
        die('Passwords do not match.');
    }

    if (strlen($password) < 8) {
        die('Password must be at least 8 characters.');
    }

    $conn = new mysqli("localhost", "root", "", "QuizLand");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die('Email is already registered.');
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        header('Location: signin.php');
        exit();
    } else {
        die('Error: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
