<?php
include '../../config/db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/signin.php');
    exit();
}

$quiz_id = isset($_POST['quiz_id']) ? (int) $_POST['quiz_id'] : 0;
$difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : 'easy';
$num_questions = isset($_POST['num_questions']) ? (int) $_POST['num_questions'] : 10;

$quizQuery = "SELECT * FROM quizzes WHERE id = $quiz_id";
$quizResult = $conn->query($quizQuery);
$quiz = $quizResult->fetch_assoc();

if (!$quiz) {
    echo "Quiz not found.";
    exit();
}

$user_id = $_SESSION['user']['id'];
$score = 0;
$total_questions = $num_questions;

$insertAttemptQuery = "INSERT INTO quiz_attempts (user_id, quiz_id, score, total_questions) 
                        VALUES ($user_id, $quiz_id, $score, $total_questions)";
$conn->query($insertAttemptQuery);
$quiz_attempt_id = $conn->insert_id;

var_dump($_POST);

for ($i = 1; $i <= $num_questions; $i++) {
    $question_id = isset($_POST["question_$i"]) ? (int) $_POST["question_$i"] : 0;
    $selected_answer = isset($_POST["answer_$i"]) ? (int) $_POST["answer_$i"] : 0;

    if ($selected_answer > 0) {
        $answerQuery = "SELECT is_correct FROM answers WHERE id = $selected_answer";
        $answerResult = $conn->query($answerQuery);
        $answer = $answerResult->fetch_assoc();

        if ($answer && $answer['is_correct'] == 1) {
            $score++;
        }

        $insertStudentAnswerQuery = "INSERT INTO student_answers (quiz_attempt_id, question_id, selected_answer) 
                                      VALUES ($quiz_attempt_id, $question_id, $selected_answer)";
        if (!$conn->query($insertStudentAnswerQuery)) {
            die("Error: " . $conn->error);
        }
    }
}



$updateScoreQuery = "UPDATE quiz_attempts SET score = $score WHERE id = $quiz_attempt_id";
$conn->query($updateScoreQuery);

header(header: 'Location: quiz-results.php?quiz_attempt_id=' . $quiz_attempt_id);
exit();
?>