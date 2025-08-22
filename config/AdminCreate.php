<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "QuizLand";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = "admin@site.com";
$plainPassword = "Admin102030";
$username = "Admin";
$role = "admin";

$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashedPassword', '$role')";

if ($conn->query($sql) === TRUE) {
    echo "New admin user created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>