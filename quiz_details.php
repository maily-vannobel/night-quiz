<?php
require_once 'classes/Quiz.php';
require_once 'classes/Questions.php';
require_once 'classes/Answers.php';

session_start();

if (!isset($_GET['id'])) {
    die("Quiz non spécifié.");
}

$quiz_id = $_GET['id'];

$quizClass = new Quiz();
$questionClass = new Questions();
$answerClass = new Answers();

try {
    $quiz = $quizClass->getQuizById($quiz_id);
    $questions = $questionClass->getQuestionsByQuiz($quiz_id);
    $numQuestions = count($questions);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

$isCreator = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $quiz['create_id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Quiz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link href="css/quiz_details.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-5">
    <div class="quizzContainer p-4 rounded">
        <div class="quiz-title-container mb-3">
            <h2 class="quiz-title"><?php echo htmlspecialchars($quiz['title']); ?></h2>
            <?php if ($isCreator): ?>
                <div class="settings-icon-container">
                    <a href="edit_quiz.php?id=<?php echo $quiz_id; ?>" class="settings-icon" title="Modifier le quiz">
                        <i class="bi bi-gear"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="quizzInfos p-3 mb-4 rounded">
            <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($quiz['category_name']); ?></p>
            <p><strong>Description :</strong> <?php echo htmlspecialchars($quiz['description']); ?></p>
            <p><strong><?php echo $numQuestions; ?> questions</strong></p>
        </div>

        <h3 class="mt-4">Questions</h3>
        <?php if (!empty($questions)): ?>
            <div id="quizCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                <div class="carousel-inner questionsContainer">
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <div class="d-flex justify-content-center">
                                <div class="question-container p-3 mb-3 rounded">
                                    <strong><?php echo htmlspecialchars($question['question']); ?></strong>
                                    <button class="btn btn-secondary btn-sm" type="button" data-toggle="collapse" data-target="#collapse<?php echo $question['id']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $question['id']; ?>">
                                        Voir les réponses
                                    </button>
                                    <div class="collapse mt-2" id="collapse<?php echo $question['id']; ?>">
                                        <?php 
                                        $answers = $answerClass->getAnswersByQuestion($question['id']);
                                        foreach ($answers as $answer): ?>
                                            <p><?php echo htmlspecialchars($answer['answer']); ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="question-number mt-2">
                                        Question <?php echo ($index + 1) . '/' . $numQuestions; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a class="carousel-control-prev" href="#quizCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Précédent</span>
                </a>
                <a class="carousel-control-next" href="#quizCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Suivant</span>
                </a>
            </div>
        <?php else: ?>
            <p>Aucune question disponible pour ce quiz.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
