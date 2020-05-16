-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1
-- Timp de generare: ian. 12, 2020 la 08:40 PM
-- Versiune server: 8.0.3-rc-log
-- Versiune PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `instastalking`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `comments`
--

CREATE TABLE `comments` (
  `id_comm` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_img` int(11) NOT NULL,
  `comm` longtext,
  `date` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Eliminarea datelor din tabel `comments`
--

INSERT INTO `comments` (`id_comm`, `id_user`, `id_img`, `comm`, `date`) VALUES
(113, 14, 50, 'La mare, la soare :)))', '2020-01-12'),
(114, 10, 55, 'Paris, Paris....', '2020-01-12'),
(115, 10, 53, 'Leule', '2020-01-12'),
(116, 11, 57, 'foarte frumos', '2020-01-12'),
(117, 11, 55, 'Frumos in Franta', '2020-01-12'),
(119, 12, 54, 'cam ploios', '2020-01-12'),
(120, 12, 52, 'La plimbare', '2020-01-12'),
(121, 12, 55, 'Apus', '2020-01-12');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `images`
--

CREATE TABLE `images` (
  `id_img` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `path` varchar(45) NOT NULL,
  `upload_date` varchar(45) NOT NULL,
  `likes` int(11) DEFAULT '0',
  `profile` tinyint(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Eliminarea datelor din tabel `images`
--

INSERT INTO `images` (`id_img`, `id_user`, `path`, `upload_date`, `likes`, `profile`) VALUES
(50, 10, 'Photos/imagine-desktop-laptop-20_e0ae08246c9c60.jpg', '2020-01-12 21:04:08', 1, 1),
(52, 11, 'Photos/foggy-road-1366x768.jpg', '2020-01-12 21:05:28', 3, 1),
(53, 12, 'Photos/tiger-leap-in-the-water.jpg', '2020-01-12 21:06:10', 1, 1),
(54, 13, 'Photos/rainy_day_6472504241.jpg', '2020-01-12 21:06:43', 3, 1),
(55, 14, 'Photos/Eiffel-Tower-Paris-France.jpg', '2020-01-12 21:07:30', 1, 1),
(57, 14, 'Photos/tumblr_n34440OatA1sf0xh9o1_500.gif', '2020-01-12 21:08:00', 4, 0),
(58, 10, 'Photos/bile.jpg', '2020-01-12 21:37:58', 0, 0);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_img` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Eliminarea datelor din tabel `likes`
--

INSERT INTO `likes` (`id`, `id_user`, `id_img`) VALUES
(39, 14, 50),
(40, 14, 52),
(42, 14, 57),
(43, 14, 54),
(45, 10, 55),
(46, 10, 53),
(48, 11, 57),
(49, 11, 54),
(51, 12, 57),
(52, 12, 54),
(53, 12, 52),
(55, 10, 52),
(56, 10, 57);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Eliminarea datelor din tabel `users`
--

INSERT INTO `users` (`id_user`, `name`, `email`, `username`, `password`) VALUES
(10, 'Vlad Mitroi', 'vladmitroi13@gmail.com', 'vlad.mitroi', '24c9e15e52afc47c225b757e7bee1f9d'),
(11, 'Popescu Alin', 'popescualin33@gmail.com', 'popescu.alin', '7e58d63b60197ceb55a1c487989a3720'),
(12, 'Ionescu Andrei', 'ionescuandrei12@gmail.com', 'ionescu.andrei', '92877af70a45fd6a2ed7fe81e1236b78'),
(13, 'Irina Maria', 'irina.maria@gmail.com', 'irina.maria', '3f02ebe3d7929b091e3d8ccfde2f3bc6'),
(14, 'Alexandra Ioana', 'alexandraioana@gmail.com', 'alexandra.ioana', '0a791842f52a0acfbb3a783378c066b8');

--
-- Indexuri pentru tabele eliminate
--

--
-- Indexuri pentru tabele `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comm`),
  ADD KEY `id_img_fk_idx` (`id_img`),
  ADD KEY `id_user_fk_idx` (`id_user`);

--
-- Indexuri pentru tabele `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id_img`),
  ADD KEY `id_user_fk_idx` (`id_user`);

--
-- Indexuri pentru tabele `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_u_fk_idx` (`id_user`),
  ADD KEY `id_im_fk_idx` (`id_img`);

--
-- Indexuri pentru tabele `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- AUTO_INCREMENT pentru tabele eliminate
--

--
-- AUTO_INCREMENT pentru tabele `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT pentru tabele `images`
--
ALTER TABLE `images`
  MODIFY `id_img` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pentru tabele `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pentru tabele `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constrângeri pentru tabele eliminate
--

--
-- Constrângeri pentru tabele `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `id_img_fkk` FOREIGN KEY (`id_img`) REFERENCES `images` (`id_img`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_usr_fkk` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constrângeri pentru tabele `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `id_user_fk` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constrângeri pentru tabele `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `id_im_fk` FOREIGN KEY (`id_img`) REFERENCES `images` (`id_img`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_u_fk` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
