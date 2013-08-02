-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 20 2013 г., 21:51
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `asterix`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id пользователя',
  `exist` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Существование',
  `sid` varchar(33) DEFAULT NULL COMMENT 'id сессии',
  `online` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Последняя активность',
  `login` varchar(15) NOT NULL COMMENT 'login',
  `pass` varchar(33) NOT NULL COMMENT 'хеш пароля',
  `data` varchar(255) NOT NULL COMMENT 'параметры пользователя',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `exist` (`exist`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Пользователи' AUTO_INCREMENT=101003 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `exist`, `sid`, `online`, `login`, `pass`, `data`) VALUES
(101002, 1, NULL, '2013-06-20 17:44:40', 'root', '63a9f0ea7bb98050796b649e85481845', '{}');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
