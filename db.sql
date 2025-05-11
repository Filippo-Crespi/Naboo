-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: m-101.th.seeweb.it
-- Generation Time: May 07, 2025 at 06:15 PM
-- Server version: 5.7.33
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `andrella47810`
--

-- --------------------------------------------------------

--
-- Table structure for table `DettRisultati`
--

CREATE TABLE `DettRisultati` (
  `IDF_Risultato` int(6) UNSIGNED NOT NULL,
  `IDF_Risposta` int(6) UNSIGNED NOT NULL,
  `RispostaBreve` varchar(269) DEFAULT NULL,
  `RispostaLunga` varchar(569) DEFAULT NULL,
  `RispostaNoLimiti` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Domande`
--

CREATE TABLE `Domande` (
  `ID_Domanda` int(6) UNSIGNED NOT NULL,
  `IDF_Sezione` int(6) UNSIGNED DEFAULT NULL,
  `IDF_Modulo` int(6) UNSIGNED DEFAULT NULL,
  `IDF_Tipologia` int(6) UNSIGNED DEFAULT NULL,
  `Testo` text NOT NULL,
  `Descrizione` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Moduli`
--

CREATE TABLE `Moduli` (
  `ID_Modulo` int(6) UNSIGNED NOT NULL,
  `IDF_Utente` int(6) UNSIGNED NOT NULL,
  `Titolo` varchar(30) NOT NULL,
  `Descrizione` text NOT NULL,
  `DataCreazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Moduli`
--

INSERT INTO `Moduli` (`ID_Modulo`, `IDF_Utente`, `Titolo`, `Descrizione`, `DataCreazione`) VALUES
(115, 3, 'Il mio primo post', 'Ciao a tutti! Questo è il mio primo post su questa piattaforma.', '2025-04-15 08:03:40'),
(116, 2, 'Consiglio musicale', 'Avete mai ascoltato gli Arctic Monkeys? Super consigliati!', '2025-04-15 08:03:40'),
(117, 5, 'Nuova playlist JS!', 'Ho creato una playlist da ascoltare mentre programmo.', '2025-04-15 08:03:40'),
(118, 9, 'Vue.js', 'Mi sto appassionando a Vue.js, è davvero potente e intuitivo.', '2025-04-15 08:03:40'),
(119, 2, 'Artwork in arrivo', 'Sto lavorando a un nuovo pezzo digitale, vi aggiorno presto!', '2025-04-15 08:03:40'),
(120, 5, 'Consiglio film', 'Qualcuno ha visto “The Social Network”? Che ne pensate?', '2025-04-15 08:03:40'),
(121, 3, 'Post test', 'Sto solo testando la piattaforma', '2025-04-15 08:03:40'),
(122, 9, 'Problema con Git', 'Sto impazzendo con un merge conflict... qualcuno ha dei consigli?', '2025-04-15 08:03:40'),
(123, 2, 'React vs Vue', 'Qual è secondo voi il framework migliore per iniziare?', '2025-04-15 08:03:40'),
(124, 5, 'Prima demo pubblica', 'Ho appena condiviso la prima demo del mio progetto personale.', '2025-04-15 08:03:40'),
(125, 9, 'Back-end & Node', 'Sto iniziando a studiare Node.js, sembra interessante!', '2025-04-15 08:03:40'),
(126, 3, 'Dark Mode attiva!', 'Ho appena implementato la modalità scura sul mio blog.', '2025-04-15 08:03:40'),
(127, 2, 'Canzoni da chill', 'Qualcuno ha suggerimenti per musica chill da usare come sottofondo?', '2025-04-15 08:03:40'),
(128, 5, 'Errore strano', 'Mi esce \"undefined is not a function\", aiuto!', '2025-04-15 08:03:40'),
(129, 3, 'Bozza copertina', 'Ecco un\'anteprima della copertina che sto disegnando.', '2025-04-15 08:03:40'),
(130, 10, 'Focus mode: on', 'Oggi solo focus e studio, niente distrazioni.', '2025-04-15 08:03:40'),
(131, 5, 'Domanda su async/await', 'Non capisco bene quando usare await dentro una funzione.', '2025-04-15 08:03:40'),
(132, 3, 'Post motivazionale', 'Ogni giorno è un’opportunità per migliorare', '2025-04-15 08:03:40'),
(133, 9, 'Musica e codice', 'Mi aiuta tantissimo ascoltare techno mentre sviluppo.', '2025-04-15 08:03:40'),
(134, 2, 'Work in progress', 'Sto sistemando il responsive del mio sito. Coming soon!', '2025-04-15 08:03:40'),
(135, 14, 'Prova', 'Modulo di prova', '2025-05-07 07:19:38'),
(136, 14, 'Prova 1', 'Prova modulo', '2025-05-07 07:22:23'),
(137, 16, 'lollo', 'sono io uwu', '2025-05-07 09:36:54'),
(11111, 16, 'modulo nuovo', 'sium', '2025-05-07 09:39:42'),
(11123, 16, 'gsdhjcg2367', 'yufiow78', '2025-05-07 10:56:23'),
(11128, 16, 'Modulo 4', 'Descrizione del modulo 4', '2025-05-07 11:42:41'),
(11129, 16, 'Modulo 5', 'Descrizione del modulo 5', '2025-05-07 11:42:41'),
(11132, 16, 'Modulo 8', 'Descrizione del modulo 8', '2025-05-07 11:42:41'),
(11133, 16, 'Modulo 9', 'Descrizione del modulo 9', '2025-05-07 11:42:41'),
(11134, 16, 'Modulo 10', 'Descrizione del modulo 10', '2025-05-07 11:42:41'),
(11135, 16, 'Modulo 11', 'Descrizione del modulo 11', '2025-05-07 11:42:41'),
(11136, 16, 'Modulo 12', 'Descrizione del modulo 12', '2025-05-07 11:42:41'),
(11137, 16, 'Modulo 13', 'Descrizione del modulo 13', '2025-05-07 11:42:41'),
(11138, 16, 'Modulo 14', 'Descrizione del modulo 14', '2025-05-07 11:42:41'),
(11139, 16, 'Modulo 15', 'Descrizione del modulo 15', '2025-05-07 11:42:41'),
(11140, 16, 'Modulo 16', 'Descrizione del modulo 16', '2025-05-07 11:42:41'),
(11141, 16, 'Modulo 17', 'Descrizione del modulo 17', '2025-05-07 11:42:41'),
(11142, 16, 'Modulo 18', 'Descrizione del modulo 18', '2025-05-07 11:42:41'),
(11143, 16, 'Modulo 19', 'Descrizione del modulo 19', '2025-05-07 11:42:41'),
(11145, 16, 'modulo fantastico', 'descriione maanfantanistica', '2025-05-07 11:50:54');

-- --------------------------------------------------------

--
-- Table structure for table `Risposte`
--

CREATE TABLE `Risposte` (
  `ID_Risposta` int(6) UNSIGNED NOT NULL,
  `IDF_Domanda` int(6) UNSIGNED DEFAULT NULL,
  `Testo` text NOT NULL,
  `Punteggio` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Risultati`
--

CREATE TABLE `Risultati` (
  `ID_Risultato` int(6) UNSIGNED NOT NULL,
  `IDF_Modulo` int(6) UNSIGNED DEFAULT NULL,
  `DataCreazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Sessioni`
--

CREATE TABLE `Sessioni` (
  `ID_Sessione` int(6) UNSIGNED NOT NULL,
  `IDF_Utente` int(6) UNSIGNED DEFAULT NULL,
  `Token` varchar(50) NOT NULL,
  `DataInizio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Sospeso` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Sessioni`
--

INSERT INTO `Sessioni` (`ID_Sessione`, `IDF_Utente`, `Token`, `DataInizio`, `Sospeso`) VALUES
(48, 14, '7d44eb94f323779997e84ddbe914fb5c', '2025-04-10 11:56:43', 1),
(49, 14, '6bccbd3b6792e4663c26f3534ef95610', '2025-05-07 07:18:36', 0),
(50, 16, 'a858b59a5673ac8c1ea6751cafc33c18', '2025-05-07 09:36:18', 1),
(51, 16, '5555df01d86faf7f3ce94b313b5e0dfe', '2025-05-07 09:51:36', 1),
(52, 16, 'a208a60a60f89ea4fca185671f95b1ac', '2025-05-07 09:51:54', 1),
(53, 16, '81cdc18369b068b3d6ef5e5ea3f24602', '2025-05-07 09:58:54', 1),
(54, 16, '2976e20337607257e9d6784d4f971dab', '2025-05-07 10:00:48', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Sezioni`
--

CREATE TABLE `Sezioni` (
  `ID_Sezione` int(6) UNSIGNED NOT NULL,
  `IDF_Modulo` int(6) UNSIGNED DEFAULT NULL,
  `Nome` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Tipologie`
--

CREATE TABLE `Tipologie` (
  `ID_Tipologia` int(6) UNSIGNED NOT NULL,
  `Nome` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Tipologie`
--

INSERT INTO `Tipologie` (`ID_Tipologia`, `Nome`) VALUES
(1, 'AutenticazioneEmail');

-- --------------------------------------------------------

--
-- Table structure for table `Utenti`
--

CREATE TABLE `Utenti` (
  `ID_Utente` int(6) UNSIGNED NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Nome` varchar(30) NOT NULL,
  `Cognome` varchar(30) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `DataReg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Utenti`
--

INSERT INTO `Utenti` (`ID_Utente`, `Username`, `Nome`, `Cognome`, `Email`, `Password`, `DataReg`) VALUES
(2, 'sara_b', 'Sara', 'Bianchi', 'sara.bianchi@example.com', 'mypassword', '2025-04-08 17:20:39'),
(3, 'marco22', 'Marco', 'Verdi', 'marco.verdi@example.com', 'qwerty123', '2025-04-08 17:20:39'),
(5, 'ale89', 'Alessandro', 'Russo', 'ale.russo@example.com', 'ciaociao', '2025-04-08 17:20:39'),
(9, 'andre_b', 'Andrea', 'Bruni', 'andrea.bruni@example.com', 'andrea321', '2025-04-08 17:20:39'),
(10, 'vale_f', 'Valentina', 'Ferrari', 'valentina.f@example.com', 'valepass', '2025-04-08 17:20:39'),
(14, 'lavelliandrea1964@gmail.com', 'ANDREA', 'LAVELLI', 'lavelliandrea1964@gmail.com', '$2y$10$PGZJgLfu2EK/sggqJyRzUORDfEGajJPBQ9wpchbXHJSNdy6TMT1tC', '2025-04-10 11:56:37'),
(16, 'Filips', 'Filippo', 'Crespi', 'crespifilippo123@gmail.com', '$2y$10$C3rXuTCvdvxduWnKno.SyucN4jaQjkSt2BOWQO.wHwY7oPap1yaA6', '2025-05-07 09:36:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `DettRisultati`
--
ALTER TABLE `DettRisultati`
  ADD PRIMARY KEY (`IDF_Risultato`,`IDF_Risposta`),
  ADD KEY `IDF_Risposta` (`IDF_Risposta`);

--
-- Indexes for table `Domande`
--
ALTER TABLE `Domande`
  ADD PRIMARY KEY (`ID_Domanda`),
  ADD KEY `IDF_Sezione` (`IDF_Sezione`),
  ADD KEY `IDF_Modulo` (`IDF_Modulo`),
  ADD KEY `IDF_Tipologia` (`IDF_Tipologia`);

--
-- Indexes for table `Moduli`
--
ALTER TABLE `Moduli`
  ADD PRIMARY KEY (`ID_Modulo`),
  ADD UNIQUE KEY `Titolo` (`Titolo`),
  ADD UNIQUE KEY `Titolo_2` (`Titolo`),
  ADD KEY `IDF_Utente` (`IDF_Utente`);

--
-- Indexes for table `Risposte`
--
ALTER TABLE `Risposte`
  ADD PRIMARY KEY (`ID_Risposta`),
  ADD KEY `IDF_Domanda` (`IDF_Domanda`);

--
-- Indexes for table `Risultati`
--
ALTER TABLE `Risultati`
  ADD PRIMARY KEY (`ID_Risultato`),
  ADD KEY `IDF_Modulo` (`IDF_Modulo`);

--
-- Indexes for table `Sessioni`
--
ALTER TABLE `Sessioni`
  ADD PRIMARY KEY (`ID_Sessione`),
  ADD KEY `IDF_Utente` (`IDF_Utente`);

--
-- Indexes for table `Sezioni`
--
ALTER TABLE `Sezioni`
  ADD PRIMARY KEY (`ID_Sezione`),
  ADD KEY `IDF_Modulo` (`IDF_Modulo`);

--
-- Indexes for table `Tipologie`
--
ALTER TABLE `Tipologie`
  ADD PRIMARY KEY (`ID_Tipologia`);

--
-- Indexes for table `Utenti`
--
ALTER TABLE `Utenti`
  ADD PRIMARY KEY (`ID_Utente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Domande`
--
ALTER TABLE `Domande`
  MODIFY `ID_Domanda` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Moduli`
--
ALTER TABLE `Moduli`
  MODIFY `ID_Modulo` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11146;

--
-- AUTO_INCREMENT for table `Risposte`
--
ALTER TABLE `Risposte`
  MODIFY `ID_Risposta` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Risultati`
--
ALTER TABLE `Risultati`
  MODIFY `ID_Risultato` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Sessioni`
--
ALTER TABLE `Sessioni`
  MODIFY `ID_Sessione` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `Sezioni`
--
ALTER TABLE `Sezioni`
  MODIFY `ID_Sezione` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Tipologie`
--
ALTER TABLE `Tipologie`
  MODIFY `ID_Tipologia` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Utenti`
--
ALTER TABLE `Utenti`
  MODIFY `ID_Utente` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `DettRisultati`
--
ALTER TABLE `DettRisultati`
  ADD CONSTRAINT `DettRisultati_ibfk_1` FOREIGN KEY (`IDF_Risultato`) REFERENCES `Risultati` (`ID_Risultato`) ON DELETE CASCADE,
  ADD CONSTRAINT `DettRisultati_ibfk_2` FOREIGN KEY (`IDF_Risposta`) REFERENCES `Risposte` (`ID_Risposta`) ON DELETE CASCADE;

--
-- Constraints for table `Domande`
--
ALTER TABLE `Domande`
  ADD CONSTRAINT `Domande_ibfk_1` FOREIGN KEY (`IDF_Sezione`) REFERENCES `Sezioni` (`ID_Sezione`) ON DELETE CASCADE,
  ADD CONSTRAINT `Domande_ibfk_2` FOREIGN KEY (`IDF_Modulo`) REFERENCES `Moduli` (`ID_Modulo`) ON DELETE CASCADE,
  ADD CONSTRAINT `Domande_ibfk_3` FOREIGN KEY (`IDF_Tipologia`) REFERENCES `Tipologie` (`ID_Tipologia`) ON DELETE CASCADE;

--
-- Constraints for table `Moduli`
--
ALTER TABLE `Moduli`
  ADD CONSTRAINT `Moduli_ibfk_1` FOREIGN KEY (`IDF_Utente`) REFERENCES `Utenti` (`ID_Utente`) ON DELETE CASCADE;

--
-- Constraints for table `Risposte`
--
ALTER TABLE `Risposte`
  ADD CONSTRAINT `Risposte_ibfk_1` FOREIGN KEY (`IDF_Domanda`) REFERENCES `Domande` (`ID_Domanda`) ON DELETE CASCADE;

--
-- Constraints for table `Risultati`
--
ALTER TABLE `Risultati`
  ADD CONSTRAINT `Risultati_ibfk_1` FOREIGN KEY (`IDF_Modulo`) REFERENCES `Moduli` (`ID_Modulo`) ON DELETE CASCADE;

--
-- Constraints for table `Sessioni`
--
ALTER TABLE `Sessioni`
  ADD CONSTRAINT `Sessioni_ibfk_1` FOREIGN KEY (`IDF_Utente`) REFERENCES `Utenti` (`ID_Utente`) ON DELETE CASCADE;

--
-- Constraints for table `Sezioni`
--
ALTER TABLE `Sezioni`
  ADD CONSTRAINT `Sezioni_ibfk_1` FOREIGN KEY (`IDF_Modulo`) REFERENCES `Moduli` (`ID_Modulo`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
