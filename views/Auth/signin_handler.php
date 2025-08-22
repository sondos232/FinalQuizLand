<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the form values
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate the inputs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill all fields.';
        header('Location: signin.php'); // Redirect back to the sign-in form
        exit();
    }

    // Connect to the database (Replace with your database credentials)
    $conn = new mysqli("localhost", "root", "", "QuizLand");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query to find the user by email
    $stmt = $conn->prepare("SELECT id, username, password,image,role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'image' => $user['image'],
                'role' => $user['role']
            ];

            // Check the user's role and redirect accordingly
            if ($_SESSION['user']['role'] == 'admin') {
                // Redirect to the admin dashboard if the role is admin
                header('Location: ../admin');
                exit();
            } else {
                // Redirect to the user dashboard if the role is not admin
                header('Location: ../home');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Incorrect password.';
            header('Location: signin.php'); // Redirect back to the sign-in form
            exit();
        }
    } else {
        $_SESSION['error'] = 'No user found with this email.';
        header('Location: signin.php'); // Redirect back to the sign-in form
        exit();
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>