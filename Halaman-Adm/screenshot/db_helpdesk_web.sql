-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 13 Nov 2025 pada 04.29
-- Versi server: 8.0.30
-- Versi PHP: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_helpdesk_web`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_department`
--

CREATE TABLE `tbl_department` (
  `td_id` int NOT NULL,
  `td_name` varchar(75) NOT NULL,
  `td_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_department`
--

INSERT INTO `tbl_department` (`td_id`, `td_name`, `td_description`) VALUES
(72, 'IT', '\r\n\r\n'),
(73, 'PGA', '\r\n'),
(74, 'PURCHASING', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_priority`
--

CREATE TABLE `tbl_priority` (
  `tp_id` int NOT NULL,
  `tp_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_priority`
--

INSERT INTO `tbl_priority` (`tp_id`, `tp_name`) VALUES
(8, 'low'),
(9, 'Medium'),
(10, 'High');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_service`
--

CREATE TABLE `tbl_service` (
  `ts_id` int NOT NULL,
  `ts_name` varchar(225) NOT NULL,
  `ts_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_service`
--

INSERT INTO `tbl_service` (`ts_id`, `ts_name`, `ts_description`) VALUES
(7, 'Problem solving 2', 'tawakal'),
(8, 'Problem solving 1', 'ikhtiar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_ticket`
--

CREATE TABLE `tbl_ticket` (
  `tt_id` int NOT NULL,
  `tt_no_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `tt_user` int NOT NULL,
  `tt_subject` varchar(225) DEFAULT NULL,
  `tt_department` int NOT NULL,
  `tt_service` int NOT NULL,
  `tt_priority` int NOT NULL,
  `tt_message` longtext,
  `tt_problem_solving` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '',
  `tt_status` enum('NEW','PROCCESS','PENDING','CANCEL','DONE','DELETE') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'NEW',
  `tt_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tt_foto_before` varchar(100) NOT NULL,
  `tt_foto_after` varchar(100) NOT NULL,
  `tt_updated` datetime DEFAULT NULL,
  `tt_duration` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_ticket`
--

INSERT INTO `tbl_ticket` (`tt_id`, `tt_no_id`, `tt_user`, `tt_subject`, `tt_department`, `tt_service`, `tt_priority`, `tt_message`, `tt_problem_solving`, `tt_status`, `tt_created`, `tt_foto_before`, `tt_foto_after`, `tt_updated`, `tt_duration`) VALUES
(458, 'ITHDK-001', 21, 'TEST', 74, 8, 10, 'TEST\r\n', '', 'NEW', '2025-11-12 16:13:54', '', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
  `tu_id` int NOT NULL,
  `tu_role` enum('admin','customer') NOT NULL,
  `tu_user` varchar(100) NOT NULL,
  `tu_pass` varchar(100) NOT NULL,
  `tu_full_name` varchar(200) NOT NULL,
  `tu_email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_user`
--

INSERT INTO `tbl_user` (`tu_id`, `tu_role`, `tu_user`, `tu_pass`, `tu_full_name`, `tu_email`) VALUES
(18, 'admin', 'admin', '202cb962ac59075b964b07152d234b70', 'Super Admin', ''),
(19, 'admin', 'admin', '202cb962ac59075b964b07152d234b70', 'Super Admin', ''),
(20, 'customer', 'user', '202cb962ac59075b964b07152d234b70', 'User helpdesk', ''),
(21, 'customer', 'user', '202cb962ac59075b964b07152d234b70', 'User helpdesk', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_department`
--
ALTER TABLE `tbl_department`
  ADD PRIMARY KEY (`td_id`);

--
-- Indeks untuk tabel `tbl_priority`
--
ALTER TABLE `tbl_priority`
  ADD PRIMARY KEY (`tp_id`);

--
-- Indeks untuk tabel `tbl_service`
--
ALTER TABLE `tbl_service`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indeks untuk tabel `tbl_ticket`
--
ALTER TABLE `tbl_ticket`
  ADD PRIMARY KEY (`tt_id`),
  ADD KEY `tt_department` (`tt_department`),
  ADD KEY `tt_user` (`tt_user`),
  ADD KEY `tt_service` (`tt_service`),
  ADD KEY `tt_priority` (`tt_priority`);

--
-- Indeks untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`tu_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_department`
--
ALTER TABLE `tbl_department`
  MODIFY `td_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT untuk tabel `tbl_priority`
--
ALTER TABLE `tbl_priority`
  MODIFY `tp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `tbl_service`
--
ALTER TABLE `tbl_service`
  MODIFY `ts_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tbl_ticket`
--
ALTER TABLE `tbl_ticket`
  MODIFY `tt_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=459;

--
-- AUTO_INCREMENT untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `tu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_ticket`
--
ALTER TABLE `tbl_ticket`
  ADD CONSTRAINT `tbl_ticket_ibfk_1` FOREIGN KEY (`tt_department`) REFERENCES `tbl_department` (`td_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_ticket_ibfk_2` FOREIGN KEY (`tt_service`) REFERENCES `tbl_service` (`ts_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_ticket_ibfk_3` FOREIGN KEY (`tt_priority`) REFERENCES `tbl_priority` (`tp_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_ticket_ibfk_4` FOREIGN KEY (`tt_user`) REFERENCES `tbl_user` (`tu_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
