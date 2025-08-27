<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.php');
    exit();
}

$errors = [];
$old = [];

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$old = ['username' => $username, 'email' => $email];

if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
    $errors[] = 'Please fill all fields.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
}
if ($password !== $confirmPassword) {
    $errors[] = 'Passwords do not match.';
}
if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}

if (!$errors) {
    $stmt = $conn->prepare("SELECT email, username FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $res = $stmt->get_result();

    $emailExists = false;
    $usernameExists = false;

    while ($row = $res->fetch_assoc()) {
        if (strcasecmp($row['email'] ?? '', $email) === 0)
            $emailExists = true;
        if (strcasecmp($row['username'] ?? '', $username) === 0)
            $usernameExists = true;
    }
    $stmt->close();

    if ($emailExists)
        $errors[] = 'Email is already registered.';
    if ($usernameExists)
        $errors[] = 'Username is already taken.';
}

if ($errors) {
    $_SESSION['signup_errors'] = $errors;
    $_SESSION['signup_old'] = $old;
    header('Location: signup.php');
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    $_SESSION['signup_success'] = 'Account created successfully. You can sign in now.';
    unset($_SESSION['signup_old']);
    header('Location: signup.php');
    exit();
} else {
    $_SESSION['signup_errors'] = ['Unexpected error: ' . $stmt->error];
    $_SESSION['signup_old'] = $old;
    header('Location: signup.php');
    exit();
}
