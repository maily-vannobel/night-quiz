<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'classes/Users.php';
require_once 'classes/Quiz.php';
require_once 'classes/Questions.php';

$user_id = $_SESSION['user_id'];
$userClass = new Users();
$user = $userClass->getUserById($user_id);
$quizClass = new Quiz();
$questionClass = new Questions();

$userQuizzes = $quizClass->getQuizzesByUser($user_id);

// Statistiques utilisateur
$numQuizzes = count($userQuizzes);
$numQuestions = 0;
foreach ($userQuizzes as $quiz) {
    $numQuestions += count($questionClass->getQuestionsByQuiz($quiz['id']));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link href="css/dashboard.css?v=1.1" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-5 dashboard-container">
    <div class="row">
        <div class="col-md-4 user-stats-container">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bienvenue, <?php echo htmlspecialchars($user['username']); ?>!</h5>
                    <p class="card-text">Voici vos statistiques :</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Quiz créés : <?php echo $numQuizzes; ?></li>
                        <li class="list-group-item">Questions créées : <?php echo $numQuestions; ?></li>
                    </ul>
                    <a href="create_quiz.php" class="btn btn-primary mt-3">Créer un quiz</a>
                    <a href="logout.php" class="btn btn-danger mt-3">Se déconnecter</a>
                </div>
            </div>
        </div>
        <div class="col-md-8 user-quizzes-container">
            <h3>Vos Quizs</h3>
            <?php
            if (!empty($userQuizzes)) {
                foreach ($userQuizzes as $quiz) {
                    echo "<div class='card mb-3'>";
                    echo "<div class='card-body d-flex justify-content-between align-items-center'>";
                    echo "<div>";
                    echo "<h5 class='card-title'><a href='quiz_details.php?id=" . htmlspecialchars($quiz["id"]) . "' class='quiz-title-link'>" . htmlspecialchars($quiz["title"]) . "</a></h5>";
                    echo "<p class='card-text'>" . htmlspecialchars($quiz["description"]) . "</p>";
                    echo "</div>";
                    echo "<form method='POST' action='delete_quiz.php' onsubmit='return confirm(\"Voulez-vous vraiment supprimer ce quiz ?\");' style='display:inline;'>";
                    echo "<input type='hidden' name='quiz_id' value='" . htmlspecialchars($quiz["id"]) . "'>";
                    echo "<button type='submit' class='btn btn-link text-danger'><i class='bi bi-trash'></i></button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>Vous n'avez créé aucun quiz pour l'instant.</p>";
            }
            ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
