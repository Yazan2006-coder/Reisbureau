-- Maak database aan
CREATE DATABASE IF NOT EXISTS reisbureau CHARACTER SET utf8 COLLATE utf8_general_ci;

USE reisbureau;

-- Gebruikerstabel
CREATE TABLE IF NOT EXISTS gebruikers (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    voornaam      VARCHAR(100)  NOT NULL,
    achternaam    VARCHAR(100)  NOT NULL,
    email         VARCHAR(255)  NOT NULL UNIQUE,
    wachtwoord    VARCHAR(255)  NOT NULL,
    rol           ENUM('klant','admin') NOT NULL DEFAULT 'klant',
    aangemaakt_op DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Demo-accounts (wachtwoorden: demo1234 / admin1234)
INSERT INTO gebruikers (voornaam, achternaam, email, wachtwoord, rol) VALUES
('Demo',  'Klant', 'demo@horizont.nl',  '$2y$12$DEMO_HASH_KLANT_VERVANG_DIT',  'klant'),
('Admin', 'User',  'admin@horizont.nl', '$2y$12$DEMO_HASH_ADMIN_VERVANG_DIT',  'admin');
