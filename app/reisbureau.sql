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
-- INSERT IGNORE zodat je dit bestand opnieuw kan uitvoeren zonder fout
INSERT IGNORE INTO gebruikers (voornaam, achternaam, email, wachtwoord, rol) VALUES
('Demo',  'Klant', 'demo@horizont.nl',  '$2y$12$4Ubih/.isRedbVGXDnoPSet2gh7PDNPRYPhiFJZnxTX1M7n.uEXdC',  'klant'),
('Admin', 'User',  'admin@horizont.nl', '$2y$12$rSAdjVRzmEhMguY0YXxU5eNJ8Ct62ODoKiejdd5uZzZ7xP5txN7PK',  'admin');

-- Reizen tabel
CREATE TABLE IF NOT EXISTS reizen (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    bestemming        VARCHAR(100)  NOT NULL,
    beschrijving      TEXT          NOT NULL,
    type_reis         VARCHAR(50)   NOT NULL,
    prijs             DECIMAL(8, 2) NOT NULL,
    kleur             VARCHAR(7)    NOT NULL DEFAULT '#3498db',
    startdatum        DATE          NOT NULL,
    einddatum         DATE          NOT NULL,
    max_personen      INT           NOT NULL,
    geboekt_personen  INT           DEFAULT 0,
    actief            BOOLEAN       DEFAULT TRUE,
    aangemaakt_op     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    bijgewerkt_op     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Demo reizen
INSERT INTO reizen (bestemming, beschrijving, type_reis, prijs, kleur, startdatum, einddatum, max_personen) VALUES
('Barcelona', 'Geniet van de Sagrada Familia, Park Güell en het Gotische Kwartier. Een onvergetelijke citytrip vol cultuur en moderne architectuur.', 'Citytrip', 599.99, '#E74C3C', '2026-07-01', '2026-07-05', 20),
('Bali', 'Ontdek tropische stranden, tempels en rijstvelden in het paradijs van Indonesië. Perfect voor ontspanning en avontuur.', 'Strand', 799.99, '#27AE60', '2026-07-10', '2026-07-17', 15),
('Noorwegen', 'Beleef de fjorden, Northern Lights en wilde natuur. Een avontuurlijke reis door één van de mooiste landen ter wereld.', 'Avontuur', 1299.99, '#2E86DE', '2026-08-01', '2026-08-10', 12),
('Amsterdam', 'Ontdek grachten, musea en typische Nederlandse gezelligheid. De perfecte korte citytrip voor herhaling.', 'Citytrip', 349.99, '#F39C12', '2026-07-15', '2026-07-18', 25),
('Marokko', 'Marrakech, Fez en de Sahara wachten op je. Een magische reis met exotische markten en prachtige landschappen.', 'Avontuur', 649.99, '#9B59B6', '2026-08-05', '2026-08-12', 18);

-- Boekingen tabel
CREATE TABLE IF NOT EXISTS boekingen (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    gebruiker_id    INT NOT NULL,
    reis_id         INT NOT NULL,
    aantal_personen INT NOT NULL DEFAULT 1,
    totaal_prijs    DECIMAL(10, 2) NOT NULL,
    status          ENUM('actief', 'geannuleerd') NOT NULL DEFAULT 'actief',
    aangemaakt_op   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gebruiker_id) REFERENCES gebruikers(id),
    FOREIGN KEY (reis_id) REFERENCES reizen(id)
);

-- Recensies tabel
CREATE TABLE IF NOT EXISTS recensies (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    gebruiker_id  INT NOT NULL,
    reis_id       INT NOT NULL,
    beoordeling   INT NOT NULL CHECK (beoordeling BETWEEN 1 AND 5),
    tekst         TEXT NOT NULL,
    aangemaakt_op DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gebruiker_id) REFERENCES gebruikers(id),
    FOREIGN KEY (reis_id) REFERENCES reizen(id)
);
