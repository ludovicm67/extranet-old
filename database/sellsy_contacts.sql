SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `sellsy_contacts`;

CREATE TABLE `sellsy_contacts` (
  `id` int(11) NOT NULL,
  `pic` varchar(255) COLLATE utf8mb4_unicode_ci,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci,
  `forename` varchar(255) COLLATE utf8mb4_unicode_ci,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci,
  `civil` varchar(255) COLLATE utf8mb4_unicode_ci,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci,
  `birthdate` varchar(255) COLLATE utf8mb4_unicode_ci,
  `thirdid` int(11),
  `peopleid` int(11),
  `fullName` varchar(255) COLLATE utf8mb4_unicode_ci,
  `corpid` int(11),
  `formatted_tel` varchar(255) COLLATE utf8mb4_unicode_ci,
  `formatted_mobile` varchar(255) COLLATE utf8mb4_unicode_ci,
  `formatted_fax` varchar(255) COLLATE utf8mb4_unicode_ci,
  `formatted_birthdate` varchar(255) COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `sellsy_contacts` ADD PRIMARY KEY (`id`);
COMMIT;


