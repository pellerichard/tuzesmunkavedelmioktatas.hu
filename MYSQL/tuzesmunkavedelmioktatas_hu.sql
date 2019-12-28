-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2019. Dec 28. 22:04
-- Kiszolgáló verziója: 10.1.38-MariaDB
-- PHP verzió: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `tuzesmunkavedelmioktatas.hu`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `email` varchar(64) CHARACTER SET utf8 COLLATE utf8_hungarian_ci DEFAULT NULL,
  `password` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci,
  `firstName` varchar(32) CHARACTER SET utf8 COLLATE utf8_hungarian_ci DEFAULT NULL,
  `lastName` varchar(32) CHARACTER SET utf8 COLLATE utf8_hungarian_ci DEFAULT NULL,
  `phoneNumber` varchar(32) CHARACTER SET utf8 COLLATE utf8_hungarian_ci DEFAULT NULL,
  `groups` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `educationReason` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_hungarian_ci DEFAULT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '1',
  `creUserId` int(11) NOT NULL DEFAULT '0',
  `creDate` datetime DEFAULT NULL,
  `modUserId` int(11) NOT NULL DEFAULT '0',
  `modDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tábla szerkezet ehhez a táblához `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `testId` int(11) DEFAULT NULL,
  `testInputId` int(11) DEFAULT NULL,
  `question` varchar(256) CHARACTER SET utf8 COLLATE utf8_hungarian_ci DEFAULT NULL,
  `answer` varchar(256) CHARACTER SET utf8 COLLATE utf8_hungarian_ci DEFAULT NULL,
  `correct` tinyint(2) DEFAULT NULL,
  `educationReason` int(11) NOT NULL DEFAULT '0',
  `creUserId` int(11) DEFAULT NULL,
  `creDate` datetime DEFAULT NULL,
  `modUserId` int(11) DEFAULT NULL,
  `modDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tábla szerkezet ehhez a táblához `blockedemail`
--

CREATE TABLE `blockedemail` (
  `id` int(11) NOT NULL,
  `email` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci,
  `ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_hungarian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `changepassword`
--

CREATE TABLE `changepassword` (
  `id` int(11) NOT NULL,
  `email` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci,
  `ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL DEFAULT '127.0.0.1',
  `creUserId` int(11) DEFAULT NULL,
  `creDate` datetime DEFAULT NULL,
  `modUserId` int(11) DEFAULT NULL,
  `modDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `project` int(11) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_hungarian_ci DEFAULT NULL,
  `ref` varchar(64) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci,
  `licenseDate` datetime DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  `creUserId` int(11) NOT NULL DEFAULT '0',
  `creDate` datetime DEFAULT NULL,
  `modUserId` int(11) NOT NULL DEFAULT '0',
  `modDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tábla szerkezet ehhez a táblához `conaccountcompany`
--

CREATE TABLE `conaccountcompany` (
  `id` int(11) NOT NULL,
  `accountId` int(11) DEFAULT NULL,
  `companyId` int(11) DEFAULT NULL,
  `permission` tinyint(1) NOT NULL DEFAULT '0',
  `state` tinyint(3) DEFAULT '1',
  `creUserId` int(11) NOT NULL DEFAULT '0',
  `creDate` datetime DEFAULT NULL,
  `modUserId` int(11) NOT NULL DEFAULT '0',
  `modDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tábla szerkezet ehhez a táblához `diarylog`
--

CREATE TABLE `diarylog` (
  `id` int(11) NOT NULL,
  `companyId` int(11) DEFAULT NULL,
  `creDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Tábla szerkezet ehhez a táblához `emailnotification`
--

CREATE TABLE `emailnotification` (
  `id` int(11) NOT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `state` smallint(11) NOT NULL DEFAULT '1',
  `creUserId` int(11) DEFAULT NULL,
  `creDate` datetime DEFAULT NULL,
  `modUserId` int(11) DEFAULT NULL,
  `modDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tábla szerkezet ehhez a táblához `group`
--

CREATE TABLE `group` (
  `id` int(11) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `companyId` int(11) NOT NULL,
  `creUserId` int(11) NOT NULL,
  `creDate` date NOT NULL,
  `modUserId` int(11) NOT NULL,
  `modDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `ref` text COLLATE utf8_hungarian_ci NOT NULL,
  `name` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `companyId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `creUserId` int(11) DEFAULT NULL,
  `creDate` date DEFAULT NULL,
  `modUserId` int(11) DEFAULT NULL,
  `modDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci ROW_FORMAT=COMPACT;

--
-- A tábla adatainak kiíratása `project`
--

INSERT INTO `project` (`id`, `ref`, `name`, `companyId`, `userId`, `creUserId`, `creDate`, `modUserId`, `modDate`) VALUES
(1, 'tuz-es-munkavedelmi-oktatas', 'Tűz és Munkavédelmi Oktatás', NULL, NULL, 1, '2019-04-07', NULL, '2019-04-07');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `projectId` int(11) NOT NULL,
  `videoUrl` text COLLATE utf8_hungarian_ci NOT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  `creUserId` int(11) DEFAULT NULL,
  `creDate` datetime DEFAULT NULL,
  `modUserId` int(11) DEFAULT NULL,
  `modDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `testinput`
--

CREATE TABLE `testinput` (
  `id` int(11) NOT NULL,
  `question` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `options` text CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `testId` int(11) DEFAULT NULL,
  `rightAnswer` int(11) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  `creUserId` int(11) DEFAULT NULL,
  `creDate` datetime DEFAULT NULL,
  `modUserId` int(11) DEFAULT NULL,
  `modDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `blockedemail`
--
ALTER TABLE `blockedemail`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `changepassword`
--
ALTER TABLE `changepassword`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `conaccountcompany`
--
ALTER TABLE `conaccountcompany`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `diarylog`
--
ALTER TABLE `diarylog`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `emailnotification`
--
ALTER TABLE `emailnotification`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `testinput`
--
ALTER TABLE `testinput`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344;

--
-- AUTO_INCREMENT a táblához `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3107;

--
-- AUTO_INCREMENT a táblához `blockedemail`
--
ALTER TABLE `blockedemail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT a táblához `changepassword`
--
ALTER TABLE `changepassword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT a táblához `conaccountcompany`
--
ALTER TABLE `conaccountcompany`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=354;

--
-- AUTO_INCREMENT a táblához `diarylog`
--
ALTER TABLE `diarylog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT a táblához `emailnotification`
--
ALTER TABLE `emailnotification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1256;

--
-- AUTO_INCREMENT a táblához `group`
--
ALTER TABLE `group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT a táblához `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `testinput`
--
ALTER TABLE `testinput`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
