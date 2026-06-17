-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jun 16, 2026 at 10:40 AM
-- Server version: 9.7.0
-- PHP Version: 8.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reisbureau`
--

-- --------------------------------------------------------

--
-- Table structure for table `boekingen`
--

CREATE TABLE `boekingen` (
  `id` int NOT NULL,
  `gebruiker_id` int NOT NULL,
  `reis_id` int NOT NULL,
  `aantal_personen` int NOT NULL DEFAULT '1',
  `totaal_prijs` decimal(10,2) NOT NULL,
  `status` enum('actief','geannuleerd') NOT NULL DEFAULT 'actief',
  `aangemaakt_op` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `boekingen`
--

INSERT INTO `boekingen` (`id`, `gebruiker_id`, `reis_id`, `aantal_personen`, `totaal_prijs`, `status`, `aangemaakt_op`) VALUES
(1, 3, 1, 1, 599.99, 'actief', '2026-06-15 14:15:47'),
(2, 4, 5, 3, 1949.97, 'geannuleerd', '2026-06-15 14:33:37'),
(3, 3, 4, 1, 349.99, 'actief', '2026-06-16 07:40:57');

-- --------------------------------------------------------

--
-- Table structure for table `gebruikers`
--

CREATE TABLE `gebruikers` (
  `id` int NOT NULL,
  `voornaam` varchar(100) NOT NULL,
  `achternaam` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `rol` enum('klant','admin') NOT NULL DEFAULT 'klant',
  `aangemaakt_op` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `gebruikers`
--

INSERT INTO `gebruikers` (`id`, `voornaam`, `achternaam`, `email`, `wachtwoord`, `rol`, `aangemaakt_op`) VALUES
(1, 'Demo', 'Klant', 'demo@horizont.nl', '$2y$12$DEMO_HASH_KLANT_VERVANG_DIT', 'klant', '2026-06-11 13:20:09'),
(2, 'Admin', 'User', 'admin@horizont.nl', '$2y$12$DEMO_HASH_ADMIN_VERVANG_DIT', 'admin', '2026-06-11 13:20:09'),
(3, 'Test', 'Admin', 'testadmin@test.nl', '$2y$12$ZEb83VSo8fKUD5MBsYRsbuCCVGXE8JWX1SLs.qpUxWmmm31wJsnq.', 'admin', '2026-06-11 13:23:41'),
(4, 'Jason', 'Voorhees', 'jasonvoorhees@gmail.com', '$2y$12$moMS2mpQyWZq.aAdRFYQxu/dqUFK.MqCk0kuR7wYUsGbaxXgmtCOm', 'klant', '2026-06-15 14:31:00');

-- --------------------------------------------------------

--
-- Table structure for table `recensies`
--

CREATE TABLE `recensies` (
  `id` int NOT NULL,
  `gebruiker_id` int NOT NULL,
  `reis_id` int NOT NULL,
  `beoordeling` int NOT NULL,
  `tekst` text NOT NULL,
  `aangemaakt_op` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `recensies`
--

INSERT INTO `recensies` (`id`, `gebruiker_id`, `reis_id`, `beoordeling`, `tekst`, `aangemaakt_op`) VALUES
(1, 3, 1, 2, 'jaaa', '2026-06-15 14:15:43'),
(2, 3, 4, 2, 'jaa', '2026-06-16 07:40:55'),
(3, 3, 8, 5, 'yes', '2026-06-16 10:39:33');

-- --------------------------------------------------------

--
-- Table structure for table `reizen`
--

CREATE TABLE `reizen` (
  `id` int NOT NULL,
  `bestemming` varchar(100) NOT NULL,
  `beschrijving` text NOT NULL,
  `type_reis` varchar(50) NOT NULL,
  `prijs` decimal(8,2) NOT NULL,
  `afbeelding_url` varchar(255) DEFAULT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date NOT NULL,
  `max_personen` int NOT NULL,
  `geboekt_personen` int DEFAULT '0',
  `actief` tinyint(1) DEFAULT '1',
  `aangemaakt_op` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bijgewerkt_op` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `kleur` varchar(7) NOT NULL DEFAULT '#3498db'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `reizen`
--

