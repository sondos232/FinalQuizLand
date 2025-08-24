<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

$quizId = $_GET['id']; // Get the quiz ID from the URL

// Delete quiz from the database
$query = "DELETE FROM quizzes WHERE id = '$quizId'";
if ($conn->query($query)) {
    header('Location: index.php'); // Redirect back to the quizzes list
    exit();
} else {
    echo "حدث خطأ أثناء حذف الاختبار!";
}
?>