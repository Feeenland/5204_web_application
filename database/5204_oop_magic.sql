-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 01. Apr 2021 um 13:16
-- Server-Version: 10.4.14-MariaDB
-- PHP-Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `5204_oop_magic`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `lang` varchar(20) NOT NULL,
  `scryfall_uri` varchar(250) NOT NULL,
  `cmc` decimal(10,0) NOT NULL,
  `mana_cost` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `power` varchar(250) NOT NULL,
  `toughness` varchar(250) NOT NULL,
  `image_uris` varchar(250) NOT NULL,
  `rarity` varchar(250) NOT NULL,
  `set_name` int(250) NOT NULL,
  `type_line` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cards_has_colors`
--

CREATE TABLE `cards_has_colors` (
  `id` int(11) NOT NULL,
  `cards_id` int(11) NOT NULL,
  `colors_id` int(11) NOT NULL,
  `cards_colors_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cards_has_formats_has_legalities`
--

CREATE TABLE `cards_has_formats_has_legalities` (
  `id` int(11) NOT NULL,
  `cards_id` int(11) NOT NULL,
  `formats_id` int(11) NOT NULL,
  `legalities_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `color` varchar(50) NOT NULL,
  `abbr` varchar(20) NOT NULL,
  `basic_land` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `colors`
--

INSERT INTO `colors` (`id`, `color`, `abbr`, `basic_land`) VALUES
(1, 'White', 'W', 'Plains'),
(2, 'Blue', 'U', 'Island'),
(3, 'Black', 'B', 'Swamp'),
(4, 'Red', 'R', 'Mountain'),
(5, 'Green', 'G', 'Forest');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `decks`
--

CREATE TABLE `decks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `format_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `decks_has_cards`
--

