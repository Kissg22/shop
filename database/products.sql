-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Okt 02. 22:06
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `shop`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `buying_price` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photos` text DEFAULT NULL,
  `category_id` int(11) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `products`
--

INSERT INTO `products` (`id`, `name`, `buying_price`, `price`, `description`, `photos`, `category_id`, `user_id`) VALUES
(1, 'fddfd', 43, 43, 'hgzhjgj', '[{\"src\":\"uploads/1727809063-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809063-100000.jpeg\"},{\"src\":\"uploads/1727809063-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809063-100000.jpeg\"},{\"src\":\"uploads/1727809064-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809064-100000.jpeg\"},{\"src\":\"uploads/1727809064-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809064-100000.jpeg\"},{\"src\":\"uploads/1727809065-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809065-100000.jpeg\"},{\"src\":\"uploads/1727809065-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809065-100000.jpeg\"}]', 1, 1),
(2, 'efdsyfds', 43, 43, 'fdsfdrste', '[{\"src\":\"uploads/1727809526-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809526-100000.jpeg\"},{\"src\":\"uploads/1727809527-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809527-100000.jpeg\"},{\"src\":\"uploads/1727809527-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809527-100000.jpeg\"},{\"src\":\"uploads/1727809527-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809527-100000.jpeg\"},{\"src\":\"uploads/1727809528-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809528-100000.jpeg\"},{\"src\":\"uploads/1727809528-100000.jpeg\",\"thumb\":\"uploads/thumb_1727809528-100000.jpeg\"}]', 3, 1),
(3, 'mkiss0516@gmail.com', 43, 43, 'fdfd', '[{\"src\":\"uploads/1727877602-100000.jpeg\",\"thumb\":\"uploads/thumb_1727877602-100000.jpeg\"}]', 3, 1);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
