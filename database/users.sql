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

CREATE TABLE IF NOT EXISTS `reset_password` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `user_id` int (11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_favorites` (
  `project_id` int (11) NOT NULL,
  `user_id` int (11) NOT NULL,
  PRIMARY KEY (`project_id`, `user_id`),
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_users` (
  `project_id` int (11) NOT NULL,
  `user_id` int (11) NOT NULL,
  PRIMARY KEY (`project_id`, `user_id`),
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `rights` (
  `role_id` int (11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `show` tinyint(1) DEFAULT 0,
  `add` tinyint(1) DEFAULT 0,
  `edit` tinyint(1) DEFAULT 0,
  `delete` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`role_id`, `name`),
  FOREIGN KEY (`role_id`) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `leave` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `user_id` int (11) NOT NULL,
  `accepted` int(1) DEFAULT 0,
  `start` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  `end` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  `start_time` int(11) DEFAULT 9,
  `end_time` int(11) DEFAULT 18,
  `days` float DEFAULT 0,
  `reason` varchar(255) DEFAULT "Autre",
  `file` varchar(255),
  `details` text,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `user_id` int (11) NOT NULL,
  `accepted` int(1) DEFAULT 0,
  `year` int(11),
  `month` int(11),
  `amount` decimal(10, 2),
  `type` varchar(255) DEFAULT "Dépense",
  `file` varchar(255),
  `details` text,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `contracts` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `user_id` int (11) NOT NULL,
  `type` varchar(255) DEFAULT "CDI",
  `start_at` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  `end_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `days` float,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
