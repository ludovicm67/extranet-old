SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `client_id` int (11),
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (client_id) REFERENCES sellsy_clients(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_tags` (
  `project_id` int (11) NOT NULL,
  `tag_id` int (11) NOT NULL,
  `value` varchar(255),
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_contacts` (
  `project_id` int (11) NOT NULL,
  `contact_id` int (11) NOT NULL,
  PRIMARY KEY (`project_id`, `contact_id`),
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  FOREIGN KEY (contact_id) REFERENCES sellsy_contacts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_orders` (
  `project_id` int (11) NOT NULL,
  `order_id` int (11) NOT NULL,
  PRIMARY KEY (`project_id`, `order_id`),
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  FOREIGN KEY (order_id) REFERENCES sellsy_orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `types` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `type_id` int(11), -- type de contact
  `mail` varchar(255),
  `phone` varchar(255),
  `address` varchar(255),
  `other` text,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`type_id`) REFERENCES types(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `identifiers` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_identifiers` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `identifier_id` int(11),
  `value` text,
  `confidential` tinyint(1),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`project_id`) REFERENCES projects(id) ON DELETE CASCADE,
  FOREIGN KEY (`identifier_id`) REFERENCES identifiers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_urls` (
  `project_id` int(11) NOT NULL,
  `name` varchar(255),
  `value` varchar(255),
  `order` int(11) DEFAULT 0 NOT NULL,
  PRIMARY KEY (`project_id`, `order`),
  FOREIGN KEY (`project_id`) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
