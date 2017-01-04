-- phpMyAdmin SQL Dump
-- version 4.6.5.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 04 Sty 2017, 10:01
-- Wersja serwera: 10.0.28-MariaDB
-- Wersja PHP: 5.6.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `szzsip`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `client`
--

CREATE TABLE `client` (
  `id` int(6) UNSIGNED NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `acronym` varchar(10) NOT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `nip` varchar(13) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `street_no` varchar(10) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `status` enum('active','deleted','locked') DEFAULT NULL,
  `attendant` int(6) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `type` enum('customer','company') DEFAULT NULL,
  `info` varchar(500) DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `updated_by` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

--
-- Zrzut danych tabeli `client`
--

INSERT INTO `client` (`id`, `firstname`, `lastname`, `acronym`, `phone`, `nip`, `street`, `street_no`, `postcode`, `city`, `email`, `status`, `attendant`, `created_at`, `updated_at`, `type`, `info`, `created_by`, `updated_by`) VALUES
(1, 'Marcin', 'Pikul', 'MAR_PIK', '609331337', '', 'Wspólna', '5', '21-100', 'Lubartów', 'marcin.pikul@kom-pik.pl', 'active', 2, 2147483647, 1483482467, 'customer', 'straszny maruda', NULL, 1),
(3, 'Kompik Marcin Pikul', '', 'KOMPIK', '609331331', '7141812005', 'Wspólna', '5', '21-100', 'Lubartów', 'pogotowie@kom-pik.pl', 'active', 2, 2147483647, 1483448017, 'company', '', NULL, 1),
(4, 'Jakaś nowa firma', '', 'JAK_NOW', NULL, '777-11-22-33', 'Komunalna', '5', '21-100', 'Lubartów', 'marcin.pikul@kom-pik.pl', 'active', 3, 1483392190, 1483401835, 'company', 'nowa firma', 1, 1),
(5, 'Jakaś nowa firma', '', 'JAK_NOW2', NULL, '', '', '', '', '', 'pogotowie@kom-pik.pl', 'active', 2, 1483449136, 1483449230, 'company', '', 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `group`
--

CREATE TABLE `group` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `status` enum('active','deleted','locked') DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `updated_by` int(6) DEFAULT NULL,
  `owner_id` int(6) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order`
--

CREATE TABLE `order` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `owner_id` int(6) UNSIGNED DEFAULT NULL,
  `executive_id` int(6) UNSIGNED DEFAULT NULL,
  `client_id` int(6) UNSIGNED DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `updated_by` int(6) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `project_id` int(6) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

--
-- Zrzut danych tabeli `order`
--

INSERT INTO `order` (`id`, `name`, `owner_id`, `executive_id`, `client_id`, `description`, `created_at`, `updated_at`, `created_by`, `updated_by`, `status`, `project_id`) VALUES
(1, 'Zlecenie testowe', 2, 2, 1, 'nowe zlecenie', 1481811506, 1481814677, NULL, NULL, 0, NULL),
(2, 'Zlecenie testowe numer 12', 1, 2, 1, '', 1481813255, 1481814622, NULL, NULL, 2, 19),
(3, 'Zlecenie testowe asd asd ', 1, 2, 1, 'No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo długi opis. No i tutaj bardzo dł', 1481813573, 1482188631, NULL, NULL, 1, 21),
(4, 'Zlecenie testowe some2', 1, NULL, 4, '', 1482270008, 1483392680, NULL, 1, 1, 19),
(5, 'Zlecenie testowe some22', 1, NULL, 3, '', 1482536346, 1482536361, 1, 1, 1, 19),
(6, 'Zlecenie mobile', 1, NULL, 1, 'Dodaję zlecenie mobile. ', 1482847383, 1482847383, 1, 1, 1, 19);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order_task`
--

CREATE TABLE `order_task` (
  `id` int(6) UNSIGNED NOT NULL,
  `order_id` int(6) UNSIGNED DEFAULT NULL,
  `task_id` int(6) UNSIGNED DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `updated_by` int(6) DEFAULT NULL,
  `locked` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

--
-- Zrzut danych tabeli `order_task`
--

INSERT INTO `order_task` (`id`, `order_id`, `task_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `locked`) VALUES
(36, 2, 3, 1482842774, 1, 1483385166, 1, 0),
(38, 6, 4, 1482847442, 1, 1483021164, 2, 0),
(39, 6, 6, 1482847442, 1, 1482847442, 1, NULL),
(40, 3, 1, 1482880737, 1, 1482961384, 1, NULL),
(41, 6, 1, 1482880754, 1, 1482880754, 1, NULL),
(42, 2, 1, 1482919026, 1, 1483385169, 1, 0),
(43, 2, 2, 1482919026, 1, 1483136336, 1, 0),
(44, 2, 3, 1482919026, 1, 1483017531, 2, 0),
(45, 2, 4, 1482919026, 1, 1483136332, 1, 0),
(46, 2, 5, 1482919026, 1, 1483020795, 2, 0),
(47, 2, 6, 1482919026, 1, 1482919026, 1, NULL),
(48, 2, 7, 1482919026, 1, 1483053850, 1, 0),
(49, 2, 8, 1482919026, 1, 1482919026, 1, NULL),
(50, 2, 9, 1482919026, 1, 1482919026, 1, NULL),
(51, 3, 2, 1482961458, 1, 1482961476, 1, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `project`
--

CREATE TABLE `project` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `owner_id` int(6) UNSIGNED DEFAULT NULL,
  `client_id` int(6) UNSIGNED DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `created_by` int(6) DEFAULT NULL,
  `updated_by` int(6) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

--
-- Zrzut danych tabeli `project`
--

INSERT INTO `project` (`id`, `name`, `description`, `owner_id`, `client_id`, `created_at`, `updated_at`, `created_by`, `updated_by`, `status`) VALUES
(9, 'Projekt testowy', 'Dodaję opis', 1, 1, 0, 0, NULL, NULL, 0),
(10, 'Projekt testowy', '', 1, 3, 0, 0, NULL, NULL, 0),
(11, 'Projekt testowy z bardzo długą nazwą', 'Jakiś popis testowy', 1, 3, 0, 1482187678, NULL, NULL, 2),
(12, 'Projekt testowy 2', 'Jakiś popis testowy', 1, 3, 0, 0, NULL, NULL, 0),
(13, 'Some', '', 1, 3, 0, 0, NULL, NULL, 0),
(14, 'Projekt testowy nr 3555', 'Jakiś popis testowy', 1, 1, 0, 0, NULL, NULL, 0),
(15, 'Projekt testowy 2', '', 1, 3, 0, 1481761807, NULL, NULL, 0),
(16, '555a55a5a', 'asasdasd asdas ', 1, 1, 0, 0, NULL, NULL, 0),
(17, 'supervisor', '', 2, 1, 0, 0, NULL, NULL, 1),
(18, 'zamknięty', '', 2, 3, 0, 1481802862, NULL, NULL, 3),
(19, 'Projekt testowy z bardzo długą nazwą 2', 'asda s', 1, 1, 1481761041, 1482536263, NULL, 1, 2),
(20, 'df fds ', '', 1, 1, 1481761997, 1481761997, NULL, NULL, 1),
(21, 'Projekt testowy', 'sasd asd asd a', 1, 3, 1481791589, 1482233662, NULL, NULL, 0),
(22, 'Some', '', 1, 1, 1482535149, 1482535149, 1, 1, 1),
(23, 'Some', '', 1, 1, 1482535182, 1482535602, 1, 1, 0),
(24, 'Some', '', 1, NULL, 1482535221, 1482535586, 1, 1, 0),
(25, 'Some', '', 1, NULL, 1482535365, 1482535591, 1, 1, 0),
(26, 'Some', '', 1, NULL, 1482535507, 1482535594, 1, 1, 0),
(27, 'Some', '', 1, NULL, 1482535608, 1482535608, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `project_order`
--

CREATE TABLE `project_order` (
  `id` int(6) UNSIGNED NOT NULL,
  `order_id` int(6) UNSIGNED DEFAULT NULL,
  `project_id` int(6) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `start_stop`
--

CREATE TABLE `start_stop` (
  `id` int(6) NOT NULL,
  `order_task_id` int(6) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `type` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

--
-- Zrzut danych tabeli `start_stop`
--

INSERT INTO `start_stop` (`id`, `order_task_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `type`) VALUES
(1, 36, 1483134414, 1, 1483134414, 1, 1),
(5, 36, 1483136038, 1, 1483136038, 1, 2),
(6, 36, 1483136045, 1, 1483136045, 1, 1),
(7, 36, 1483136051, 1, 1483136051, 1, 0),
(8, 36, 1483136057, 1, 1483136057, 1, 1),
(9, 36, 1483136063, 1, 1483136063, 1, 0),
(10, 43, 1483136286, 1, 1483136286, 1, 1),
(11, 45, 1483136294, 1, 1483136294, 1, 1),
(12, 43, 1483136305, 1, 1483136305, 1, 2),
(13, 45, 1483136313, 1, 1483136313, 1, 2),
(14, 45, 1483136320, 1, 1483136320, 1, 0),
(15, 45, 1483136325, 1, 1483136325, 1, 1),
(16, 45, 1483136332, 1, 1483136332, 1, 0),
(17, 43, 1483136336, 1, 1483136336, 1, 0),
(18, 36, 1483384356, 1, 1483384356, 1, 1),
(19, 36, 1483384376, 1, 1483384376, 1, 0),
(20, 36, 1483385144, 1, 1483385144, 1, 1),
(21, 42, 1483385151, 1, 1483385151, 1, 1),
(22, 42, 1483385159, 1, 1483385159, 1, 2),
(23, 36, 1483385166, 1, 1483385166, 1, 0),
(24, 42, 1483385169, 1, 1483385169, 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `task`
--

CREATE TABLE `task` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `updated_by` int(6) DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

--
-- Zrzut danych tabeli `task`
--

INSERT INTO `task` (`id`, `name`, `status`, `created_at`, `updated_at`, `updated_by`, `created_by`) VALUES
(1, 'Task 11111', 1, NULL, 1482536206, 1, 1),
(2, 'Nowe zadanie 444', 1, 1482359619, 1482362682, 1, 1),
(3, 'Nowe zadanie 2222', 0, 1482359751, 1482874636, 1, 1),
(4, 'Nowe zadanie 3', 1, 1482360019, 1482360019, 1, 1),
(5, 'Nowe zadanie 4', 1, 1482360039, 1482360039, 1, 1),
(6, 'Nowe zadanie 44', 0, 1482360198, 1482362154, 1, 1),
(7, 'Task 11', 1, NULL, NULL, NULL, 1),
(8, 'Task 111', 1, NULL, NULL, NULL, 1),
(9, 'Task 1111', NULL, 1482535969, 1482535969, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(6) UNSIGNED NOT NULL,
  `username` varchar(10) NOT NULL,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `status` enum('active','deleted','locked') DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `updated_by` int(6) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `type` enum('admin','supervisor','serviceman','client') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `username`, `firstname`, `lastname`, `phone`, `password_hash`, `password_reset_token`, `email`, `auth_key`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`, `group_id`, `type`) VALUES
(1, 'admin', NULL, NULL, NULL, '$2y$13$UQvGOnP11ZIjazZJZXOEle6AARSrzWMKNH11qhlWyzLeJKqc0Diey', NULL, 'marcin.pikul@itcomplete.pl', 'rMZoPAMK2s06vwkJ1UMFPk-MTOruvCc6', 'active', 2147483647, 0, NULL, NULL, NULL, 'admin'),
(2, 'supervisor', NULL, NULL, NULL, '$2y$13$sBUByGZSdfoD.ZgAChNu3.uHYWajqddMIPez2Up.zxTjo1GWVYpoy', NULL, 'marcin.pikul@kom-pik.pl', 'rMZoPAMK2s06vwkJ1UMFPk-MTOruvCc6', 'active', 2147483647, 0, NULL, NULL, NULL, 'supervisor'),
(3, 'admin2', NULL, NULL, NULL, '$2y$13$kjSYCdynEYWF9rKyNc.bHO7ZI96jS9p99C3wafCSvRbF.HrtwaZgC', NULL, 'pogotowie@kom-pik.pl', 'rMZoPAMK2s06vwkJ1UMFPk-MTOruvCc6', 'active', 2147483647, 2147483647, NULL, NULL, NULL, 'admin');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_task`
--
ALTER TABLE `order_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_order`
--
ALTER TABLE `project_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `start_stop`
--
ALTER TABLE `start_stop`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `client`
--
ALTER TABLE `client`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT dla tabeli `group`
--
ALTER TABLE `group`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `order`
--
ALTER TABLE `order`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT dla tabeli `order_task`
--
ALTER TABLE `order_task`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT dla tabeli `project`
--
ALTER TABLE `project`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT dla tabeli `project_order`
--
ALTER TABLE `project_order`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `start_stop`
--
ALTER TABLE `start_stop`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT dla tabeli `task`
--
ALTER TABLE `task`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
