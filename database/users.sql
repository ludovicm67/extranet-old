SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255),
  `lastname` varchar(255),
  `mail` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255),
  `role_id` int (11),
  `is_admin` tinyint(1),
  PRIMARY KEY (`id`),
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
