<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill all fields.';
        header('Location: signin.php');
        exit();
    }

    $conn = new mysqli("localhost", "root", "", "QuizLand");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, password,image,role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'image' => $user['image'],
                'role' => $user['role']
            ];

            if ($_SESSION['user']['role'] == 'admin') {
                // Redirect to the admin dashboard if the role is admin
                header('Location: ../admin');
                exit();
            } else {
                header('Location: ../home');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Incorrect password.';
            header('Location: signin.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'No user found with this email.';
        header('Location: signin.php');
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>