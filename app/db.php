<?php
// Database draait in docker container anders zou het localhost heten
$host     = 'db';
$dbname   = 'reisbureau';
$username = 'root';
$password = 'rootpassword';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8", 
        $username,
        $password
    );
    // Dit zorgt ervoor als er een database fout is dat PHP een exception gooit
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Maakt array van reis[0] naar reis['bestemming']
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Databaseverbinding mislukt: ' . $e->getMessage());
}
