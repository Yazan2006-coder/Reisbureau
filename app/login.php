<?php
session_start();

if (isset($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}

require 'db.php';

$fout = '';
$oud_email = '';
// trim() haalt spaties weg. ?? '' = als het veld niet bestaat, gebruik een lege string. 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = trim($_POST['email']      ?? '');
    $wachtwoord = $_POST['wachtwoord']      ?? '';
    $onthoud    = isset($_POST['onthoud']);
    $oud_email  = $email;
// Is een van beide leeg? Foutmelding.
    if ($email === '' || $wachtwoord === '') {
        $fout = 'Vul je e-mailadres en wachtwoord in.';
        // Zoekt met een prepared statement de gebruiker op
    } else {
        $stmt = $pdo->prepare('SELECT * FROM gebruikers WHERE email = ?');
        $stmt->execute([$email]);
        $gebruiker = $stmt->fetch();
        // vergelijkt het ingetypte wachtwoord met de opgeslagen hash.
        if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
            // Sessie instellen
            $_SESSION['gebruiker_id']   = $gebruiker['id'];
            $_SESSION['gebruiker_naam'] = $gebruiker['voornaam'];
            $_SESSION['gebruiker_rol']  = $gebruiker['rol'];
        // de sessie-cookie blijft 30 dagen geldig als "Onthoud mij" is aangevinkt.
            if ($onthoud) {
                session_set_cookie_params(60 * 60 * 24 * 30);
                session_regenerate_id(true);
            }

            header('Location: index.php');
            exit;
        } else {
            $fout = 'E-mailadres of wachtwoord is onjuist.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Inloggen Horizont Reizen</title>
</head>

<body>
    <?php require('includes/header.php'); ?>

    <section class="auth-page">
        <div class="auth-container">
            <h1 class="auth-title">Inloggen</h1>

            <div class="auth-card">
                <?php if ($fout !== ''){ ?>
                    <div class="auth-melding auth-melding--fout">
                        <p><?= ($fout) ?></p>
                    </div>
                <?php } ?>

                <form action="login.php" method="POST">
                    <div class="auth-form-group">
                        <label for="email">E-mailadres</label>
                        <input type="email" id="email" name="email"
                               value="<?= ($oud_email) ?>" required autofocus>
                    </div>

                    <div class="auth-form-group">
                        <label for="wachtwoord">Wachtwoord</label>
                        <input type="password" id="wachtwoord" name="wachtwoord" required>
                    </div>

                    <div class="auth-options">
                        <label class="auth-remember">
                            <input type="checkbox" name="onthoud"> Onthoud mij
                        </label>
                        <a href="wachtwoord-vergeten.php" class="auth-forgot">Wachtwoord vergeten?</a>
                    </div>

                    <button type="submit" class="auth-btn">Inloggen</button>
                </form>

                <p class="auth-switch">Nog geen account? <a href="register.php">Account aanmaken</a></p>

                <div class="demo-accounts">
                    <p><strong>Demo-accounts:</strong></p>
                    <p>Admin: testadmin@test.nl / test1234</p>
                </div>
            </div>
        </div>
    </section>

    <?php require('includes/footer.php'); ?>
</body>
</html>
