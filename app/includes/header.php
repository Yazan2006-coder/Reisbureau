    <header class="header">
        <div class="header-content">
            <div class="logo">Horizont Reizen</div>
            <nav class="nav">
                <a href="/index.php" class="header-btns">Home</a>
                <a href="/reizen.php" class="header-btns">Reizen</a>
                <a href="/overons.php" class="header-btns">Over ons</a>
                <a href="/contact.php" class="header-btns">Contact</a>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['gebruiker_id'])){ ?>
                    <span class="header-welkom">Hallo, <?= ($_SESSION['gebruiker_naam']) ?></span>
                    <a href="/mijn-account.php" class="btn-login">Mijn Account</a>
                    <a href="/logout.php" class="btn-login">Uitloggen</a>
                <?php }else{ ?>
                    <a href="/login.php" class="btn-login">Inloggen</a>
                    <a href="/register.php" class="btn-signup">Account aanmaken</a>
                <?php } ?>
            </div>
        </div>
    </header>
