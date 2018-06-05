SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `client_id` int (11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (client_id) REFERENCES sellsy_clients(id)
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
  PRIMARY KEY (`project_id`, `tag_id`),
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

COMMIT;
