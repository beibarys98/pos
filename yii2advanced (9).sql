-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: mysql:3306
-- Время создания: Янв 28 2026 г., 10:27
-- Версия сервера: 5.7.44
-- Версия PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yii2advanced`
--

-- --------------------------------------------------------

--
-- Структура таблицы `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `item`
--

INSERT INTO `item` (`id`, `title`, `price`, `quantity`) VALUES
(-3, 'Кіру үшін', 1500, -1),
(-2, 'Кіру үшін (Бала)', 750, -1),
(1, 'Балық S', 2100, 0),
(2, 'Балық M', 2500, 0),
(3, 'Балық L', 3000, 0),
(4, 'Балық XL', 3500, 1),
(5, 'Чипсы', 700, 87),
(6, 'Бал', 250, 98);

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1769423767),
('m130524_201442_init', 1769423770),
('m190124_110200_add_verification_token_column_to_user_table', 1769423770),
('m260125_154326_create_order_system_tables', 1769423771);

-- --------------------------------------------------------

--
-- Структура таблицы `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `key_number` int(11) NOT NULL,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `order`
--

INSERT INTO `order` (`id`, `key_number`, `status`, `total`, `created_at`) VALUES
(1, 45, 'Төленді', 8800, '2026-01-27 09:53:12'),
(2, 46, 'Төленді', 10000, '2026-01-27 09:53:12'),
(3, 88, 'Төленді', 8400, '2026-01-27 09:53:12'),
(4, 89, 'Төленді', 33900, '2026-01-27 09:53:12'),
(5, 101, 'Төленді', 12600, '2026-01-27 09:53:12'),
(6, 25, 'Төленді', 9000, '2026-01-28 10:15:54'),
(7, 26, 'Төленді', 12000, '2026-01-28 10:16:04'),
(8, 27, 'Төленді', 10000, '2026-01-28 10:16:10'),
(9, 56, 'Төленді', 7500, '2026-01-27 10:17:46'),
(10, 57, 'Төленді', 3250, '2026-01-27 10:20:41'),
(11, 10, 'Берілді', 5000, '2026-01-27 10:40:22'),
(12, 33, 'Берілді', 17200, '2026-01-27 12:24:16'),
(13, 27, 'Төленді', 13200, '2026-01-28 07:40:06'),
(14, 30, 'Төленді', 17100, '2026-01-28 10:01:27'),
(15, 66, 'Төленді', 14250, '2026-01-28 10:02:37');

-- --------------------------------------------------------

--
-- Структура таблицы `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `order_item`
--

INSERT INTO `order_item` (`id`, `order_id`, `item_id`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 1),
(3, 1, 2, 1),
(4, 1, 2, 2),
(5, 1, 2, 1),
(6, 1, 1, 2),
(7, 2, 1, 1),
(8, 2, 3, 1),
(9, 2, 4, 1),
(10, 2, 5, 2),
(22, 4, 3, 5),
(23, 4, 4, 4),
(24, 4, 5, 7),
(25, 1, 5, 3),
(26, 5, 1, 6),
(27, 3, 1, 4),
(28, 6, 2, 1),
(29, 6, 3, 1),
(30, 6, 4, 1),
(31, 7, 3, 4),
(32, 8, 2, 4),
(33, 9, 2, 3),
(35, 10, 3, 1),
(38, 10, 6, 1),
(39, 11, 2, 2),
(40, 12, 1, 2),
(41, 12, 3, 2),
(42, 12, 4, 2),
(43, 13, 1, 2),
(44, 13, 3, 2),
(45, 13, -3, 2),
(46, 14, 1, 1),
(47, 14, 4, 3),
(48, 14, -3, 3),
(49, 15, 4, 3),
(50, 15, -3, 2),
(51, 15, -2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`) VALUES
(1, 'admin', 'aBhCUrPMmAJ88QMZvjYS1ZgtFIvMVOp8', '$2y$13$W0g3wTCp2q38aCiYDzrSHehoO5EPVl9iSY4npY3McPd2eQaOdr2V6', NULL, 'admin@gmail.com', 10, 1769424280, 1769424280, 'OIX5eu72VN3uep5PSOaYB4yDQld_cvAM_1769424280');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-order_item-order_id` (`order_id`),
  ADD KEY `idx-order_item-item_id` (`item_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk-order_item-item_id` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-order_item-order_id` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
