-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: m-101.th.seeweb.it
-- Generation Time: May 15, 2025 at 12:50 AM
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
  `DataCreazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Codice` char(36) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Risposte`
--

CREATE TABLE `Risposte` (
  `ID_Risposta` int(6) UNSIGNED NOT NULL,
  `IDF_Domanda` int(6) UNSIGNED NOT NULL,
  `Testo` varchar(100) NOT NULL,
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
(1, 'AutenticazioneEmail'),
(4, 'RispostaMultipla'),
(5, 'VeroFalso'),
(6, 'RispostaBreve'),
(7, 'RispostaLunga'),
(8, 'RispostaNoLimiti');

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
  MODIFY `ID_Domanda` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Moduli`
--
ALTER TABLE `Moduli`
  MODIFY `ID_Modulo` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Risposte`
--
ALTER TABLE `Risposte`
  MODIFY `ID_Risposta` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Risultati`
--
ALTER TABLE `Risultati`
  MODIFY `ID_Risultato` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Sessioni`
--
ALTER TABLE `Sessioni`
  MODIFY `ID_Sessione` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Sezioni`
--
ALTER TABLE `Sezioni`
  MODIFY `ID_Sezione` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Tipologie`
--
ALTER TABLE `Tipologie`
  MODIFY `ID_Tipologia` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Utenti`
--
ALTER TABLE `Utenti`
  MODIFY `ID_Utente` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

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

-- DATI DI ESEMPIO (ID > 20, associato a IDF_Utente 1)

-- Modulo di esempio
INSERT INTO `Moduli` (`ID_Modulo`, `IDF_Utente`, `Titolo`, `Descrizione`, `Codice`) VALUES
(21, 1, 'Modulo Demo', 'Un modulo di esempio con domande di tutti i tipi', 'DEMO-0021-0021-0021-0021');

-- Sezione di esempio
INSERT INTO `Sezioni` (`ID_Sezione`, `IDF_Modulo`, `Nome`) VALUES
(21, 21, 'Sezione Unica');

-- Domande di esempio
INSERT INTO `Domande` (`ID_Domanda`, `IDF_Sezione`, `IDF_Modulo`, `IDF_Tipologia`, `Testo`, `Descrizione`) VALUES
(21, 21, 21, 4, 'Qual è il colore del cielo?', 'Domanda a scelta multipla'),
(22, 21, 21, 5, 'Il fuoco è caldo?', 'Domanda vero/falso'),
(23, 21, 21, 6, 'Come ti chiami?', 'Risposta breve'),
(24, 21, 21, 7, 'Descrivi la tua giornata tipo', 'Risposta lunga'),
(25, 21, 21, 8, 'Scrivi liberamente un pensiero', 'Risposta senza limiti');

-- Risposte per domande a scelta multipla e vero/falso
INSERT INTO `Risposte` (`ID_Risposta`, `IDF_Domanda`, `Testo`, `Punteggio`) VALUES
(21, 21, 'Blu', 1),
(22, 21, 'Verde', 0),
(23, 21, 'Rosso', 0),
(24, 21, 'Giallo', 0),
(25, 22, 'Vero', 1),
(26, 22, 'Falso', 0);

-- Esempio di risultato e dettaglio risultato (opzionale, per test)
INSERT INTO `Risultati` (`ID_Risultato`, `IDF_Modulo`) VALUES (21, 21);
INSERT INTO `DettRisultati` (`IDF_Risultato`, `IDF_Risposta`, `RispostaBreve`, `RispostaLunga`, `RispostaNoLimiti`) VALUES
(21, 21, NULL, NULL, NULL), -- Risposta multipla: Blu
(21, 25, NULL, NULL, NULL), -- Vero/Falso: Vero
(21, NULL, 'Luca', NULL, NULL), -- Risposta breve
(21, NULL, NULL, 'Mi sveglio, lavoro, faccio sport.', NULL), -- Risposta lunga
(21, NULL, NULL, NULL, 'Questo è un pensiero libero.'); -- Risposta senza limiti
