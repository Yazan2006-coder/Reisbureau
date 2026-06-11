<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $voornaam = trim($_POST['voornaam']);
    $achternaam = trim($_POST['achternaam']);
    $email = trim($_POST['email']);
    $wachtwoord = $_POST['wachtwoord'];
    $bevestig_wachtwoord = $_POST['bevestig_wachtwoord'];

    $fouten =[];

    if($voornaam === ''){
        $fouten[] = 'Voornaam is verplicht.';
    }
    if ($achternaam ===''){
        $fouten[] = 'Achternaam is verplicht.';
    }
    if ($email ===''){
        $fouten[] = 'E-mailadres is verplicht.';
    }
    if ($wachtwoord !== $bevestig_wachtwoord){
        $fouten[] = 'Wachtwoorden komen niet overeen.';
    }
        if (strlen($wachtwoord) < 4 ){
        $fouten[] = 'Wachtwoord moet minimaal 4 tekens zijn.';
    }
    if (empty($fouten)){
        $stmt = $pdo->prepare('SELECT id FROM gebruikers WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()){
            $fouten[] = 'Dit e-mailadres is al in gebruik.';
        } else {
            $hash = password_hash($wachtwoord, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO gebruikers (voornaam, achternaam, email, wachtwoord) VALUES (?,?,?,?)');
            $stmt->execute([$voornaam, $achternaam, $email, $hash]);
            header('Location: login.php?registered=1');
            exit;
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
    <title>Account aanmaken - Horizont Reizen</title>
</head>

<body>
    <?php require('includes/header.php'); ?>

    <section class="auth-page">
        <div class="auth-container">
            <h1 class="auth-title">Account aanmaken</h1>

            <div class="auth-card">
                <?php foreach ($fouten ?? [] as $fout) echo "<p style='color:red'>$fout</p>"; ?>

                <form action="register.php" method="POST">
                    <div class="auth-form-row">
                        <div class="auth-form-group">
                            <label for="voornaam">Voornaam</label>
                            <input type="text" id="voornaam" name="voornaam" value="<?= $voornaam ?? '' ?>" required>
                        </div>
                        <div class="auth-form-group">
                            <label for="achternaam">Achternaam</label>
                            <input type="text" id="achternaam" name="achternaam" value="<?= $achternaam ?? '' ?>" required>
                        </div>
                    </div>

                    <div class="auth-form-group">
                        <label for="email">E-mailadres</label>
                        <input type="email" id="email" name="email" value="<?= $email ?? '' ?>" required>
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
