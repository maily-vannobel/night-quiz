<footer class="footer mt-5 py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">&copy; 2024 Quiz Night. Tous droits réservés.</p>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="index.php" class="footer-link">Accueil</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="footer-link">Tableau de Bord</a>
                    <a href="logout.php" class="footer-link">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php" class="footer-link">Connexion</a>
                    <a href="register.php" class="footer-link">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>
