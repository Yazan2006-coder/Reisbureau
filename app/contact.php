<?php session_start(); ?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Contact â€” Horizont Reizen</title>
</head>
<body>
    <?php require('includes/header.php'); ?>
    <section class="contact-form">
        <div class="contact-container">
            <div class="contact-header">
                <h2>Neem contact met ons op</h2>
                <p>Heb je vragen over onze reizen? We helpen je graag!</p>
            </div>
            
            <form class="contact-form-content" onsubmit="return verstuurFormulier()">
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
    <script>
        function verstuurFormulier() {
            // Haal de waarden op uit het formulier
            var naam = document.getElementById("name").value;
            var email = document.getElementById("email").value;
            var onderwerp = document.getElementById("subject").value;
            var bericht = document.getElementById("message").value;
            // Controleer of naam is ingevuld
            if (naam == "") {
                alert("Vul je naam in!");
                return false;
            }
            // Controleer of email is ingevuld
            if (email == "") {
                alert("Vul je e-mailadres in!");
                return false;
            }
            // Controleer of email een @ bevat
            if (email.includes("@") == false) {
                alert("Vul een geldig e-mailadres in!");
                return false;
            }
            // Controleer of onderwerp is ingevuld
            if (onderwerp == "") {
                alert("Vul een onderwerp in!");
                return false;
            }
            // Controleer of bericht is ingevuld
            if (bericht == "") {
                alert("Vul een bericht in!");
                return false;
            }
            // Als alles is ingevuld, geef een melding
            alert("Je bericht is verstuurd! We nemen zo snel mogelijk contact met je op.");
        }
    </script>
    <?php require('includes/footer.php'); ?>
</body>
</html>
