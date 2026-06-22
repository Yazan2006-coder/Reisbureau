<?php
session_start();

// Als je al ingelogd bent hoef je geen wachtwoord te resetten
if (isset($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}

require('db.php');

$fout = '';
$melding = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email              = trim($_POST['email'] ?? '');
    $wachtwoord         = $_POST['wachtwoord'] ?? '';
    $bevestig_wachtwoord = $_POST['bevestig_wachtwoord'] ?? '';

    // Validatie
    if ($email === '') {
        $fout = 'Vul je e-mailadres in.';
    } elseif (strlen($wachtwoord) < 6) {
        $fout = 'Het nieuwe wachtwoord moet minimaal 6 tekens zijn.';
    } elseif ($wachtwoord !== $bevestig_wachtwoord) {
        $fout = 'De wachtwoorden komen niet overeen.';
    } else {
        // Controleer of het e-mailadres bestaat
        $stmt = $pdo->prepare('SELECT id FROM gebruikers WHERE email = ?');
        $stmt->execute([$email]);
        $gebruiker = $stmt->fetch();

        if ($gebruiker) {
            // Nieuw wachtwoord hashen en opslaan
            $hash = password_hash($wachtwoord, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('UPDATE gebruikers SET wachtwoord = ? WHERE id = ?');
            $stmt->execute([$hash, $gebruiker['id']]);

            $melding = 'Je wachtwoord is aangepast. Je kunt nu inloggen met je nieuwe wachtwoord.';
        } else {
            $fout = 'Er is geen account gevonden met dit e-mailadres.';
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
    <title>Wachtwoord vergeten – Horizont Reizen</title>
</head>
<body>
    <?php require('includes/header.php'); ?>

    <section>
        <h1>Wachtwoord vergeten</h1>

        <?php if ($melding !== ''){ ?>
            <div class="alert alert-success"><?= ($melding) ?></div>
            <p><a href="login.php">Naar inloggen</a></p>
        <?php }else{ ?>
            <?php if ($fout !== ''){ ?>
                <div class="alert alert-danger"><?= ($fout) ?></div>
            <?php } ?>

            <p>Vul je e-mailadres en een nieuw wachtwoord in.</p>

            <form action="wachtwoord-vergeten.php" method="POST">
                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>

                <div class="form-group">
                    <label for="wachtwoord">Nieuw wachtwoord</label>
                    <input type="password" id="wachtwoord" name="wachtwoord" required>
                </div>

                <div class="form-group">
                    <label for="bevestig_wachtwoord">Bevestig nieuw wachtwoord</label>
                    <input type="password" id="bevestig_wachtwoord" name="bevestig_wachtwoord" required>
                </div>

                <button type="submit" class="auth-btn">Wachtwoord aanpassen</button>
            </form>

            <p>Weet je het weer? <a href="login.php">Inloggen</a></p>
        <?php } ?>
    </section>

    <?php require('includes/footer.php'); ?>
</body>
</html>
