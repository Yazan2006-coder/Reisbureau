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
                <a href="#" class="btn-login">Inloggen</a>
                <a href="#" class="btn-signup">Account aanmaken</a>
            </div>
        </div>
    </header>
