-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-gestiondesmateriel.alwaysdata.net
-- Generation Time: Jun 02, 2025 at 10:59 PM
-- Server version: 10.11.11-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestiondesmateriel_bdd`
--
CREATE DATABASE IF NOT EXISTS `gestiondesmateriel_bdd` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gestiondesmateriel_bdd`;

-- --------------------------------------------------------

--
-- Table structure for table `Administration`
--

CREATE TABLE `Administration` (
  `Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Administration`
--

INSERT INTO `Administration` (`Id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `Agent`
--

CREATE TABLE `Agent` (
  `Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Agent`
--

INSERT INTO `Agent` (`Id`) VALUES
(4);

-- --------------------------------------------------------

--
-- Table structure for table `Commentaire`
--

CREATE TABLE `Commentaire` (
  `Id_commentaire` int(11) NOT NULL,
  `texte_com` text DEFAULT NULL,
  `date_com` date DEFAULT NULL,
  `Id_etudiant` int(11) DEFAULT NULL,
  `Id_prof` int(11) DEFAULT NULL,
  `Id_reservation` int(11) DEFAULT NULL,
  `Id_salle` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Etudiant`
--

CREATE TABLE `Etudiant` (
  `Id` int(11) NOT NULL,
  `annee` varchar(50) DEFAULT NULL,
  `groupe_td` varchar(50) DEFAULT NULL,
  `groupe_tp` varchar(50) DEFAULT NULL,
  `promotion` varchar(50) DEFAULT NULL,
  `numero_carte` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Etudiant`
--

INSERT INTO `Etudiant` (`Id`, `annee`, `groupe_td`, `groupe_tp`, `promotion`, `numero_carte`) VALUES
(2, '1ère année', 'TD3', 'TPE', 'BUT MMI', 285690),
(7, '1ère année', 'TD3', 'TPE', 'BUT MMI', 287063),
(21, '1ère année', 'TD3', 'TPF', 'BUT MMI', 288888),
(28, '1ère année', 'TD3', 'TPF', 'BUT MMI', 289568),
(33, '1ère année', 'TD3', 'TPF', 'BUT MMI', 285774),
(34, NULL, NULL, NULL, NULL, 269271),
(36, NULL, NULL, NULL, NULL, 12345),
(37, NULL, NULL, NULL, NULL, 123456),
(38, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `attempt_time` datetime DEFAULT NULL,
  `is_blocked` tinyint(1) DEFAULT 0,
  `block_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `email`, `ip_address`, `attempt_time`, `is_blocked`, `block_expires`) VALUES
(67, 'aaaa@aaa', '2001:861:38c3:6970:2c1d:a220:ac22:658b', '2025-06-01 20:32:57', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Materiel`
--

CREATE TABLE `Materiel` (
  `Id_materiel` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type_materiel` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Materiel`
--

INSERT INTO `Materiel` (`Id_materiel`, `nom`, `description`, `quantite`, `image`, `type_materiel`) VALUES
(7, 'Go Pro ', 'Go Pro portative ', 14, 'images/materiels/682f55dc083df.jpg', 'audiovisuel'),
(10, 'Micro Hyper X', 'Micro Hyper X pour bureau ', 15, 'images/materiels/682f55e30d26b.jpg', 'audiovisuel'),
(17, 'Casque Gaming', 'Casque Gaming avec microphone ajustable', 7, 'images/materiels/682f55ce9b4b0.jpg', 'audiovisuel'),
(18, 'Tablette graphique-Wacom', 'Tablette graphique Wacom avec son stylet ', 5, 'images/materiels/682f5693e8dfe.jpg', 'multimedia'),
(19, 'Webcam Logitech Brio Ultra HD 4K', 'Logitech Brio Webcam Ultra HD 4K pour vidéoconférence, Enregistrement et Streaming', 10, 'images/materiels/682f573ddda09.jpg', 'audiovisuel'),
(20, 'Manette de Jeu MSI FORCE GC30 V2', 'Manette de Jeu Gamepad PC Sans Fil- 2.4 GHz, Batterie Li-ion 600 mAh, Croix Directionnelle Interchangeable, Double Moteur de Vibration, USB 2.0 - Filaire/Sans Fil', 6, 'images/materiels/682f57a4de785.jpg', 'multimedia'),
(21, 'chaise blanche Jarvisone ', 'chaise intelligente ', 10, 'images/materiels/6833226710854.png', 'informatique'),
(22, 'chaise noir Jarvisone ', 'chaise intelligente ', 10, 'images/materiels/683322b74f628.png', 'informatique');

-- --------------------------------------------------------

--
-- Table structure for table `Personne`
--

CREATE TABLE `Personne` (
  `Id_user` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Personne`
--

INSERT INTO `Personne` (`Id_user`, `nom`, `prenom`, `email`, `telephone`, `date_naissance`, `mot_de_passe`, `photo`) VALUES
(1, 'Admin', 'Admin', 'Tatusae2013@gmail.com', NULL, NULL, '$2y$10$lXfssegidxbbt/CCgUqhLeqnxVXFqdzlNgpHoEMghSz4IbLThijyS', NULL),
(2, 'DA COSTA', 'Timéo', 'dacostatimeo@gmail.com', '0769695895', '2006-07-17', '$2y$10$ZWgESMVAwgDNAkRB.R8sA.oZqyHUWx5y6xVY2Ci.PrsjErwqObeYm', NULL),
(4, 'cailloux', 'dexter', 'cailloux@gmail.com', '', '2017-06-22', '$2y$10$Iewzq9Ey7ryEsjbCxh568uR08PLcQiX908.r1nlJbj2Y2tN0sTPeG', NULL),
(6, 'Sigalas', 'Clément', 'clement.sigalas@univ-eiffel.fr', '', NULL, '$2y$10$Iyy9rJLUBkFihmqHf.QTgeDBV/9LpFOXj6JRWqTlQL4xRS4slxPUq', NULL),
(7, 'sigalas', 'thomas', 'tom97177@gmail.com', '', '2005-02-18', '$2y$10$3ODmhJWlifyoYqP/6PAHS.pw6D2N6.LJPipb17e1BF86u80fnr.Vi', NULL),
(21, 'Drame', 'Ibrahim', 'ibrahimdrame165@gmail.com', '', '2005-05-16', '$2y$10$/VdfIofk83C8jgxlQcgxvukilaa1m5dIb1LSK5asg9uBKckekJK7i', NULL),
(27, 'TIR', 'Fouad', 'tirfouad@gmail.com', NULL, NULL, '$2y$10$67/fW.tYzb7kkUp3ZNo3PevpPGB/MQR99JdItf7Nwb8nQ0/ga7Yi2', NULL),
(28, 'FAHEM', 'Hajar', 'hajarhajf@gmail.com', '2754524222', '2006-12-06', '$2y$10$cn1gWH7HRqM3my4CZx7mO.iE8JraV1ugHZUKofCx0DIWJ0duJcDJW', NULL),
(32, 'laroussi', 'reda', 'reda.laroussi@univ-eiffel.fr', '', NULL, '$2y$10$Sab7y4EABDBhhNvq8uTRV.gEbEpOuc24W/MsNkb.rga96Qe/.CUki', NULL),
(33, 'Choudjay', 'Dylan', 'dydy771269@icloud.com', '', '2006-04-25', '$2y$10$QJ9kbrpigKvc54JHLDbJt.OlyB9otPXxGNxwdzBmxpGsyElLFuJD.', NULL),
(34, 'Kergastel', 'Témi', 'temi.kergastel@gmail.com', NULL, '2004-11-07', '$2y$10$bXj.SkHLDRFr9reKucPr0uJxL6XI6QVRqGLTGVd2lmD.mXrLQeXy6', NULL),
(36, 'BACCOUCH', 'Chokri', 'chokri.baccouch@univ-eiffel.fr', NULL, '2000-07-13', '$2y$10$wfIp6iuMBQZ27.Jj/RDOmuCcZm5uJQ/YVzP9I/jxQpVXauXD384Hi', NULL),
(37, 'savourin', 'thomas', 'thomas.savourin@gmail.com', NULL, '2005-02-05', '$2y$10$q46/A8Ez67SIO0c6LWx.2Oii02.kGnE92vPToFCdl8KMYDHFpwjRq', NULL),
(38, 'aaa', 'aaa', 'aaaa@aaa', NULL, '2025-05-31', '$2y$10$nIEwXxiDmG5ZaLgPr8fjy.AE3woh1c/z3hKREfu.lG9Erp/gKZ43W', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Professeur`
--

CREATE TABLE `Professeur` (
  `Id` int(11) NOT NULL,
  `date_embauche` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Professeur`
--

INSERT INTO `Professeur` (`Id`, `date_embauche`) VALUES
(6, '2011-08-23'),
(27, '2006-07-17'),
(32, '2000-09-25');

-- --------------------------------------------------------

--
-- Table structure for table `Reservation`
--

CREATE TABLE `Reservation` (
  `Id_reservation` int(11) NOT NULL,
  `type_user` varchar(50) DEFAULT NULL,
  `Id_salle` int(11) DEFAULT NULL,
  `Id_materiel` int(11) DEFAULT NULL,
  `date_reservation` date DEFAULT NULL,
  `creneau` varchar(50) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `signature_admin` text DEFAULT NULL,
  `date_verification` date DEFAULT NULL,
  `reservation_materiel` text DEFAULT NULL,
  `reservation_salle` text DEFAULT NULL,
  `historique_reservation` text DEFAULT NULL,
  `modif_reservation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Reservation`
--

INSERT INTO `Reservation` (`Id_reservation`, `type_user`, `Id_salle`, `Id_materiel`, `date_reservation`, `creneau`, `statut`, `signature_admin`, `date_verification`, `reservation_materiel`, `reservation_salle`, `historique_reservation`, `modif_reservation`) VALUES
(15, 'dacostatimeo@gmail.com', NULL, 7, '2025-05-23', '08:30 - 11:30', 'Refusée', NULL, NULL, 'test', NULL, NULL, NULL),
(16, 'axoupsg75@gmail.com', NULL, 7, '2025-05-27', '09:00 - 16:00', 'Acceptée', NULL, NULL, 'test', NULL, NULL, NULL),
(18, 'axoupsg75@gmail.com', 9, NULL, '2025-05-23', '09:00 - 14:30', 'Refusée', NULL, NULL, NULL, 'tedst', NULL, NULL),
(19, 'dacostatimeo@gmail.com', 9, NULL, '2025-05-24', '08:30 - 11:00', 'Acceptée', NULL, NULL, NULL, 'test', NULL, NULL),
(20, 'hajarhajf@gmail.com', NULL, 17, '2025-05-24', '08:30 - 14:30', 'Acceptée', NULL, NULL, 'test', NULL, NULL, NULL),
(21, 'dacostatimeo@gmail.com', NULL, 7, '2025-05-24', '08:30 - 15:00', 'Acceptée', NULL, NULL, 'test', NULL, NULL, NULL),
(22, 'dacostatimeo@gmail.com', 8, NULL, '2025-05-24', '08:30 - 14:00', 'Acceptée', NULL, NULL, NULL, 'test', NULL, NULL),
(23, 'axoupsg75@gmail.com', NULL, 7, '2025-05-27', '08:30 - 16:30', 'Acceptée', NULL, NULL, 'test', NULL, NULL, NULL),
(24, 'thomassavourin@gmail.com', 8, NULL, '2025-05-29', '12:28 - 17:25', 'Acceptée', NULL, NULL, NULL, 'travail', NULL, NULL),
(25, 'thomassavourin@gmail.com', 8, NULL, '2025-05-30', '15:21 - 20:19', 'Acceptée', NULL, NULL, NULL, '55', NULL, NULL),
(26, 'dydy771269@icloud.com', NULL, 10, '2025-05-26', '16:38 - 16:41', 'Refusée', NULL, NULL, 'Bara ', NULL, NULL, NULL),
(27, 'ibrahimdrame165@gmail.com', NULL, 20, '2025-05-29', '08:45 - 17:45', 'Refusée', NULL, NULL, 'oui', NULL, NULL, NULL),
(28, 'axel.treffault@gmail.com', NULL, 17, '2025-05-31', '08:30 - 12:00', 'Acceptée', NULL, NULL, 'test', NULL, NULL, NULL),
(29, 'chokri.baccouch@univ-eiffel.fr', 9, NULL, '2025-05-28', '10:00 - 12:00', 'Refusée', NULL, NULL, NULL, 'cours', NULL, NULL),
(30, 'axoupsg75@gmail.com', 9, NULL, '2025-05-28', '08:30 - 15:00', 'Acceptée', NULL, NULL, NULL, 'test', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Salle`
--

CREATE TABLE `Salle` (
  `Id_salle` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `capacite` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Salle`
--

INSERT INTO `Salle` (`Id_salle`, `nom`, `capacite`, `description`, `image`) VALUES
(8, 'Salle 212', 11, 'Salle pour faire de la 3D', 'images/salles/682f559746899.jpg'),
(9, 'Salle 138', 10, 'Salle de Gaming', 'images/salles/682f558b1c65d.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Statistique`
--

CREATE TABLE `Statistique` (
  `Id_stat` int(11) NOT NULL,
  `Date_stats` date DEFAULT NULL,
  `Nb_heure_reserv` int(11) DEFAULT NULL,
  `Nb_etudiants` int(11) DEFAULT NULL,
  `Nb_prof` int(11) DEFAULT NULL,
  `Nb_salle_reserv` int(11) DEFAULT NULL,
  `Nb_materiels_use` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Administration`
--
ALTER TABLE `Administration`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Agent`
--
ALTER TABLE `Agent`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Commentaire`
--
ALTER TABLE `Commentaire`
  ADD PRIMARY KEY (`Id_commentaire`),
  ADD KEY `Id_etudiant` (`Id_etudiant`),
  ADD KEY `Id_prof` (`Id_prof`),
  ADD KEY `Id_reservation` (`Id_reservation`),
  ADD KEY `Id_salle` (`Id_salle`);

--
-- Indexes for table `Etudiant`
--
ALTER TABLE `Etudiant`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `ip_address` (`ip_address`);

--
-- Indexes for table `Materiel`
--
ALTER TABLE `Materiel`
  ADD PRIMARY KEY (`Id_materiel`);

--
-- Indexes for table `Personne`
--
ALTER TABLE `Personne`
  ADD PRIMARY KEY (`Id_user`);

--
-- Indexes for table `Professeur`
--
ALTER TABLE `Professeur`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Reservation`
--
ALTER TABLE `Reservation`
  ADD PRIMARY KEY (`Id_reservation`);

--
-- Indexes for table `Salle`
--
ALTER TABLE `Salle`
  ADD PRIMARY KEY (`Id_salle`);

--
-- Indexes for table `Statistique`
--
ALTER TABLE `Statistique`
  ADD PRIMARY KEY (`Id_stat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Administration`
--
ALTER TABLE `Administration`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `Materiel`
--
ALTER TABLE `Materiel`
  MODIFY `Id_materiel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `Personne`
--
ALTER TABLE `Personne`
  MODIFY `Id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `Reservation`
--
ALTER TABLE `Reservation`
  MODIFY `Id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `Salle`
--
ALTER TABLE `Salle`
  MODIFY `Id_salle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Commentaire`
--
ALTER TABLE `Commentaire`
  ADD CONSTRAINT `Commentaire_ibfk_1` FOREIGN KEY (`Id_etudiant`) REFERENCES `Etudiant` (`Id`),
  ADD CONSTRAINT `Commentaire_ibfk_2` FOREIGN KEY (`Id_prof`) REFERENCES `Professeur` (`Id`),
  ADD CONSTRAINT `Commentaire_ibfk_3` FOREIGN KEY (`Id_reservation`) REFERENCES `Reservation` (`Id_reservation`),
  ADD CONSTRAINT `Commentaire_ibfk_4` FOREIGN KEY (`Id_salle`) REFERENCES `Salles` (`Id_salle`);

--
-- Constraints for table `Reservation`
--
ALTER TABLE `Reservation`
  ADD CONSTRAINT `Id_materiel` FOREIGN KEY (`Id_materiel`) REFERENCES `Materiel` (`Id_materiel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reservation_materiel` FOREIGN KEY (`Id_materiel`) REFERENCES `Materiel` (`Id_materiel`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
