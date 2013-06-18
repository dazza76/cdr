-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 08 2013 г., 17:46
-- Версия сервера: 5.1.66
-- Версия PHP: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `asterisk`
--

-- --------------------------------------------------------

--
-- Структура таблицы `timetable`
--

CREATE TABLE IF NOT EXISTS `timetable` (
  `agentid_day` varchar(32) NOT NULL,
  `event` enum('vac','ill','job') NOT NULL,
  `start` time DEFAULT NULL,
  `duration` int(11) NOT NULL,
  PRIMARY KEY (`agentid_day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `timetable`
--

INSERT INTO `timetable` (`agentid_day`, `event`, `start`, `duration`) VALUES
('1021.2012-03-17', 'job', '12:00:00', 24),
('1003.2012-03-10', 'job', '00:00:09', 8),
('1020.2013-04-06', 'ill', NULL, 0),
('1020.2013-04-07', 'ill', NULL, 0),
('1020.2013-04-08', 'ill', NULL, 0),
('1020.2013-04-09', 'ill', NULL, 0),
('1020.2013-04-10', 'ill', NULL, 0),
('1020.2013-04-11', 'ill', NULL, 0),
('1020.2013-04-12', 'ill', NULL, 0),
('1020.2013-04-13', 'ill', NULL, 0);
