<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Inloggen — Horizont Reizen</title>
</head>

<body>
    <?php require('header.php'); ?>

    <section class="auth-page">
        <div class="auth-container">
            <h1 class="auth-title">Inloggen</h1>

            <div class="auth-card">
                <form action="login.php" method="POST">
                    <div class="auth-form-group">
                        <label for="email">E-mailadres</label>
                        <input type="email" id="email" name="email" placeholder="" required autofocus>
                    </div>

                    <div class="auth-form-group">
                        <label for="wachtwoord">Wachtwoord</label>
                        <input type="password" id="wachtwoord" name="wachtwoord" placeholder="" required>
                    </div>

                    <div class="auth-options">
                        <label class="auth-remember">
                            <input type="checkbox" name="onthoud"> Onthoud mij
                        </label>
                        <a href="#" class="auth-forgot">Wachtwoord vergeten?</a>
                    </div>

                    <button type="submit" class="auth-btn">Inloggen</button>
                </form>

                <p class="auth-switch">Nog geen account? <a href="register.php">Account aanmaken</a></p>
            </div>
        </div>
    </section>

    <?php require('footer.php'); ?>
</body>
</html>
