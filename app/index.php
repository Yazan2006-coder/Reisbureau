<?php session_start(); ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Reisbureau</title>
</head>

<body>
    <?php require('includes/header.php'); ?>

    <section>
        <h1>Horizont Reizen</h1>
        <p>Premium stedentrips door Europa.</p>

        <form action="reizen.php" method="GET">
            <div class="form-group">
                <label for="destination">Bestemming</label>
                <input type="text" id="destination" name="bestemming" placeholder="Waar wil je heen?">
            </div>
            <button type="submit" class="search-btn">Zoeken</button>
        </form>
    </section>

    <section>
        <h2>Aanbevolen reizen</h2>

        <div class="trip-card">
            <h3>Parijs</h3>
            <p class="trip-price">vanaf <strong>€ 689</strong> p.p.</p>
            <a href="reizen.php" class="btn btn-primary trip-btn">Bekijk &amp; boek</a>
        </div>

        <div class="trip-card">
            <h3>Rome</h3>
            <p class="trip-price">vanaf <strong>€ 849</strong> p.p.</p>
            <a href="reizen.php" class="btn btn-primary trip-btn">Bekijk &amp; boek</a>
        </div>

        <div class="trip-card">
            <h3>Barcelona</h3>
            <p class="trip-price">vanaf <strong>€ 599</strong> p.p.</p>
            <a href="reizen.php" class="btn btn-primary trip-btn">Bekijk &amp; boek</a>
        </div>

        <div class="trip-card">
            <h3>Praag</h3>
            <p class="trip-price">vanaf <strong>€ 549</strong> p.p.</p>
            <a href="reizen.php" class="btn btn-primary trip-btn">Bekijk &amp; boek</a>
        </div>

        <p><a href="reizen.php">Bekijk alle reizen</a></p>
    </section>

    <section>
        <h2>Over Horizont</h2>
        <p>Wij hebben 25 jaar ervaring in premium reizen naar de mooiste bestemmingen van Europa.</p>
        <img src="Images/TFWCM.jpg" alt="Kantoor">
    </section>

    <?php require('includes/footer.php'); ?>
</body>
</html>
