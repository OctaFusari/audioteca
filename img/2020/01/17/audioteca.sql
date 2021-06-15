-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 17, 2020 alle 16:34
-- Versione del server: 10.4.11-MariaDB
-- Versione PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `audioteca`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `album`
--

CREATE TABLE `album` (
  `ID` int(11) NOT NULL,
  `NOME` varchar(50) NOT NULL,
  `ID_STELLE` int(11) NOT NULL,
  `ID_AUTORE` int(11) NOT NULL,
  `DATAUSCITA` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `album`
--

INSERT INTO `album` (`ID`, `NOME`, `ID_STELLE`, `ID_AUTORE`, `DATAUSCITA`) VALUES
(1, 'ASTROWORLD', 5, 4, '2018-12-09 23:00:00'),
(2, 'STARBOY', 4, 3, '2016-10-20 22:00:00'),
(3, 'Hyperion', 3, 6, '2019-03-07 23:00:00'),
(4, 'Birds in the Trap Sing McKnight', 4, 4, '2016-10-15 22:00:00'),
(27, 'Recovery', 4, 2, '2010-06-17 22:00:00'),
(28, 'Jack Ü', 3, 1, '2015-02-26 23:00:00'),
(29, 'Kind of Blue', 4, 7, '0000-00-00 00:00:00'),
(30, 'Scary Monsters and Nice Sprites', 5, 1, '2010-10-21 22:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `autori`
--

CREATE TABLE `autori` (
  `ID` int(11) NOT NULL,
  `NOMEA` varchar(50) NOT NULL,
  `COGNOME` varchar(50) NOT NULL,
  `NOMEARTE` varchar(50) NOT NULL,
  `ID_GENERE` int(11) NOT NULL,
  `STELLE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `autori`
--

INSERT INTO `autori` (`ID`, `NOMEA`, `COGNOME`, `NOMEARTE`, `ID_GENERE`, `STELLE`) VALUES
(1, 'Sonny', 'Moore', 'Skrillex', 3, 4),
(2, 'Marshall Bruce', 'Mathers III', 'Eminem', 1, 5),
(3, 'Abel', 'Tesfaye', 'The Weeknd', 7, 5),
(4, 'Travis', 'Scott', 'Travis Scott', 5, 5),
(5, '', '', 'ARTISTI VARI', 1, 5),
(6, 'Mike', 'Levi', 'Gesaffelstein', 3, 3),
(7, 'Miles', 'Devis', 'Miles Devis', 4, 8);

-- --------------------------------------------------------

--
-- Struttura della tabella `brano`
--

CREATE TABLE `brano` (
  `ID` int(11) NOT NULL,
  `NOME` varchar(50) NOT NULL,
  `ID_GENERE` int(11) NOT NULL,
  `ID_AUTORE` int(11) NOT NULL,
  `DURATA` time NOT NULL,
  `ANNO` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `STELLE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `brano`
--

INSERT INTO `brano` (`ID`, `NOME`, `ID_GENERE`, `ID_AUTORE`, `DURATA`, `ANNO`, `STELLE`) VALUES
(1, 'STARGAZING', 2, 4, '00:03:04', '2020-01-17 07:29:24', 5),
(3, 'STARBOY', 4, 2, '00:02:04', '2012-12-11 23:00:00', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `genere`
--

CREATE TABLE `genere` (
  `ID` int(11) NOT NULL,
  `GENERENOME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `genere`
--

INSERT INTO `genere` (`ID`, `GENERENOME`) VALUES
(1, 'RAP'),
(2, 'HIP POP'),
(3, 'EDM'),
(4, 'POP'),
(5, 'CLASSICA'),
(6, 'RAGGETON/LATINA'),
(7, 'R&B'),
(8, 'JAZZ');

-- --------------------------------------------------------

--
-- Struttura della tabella `login`
--

CREATE TABLE `login` (
  `ID` int(11) NOT NULL,
  `NOME` varchar(50) NOT NULL,
  `PASSWORD` varchar(50) NOT NULL,
  `COGNOME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `login`
--

INSERT INTO `login` (`ID`, `NOME`, `PASSWORD`, `COGNOME`) VALUES
(1, 'Octavian', 'Fusari', 'Fusari');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `autori`
--
ALTER TABLE `autori`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `brano`
--
ALTER TABLE `brano`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `genere`
--
ALTER TABLE `genere`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `album`
--
ALTER TABLE `album`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT per la tabella `autori`
--
ALTER TABLE `autori`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `brano`
--
ALTER TABLE `brano`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `genere`
--
ALTER TABLE `genere`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `login`
--
ALTER TABLE `login`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
