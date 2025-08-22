<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the form values
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        die('Please fill all fields.');
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die('Passwords do not match.');
    }

    // Password strength validation (optional, can be handled here too)
    if (strlen($password) < 8) {
        die('Password must be at least 8 characters.');
    }

    // Check if the email is already registered
    // Database connection and query go here
    $conn = new mysqli("localhost", "root", "", "QuizLand"); // Replace with your actual DB credentials

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

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Redirect to login page after successful sign-up
        header('Location: signin.php');
        exit();
    } else {
        die('Error: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
