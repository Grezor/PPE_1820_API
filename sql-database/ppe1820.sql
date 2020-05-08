--
-- Structure de la table `bornes`
--

DROP TABLE IF EXISTS `bornes`;
CREATE TABLE IF NOT EXISTS `bornes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `long` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `bornes`
--

INSERT INTO `bornes` (`id`, `nom`, `lat`, `long`) VALUES
(1, 'borne 1', 12, 1.1),
(2, 'borne 2', 2, 5),
(3, 'borne 3', 123, 145),
(4, 'borne 4', 10, 20),
(5, 'borne 5', 0, 0),
(6, 'borne 6', 1, 123);

-- --------------------------------------------------------

--
-- Structure de la table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_prise` datetime DEFAULT NULL,
  `id_reservation` int(11) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_reservation_idx` (`id_reservation`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `photos`
--

INSERT INTO `photos` (`id`, `date_prise`, `id_reservation`, `url`) VALUES
(1, '2012-12-01 00:00:00', 1, '1.png'),
(2, '2019-09-21 00:00:00', 1, '2.png'),
(3, '2019-05-17 06:20:00', 2, '3.png'),
(4, '2019-05-23 11:37:00', 2, '4.png'),
(5, '2019-05-23 09:32:24', 4, '6.png'),
(6, '2020-03-07 18:33:37', 2, '9.png'),
(7, '2020-03-04 10:31:40', 6, '7.png'),
(8, '2020-03-31 08:26:00', 8, '8.png'),
(9, '2020-03-31 08:26:00', 2, '9.png'),
(10, '2020-03-31 08:26:00', 2, '10.png');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_evenement` varchar(45) DEFAULT NULL,
  `id_borne` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_evenement_UNIQUE` (`code_evenement`),
  KEY `fk_borne_idx` (`id_borne`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `code_evenement`, `id_borne`) VALUES
(1, 'sona', 1),
(2, 'EFFICOM', 2),
(4, 'rocket', 3),
(6, 'test', 5),
(8, 'test1', 4);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nom` (`nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nickname`, `email`, `password`) VALUES
(4, 'tata', 'tata@gmail.com', 'tata');

-- --------------------------------------------------------

--
-- Structure de la table `user_likes`
--

DROP TABLE IF EXISTS `user_likes`;
CREATE TABLE IF NOT EXISTS `user_likes` (
  `id_user` int(11) NOT NULL,
  `id_photo` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_photo`),
  KEY `fk_user_likes_photo` (`id_photo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user_likes`
--

INSERT INTO `user_likes` (`id_user`, `id_photo`) VALUES
(4, 3),
(4, 6);

-- --------------------------------------------------------

--
-- Structure de la table `user_tokens`
--

DROP TABLE IF EXISTS `user_tokens`;
CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id_user` int(11) NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`token`),
  KEY `fk_users_token_users` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user_tokens`
--

INSERT INTO `user_tokens` (`id_user`, `token`, `creation_date`) VALUES
(4, 'xxxxxxxxxxxxxxxxxxx', '2020-05-08 12:51:07'),
(4, 'xxxxxxxxxxxxxxxxxxx', '2020-05-08 12:50:16');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `id_reservation` FOREIGN KEY (`id_reservation`) REFERENCES `reservations` (`id`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_borne` FOREIGN KEY (`id_borne`) REFERENCES `bornes` (`id`);

--
-- Contraintes pour la table `user_likes`
--
ALTER TABLE `user_likes`
  ADD CONSTRAINT `fk_user_likes_photo` FOREIGN KEY (`id_photo`) REFERENCES `photos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_likes_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `fk_users_token_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
