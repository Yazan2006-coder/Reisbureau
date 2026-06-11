<?php session_start(); ?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Account - Horizont Reizen</title>
</head>

<body>
    <?php require('includes/header.php'); ?>
    <section class="overons-hero">
        <div class="overons-hero-container">
            <p class="overons-hero-label">MIJN ACCOUNT</p>
            <h1>Hallo, <?= htmlspecialchars($_SESSION['gebruiker_naam']) ?></h1>
        </div>
    </section>
    <div class="account-layout">
        <aside class="account-menu">
            <a href="account.php" <?= basename($_SERVER['PHP_SELF']) === 'account.php' ? 'class="active"' : '' ?>>Mijn
                reizen</a>
            <a href="account-profiel.php" <?= basename($_SERVER['PHP_SELF']) === 'account-profiel.php' ? 'class="active"' : '' ?>>Profielgegevens</a>
            <a href="account-voorkeuren.php" <?= basename($_SERVER['PHP_SELF']) === 'account-voorkeuren.php' ? 'class="active"' : '' ?>>Voorkeuren</a>
        </aside>

        <main class="account-content">
            <p>Hier komen je reizen.</p>
        </main>
    </div>
    <?php require('includes/footer.php'); ?>
</body>

</html>