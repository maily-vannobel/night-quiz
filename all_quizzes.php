<?php
require_once 'classes/Quiz.php';
require_once 'classes/Categories.php';

$quizClass = new Quiz();
$categoryClass = new Categories();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_quiz'])) {
    $quiz_id = $_POST['quiz_id'];
    $quizClass->deleteQuiz($quiz_id);
}

try {
    $quizzes = $quizClass->getAllQuizzes();
    $categories = $categoryClass->getAllCategories();
} catch (Exception $e) {
    die("Les données n'ont pas pu être récupérées : " . $e->getMessage());
}

// Récupérer les catégories sélectionnées
$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tous les Quiz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link href="css/all_quizzes.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-5 allQuizzesContainer">
    <h1 class="pageTitle">Tous les Quiz</h1>
    <form method="GET" action="all_quizzes.php" id="category-form">
        <div class="form-group">
            <label for="categories">Filtrer par catégorie :</label>
            <div class="categories-container">
                <?php foreach ($categories as $category): ?>
                    <div class="form-check category-item">
                        <input class="form-check-input" type="checkbox" name="categories[]" value="<?php echo $category['id']; ?>" id="category<?php echo $category['id']; ?>"
                               <?php echo in_array($category['id'], $selectedCategories) ? 'checked' : ''; ?>
                               onchange="document.getElementById('category-form').submit();">
                        <label class="form-check-label" for="category<?php echo $category['id']; ?>">
                            <i class="bi <?php echo htmlspecialchars($category['icon']); ?>"></i> <?php echo htmlspecialchars($category['name']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </form>
    <hr>
    <div id="quiz-container">
        <?php
        if (!empty($quizzes)) {
            foreach ($quizzes as $quiz) {
                if (empty($selectedCategories) || in_array($quiz['category_id'], $selectedCategories)) {
                    echo "<div class='quizItem mb-3 p-3 border rounded'>"; 
                    echo "<div class='d-flex justify-content-between align-items-center'>";
                    echo "<h3><a href='quiz_details.php?id=" . htmlspecialchars($quiz["id"]) . "' class='quiz-title-link'>" . htmlspecialchars($quiz["title"]) . "</a></h3>"; 
                    echo "<form method='POST' action='all_quizzes.php' class='mb-0'>";
                    echo "<input type='hidden' name='quiz_id' value='" . htmlspecialchars($quiz["id"]) . "'>";
                    echo "<button type='submit' name='delete_quiz' class='btn btn-link p-0' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce quiz ?\");'><i class='bi bi-trash text-danger'></i></button>";
                    echo "</form>";
                    echo "</div>";
                    echo "<p>" . htmlspecialchars($quiz["description"]) . "</p>"; 
                    echo "</div>"; 
                }
            }
        } else { 
            echo "<p>Pas de quiz disponibles.</p>"; 
        }
        ?>
    </div>
</div>
</body>
</html>