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
--
-- Structure de la table `photos`
--
DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_prise` datetime DEFAULT NULL,
  `id_reservation` int(11) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `estAime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `id_reservation_idx` (`id_reservation`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `photos`
--

INSERT INTO `photos` (`id`, `date_prise`, `id_reservation`, `url`, `estAime`) VALUES
(1, '2012-12-01 00:00:00', 1, '1.png', 1),
(2, '2019-09-21 00:00:00', 1, '2.png', 1),
(3, '2019-05-17 06:20:00', 2, '3.png', 1),
(4, '2019-05-23 11:37:00', 2, '4.png', 1),
(5, '2019-05-23 09:32:24', 4, '6.png', 0),
(6, '2020-03-07 18:33:37', 2, '9.png', 0),
(7, '2020-03-04 10:31:40', 6, '7.png', 0),
(8, '2020-03-31 08:26:00', 8, '8.png', 0),
(9, '2020-03-31 08:26:00', 2, '9.png', 0),
(10, '2020-03-31 08:26:00', 2, '10.png', 0);

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
(2, 'efficom', 2),
(4, 'rocket', 3),
(6, 'test', 5),
(8, 'test1', 4);

--
-- Structure de la table `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `password`) VALUES
(4, 'tata', 'tata@gmail.com', 'tata');

--
-- Structure de la table `users_likes`
--
DROP TABLE IF EXISTS `users_likes`;
CREATE TABLE IF NOT EXISTS `users_likes` (
  `id_users` int(11) NOT NULL,
  `id_photo` int(11) NOT NULL,
  PRIMARY KEY (`id_users`,`id_photo`),
  KEY `fk_user_likes_photo` (`id_photo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
--
-- Structure de la table `users_tokens`
--
DROP TABLE IF EXISTS `users_tokens`;
CREATE TABLE IF NOT EXISTS `users_tokens` (
  `id_user` int(11) NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`token`),
  KEY `fk_users_token_users` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users_tokens`
--

INSERT INTO `users_tokens` (`id_user`, `token`, `creation_date`) VALUES
(4, '20vr0qoz725RHtDoQT8kJYzoOBZsIktKkZclpBUxE0', '2020-04-24 14:27:49'),
(4, '3VyRYAOQOnP0nTRFNxZBAr98agn5y7LC2jt1UiRIFG', '2020-04-24 14:29:41'),
(4, 'UaAqXcccIyXyjp4o0AzLFsrwBH1qmRFh2gH0sTdbsa', '2020-04-24 14:23:54');

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
-- Contraintes pour la table `users_likes`
--
ALTER TABLE `users_likes`
  ADD CONSTRAINT `fk_user_likes_photo` FOREIGN KEY (`id_photo`) REFERENCES `photos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_likes_users` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users_tokens`
--
ALTER TABLE `users_tokens`
  ADD CONSTRAINT `fk_users_token_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE NO ACTION;
COMMIT;
