-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2020 at 01:05 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_optik`
--

-- --------------------------------------------------------

--
-- Table structure for table `centroid_data`
--

CREATE TABLE `centroid_data` (
  `id_c` int(5) NOT NULL,
  `id_proses` int(4) NOT NULL,
  `tipe` varchar(2) NOT NULL,
  `r` float NOT NULL,
  `f` float NOT NULL,
  `m` float NOT NULL,
  `r2` float NOT NULL,
  `f2` float NOT NULL,
  `m2` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_bobot`
--

CREATE TABLE `data_bobot` (
  `id` int(4) NOT NULL,
  `id_proses` int(3) NOT NULL,
  `id_member` int(3) NOT NULL,
  `nilai_r` int(1) NOT NULL,
  `nilai_f` int(1) NOT NULL,
  `nilai_m` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_rfm`
--

CREATE TABLE `hasil_rfm` (
  `id_hasil` int(5) NOT NULL,
  `id_proses` int(3) NOT NULL,
  `id_member` int(5) NOT NULL,
  `c11` float NOT NULL,
  `c21` float NOT NULL,
  `hasil1` int(1) NOT NULL,
  `c12` float NOT NULL,
  `c22` float NOT NULL,
  `hasil2` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kalkulasi`
--

CREATE TABLE `kalkulasi` (
  `id` int(3) NOT NULL,
  `tanggal_kalkulasi` date NOT NULL,
  `periode_awal` date NOT NULL,
  `periode_akhir` date NOT NULL,
  `judul` varchar(150) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `centroid_data`
--
ALTER TABLE `centroid_data`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `data_bobot`
--
ALTER TABLE `data_bobot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hasil_rfm`
--
ALTER TABLE `hasil_rfm`
  ADD PRIMARY KEY (`id_hasil`);

--
-- Indexes for table `kalkulasi`
--
ALTER TABLE `kalkulasi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `centroid_data`
--
ALTER TABLE `centroid_data`
  MODIFY `id_c` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_bobot`
--
ALTER TABLE `data_bobot`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_rfm`
--
ALTER TABLE `hasil_rfm`
  MODIFY `id_hasil` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kalkulasi`
--
ALTER TABLE `kalkulasi`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
