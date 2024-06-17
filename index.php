<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quiz Night</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-5 container-flex">
    <h1>Bienvenue sur Quiz Night</h1>
    <p>Découvrez et participez à des quiz sur divers sujets passionnants !</p>

    <div class="row" style="background-color: #f8f9fa;">
        <div class="col-md-8">
            <?php
            require_once 'classes/Quiz.php';
            $quizClass = new Quiz();
            try {
                $recentQuizzes = $quizClass->getRecentQuizzes(5); // Récupère les 5 derniers quiz créés
            } catch (Exception $e) {
                die("Les données n'ont pas pu être récupérées : " . $e->getMessage());
            }

            if (!empty($recentQuizzes)): ?>
                <div id="recentQuizzesCarousel" class="carousel slide mt-5" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($recentQuizzes as $index => $quiz): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <div class="d-flex justify-content-center">
                                    <div class="quiz-card">
                                        <h5 class="quiz-title"><a href="quiz_details.php?id=<?php echo htmlspecialchars($quiz['id']); ?>"><?php echo htmlspecialchars($quiz['title']); ?></a></h5>
                                        <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a class="carousel-control-prev" href="#recentQuizzesCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Précédent</span>
                    </a>
                    <a class="carousel-control-next" href="#recentQuizzesCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Suivant</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-center">
            <a href="all_quizzes.php" class="custom-btn custom-btn1">Voir tous les quiz</a>
        </div>
    </div>

    <div class="row mt-5" style="background-color: #e9ecef;">
        <div class="col-md-4 d-flex align-items-center justify-content-center">
            <img src="assets/in.png" alt="Illustration" class="img-fluid">
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-center">
            <a href="create_quiz.php" class="custom-btn2">Créer un quiz</a>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-center">
            <p>Participez à la création de quiz sur divers sujets. Testez vos connaissances et amusez-vous en apprenant !</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
