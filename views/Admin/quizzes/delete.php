<?php
include '../../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../auth/signin.php');
    exit();
}

$quizId = $_GET['id'];

$query = "DELETE FROM quizzes WHERE id = '$quizId'";
if ($conn->query($query)) {
    header('Location: index.php');
    exit();
} else {
    echo "حدث خطأ أثناء حذف الاختبار!";
}
?>