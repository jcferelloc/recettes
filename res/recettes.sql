-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 12 Décembre 2017 à 23:32
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
-- Structure de la table `recettes`
--

CREATE TABLE IF NOT EXISTS `recettes` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `nom` varchar(256) COLLATE utf8_bin NOT NULL,
  `categorie` int(11) NOT NULL,
  `titre` varchar(256) COLLATE utf8_bin NOT NULL,
  `presentation` text COLLATE utf8_bin NOT NULL,
  `ingredients` text COLLATE utf8_bin NOT NULL,
  `preparation` text COLLATE utf8_bin NOT NULL,
  `indications` text COLLATE utf8_bin NOT NULL,
  `url_plat` varchar(512) COLLATE utf8_bin NOT NULL,
  `url_enfant` varchar(512) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `recettes`
--

INSERT INTO `recettes` (`id`, `userID`, `nom`, `categorie`, `titre`, `presentation`, `ingredients`, `preparation`, `indications`, `url_plat`, `url_enfant`) VALUES
(1, 1, 'Emilie Le Gall', 1, 'La cocotte au four de mamy', 'C''est une recette toute simple, pas clinquante, mais avec une pièce de viande de qualité et des pommes de terre adéquates pour le four, c''est un vrai délice : la viande, les pommes de terre et les oignons sont bien dorés, à la fois fondants et croustillants... Hum !!!', '- Un rôti de porc avec os, dans l''échine\r\n- 3 carottes\r\n- 1 bel oignon\r\n- 5 gousses d''ail\r\n- 4-5 feuilles de laurier\r\n- 8-10 pommes de terre\r\n- sel, poivre\r\n', 'Epluchez les pommes de terre, rincez-les sous l''eau courante, essuyez-les, coupez les en quartiers. Epluchez les carottes,\r\nrincez les, séchez les, coupez les en rondelles. Epluchez l''oignon, détaillez le (en lanières pas trop petites).\r\nPréchauffer le four à 210° chaleur tournante.\r\nAvant de placer la viande dans le plat, versez un peu d''huile dans le fond de celui-ci. Rajoutez autour de la viande les pommes de terre, les carottes et l''oignon. Mélangez ces 3 ingrédients pour bien les répartir.\r\nRajouter l''ail en chemise et les feuilles de laurier. Badigeonner le tout d''un peu d''huile d''olive et insérer                 quelques beaux morceaux de beurre dans la garniture et sur la viande. Salez, poivrez et rajouter un fond d''eau dans le plat, environ 1cm.\r\nEnfournez (le four n''a pas encore atteint la température choisie) pour 1h15/30 de cuisson et retournez la Viande en cours de cuisson pour bien la faire dorer.', 'Pour 4 personnes, 20 à 25 minutes minutes (cuisson : 1h15 - 1h30 pour 750 g de viande)', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
