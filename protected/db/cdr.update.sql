--
-- Структура таблицы `cdr`
--

-- Добавляем первичный ключ
ALTER TABLE  `cdr` ADD  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

-- Поле коментария
ALTER TABLE  `cdr` ADD  `comment` VARCHAR( 255 ) NULL DEFAULT  '';

-- Поле о наличии файла
ALTER TABLE  `cdr` ADD  `file_exists` TINYINT( 1 ) NULL AFTER  `uniqueid`;

-- Поле длительность аудио файла
ALTER TABLE  `cdr` ADD  `audio_duration` int(11) NULL AFTER  `uniqueid`;