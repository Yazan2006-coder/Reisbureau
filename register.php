<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Account aanmaken - Horizont Reizen</title>
</head>

<body>
    <?php require('includes/header.php'); ?>

    <section class="auth-page">
        <div class="auth-container">
            <h1 class="auth-title">Account aanmaken</h1>

            <div class="auth-card">
                <form action="register.php" method="POST">
                    <div class="auth-form-row">
                        <div class="auth-form-group">
                            <label for="voornaam">Voornaam</label>
                            <input type="text" id="voornaam" name="voornaam" required>
                        </div>
                        <div class="auth-form-group">
                            <label for="achternaam">Achternaam</label>
                            <input type="text" id="achternaam" name="achternaam" required>
                        </div>
                    </div>

                    <div class="auth-form-group">
                        <label for="email">E-mailadres</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="auth-form-group">
                        <label for="wachtwoord">Wachtwoord</label>
                        <input type="password" id="wachtwoord" name="wachtwoord" required>
                    </div>

                    <div class="auth-form-group">
                        <label for="bevestig_wachtwoord">Bevestig wachtwoord</label>
                        <input type="password" id="bevestig_wachtwoord" name="bevestig_wachtwoord" required>
                    </div>

                    <button type="submit" class="auth-btn">Account aanmaken</button>
                </form>

                <p class="auth-switch">Heb je al een account? <a href="login.php">Inloggen</a></p>
            </div>
        </div>
    </section>

    <?php require('includes/footer.php'); ?>
</body>
</html>
