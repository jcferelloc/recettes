-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 01 Janvier 2018 à 16:31
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `ape2018`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `ape2018_users` (
  `userID` int(11) NOT NULL,
  `email` varchar(512) COLLATE utf8_bin NOT NULL,
  `prenom_1` varchar(128) COLLATE utf8_bin NOT NULL,
  `nom_1` varchar(128) COLLATE utf8_bin NOT NULL,
  `classe_1` int(11) NOT NULL,
  `date_1` date NOT NULL,
  `login` varchar(30) COLLATE utf8_bin NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `users`
--

INSERT INTO `ape2018_users` (`userID`, `email`, `prenom_1`, `nom_1`, `classe_1`, `date_1`, `login`, `admin`) VALUES
(1, '', 'RAPHAEL', 'CARADEC', 7, '2014-02-23', 'rcaradec', 0),
(2, '', 'ELIAZ', 'HERVE', 7, '2014-01-06', 'eherve', 0),
(3, '', 'EMY', 'LEMARECHAL', 7, '2014-03-02', 'elemarechal', 0),
(4, '', 'KLERVI', 'MASSON', 7, '2014-01-04', 'kmasson', 0),
(5, '', 'CAMILLE', 'MICHELET', 7, '2014-01-09', 'cmichelet', 0),
(6, '', 'ALBAN', 'ROUDAUT', 7, '2014-08-10', 'aroudaut', 0),
(7, '', 'TIMOTHE', 'APPERE', 8, '2015-03-21', 'tappere', 0),
(8, '', 'ELOĎSE', 'GOASDUFF', 8, '2015-03-01', 'egoasduff', 0),
(9, '', 'ALLAN', 'RENAUX', 8, '2015-02-03', 'arenaux', 0),
(10, '', 'ZOE', 'GARZUEL', 5, '2012-06-26', 'zgarzuel', 0),
(11, '', 'TIMEO', 'KERUZORE', 5, '2012-08-09', 'tkeruzore', 0),
(12, '', 'DJEMILA', 'TUFALE DIT HALATAU', 5, '2012-02-13', 'dtufaledithalatau', 0),
(13, '', 'MATTEO', 'CALVEZ', 6, '2013-06-05', 'mcalvez', 0),
(14, '', 'EDEN', 'CORNEN', 6, '2013-10-18', 'ecornen', 0),
(15, '', 'MARTIN', 'GUEVEL', 6, '2013-11-28', 'mguevel', 0),
(16, '', 'AMANDINE', 'LE PAGE', 6, '2013-09-30', 'alepage', 0),
(17, '', 'ANTOINE', 'MAHO', 6, '2013-10-20', 'amaho', 0),
(18, '', 'CASSANDRE', 'WATTRELOT', 6, '2013-04-06', 'cwattrelot', 0),
(19, '', 'NOAH', 'CHITRE', 5, '2012-01-03', 'nchitre', 0),
(20, '', 'NOE', 'FEREC', 5, '2012-11-01', 'nferec', 0),
(21, '', 'MANO', 'LELEU', 5, '2012-05-29', 'mleleu', 0),
(22, '', 'YNESS', 'ABALLEA', 4, '2011-12-11', 'yaballea', 0),
(23, '', 'LEO', 'COZIC', 4, '2011-06-25', 'lcozic', 0),
(24, '', 'ETHAN', 'FAUCHER', 4, '2011-06-23', 'efaucher', 0),
(25, '', 'CELIA', 'GOURMELON', 4, '2011-09-08', 'cgourmelon', 0),
(26, '', 'AKSEL', 'GRIMAUD', 4, '2011-03-16', 'agrimaud', 0),
(27, '', 'ETHAN', 'JANSSENS', 4, '2011-03-22', 'ejanssens', 0),
(28, '', 'ELYA', 'LE ROUX', 4, '2011-04-25', 'eleroux', 0),
(29, '', 'LILOU', 'LEOST', 4, '2011-01-08', 'lleost', 0),
(30, '', 'LOEVAN', 'LESAGE', 4, '2011-10-07', 'llesage', 0),
(31, '', 'ALISIA', 'LUCATI', 4, '2011-01-15', 'alucati', 0),
(32, '', 'LUCIE', 'RANNOU', 4, '2011-10-18', 'lrannou', 0),
(33, '', 'NINON', 'ROUARCH', 4, '2011-07-28', 'nrouarch', 0),
(34, '', 'JANELLE', 'ROUDAUT', 4, '2011-01-07', 'jroudaut', 0),
(35, '', 'THEO', 'BLONDEL', 3, '2010-11-02', 'tblondel', 0),
(36, '', 'THOMAS', 'CABON', 3, '2010-06-11', 'tcabon', 0),
(37, '', 'THEO', 'CREIGNOU', 3, '2010-06-13', 'tcreignou', 0),
(38, '', 'SUZANNE', 'FERELLOC', 3, '2010-06-19', 'sferelloc', 2),
(39, '', 'ARTHUR', 'GALEA', 3, '2010-08-22', 'agalea', 0),
(40, '', 'LYAM', 'HERVE', 3, '2010-12-22', 'lherve', 0),
(41, '', 'LENNY', 'LE FLOCH', 3, '2010-01-15', 'llefloch', 0),
(42, '', 'ENZO', 'LE HER', 3, '2010-12-28', 'eleher', 0),
(43, '', 'GASPARD', 'LEDEME-FABRY', 3, '2010-03-13', 'gledeme-fabry', 0),
(44, '', 'SARAH', 'LIBERGE', 3, '2010-05-11', 'sliberge', 0),
(45, '', 'CELIA', 'MAZE', 3, '2010-11-10', 'cmaze', 0),
(46, '', 'LENNY', 'OMNES', 3, '2010-07-18', 'lomnes', 0),
(47, '', 'MAROT', 'PANNIER', 3, '2010-01-12', 'mpannier', 0),
(48, '', 'LANA', 'PARENTHOEN', 3, '2010-02-02', 'lparenthoen', 0),
(49, '', 'LOUKA', 'PIRIOU', 3, '2010-06-26', 'lpiriou', 0),
(50, '', 'NOLAN', 'PRONOST', 3, '2010-06-03', 'npronost', 0),
(51, '', 'LALY', 'BODIVIT', 2, '2009-01-18', 'lbodivit', 0),
(52, '', 'LOLA', 'CREFF', 2, '2009-06-03', 'lcreff', 0),
(53, '', 'DAMIEN', 'LEON', 2, '2009-09-29', 'dleon', 0),
(54, '', 'LUNA', 'PERON', 2, '2009-03-05', 'lperon', 0),
(55, '', 'ALBAN', 'AZOU', 2, '2009-09-15', 'aazou', 0),
(56, '', 'NOLAN', 'BOTINO', 2, '2009-08-19', 'nbotino', 0),
(57, '', 'LEONIE', 'CABEL', 2, '2009-02-06', 'lcabel', 0),
(58, '', 'ELOUAN', 'CREAC''H', 2, '2009-09-23', 'ecreac''h', 0),
(59, '', 'HUGO', 'DENIEL', 2, '2008-08-25', 'hdeniel', 0),
(60, '', 'OWEN', 'LE LANN', 2, '2009-05-07', 'olelann', 0),
(61, '', 'VALERIAN', 'QUIVIGER', 2, '2009-01-15', 'vquiviger', 0),
(62, '', 'DORIAN', 'ROUDAUT', 2, '2009-11-24', 'droudaut', 0),
(63, '', 'EMMA', 'BODENNEC', 1, '2008-06-19', 'ebodennec', 0),
(64, '', 'LUKA', 'CHATELIN', 1, '2008-03-14', 'lchatelin', 0),
(65, '', 'ALYSSA', 'COCHET', 1, '2008-05-16', 'acochet', 0),
(66, '', 'MAIWENN', 'DESHAIES', 1, '2008-03-06', 'mdeshaies', 0),
(67, '', 'FLORYS', 'LARGENTON', 1, '2008-06-26', 'flargenton', 0),
(68, '', 'AXEL', 'LE GALL', 1, '2008-12-11', 'alegall', 0),
(69, '', 'EMERICK', 'LE GUERN', 1, '2008-05-25', 'eleguern', 0),
(70, '', 'KYRIO', 'LEOST', 1, '2008-05-12', 'kleost', 0),
(71, '', 'EWEN', 'LUCHIER', 1, '2008-07-02', 'eluchier', 0),
(72, '', 'CHARLINE', 'QUILLIEN', 1, '2008-07-28', 'cquillien', 0),
(73, '', 'YLAN', 'CHESNOT', 1, '2008-08-14', 'ychesnot', 0),
(74, '', 'DYLAN', 'FRIEDMANN', 1, '2008-06-24', 'dfriedmann', 0),
(75, '', 'INDIRA', 'GOURLAOUEN', 1, '2008-02-28', 'igourlaouen', 0),
(76, '', 'LUCAS', 'GRYSOLE-ALLAOUSSE', 1, '2008-02-29', 'lgrysole-allaousse', 0),
(77, '', 'TAINA', 'LE ROUX', 1, '2008-12-29', 'tleroux', 0),
(78, '', 'MATHIEU', 'MAISEL', 1, '2008-02-14', 'mmaisel', 0),
(79, '', 'KENZO', 'AUTRET', 0, '2007-06-28', 'kautret', 0),
(80, '', 'EWENN', 'BOURDIER ', 0, '2007-01-09', 'ebourdier', 0),
(81, '', 'SELMA', 'DEVINEAU', 0, '2007-11-20', 'sdevineau', 0),
(82, '', 'STACY', 'GUEGUEN', 0, '2007-01-27', 'sgueguen', 0),
(83, '', 'ERWIN', 'GUEVEL-GOASDOUE', 0, '2007-09-14', 'eguevel-goasdoue', 0),
(84, '', 'YANIS', 'HABASQUE', 0, '2007-03-27', 'yhabasque', 0),
(85, '', 'ELSA', 'JEFFROY', 0, '2007-06-21', 'ejeffroy', 0),
(86, '', 'MARINE', 'LAURANS', 0, '2007-10-02', 'mlaurans', 0),
(87, '', 'KELYSSA', 'LE FOURN', 0, '2008-05-30', 'klefourn', 0),
(88, '', 'MAEVA', 'LE ROUX', 0, '2007-08-17', 'mleroux', 0),
(89, '', 'NATHAN', 'LOAEC', 0, '2007-10-10', 'nloaec', 0),
(90, '', 'ELOISE', 'MECHIN', 0, '2007-04-18', 'emechin', 0),
(91, '', 'KENAN', 'MESCOFF', 0, '2007-09-21', 'kmescoff', 0),
(92, '', 'PROSPER', 'NZAKOU', 0, '2007-11-27', 'pnzakou', 0),
(93, '', 'LOLA', 'PRIGENT', 0, '2007-01-25', 'lprigent', 0),
(94, '', 'LEANA', 'QUINTERO', 0, '2007-10-05', 'lquintero', 0),
(95, '', 'ERWANN', 'SALAUN', 0, '2007-11-26', 'esalaun', 0),
(96, '', 'SARAH', 'WEYH', 0, '2006-05-18', 'sweyh', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
