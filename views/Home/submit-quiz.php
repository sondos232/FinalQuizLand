<?php
include '../../config/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/signin.php');
    exit();
}

// Get the quiz data from the POST request
$quiz_id = isset($_POST['quiz_id']) ? (int) $_POST['quiz_id'] : 0;
$difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : 'easy';
$num_questions = isset($_POST['num_questions']) ? (int) $_POST['num_questions'] : 10;

// Check if the quiz exists
$quizQuery = "SELECT * FROM quizzes WHERE id = $quiz_id";
$quizResult = $conn->query($quizQuery);
$quiz = $quizResult->fetch_assoc();

if (!$quiz) {
    echo "Quiz not found.";
    exit();
}

// Start a new quiz attempt
$user_id = $_SESSION['user']['id']; // Assuming the user ID is stored in the session
$score = 0;
$total_questions = $num_questions;

// Insert the quiz attempt into the quiz_attempts table
$insertAttemptQuery = "INSERT INTO quiz_attempts (user_id, quiz_id, score, total_questions) 
                        VALUES ($user_id, $quiz_id, $score, $total_questions)";
$conn->query($insertAttemptQuery);
$quiz_attempt_id = $conn->insert_id; // Get the ID of the inserted quiz attempt

// Check the posted data
var_dump($_POST);  // Add this line to check the data received from the form

// Loop through each question and store the answers
for ($i = 1; $i <= $num_questions; $i++) {
    // Retrieve the correct question_id from the form data
    $question_id = isset($_POST["question_$i"]) ? (int) $_POST["question_$i"] : 0;
    $selected_answer = isset($_POST["answer_$i"]) ? (int) $_POST["answer_$i"] : 0;

    if ($selected_answer > 0) {
        // Check if the answer is correct
        $answerQuery = "SELECT is_correct FROM answers WHERE id = $selected_answer";
        $answerResult = $conn->query($answerQuery);
        $answer = $answerResult->fetch_assoc();

        if ($answer && $answer['is_correct'] == 1) {
            $score++;
        }

        // Save the student answer
        $insertStudentAnswerQuery = "INSERT INTO student_answers (quiz_attempt_id, question_id, selected_answer) 
                                      VALUES ($quiz_attempt_id, $question_id, $selected_answer)";
        if (!$conn->query($insertStudentAnswerQuery)) {
            die("Error: " . $conn->error);
        }
    }
}



// Update the score in the quiz_attempts table
$updateScoreQuery = "UPDATE quiz_attempts SET score = $score WHERE id = $quiz_attempt_id";
$conn->query($updateScoreQuery);

// Redirect to the results page
header(header: 'Location: quiz-results.php?quiz_attempt_id=' . $quiz_attempt_id);
exit();
?>