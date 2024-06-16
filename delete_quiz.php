<?php
require_once 'classes/Quiz.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quiz_id = $_POST['quiz_id'];

    $quizClass = new Quiz();

    try {
        $quizClass->deleteQuiz($quiz_id, $user_id);
        header('Location: all_quizzes.php');
        exit();
    } catch (Exception $e) {
        die("Erreur lors de la suppression du quiz : " . $e->getMessage());
    }
} else {
    header('Location: all_quizzes.php');
    exit();
}
?>