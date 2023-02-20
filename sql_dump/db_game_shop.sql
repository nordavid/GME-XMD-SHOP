-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2023 at 10:30 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_game_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `entity`
--

CREATE TABLE `entity` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('Player','Shop') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `entity`
--

INSERT INTO `entity` (`id`, `type`) VALUES
(1, 'Shop'),
(2, 'Shop'),
(3, 'Shop'),
(4, 'Player'),
(5, 'Player'),
(6, 'Player');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `entity_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_trade` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`entity_id`, `item_id`, `amount`, `last_trade`) VALUES
(1, 3, 2, '2023-02-20 21:18:00'),
(1, 4, 2, '2023-02-20 21:18:01'),
(2, 5, 1, '2023-02-20 21:17:54'),
(2, 6, 0, '2023-02-20 21:18:12'),
(3, 1, 0, '2023-02-20 20:57:09'),
(3, 2, 1, '2023-02-20 21:17:57'),
(5, 1, 1, '2023-02-20 21:05:42'),
(5, 2, 1, '2023-02-20 21:06:48'),
(5, 5, 1, '2023-02-20 21:14:55'),
(6, 6, 1, '2023-02-20 21:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `category` enum('Weapon','Armor','Spaceship') NOT NULL,
  `rarity` enum('Common','Rare','Legendary') NOT NULL,
  `description` varchar(1024) NOT NULL,
  `cost` mediumint(8) UNSIGNED NOT NULL,
  `image` varchar(128) NOT NULL DEFAULT 'img_item/default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `category`, `rarity`, `description`, `cost`, `image`) VALUES
(1, 'Raumschiff One', 'Spaceship', 'Rare', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 150, 'img_item/item_1.png'),
(2, 'Raumschiff Two', 'Spaceship', 'Common', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 50, 'img_item/item_2.png'),
(3, 'Weapon One', 'Weapon', 'Legendary', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 200, 'img_item/item_3.png'),
(4, 'Weapon Two', 'Weapon', 'Rare', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 100, 'img_item/item_4.png'),
(5, 'Shield One', 'Armor', 'Rare', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 250, 'img_item/item_5.png'),
(6, 'Shield Two', 'Armor', 'Legendary', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 500, 'img_item/item_6.png');

-- --------------------------------------------------------

--
-- Table structure for table `item_property`
--

CREATE TABLE `item_property` (
  `item_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `property_type` enum('Playerstats','Buff') NOT NULL,
  `stat_type` enum('Hp','Armor','Damage','Speed','Strength','Charism','Intelligence','Skill') NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_property`
--

INSERT INTO `item_property` (`item_id`, `name`, `property_type`, `stat_type`, `value`) VALUES
(1, 'Geschwindigkeit', 'Playerstats', 'Speed', 20),
(2, 'Geschwindigkeit', 'Playerstats', 'Speed', 10),
(5, 'HP', 'Playerstats', 'Hp', 200),
(6, 'HP', 'Buff', 'Hp', 200),
(1, 'Hyperspeed', 'Buff', 'Speed', 150),
(4, 'Laser Aim', 'Buff', 'Skill', 100),
(5, 'Rüstung', 'Playerstats', 'Armor', 50),
(6, 'Rüstung', 'Playerstats', 'Armor', 400),
(3, 'Schaden', 'Playerstats', 'Damage', 220),
(4, 'Schaden', 'Playerstats', 'Damage', 160),
(2, 'Star Boost', 'Buff', 'Speed', 200),
(3, 'Ultra Knockback', 'Buff', 'Damage', 100);

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE `player` (
  `id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(256) NOT NULL,
  `balance` int(11) NOT NULL DEFAULT 3000,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`id`, `entity_id`, `username`, `password`, `email`, `balance`, `is_admin`, `created_at`) VALUES
(1, 4, 'David', '$2y$10$l3KfSJ6TWthhHJwQzbplPOQKaWm1Tv7y6j36G4AbY/egKBCl9g5Oi', 'david@einenkel.de', 500, 1, '2023-02-19 13:35:12'),
(2, 5, 'Sven', '$2y$10$EQKCUUFYVbFyhwdeC2mSN.eLRz6p3GCIx9wSC35i76bYzQmBMNNgW', 'sven@braeumer.de', 3300, 0, '2023-02-20 21:03:36'),
(3, 6, 'Polar', '$2y$10$EWD2k6ZB/nXdk7BEncNQDuHy0vG15s7TQVyfABgQ1gIpwYo94YmvG', 'polar@corvomusic.de', 3600, 0, '2023-02-20 21:17:31');

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `category` enum('Weapon','Armor','Spaceship') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`id`, `entity_id`, `name`, `category`) VALUES
(1, 1, 'Waffen', 'Weapon'),
(2, 2, 'Rüstung', 'Armor'),
(3, 3, 'Raumschiffe', 'Spaceship');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `entity`
--
ALTER TABLE `entity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`entity_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_property`
--
ALTER TABLE `item_property`
  ADD PRIMARY KEY (`name`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `entity_id` (`entity_id`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `entity`
--
ALTER TABLE `entity`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `player`
--
ALTER TABLE `player`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`),
  ADD CONSTRAINT `inventory_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Constraints for table `item_property`
--
ALTER TABLE `item_property`
  ADD CONSTRAINT `item_property_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Constraints for table `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`);

--
-- Constraints for table `shop`
--
ALTER TABLE `shop`
  ADD CONSTRAINT `shop_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
