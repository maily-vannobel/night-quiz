<?php
require_once 'classes/Questions.php';
require_once 'classes/Answers.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['quiz_id'])) {
    header('Location: dashboard.php');
    exit();
}

$quiz_id = $_GET['quiz_id'];

$questionClass = new Questions();
$answerClass = new Answers();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answers = $_POST['answers'];
    try {
        foreach ($answers as $question_id => $answer_text) {
            if ($answerClass->getAnswersByQuestion($question_id)) {
                $answerClass->updateAnswer($question_id, $answer_text);
            } else {
                $answerClass->addAnswer($question_id, $answer_text);
            }
        }

        header('Location: quiz_details.php?id=' . $quiz_id);
        exit();
    } catch (Exception $e) {
        $error = "Erreur lors de la modification des réponses : " . $e->getMessage();
    }
}

$questions = $questionClass->getQuestionsByQuiz($quiz_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier les réponses</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/edit.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

<div class="editAnswersContainer container mt-5">
    <h2 class="editAnswersTitle">Modifier les Réponses</h2>
    <?php if (isset($error)) { echo '<p class="text-danger">' . $error . '</p>'; } ?>
    <form method="post">
        <?php foreach ($questions as $question): ?>
            <div class="form-group">
                <label for="answer_<?php echo $question['id']; ?>"><?php echo htmlspecialchars($question['question']); ?></label>
                <?php 
                $existingAnswer = $answerClass->getAnswersByQuestion($question['id']);
                $answerText = !empty($existingAnswer) ? $existingAnswer[0]['answer'] : '';
                ?>
                <input type="text" class="form-control" id="answer_<?php echo $question['id']; ?>" name="answers[<?php echo $question['id']; ?>]" value="<?php echo htmlspecialchars($answerText); ?>" required>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary mt-3">Modifier les réponses</button>
    </form>
</div>
</body>
</html>
