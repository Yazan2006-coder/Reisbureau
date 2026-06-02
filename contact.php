<?php session_start(); ?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Contact — Horizont Reizen</title>
</head>
<body>
    <?php require('header.php'); ?>
    <section class="contact-form">
        <div class="contact-container">
            <div class="contact-header">
                <h2>Neem contact met ons op</h2>
                <p>Heb je vragen over onze reizen? We helpen je graag!</p>
            </div>
            
            <form class="contact-form-content">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Naam *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mailadres *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Telefoonnummer</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="subject">Onderwerp *</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                </div>
                
                <div class="form-group full-width">
                    <label for="message">Bericht *</label>
                    <textarea id="message" name="message" rows="6" required></textarea>
                </div>
                
                <button type="submit" class="contact-submit-btn">Verstuur bericht</button>
            </form>
        </div>
    </section>
    <?php require('footer.php'); ?>
</body>
</html>