-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: Michele
-- Creato il: Giu 16, 2022 alle 23:48
-- Versione del server: 10.4.22-MariaDB
-- Versione PHP: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Struttura della tabella `articolo`
--

DROP TABLE IF EXISTS `articolo`;
CREATE TABLE `articolo` (
  `titolo` varchar(50) NOT NULL,
  `id` int(11) NOT NULL,
  `corpo` longtext NOT NULL,
  `data_inserimento` datetime NOT NULL,
  `percorso_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `articolo`
--

INSERT INTO `articolo` (`titolo`, `id`, `corpo`, `data_inserimento`, `percorso_img`) VALUES
('Ufficiale! Arriva la saetta. Filè nuovo giocatore', 1, 'Nella giornata di oggi la società è lieta di annunciare il nuovo acquisto del giocatore Gilberto Filè come nuovo componente della squadra A.C.Torrearchimede, il quale resterà legato ai nostri colori con un contratto di 3 anni +2 aventuali di bonus in base ad un minimo numero di presenze. Il giovane esterno del 2000, arrivato a Padova nella mattinata per le visite mediche di routine, ha atteso i risultati per poi procedere con la revisione e la firma del contratto. Lunedì ci sarà una cerimonia di presentazione presso il centro sportivo alle ore 16:30 per permettere al nuovo giocatore di salutare i tifosi.', '2022-05-01 15:00:00', 'news/1807239.jpg'),
('Serata magica', 2, 'Finisce 6-0 la partita amichevole di domenica contro il Piovego, grande prova di forza da parte della squadra che vuole dare una scossa al campionato e ribadire la prova superiorità sul piano del gioco contro gli avversari, già sconfitti all\'andata per 3-1. Doppietta per il nostro centravanti Bossetti, che si riconferma top-scorer del campionato, e altra prova difensiva eccellente, con i nostri centrali che a più riprese sono riusciti a fermare il reparto offensivo avversario. In conferenza stampa il mister ha voluto far sapere che è molto orgaglioso dei suoi e ha voluto rassicurare i tifosi affermando che non ci saranno problemi nel firmare il tanto discusso rinnovo nei prossimi giorni.', '2022-05-07 13:00:00', 'news/vittoria.jpg'),
('Nicola Berti eterno campione', 3, 'Nicola Berti ha indossato la maglia azzurra per 10 anni, dal 1988 al 1998, ha illuminato con le sue giocate e trascinato i tifosi con la sua spiccata personalità e la capacità di incarnare lo spirito padovano. 311 le presenze in campo e 41 i gol segnati, un record nella storia della squadra. Oggi Nicola festeggia 55 anni e riceve gli auguri del Club e di tutti i tifosi sparsi per il mondo.', '2022-05-10 12:00:00', 'news/intervista.jpg'),
('Biglietti A.C.Torrearchimede - Piovego disponibili', 4, 'Sono ora disponibili i biglietti per lo scontro al vertice tra la nostra squadra e i biancorossi del Piovego, che si disputerà il 28 maggio alle 18, nel nostro stadio Torre Archimede. Partita il cui risultato potrebbe già essere decisivo per le sorti del campionato. La squadra è pronta a dare tutto in campo, per il resto siamo fiduciosi di poter contare sul solito grande supporto che solo i tifosi dell\'A.C.Torrearchimede sapranno darci. Vi aspettiamo.', '2022-05-15 10:00:00', 'news/s-l500.jpg'),
('Summer Camp 2022', 5, 'L\'A.C. Torre Archimede organizza nel mese di giugno il Summer Camp per ragazzi nati dal 2008 al 2015.\n\nPer tutte le informazioni rivolgersi alla segreteria.', '2022-04-28 18:05:40', 'news/summer.png'),
('Presto l\'album delle figurine di Torre Archimede', 6, 'Torre Archimede aderisce al progetto di Noi Sportivi e presenta l’album delle figurine di tutte le squadre per la stagione 2021-2022. \nPresto disponibili in tutte le edicole di Padova!', '2022-05-13 08:00:00', 'news/album.jpg'),
('Prima vittoria ufficiale contro il Piovego', 7, 'Torre Archimede batte il Piovego in casa 1-0. Un\'ottima partenza per questo campionato. Ha segnato il nuovo arrivato, Filè, grazie a un\'incredibile calcio di punizione al limite dell\'area di rigore.', '2022-05-28 23:00:00', 'news/1651854574.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `biglietto`
--

DROP TABLE IF EXISTS `biglietto`;
CREATE TABLE `biglietto` (
  `id` int(11) NOT NULL,
  `id_posto` smallint(6) NOT NULL,
  `id_partita` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `biglietto`
--

INSERT INTO `biglietto` (`id`, `id_posto`, `id_partita`, `id_utente`, `data`) VALUES
(1, 2, 1, 0, NULL),
(2, 2, 1, 0, NULL),
(3, 4, 10, 0, NULL),
(4, 1, 9, 0, NULL),
(5, 1, 9, 0, NULL),
(6, 1, 9, 0, NULL),
(7, 1, 1, 4, NULL),
(8, 1, 5, 4, NULL),
(9, 1, 5, 4, NULL),
(10, 1, 5, 4, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `partita`
--

DROP TABLE IF EXISTS `partita`;
CREATE TABLE `partita` (
  `id` int(11) NOT NULL,
  `id_host` int(11) NOT NULL,
  `id_guest` int(11) NOT NULL,
  `id_stadio` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `costo1` float NOT NULL,
  `costo2` float NOT NULL,
  `costo3` float NOT NULL,
  `costo4` float NOT NULL,
  `giornata` int(11) NOT NULL,
  `risultato1` int(11) DEFAULT NULL,
  `risultato2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `partita`
--

INSERT INTO `partita` (`id`, `id_host`, `id_guest`, `id_stadio`, `data`, `costo1`, `costo2`, `costo3`, `costo4`, `giornata`, `risultato1`, `risultato2`) VALUES
(1, 1, 2, 0, '2022-05-28 16:00:00', 10, 15, 20, 25, 1, 2, 0),
(2, 3, 4, 1, '2022-05-28 16:00:00', 12, 15, 20, 25, 1, 0, 0),
(3, 5, 6, 2, '2022-05-28 16:00:00', 8, 10, 15, 20, 1, 2, 3),
(4, 1, 4, 2, '2022-06-25 16:00:00', 8, 10, 12, 15, 2, NULL, NULL),
(5, 2, 5, 0, '2022-06-25 16:00:00', 10, 12, 15, 25, 2, NULL, NULL),
(6, 3, 6, 1, '2022-06-25 16:00:00', 11, 15, 20, 18, 2, NULL, NULL),
(7, 1, 3, 2, '2022-07-30 16:00:00', 15, 17, 19, 20, 3, NULL, NULL),
(8, 2, 6, 1, '2022-07-30 16:00:00', 11, 12, 15, 20, 3, NULL, NULL),
(9, 4, 5, 0, '2022-07-30 16:00:00', 10, 15, 17, 20, 3, NULL, NULL),
(10, 1, 5, 0, '2022-08-27 16:00:00', 5, 10, 15, 20, 4, NULL, NULL),
(11, 2, 3, 1, '2022-08-27 16:00:00', 12, 15, 12, 20, 4, NULL, NULL),
(12, 4, 6, 2, '2022-08-27 16:00:00', 10, 15, 20, 25, 4, NULL, NULL),
(13, 1, 6, 0, '2022-09-24 16:00:00', 15, 10, 13, 17, 5, NULL, NULL),
(14, 3, 5, 1, '2022-09-24 16:00:00', 7, 10, 15, 13, 5, NULL, NULL),
(15, 2, 4, 2, '2022-09-24 16:00:00', 10, 15, 23, 20, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `posto`
--

DROP TABLE IF EXISTS `posto`;
CREATE TABLE `posto` (
  `id` int(11) NOT NULL,
  `settore` varchar(15) NOT NULL,
  `max` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `posto`
--

INSERT INTO `posto` (`id`, `settore`, `max`) VALUES
(1, 'Platea', 10),
(2, 'Tribuna', 5),
(3, 'Spalti', 10),
(4, 'Curva', 30);

-- --------------------------------------------------------

--
-- Struttura della tabella `squadra`
--

DROP TABLE IF EXISTS `squadra`;
CREATE TABLE `squadra` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `squadra`
--

INSERT INTO `squadra` (`id`, `nome`) VALUES
(1, 'AC Torre Archimede'),
(2, 'Piovego'),
(3, 'FC Vicenza'),
(4, 'Arcella'),
(5, 'San Paolo'),
(6, 'Chiesanuova');

-- --------------------------------------------------------

--
-- Struttura della tabella `stadio`
--

DROP TABLE IF EXISTS `stadio`;
CREATE TABLE `stadio` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `indirizzo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `stadio`
--

INSERT INTO `stadio` (`id`, `nome`, `indirizzo`) VALUES
(0, 'Torre Archimede', 'Via Trieste 63, 35121 Padova PD\r\n'),
(1, 'Silvio Appiani', ''),
(2, 'Euganeo', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

DROP TABLE IF EXISTS `utente`;
CREATE TABLE `utente` (
  `nazione` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `livello_privilegi` int(11) NOT NULL,
  `indirizzo` varchar(255) NOT NULL,
  `citta` varchar(255) NOT NULL,
  `cap` int(11) NOT NULL,
  `telefono` tinytext NOT NULL,
  `data_di_nascita` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`nazione`, `id`, `nome`, `cognome`, `email`, `password`, `livello_privilegi`, `indirizzo`, `citta`, `cap`, `telefono`, `data_di_nascita`) VALUES
('IT', 0, 'mario', 'rossi', 'utente', '3ce98305181b1bac59d024a49b0ffd73', 0, 'via Roma 1', 'Venezia', 34123, '+39 345 7412589', '2000-04-02'),
('IT', 1, 'Giancarlo', 'Mariello', 'GianMar@gmail.com', '4cadf1eb5ae9f748ae2b7518908ef46d', 0, 'via Belughi 22', 'Padova', 35132, '3451234567', '1999-09-09'),
('NZ', 2, 'Dwayne Douglas', 'Johnson', 'TheRock@gmail.com', '160da7df7aaf9075930d6910ede09b0a', 0, 'via Potente 11', 'Padova', 35145, '3661478523', '1972-05-02'),
('WWW', 4, 'admin', 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'via Dall\'Amministratore 404', 'Root', 8080, '192168001001', '1970-01-01'),
('IT', 41, 'Ombretta', 'Gaggi', 'gaggi@archimede.it', 'df1d3e153da7c0372c33daf890d7d7a7', 1, 'via Trieste 63', 'Padova', 35131, '0498123456', '1975-10-10');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `articolo`
--
ALTER TABLE `articolo`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `biglietto`
--
ALTER TABLE `biglietto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posto` (`id_posto`),
  ADD KEY `id_partita` (`id_partita`),
  ADD KEY `id_partita_2` (`id_partita`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `partita`
--
ALTER TABLE `partita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stadio` (`id_stadio`),
  ADD KEY `squadra_avversaria` (`id_guest`),
  ADD KEY `squadra` (`id_host`);

--
-- Indici per le tabelle `posto`
--
ALTER TABLE `posto`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `squadra`
--
ALTER TABLE `squadra`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `stadio`
--
ALTER TABLE `stadio`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `articolo`
--
ALTER TABLE `articolo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `biglietto`
--
ALTER TABLE `biglietto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `partita`
--
ALTER TABLE `partita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `posto`
--
ALTER TABLE `posto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT per la tabella `squadra`
--
ALTER TABLE `squadra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `stadio`
--
ALTER TABLE `stadio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
