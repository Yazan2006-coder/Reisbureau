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
    <div class="overons-stat-card"></div>
    <section class="overons-main">
        <div class="overons-container">
            <div class="overons-text overons-waarden">
                <p>Horizont Reizen werd in <strong>2001</strong> opgericht door drie reisspecialisten met één gedeelde overtuiging: een goede reis verdient persoonlijke aandacht. Sindsdien zijn we uitgegroeid tot een team van 24 specialisten, allen met diepe kennis van hun eigen bestemmingen.</p>
                <p>We werken niet met massabestemmingen of standaardpakketten. Elke reis wordt door een specialist samengesteld die zelf in de bestemming heeft gewoond of gereisd. Dat geeft jou de zekerheid dat de hotels, ervaringen en logistiek kloppen.</p>

                <h2>Onze waarden</h2>

                <div class="waarde-item">
                    <h4>Persoonlijk</h4>
                    <p>Elke boeking heeft een vast aanspreekpunt — voor, tijdens en na de reis.</p>
                </div>
                <div class="waarde-item">
                    <h4>Verantwoord</h4>
                    <p>We werken samen met lokale partners en kiezen waar het kan voor de trein.</p>
                </div>
                <div class="waarde-item">
                    <h4>Betrouwbaar</h4>
                    <p>ANVR-, SGR- en Calamiteitenfonds-aangesloten. Je geld is gewoon veilig.</p>
                </div>
            </div>

            <div class="overons-right">
                <div class="team-image-wrapper">
                    <img src="images/TFWCM.jpg" alt="Team Horizont Utrecht">
                    <div class="team-image-label">TEAM HORIZONT — UTRECHT</div>
                </div>

            </div>
        </div>
    </section>
    <?php require('includes/footer.php'); ?>
</body>
</html>