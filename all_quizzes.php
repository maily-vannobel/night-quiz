<?php
require_once 'classes/Quiz.php';
require_once 'classes/Categories.php';

$quizClass = new Quiz();
$categoryClass = new Categories();

try {
    $quizzes = $quizClass->getAllQuizzes();
    $categories = $categoryClass->getAllCategories();
} catch (Exception $e) {
    die("Les données n'ont pas pu être récupérées : " . $e->getMessage());
}

$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tous les Quiz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-5">
    <h1>Tous les Quiz</h1>
    <form method="GET" action="all_quizzes.php" id="category-form">
        <div class="form-group">
            <label for="categories">Filtrer par catégorie :</label><br>
            <?php foreach ($categories as $category): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="categories[]" value="<?php echo $category['id']; ?>" id="category<?php echo $category['id']; ?>"
                           <?php echo in_array($category['id'], $selectedCategories) ? 'checked' : ''; ?>
                           onchange="document.getElementById('category-form').submit();">
                    <label class="form-check-label" for="category<?php echo $category['id']; ?>">
                        <i class="bi <?php echo htmlspecialchars($category['icon']); ?>"></i> <?php echo htmlspecialchars($category['name']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </form>
    <hr>
    <?php
    if (!empty($quizzes)) { 
        foreach ($quizzes as $quiz) { 
            if (empty($selectedCategories) || in_array($quiz['category_id'], $selectedCategories)) {
                echo "<div class='quiz-item mb-3 p-3 border rounded'>"; 
                echo "<h3><a href='quiz_details.php?id=" . htmlspecialchars($quiz["id"]) . "' class='quiz-title-link'>" . htmlspecialchars($quiz["title"]) . "</a></h3>"; 
                echo "<p>" . htmlspecialchars($quiz["description"]) . "</p>"; 
                echo "</div>"; 
            }
        }
    } else { 
        echo "<p>Pas de quiz disponibles.</p>"; 
    }
    ?>
</div>
</body>
</html>
