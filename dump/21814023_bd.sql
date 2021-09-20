-- phpMyAdmin SQL Dump
-- version 4.6.6deb4+deb9u1
-- https://www.phpmyadmin.net/
--
-- Client :  mysql.info.unicaen.fr:3306
-- Généré le :  Lun 07 Décembre 2020 à 22:27
-- Version du serveur :  10.1.44-MariaDB-0+deb9u1
-- Version de PHP :  7.0.33-0+deb9u10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `21814023_bd`
--

-- --------------------------------------------------------

--
-- Structure de la table `album`
--

CREATE TABLE `album` (
  `id_album` int(11) NOT NULL,
  `artist` varchar(255) DEFAULT NULL,
  `discname` varchar(255) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `releaseYear` int(4) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `album`
--

INSERT INTO `album` (`id_album`, `artist`, `discname`, `cover`, `releaseYear`, `label`, `user_id`) VALUES
(1, 'GIMS', 'Ceinture Noire', 'resources/album_thumb/resize-pic/ceinture_noire.png', 2018, 'Chahawat, Play Two, TF1 Sony', 3),
(2, 'Booba', '0.9', 'resources/album_thumb/resize-pic/09.jpg', 2008, 'Tallac, Universal, Barclay', 3),
(3, 'Garou', 'Seul', 'resources/album_thumb/resize-pic/seul.png', 2000, 'Columbia', 1),
(4, 'Niska', 'Commando', 'resources/album_thumb/resize-pic/commando.jpg', 2017, 'Universal, Capitole, Millénium', 3),
(5, 'Aya Nakamura', 'AYA', 'resources/album_thumb/resize-pic/https _images.genius.com_17e63acb421f33b2158ba710ec2079e8.1000x1000x1.png', 2020, 'Rec. 118, Parlophone Warner Music France', 8),
(6, 'Ninho', 'Destin', 'resources/album_thumb/resize-pic/destin.jpg', 2019, 'Mal Luné Music, Parlophone, Rec. 118', 2),
(7, 'Jul', 'Rien 100 Rien', 'resources/album_thumb/resize-pic/jul_rien100rien.jpg', 2019, 'D\'or et de platine', 1),
(8, 'Vald', 'Agartha', 'resources/album_thumb/resize-pic/agartha.jpg', 2017, 'Mezoued Records & Suther Kane Films, Millenium, Capitol, Universal', 4),
(9, 'Damso', 'Ipséité', 'resources/album_thumb/resize-pic/ipseité.jpg', 2017, ' 92i Records, Capitol, Universal', 3),
(10, 'Green Montana', 'Alaska', 'resources/album_thumb/resize-pic/alaska.png', 2020, '92i Records, Capitol, Universal', 4),
(11, 'Siboy', 'Spécial', 'resources/album_thumb/resize-pic/special.jpg', 2017, '92i Records, Capitol, Universal', 4),
(12, 'P-Square', 'The Invasion', 'resources/album_thumb/resize-pic/double_trouble.png', 2011, 'Square Records', 5),
(13, 'Rick Ross', 'Port of Miami', 'resources/album_thumb/resize-pic/port_of_miami.png', 2006, ' Slip-N-Slide Records, Def Jam', 7),
(14, 'Jay-Z', 'Magna Carta Holy Grail', 'resources/album_thumb/resize-pic/jay-z-magna-carta-holy-grail.jpg', 2013, 'Roc-A-Fella, Roc Nation, Universal', 3),
(15, 'Tayc', 'NYXIA Tome III', 'resources/album_thumb/resize-pic/nyxia3.png', 2019, 'H24 Music, Universal', 6),
(16, 'Burna Boy', 'African Giant', 'resources/album_thumb/resize-pic/Burna_Boy_-_African_Giant.jpg', 2019, '', 2),
(17, 'Shay', 'Jolie Garce', 'resources/album_thumb/resize-pic/jolie_garce.jpg', 2016, '92i, Capitol, Universal', 6),
(18, 'Johnny Hallyday', 'Ma vérité', 'resources/album_thumb/resize-pic/ma_verite.jpg', 2005, 'Mercury France, Universal', 2),
(19, 'Michael Jackson', 'Invincible', 'resources/album_thumb/resize-pic/invincible.png', 2001, 'Epic Records', 1),
(20, 'Kaaris', 'Okou Gnakouri', 'resources/album_thumb/resize-pic/4d7c3232803b1646361daf984ef5d7fa.1000x1000x1.jpg', 2016, 'Def Jam France, Universal', 3),
(21, 'Gradur', 'ShegueyVara 2', 'resources/album_thumb/resize-pic/71UuT2GiDkL._SL1400_.jpg', 2015, 'Millenium Barclay, Universal', 4),
(22, 'Zaho', 'Dima', 'resources/album_thumb/resize-pic/zaho_dima.jpg', 2008, 'Capitol France, EMI France', 2),
(23, 'Fally Ipupa', 'Droit Chemin', 'resources/album_thumb/resize-pic/droit_chemin.jpg', 2006, 'Obouo Music, Because Music', 5),
(24, 'Djé', 'Traffic', 'resources/album_thumb/resize-pic/traffic.jpg', 2015, '', 6),
(25, 'DJ Arafat', 'Renaissance', 'resources/album_thumb/resize-pic/renaissance.jpg', 2018, 'Universal Music Africa', 1),
(26, 'GIMS', 'Mon cœur avait raison', 'resources/album_thumb/resize-pic/mcar_maitre_gims1.jpg', 2015, 'Wati B, Jive Epic, Sony Music', 1),
(27, 'Booba', 'Futur', 'resources/album_thumb/resize-pic/booba-futur.jpg', 2012, 'Tallac, AZ, Universal', 2),
(28, 'PNL', 'Dans la légende', 'resources/album_thumb/resize-pic/61qSKECqrKL._SL1440_.jpg', 2016, 'QLF Records', 3),
(29, 'Dosseh', 'Vidalo$$a', 'resources/album_thumb/resize-pic/vidalossa.jpg', 2018, 'Golden Eye Music, Capitol, Universal', 4),
(30, 'Dadju', 'Poison ou Antidote (Edition Miel Book)', 'resources/album_thumb/resize-pic/910D7OVAfNL._SL1500_.jpg', 2020, 'Amaterasu Prod', 2),
(31, 'Kaaris', 'Or Noir', 'resources/album_thumb/resize-pic/or_noir.jpg', 2013, 'Universal, Capitol, AZ, Therapy Music', 3),
(32, 'Tayc', 'Fleur Froide', 'resources/album_thumb/resize-pic/tayc_ff.jpg', 2020, 'H24 Music, Universal', 6);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `pseudo` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `pseudo`, `password`, `status`) VALUES
(1, 'VANIER', 'Pascal', 'vanier', '$2y$10$JXK2Mf7Br6IDisX1YUXKfuVaExJb0IcPZ/uvWRDfYz7CgWebQxVNi', 'user'),
(2, 'LECARPENTIER', 'Jean Marc', 'lecarpentier', '$2y$10$JXK2Mf7Br6IDisX1YUXKfuVaExJb0IcPZ/uvWRDfYz7CgWebQxVNi', 'user'),
(3, 'VOUVOU', 'Brandon', 'brandon242', '$2y$10$J3rGzD.fjHJl8dx914WWQ.5P9h0msOjnLPTBU0Y.HnqaHopkcWzEO', 'admin'),
(4, 'KEITA', 'Lansana', 'lanskei', '$2y$10$Uy5j7EeYvTBR0R3Q0eslv.TCzZS9f69MoPJMc9XKgRSR2ZygQ4rx2', 'admin'),
(5, 'OGOUWOLE', 'Derrick', 'deathnote', '$2y$10$zgchynqEN.coEYRdm3dB5uzJCevpY6exjzgC0TUUROZvKFNoEnJNG', 'admin'),
(6, 'TOUBAKILA', 'Naslie', 'nanaJoress', '$2y$10$OFSDIlrU/xOK3U8HqgT/OuS7rtMgDsGm5EPkMpuYcEtWHnCT6fj/6', 'admin'),
(7, 'VANIER', 'Pascal', 'vanier1', '$2y$10$JXK2Mf7Br6IDisX1YUXKfuVaExJb0IcPZ/uvWRDfYz7CgWebQxVNi', 'admin'),
(8, 'LECARPENTIER', 'Jean Marc', 'lecarpentier1', '$2y$10$JXK2Mf7Br6IDisX1YUXKfuVaExJb0IcPZ/uvWRDfYz7CgWebQxVNi', 'admin');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`id_album`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `album`
--
ALTER TABLE `album`
  MODIFY `id_album` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