INSERT INTO `reizen` (`id`, `bestemming`, `beschrijving`, `type_reis`, `prijs`, `afbeelding_url`, `startdatum`, `einddatum`, `max_personen`, `geboekt_personen`, `actief`, `aangemaakt_op`, `bijgewerkt_op`, `kleur`) VALUES
(1, 'Barcelona', 'Gaudí & strand', 'Citytrip', 599.99, '/images/barcelona.jpg', '2026-07-01', '2026-07-05', 20, 1, 1, '2026-06-11 13:20:09', '2026-06-15 14:15:47', '#6289a3'),
(2, 'Bali', 'Ontdek tropische stranden, tempels en rijstvelden in het paradijs van IndonesiÃ«. Perfect voor ontspanning en avontuur.', 'Strand', 799.99, '/images/bali.jpg', '2026-07-10', '2026-07-17', 15, 0, 1, '2026-06-11 13:20:09', '2026-06-11 13:20:09', '#3498db'),
(3, 'Noorwegen', 'Beleef de fjorden, Northern Lights en wilde natuur. Een avontuurlijke reis door Ã©Ã©n van de mooiste landen ter wereld.', 'Avontuur', 1299.99, '/images/noorwegen.jpg', '2026-08-01', '2026-08-10', 12, 0, 1, '2026-06-11 13:20:09', '2026-06-11 13:20:09', '#3498db'),
(4, 'Amsterdam', 'Ontdek grachten, musea en typische Nederlandse gezelligheid. De perfecte korte citytrip voor herhaling.', 'Citytrip', 349.99, '/images/amsterdam.jpg', '2026-07-15', '2026-07-18', 25, 1, 1, '2026-06-11 13:20:09', '2026-06-16 07:40:57', '#3498db'),
(5, 'Marokko', 'Marrakech, Fez en de Sahara wachten op je. Een magische reis met exotische markten en prachtige landschappen.', 'Avontuur', 649.99, '/images/marokko.jpg', '2026-08-05', '2026-08-12', 18, 0, 1, '2026-06-11 13:20:09', '2026-06-15 14:33:45', '#3498db'),
(7, 'Parijs', 'Stad van het licht met de Eiffeltoren, het Louvre en gezellige terrasjes. Een romantische citytrip die je niet snel vergeet.', 'Citytrip', 689.00, NULL, '2026-07-20', '2026-07-24', 20, 0, 1, '2026-06-15 14:56:32', '2026-06-15 14:56:32', '#2E86DE'),
(8, 'Rome', 'Wandel langs het Colosseum, het Forum Romanum en gooi een muntje in de Trevifontein. Geschiedenis op elke hoek.', 'Citytrip', 849.00, NULL, '2026-08-15', '2026-08-20', 18, 0, 1, '2026-06-15 14:56:32', '2026-06-15 14:56:32', '#E67E22'),
(9, 'Praag', 'De Gouden Stad met de Karelsbrug, de astronomische klok en sfeervolle straatjes. Perfect voor een korte stedentrip.', 'Citytrip', 549.00, NULL, '2026-09-01', '2026-09-04', 22, 0, 1, '2026-06-15 14:56:32', '2026-06-15 14:56:32', '#8E44AD');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boekingen`
--
ALTER TABLE `boekingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gebruiker_id` (`gebruiker_id`),
  ADD KEY `reis_id` (`reis_id`);

--
-- Indexes for table `gebruikers`
--
ALTER TABLE `gebruikers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `recensies`
--
ALTER TABLE `recensies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gebruiker_id` (`gebruiker_id`),
  ADD KEY `reis_id` (`reis_id`);

--
-- Indexes for table `reizen`
--
ALTER TABLE `reizen`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boekingen`
--
ALTER TABLE `boekingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gebruikers`
--
ALTER TABLE `gebruikers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `recensies`
--
ALTER TABLE `recensies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reizen`
--
ALTER TABLE `reizen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `boekingen`
--
ALTER TABLE `boekingen`
  ADD CONSTRAINT `boekingen_ibfk_1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruikers` (`id`),
  ADD CONSTRAINT `boekingen_ibfk_2` FOREIGN KEY (`reis_id`) REFERENCES `reizen` (`id`);

--
-- Constraints for table `recensies`
--
ALTER TABLE `recensies`
  ADD CONSTRAINT `recensies_ibfk_1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruikers` (`id`),
  ADD CONSTRAINT `recensies_ibfk_2` FOREIGN KEY (`reis_id`) REFERENCES `reizen` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
