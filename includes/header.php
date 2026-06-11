    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo">Horizont Reizen</div>
                <div class="logo-tagline">JOUW WERELD, JOUW KOERS</div>
            </div>
            <nav class="nav">
                <a href="index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'nav-active' : ''; ?>">Home</a>
                <a href="reizen.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'reizen.php') ? 'nav-active' : ''; ?>">Reizen</a>
                <a href="overons.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'overons.php') ? 'nav-active' : ''; ?>">Over ons</a>
                <a href="contact.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'nav-active' : ''; ?>">Contact</a>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['gebruiker_id'])): ?>
                    <span class="header-welkom">Hallo, <?= htmlspecialchars($_SESSION['gebruiker_naam']) ?></span>
                    <a href="logout.php" class="btn-login">Uitloggen</a>
                    <a href="account.php" class="btn-signup">Account</a>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Inloggen</a>
                    <a href="register.php" class="btn-signup">Account aanmaken</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
