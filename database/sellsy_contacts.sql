SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `sellsy_contacts` (
  `id` int (11) NOT NULL AUTO_INCREMENT,
  `sellsy_id` int (11),
  `pic` varchar(255),
  `name` varchar(255),
  `forename` varchar(255),
  `tel` varchar(255),
  `email` varchar(255),
  `mobile` varchar(255),
  `civil` varchar(255),
  `position` varchar(255),
  `birthdate` varchar(255),
  `thirdid` int(11),
  `fullName` varchar(255),
  `corpid` int(11),
  `formatted_tel` varchar(255),
  `formatted_mobile` varchar(255),
  `formatted_fax` varchar(255),
  `formatted_birthdate` varchar(255),
  PRIMARY KEY (`id`, `sellsy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
