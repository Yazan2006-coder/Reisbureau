<?php
session_start();

if (isset($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}

require 'db.php';

$fouten  = [];
$success = false;
$oud     = ['voornaam' => '', 'achternaam' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voornaam           = trim($_POST['voornaam']           ?? '');
    $achternaam         = trim($_POST['achternaam']         ?? '');
    $email              = trim($_POST['email']              ?? '');
    $wachtwoord         = $_POST['wachtwoord']              ?? '';
    $bevestig_wachtwoord = $_POST['bevestig_wachtwoord']   ?? '';

    $oud = ['voornaam' => $voornaam, 'achternaam' => $achternaam, 'email' => $email];

    // Validatie, foutmelding voor lege veld
    if ($voornaam === '')   $fouten[] = 'Voornaam is verplicht.';
    if ($achternaam === '') $fouten[] = 'Achternaam is verplicht.';

    if ($email === '') {
        $fouten[] = 'E-mailadres is verplicht.';
        // Validatie, foutmelding voor ongeldig e-mailadres
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fouten[] = 'Voer een geldig e-mailadres in.';
    }
    // Wachtwoord moet minstens 6 tekens zijn
    if (strlen($wachtwoord) < 6) {
        $fouten[] = 'Wachtwoord moet minimaal 6 tekens zijn.';
    } elseif ($wachtwoord !== $bevestig_wachtwoord) {
        $fouten[] = 'Wachtwoorden komen niet overeen.';
    }

    // Controleer of e-mail al bestaat
    if (empty($fouten)) {
        $stmt = $pdo->prepare('SELECT id FROM gebruikers WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $fouten[] = 'Dit e-mailadres is al in gebruik.';
        }
    }

    // Opslaan in database, veilige hash van het wachtwoord
    if (empty($fouten)) {
        $hash = password_hash($wachtwoord, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare(
            'INSERT INTO gebruikers (voornaam, achternaam, email, wachtwoord) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$voornaam, $achternaam, $email, $hash]);

        // Direct inloggen na registratie
        $nieuweId = $pdo->lastInsertId();
        $_SESSION['gebruiker_id']   = $nieuweId;
        $_SESSION['gebruiker_naam'] = $voornaam;
        $_SESSION['gebruiker_rol']  = 'klant';

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Account aanmaken Horizont Reizen</title>
</head>

<body>
    <?php require('includes/header.php'); ?>

    <section>
        <h1>Account aanmaken</h1>

        <?php if (!empty($fouten)){ ?>
            <div class="alert alert-danger">
                <?php foreach ($fouten as $fout){ ?>
                    <p><?= ($fout) ?></p>
                <?php } ?>
            </div>
        <?php } ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="voornaam">Voornaam</label>
                <input type="text" id="voornaam" name="voornaam" value="<?= ($oud['voornaam']) ?>" required>
            </div>
            <div class="form-group">
                <label for="achternaam">Achternaam</label>
                <input type="text" id="achternaam" name="achternaam" value="<?= ($oud['achternaam']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" value="<?= ($oud['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="wachtwoord">Wachtwoord</label>
                <input type="password" id="wachtwoord" name="wachtwoord" required>
            </div>
            <div class="form-group">
                <label for="bevestig_wachtwoord">Bevestig wachtwoord</label>
                <input type="password" id="bevestig_wachtwoord" name="bevestig_wachtwoord" required>
            </div>

            <button type="submit" class="auth-btn">Account aanmaken</button>
        </form>

        <p>Heb je al een account? <a href="login.php">Inloggen</a></p>
    </section>

    <?php require('includes/footer.php'); ?>
</body>
</html>
