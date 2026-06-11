<?php

// Controleer of de sessie gestart is
if (!isset($_SESSION)) {
    session_start();
}

// Controleer of gebruiker ingelogd is en admin is
if (!isset($_SESSION['gebruiker_id']) || $_SESSION['gebruiker_rol'] !== 'admin') {
    // Redirect naar index.php als gebruiker geen admin is
    header('Location: ../index.php');
    exit;
}
?>