CREATE TABLE `decks_has_cards` (
  `id` int(11) NOT NULL,
  `deck_id` int(11) NOT NULL,
  `cards_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `decks_has_colors`
--

CREATE TABLE `decks_has_colors` (
  `id` int(11) NOT NULL,
  `deck_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `formats`
--

CREATE TABLE `formats` (
  `id` int(11) NOT NULL,
  `format` varchar(20) NOT NULL,
  `cards` int(255) NOT NULL,
  `sideboard` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `formats`
--

INSERT INTO `formats` (`id`, `format`, `cards`, `sideboard`) VALUES
(1, 'standard', 60, 15),
(2, 'future', 0, 0),
(3, 'historic', 0, 0),
(4, 'gladiator', 0, 0),
(5, 'pioneer', 0, 0),
(6, 'modern', 0, 0),
(7, 'legacy', 0, 0),
(8, 'pauper', 0, 0),
(9, 'vintage', 0, 0),
(10, 'penny', 0, 0),
(11, 'commander', 0, 0),
(12, 'brawl', 0, 0),
(13, 'duel', 0, 0),
(14, 'oldschool', 0, 0),
(15, 'premodern', 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `legalities`
--

CREATE TABLE `legalities` (
  `id` int(11) NOT NULL,
  `legality` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `legalities`
--

INSERT INTO `legalities` (`id`, `legality`) VALUES
(1, 'legal'),
(2, 'not_legal'),
(3, 'restricted'),
(4, 'banned');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `set_edition`
--

CREATE TABLE `set_edition` (
  `id` int(11) NOT NULL,
  `set_name` varchar(250) NOT NULL,
  `set` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `nickname` varchar(200) NOT NULL,
  `favourite_card` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `login_try` int(3) NOT NULL,
  `banned_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `name`, `nickname`, `favourite_card`, `password`, `login_try`, `banned_at`) VALUES
(1, 'testxz', 'test', 'land', 'test', 0, NULL),
(3, '', '', '', '', 0, NULL),
(4, '', '', '', '', 0, NULL),
(6, '', '', '', '', 0, NULL),
(7, '', '', '', '', 0, NULL),
(8, '', '', '', '', 0, NULL),
(9, '', '', '', '', 0, NULL),
(10, '', '', '', '', 0, NULL),
(11, '', '', '', '', 0, NULL),
(12, '', '', '', '', 0, NULL),
(13, '', '', '', '', 0, NULL),
(14, 'test3', 'test4', 'island', 'test', 0, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users_has_cards`
--

CREATE TABLE `users_has_cards` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `cards_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_set_card` (`set_name`);

--
-- Indizes für die Tabelle `cards_has_colors`
--
ALTER TABLE `cards_has_colors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cards_col` (`cards_id`),
  ADD KEY `fk_colors_col` (`colors_id`);

--
-- Indizes für die Tabelle `cards_has_formats_has_legalities`
--
ALTER TABLE `cards_has_formats_has_legalities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_card_ly` (`cards_id`),
  ADD KEY `fk_format_ly` (`formats_id`),
  ADD KEY `fk_legality_ly` (`legalities_id`);

--
-- Indizes für die Tabelle `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `decks`
--
ALTER TABLE `decks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_deck` (`user_id`),
  ADD KEY `fk_format_deck` (`format_id`);

--
-- Indizes für die Tabelle `decks_has_cards`
--
ALTER TABLE `decks_has_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_deck_card` (`cards_id`),
  ADD KEY `fk_deck_deck` (`deck_id`);

--
-- Indizes für die Tabelle `decks_has_colors`
--
ALTER TABLE `decks_has_colors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_deck_collor` (`deck_id`),
  ADD KEY `fk_color_color` (`color_id`);

--
-- Indizes für die Tabelle `formats`
--
ALTER TABLE `formats`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `legalities`
--
ALTER TABLE `legalities`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `set_edition`
--
ALTER TABLE `set_edition`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users_has_cards`
--
ALTER TABLE `users_has_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_card_crd` (`cards_id`),
  ADD KEY `fk_user_crd` (`users_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `cards_has_colors`
--
ALTER TABLE `cards_has_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `cards_has_formats_has_legalities`
--
ALTER TABLE `cards_has_formats_has_legalities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `decks`
--
ALTER TABLE `decks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `decks_has_cards`
--
ALTER TABLE `decks_has_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `decks_has_colors`
--
ALTER TABLE `decks_has_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `formats`
--
ALTER TABLE `formats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT für Tabelle `legalities`
--
ALTER TABLE `legalities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `set_edition`
--
ALTER TABLE `set_edition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `users_has_cards`
--
ALTER TABLE `users_has_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `fk_set_card` FOREIGN KEY (`set_name`) REFERENCES `set_edition` (`id`);

--
-- Constraints der Tabelle `cards_has_colors`
--
ALTER TABLE `cards_has_colors`
  ADD CONSTRAINT `fk_cards_col` FOREIGN KEY (`cards_id`) REFERENCES `cards` (`id`),
  ADD CONSTRAINT `fk_colors_col` FOREIGN KEY (`colors_id`) REFERENCES `colors` (`id`);

--
-- Constraints der Tabelle `cards_has_formats_has_legalities`
--
ALTER TABLE `cards_has_formats_has_legalities`
  ADD CONSTRAINT `fk_card_ly` FOREIGN KEY (`cards_id`) REFERENCES `cards` (`id`),
  ADD CONSTRAINT `fk_format_ly` FOREIGN KEY (`formats_id`) REFERENCES `formats` (`id`),
  ADD CONSTRAINT `fk_legality_ly` FOREIGN KEY (`legalities_id`) REFERENCES `legalities` (`id`);

--
-- Constraints der Tabelle `decks`
--
ALTER TABLE `decks`
  ADD CONSTRAINT `fk_format_deck` FOREIGN KEY (`format_id`) REFERENCES `formats` (`id`),
  ADD CONSTRAINT `fk_user_deck` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints der Tabelle `decks_has_cards`
--
ALTER TABLE `decks_has_cards`
  ADD CONSTRAINT `fk_deck_card` FOREIGN KEY (`cards_id`) REFERENCES `cards` (`id`),
  ADD CONSTRAINT `fk_deck_deck` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`);

--
-- Constraints der Tabelle `decks_has_colors`
--
ALTER TABLE `decks_has_colors`
  ADD CONSTRAINT `fk_color_color` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`),
  ADD CONSTRAINT `fk_deck_collor` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`);

--
-- Constraints der Tabelle `users_has_cards`
--
ALTER TABLE `users_has_cards`
  ADD CONSTRAINT `fk_card_crd` FOREIGN KEY (`cards_id`) REFERENCES `cards` (`id`),
  ADD CONSTRAINT `fk_user_crd` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
