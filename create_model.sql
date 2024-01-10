DROP DATABASE IF EXISTS `BI`;
CREATE DATABASE `BI`;
USE `BI`;
CREATE TABLE `labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  `confidence_level` double NOT NULL,
  PRIMARY KEY (`id`)
);
