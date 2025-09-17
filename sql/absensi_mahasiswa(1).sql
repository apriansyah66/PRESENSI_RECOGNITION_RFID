-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2025 at 08:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi_mahasiswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absen` int(11) NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `id_matkul` int(11) DEFAULT NULL,
  `waktu` datetime DEFAULT current_timestamp(),
  `status` enum('Hadir','Terlambat','Tidak Hadir','Ditolak') DEFAULT 'Hadir',
  `foto_bukti` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absen`, `nim`, `id_matkul`, `waktu`, `status`, `foto_bukti`) VALUES
(247, 'C030322115', 12, '2025-07-17 01:09:08', 'Hadir', NULL),
(252, 'C030322115', 14, '2025-07-17 17:56:29', 'Hadir', NULL),
(254, 'C030322109', 14, '2025-07-21 00:56:20', 'Hadir', NULL),
(255, 'C030322115', 14, '2025-07-21 00:56:42', 'Hadir', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(12) NOT NULL,
  `admin_username` varchar(30) NOT NULL,
  `admin_password` varchar(50) NOT NULL,
  `admin_nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_username`, `admin_password`, `admin_nama`) VALUES
(1, 'admin', 'admin', 'Administrator'),
(2, 'Apri', '12345', 'Apriansyah');

-- --------------------------------------------------------

--
-- Table structure for table `data_users`
--

CREATE TABLE `data_users` (
  `id` double NOT NULL,
  `rfid` varchar(50) NOT NULL,
  `nama` varchar(64) NOT NULL,
  `alamat` text NOT NULL,
  `umur` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `data_users`
--

INSERT INTO `data_users` (`id`, `rfid`, `nama`, `alamat`, `umur`, `status`, `updated_at`) VALUES
(1, 'A19E551B', 'Wardana Adiaksa', 'Jakarta', 12, 0, '2022-04-27 04:08:08'),
(2, 'B23D221B', 'Rudi', 'Bandung', 12, 0, '2022-04-27 04:10:49'),
(15, 'F3C1D89A', 'Badrun Alam', 'Bandung', 13, 0, '2022-07-09 12:28:38');

-- --------------------------------------------------------

--
-- Table structure for table `izin`
--

CREATE TABLE `izin` (
  `izin_id` int(12) NOT NULL,
  `karyawan_id` int(12) NOT NULL,
  `izin_nama` varchar(50) NOT NULL,
  `izin_dari` date NOT NULL,
  `izin_sampai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `jabatan_id` int(12) NOT NULL,
  `jabatan_nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`jabatan_id`, `jabatan_nama`) VALUES
(5, 'asd'),
(4, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `jadwal_id` int(12) NOT NULL,
  `jabatan_id` int(12) NOT NULL,
  `jadwal_hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jadwal_masuk` time NOT NULL,
  `jadwal_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`jadwal_id`, `jabatan_id`, `jadwal_hari`, `jadwal_masuk`, `jadwal_pulang`) VALUES
(8, 4, 'Senin', '12:00:00', '12:00:00'),
(9, 4, 'Selasa', '00:00:00', '00:00:00'),
(10, 4, 'Rabu', '00:00:00', '00:00:00'),
(11, 4, 'Kamis', '00:01:00', '00:36:00'),
(12, 4, 'Jumat', '00:00:00', '00:00:00'),
(13, 4, 'Sabtu', '00:00:00', '00:00:00'),
(14, 4, 'Minggu', '00:00:00', '00:00:00'),
(15, 5, 'Senin', '00:00:00', '00:00:00'),
(16, 5, 'Selasa', '00:00:00', '00:00:00'),
(17, 5, 'Rabu', '00:00:00', '00:00:00'),
(18, 5, 'Kamis', '00:00:00', '00:00:00'),
(19, 5, 'Jumat', '00:00:00', '00:00:00'),
(20, 5, 'Sabtu', '00:00:00', '00:00:00'),
(21, 5, 'Minggu', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kuliah`
--

CREATE TABLE `jadwal_kuliah` (
  `id` int(11) NOT NULL,
  `id_matkul` int(11) DEFAULT NULL,
  `hari` varchar(11) DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_kuliah`
--

INSERT INTO `jadwal_kuliah` (`id`, `id_matkul`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(25, 13, 'Senin', '08:00:00', '10:00:00'),
(26, 16, 'selasa', '13:00:00', '15:00:00'),
(27, 16, 'rabu', '09:00:00', '11:00:00'),
(28, 16, 'kamis', '13:00:00', '15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `karyawan_id` int(12) NOT NULL,
  `jabatan_id` int(12) NOT NULL,
  `karyawan_rfid` varchar(10) NOT NULL,
  `karyawan_nama` varchar(50) NOT NULL,
  `karyawan_nik` varchar(16) NOT NULL,
  `karyawan_jeniskelamin` enum('M','F') NOT NULL,
  `karyawan_lahir` date NOT NULL,
  `karyawan_nomorhp` varchar(20) NOT NULL,
  `karyawan_alamat` varchar(500) NOT NULL,
  `karyawan_foto` varchar(255) NOT NULL,
  `karyawan_status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`karyawan_id`, `jabatan_id`, `karyawan_rfid`, `karyawan_nama`, `karyawan_nik`, `karyawan_jeniskelamin`, `karyawan_lahir`, `karyawan_nomorhp`, `karyawan_alamat`, `karyawan_foto`, `karyawan_status`) VALUES
(9, 4, 'E3E64D15', 'nama1', '1234567890123456', 'M', '2022-07-17', '1234567890', 'jakarta', '82799958662d3e0b6971bd.png', '1');

-- --------------------------------------------------------

--
-- Table structure for table `libur`
--

CREATE TABLE `libur` (
  `libur_id` int(12) NOT NULL,
  `libur_keterangan` varchar(50) NOT NULL,
  `libur_dari` date NOT NULL,
  `libur_sampai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_prodi` int(11) DEFAULT NULL,
  `rfid_uid` varchar(50) NOT NULL,
  `foto_wajah` text DEFAULT NULL,
  `id_matkul` int(11) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `nama`, `id_prodi`, `rfid_uid`, `foto_wajah`, `id_matkul`, `password`) VALUES
('C030322109', 'David Ramadhan', 5, '77:70:7B:05', 'known_faces/david_ramadhan.jpg', 11, '12345'),
('C030322115', 'Apriansyah', 3, '14:77:7A:05', 'known_faces/apriansyah.jpg', 11, '1234567');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id_matkul` int(11) NOT NULL,
  `id_prodi` int(11) DEFAULT NULL,
  `nama_matkul` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id_matkul`, `id_prodi`, `nama_matkul`, `status`) VALUES
(10, 1, 'Algoritma dan Pemrograman', 0),
(11, 1, 'Pemrograman Web', 0),
(12, 1, 'Pemrograman Mobile', 0),
(13, 2, 'Kecerdasan Buatan', 0),
(14, 2, 'Sistem Operasi', 1),
(15, 3, 'Manajemen Proyek', 0),
(16, 4, 'Keamanan Jaringan', 0);

-- --------------------------------------------------------

--
-- Table structure for table `program_studi`
--

CREATE TABLE `program_studi` (
  `id_prodi` int(11) NOT NULL,
  `nama_prodi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_studi`
--

INSERT INTO `program_studi` (`id_prodi`, `nama_prodi`) VALUES
(1, 'Teknik Informatika'),
(2, 'Sistem Informasi'),
(3, 'Teknik Elektro'),
(4, 'Manajemen'),
(5, 'SIKC'),
(10, 'Matematika'),
(11, 'Kuda');

-- --------------------------------------------------------

--
-- Table structure for table `rekap`
--

CREATE TABLE `rekap` (
  `rekap_id` bigint(20) NOT NULL,
  `jadwal_id` int(12) NOT NULL,
  `karyawan_id` int(12) NOT NULL,
  `rekap_tanggal` date NOT NULL,
  `rekap_masuk` time DEFAULT NULL,
  `rekap_keluar` time DEFAULT NULL,
  `rekap_photomasuk` varchar(255) DEFAULT NULL,
  `status1` tinyint(2) NOT NULL DEFAULT 0,
  `rekap_photokeluar` varchar(255) DEFAULT NULL,
  `status2` tinyint(2) NOT NULL DEFAULT 0,
  `rekap_keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rekap`
--

INSERT INTO `rekap` (`rekap_id`, `jadwal_id`, `karyawan_id`, `rekap_tanggal`, `rekap_masuk`, `rekap_keluar`, `rekap_photomasuk`, `status1`, `rekap_photokeluar`, `status2`, `rekap_keterangan`) VALUES
(5, 8, 9, '2022-07-17', '17:13:18', '17:13:37', '2022.07.17_10:13:21.jpg', 0, '2022.07.17_10:13:38.jpg', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rfid_code`
--

CREATE TABLE `rfid_code` (
  `id` double NOT NULL,
  `rfid_code` varchar(64) NOT NULL,
  `used` int(11) NOT NULL DEFAULT 0,
  `time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rfid_code`
--

INSERT INTO `rfid_code` (`id`, `rfid_code`, `used`, `time_update`) VALUES
(1, 'E3E64D15', 1, '2022-07-17 10:13:10'),
(2, '43050017', 0, '2022-07-17 10:11:28'),
(3, 'A35F6817', 0, '2022-07-17 10:11:37');

-- --------------------------------------------------------

--
-- Table structure for table `rfid_terakhir`
--

CREATE TABLE `rfid_terakhir` (
  `id` int(11) NOT NULL,
  `rfid_uid` varchar(100) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfid_terakhir`
--

INSERT INTO `rfid_terakhir` (`id`, `rfid_uid`, `waktu`) VALUES
(686, '03:3E:7A:05', '2025-07-04 20:08:02'),
(687, '03:3E:7A:05', '2025-07-04 20:08:07'),
(688, 'F3:68:36:1A', '2025-07-08 15:39:45'),
(689, '77:70:7B:05', '2025-07-08 15:39:51'),
(690, 'F3:68:36:1A', '2025-07-08 15:40:01'),
(691, '03:3E:7A:05', '2025-07-08 15:40:07'),
(692, '03:3E:7A:05', '2025-07-08 15:40:25'),
(693, '03:3E:7A:05', '2025-07-08 15:41:25'),
(694, '03:3E:7A:05', '2025-07-08 15:46:22'),
(695, '14:77:7A:05', '2025-07-08 15:46:27'),
(696, '1E:43:7B:05', '2025-07-08 15:46:32'),
(697, '77:70:7B:05', '2025-07-08 15:46:38'),
(698, '77:70:7B:05', '2025-07-08 15:46:42'),
(699, 'F3:68:36:1A', '2025-07-08 15:46:46'),
(700, '03:3E:7A:05', '2025-07-08 15:48:17'),
(701, '14:77:7A:05', '2025-07-08 15:48:21'),
(702, '14:77:7A:05', '2025-07-08 15:48:54'),
(703, '14:77:7A:05', '2025-07-08 15:55:06'),
(704, '14:77:7A:05', '2025-07-08 16:03:13'),
(705, '14:77:7A:05', '2025-07-08 16:03:37'),
(706, '03:3E:7A:05', '2025-07-08 16:22:43'),
(707, '14:77:7A:05', '2025-07-08 16:22:51'),
(708, '1E:43:7B:05', '2025-07-08 16:35:37'),
(709, '77:70:7B:05', '2025-07-08 16:35:42'),
(710, 'F3:68:36:1A', '2025-07-08 16:35:50'),
(711, 'F3:68:36:1A', '2025-07-08 16:36:25'),
(712, '14:77:7A:05', '2025-07-08 16:36:29'),
(713, '14:77:7A:05', '2025-07-08 16:36:40'),
(714, '77:70:7B:05', '2025-07-08 16:36:43'),
(715, '03:3E:7A:05', '2025-07-08 16:36:47'),
(716, '1E:43:7B:05', '2025-07-08 16:36:51'),
(717, '03:3E:7A:05', '2025-07-08 16:37:05'),
(718, 'F3:68:36:1A', '2025-07-08 16:37:10'),
(719, '14:77:7A:05', '2025-07-08 16:37:16'),
(720, '77:70:7B:05', '2025-07-08 16:37:21'),
(721, '03:3E:7A:05', '2025-07-08 16:38:48'),
(722, '03:3E:7A:05', '2025-07-08 16:38:53'),
(723, '14:77:7A:05', '2025-07-08 16:38:57'),
(724, '14:77:7A:05', '2025-07-08 16:44:43'),
(725, '03:3E:7A:05', '2025-07-08 16:44:50'),
(726, '77:70:7B:05', '2025-07-08 16:44:55'),
(727, 'F3:68:36:1A', '2025-07-08 16:44:59'),
(728, '14:77:7A:05', '2025-07-08 16:45:37'),
(729, '1E:43:7B:05', '2025-07-08 17:50:05'),
(730, '77:70:7B:05', '2025-07-08 18:25:57'),
(731, '77:70:7B:05', '2025-07-09 17:35:21'),
(732, '14:77:7A:05', '2025-07-09 17:35:25'),
(733, 'F3:68:36:1A', '2025-07-09 17:35:28'),
(734, '03:3E:7A:05', '2025-07-09 17:35:32'),
(735, '1E:43:7B:05', '2025-07-09 17:35:36'),
(736, '03:3E:7A:05', '2025-07-09 17:40:08'),
(737, '1E:43:7B:05', '2025-07-09 17:40:13'),
(738, 'F3:68:36:1A', '2025-07-09 17:40:22'),
(739, '14:77:7A:05', '2025-07-09 17:40:27'),
(740, '14:77:7A:05', '2025-07-09 17:44:54'),
(741, '', '2025-07-09 18:00:15'),
(742, '', '2025-07-09 18:16:10'),
(743, '', '2025-07-09 18:16:16'),
(744, '', '2025-07-09 18:21:10'),
(745, '', '2025-07-09 18:21:16'),
(746, '', '2025-07-09 18:21:21'),
(747, '14:77:7A:05', '2025-07-09 18:21:26'),
(748, '', '2025-07-09 18:21:41'),
(749, '', '2025-07-09 18:21:45'),
(750, '', '2025-07-09 18:21:49'),
(751, '', '2025-07-09 18:21:59'),
(752, '', '2025-07-09 18:24:38'),
(753, '', '2025-07-09 18:24:47'),
(754, '', '2025-07-09 18:25:17'),
(755, '', '2025-07-09 18:35:17'),
(756, '', '2025-07-09 18:35:26'),
(757, '', '2025-07-09 18:35:33'),
(758, '', '2025-07-09 18:35:39'),
(759, '', '2025-07-09 18:36:07'),
(760, '', '2025-07-09 18:36:11'),
(761, '', '2025-07-09 18:36:19'),
(762, '', '2025-07-09 18:36:25'),
(763, '', '2025-07-09 18:45:48'),
(764, '', '2025-07-09 18:45:59'),
(765, '14:77:7A:05', '2025-07-09 18:54:58'),
(766, '', '2025-07-09 18:55:08'),
(767, '', '2025-07-09 18:56:59'),
(768, '', '2025-07-09 18:57:07'),
(769, '', '2025-07-09 18:58:58'),
(770, '', '2025-07-09 19:06:36'),
(771, '', '2025-07-09 19:10:39'),
(772, '14:77:7A:05', '2025-07-09 19:14:38'),
(773, '77:70:7B:05', '2025-07-09 19:14:45'),
(774, '77:70:7B:05', '2025-07-09 19:14:51'),
(775, '77:70:7B:05', '2025-07-09 19:15:11'),
(776, '77:70:7B:05', '2025-07-09 19:15:16'),
(777, '77:70:7B:05', '2025-07-09 19:15:23'),
(778, '77:70:7B:05', '2025-07-09 19:15:30'),
(779, '14:77:7A:05', '2025-07-09 19:15:40'),
(780, '77:70:7B:05', '2025-07-09 19:15:45'),
(781, '77:70:7B:05', '2025-07-09 19:15:55'),
(782, '14:77:7A:05', '2025-07-09 19:16:10'),
(783, '77:70:7B:05', '2025-07-09 19:16:24'),
(784, '77:70:7B:05', '2025-07-09 19:16:29'),
(785, '77:70:7B:05', '2025-07-09 19:16:33'),
(786, '77:70:7B:05', '2025-07-09 19:16:37'),
(787, '', '2025-07-09 19:16:44'),
(788, '', '2025-07-09 19:44:37'),
(789, '', '2025-07-09 19:44:44'),
(790, '', '2025-07-09 19:44:59'),
(791, '03:3E:7A:05', '2025-07-09 19:45:11'),
(792, '', '2025-07-09 19:45:19'),
(793, '', '2025-07-09 19:45:25'),
(794, '14:77:7A:05', '2025-07-09 19:45:32'),
(795, '', '2025-07-09 19:45:43'),
(796, '', '2025-07-09 19:50:03'),
(797, '', '2025-07-09 19:52:26'),
(798, '', '2025-07-09 19:53:44'),
(799, '', '2025-07-09 19:53:51'),
(800, '', '2025-07-09 19:53:56'),
(801, '', '2025-07-09 19:54:03'),
(802, '', '2025-07-09 19:54:09'),
(803, '', '2025-07-09 19:54:16'),
(804, '', '2025-07-10 03:49:13'),
(805, '', '2025-07-10 03:50:35'),
(806, '77:70:7B:05', '2025-07-10 03:51:52'),
(807, '77:70:7B:05', '2025-07-10 03:52:08'),
(808, '77:70:7B:05', '2025-07-10 03:52:12'),
(809, '14:77:7A:05', '2025-07-10 03:52:18'),
(810, '', '2025-07-10 03:52:23'),
(811, '', '2025-07-16 13:52:55'),
(812, '77:70:7B:05', '2025-07-16 14:02:38'),
(813, '77:70:7B:05', '2025-07-16 14:02:42'),
(814, '77:70:7B:05', '2025-07-16 14:02:46'),
(815, '77:70:7B:05', '2025-07-16 14:02:50'),
(816, '1E:43:7B:05', '2025-07-16 14:02:54'),
(817, '', '2025-07-16 14:03:00'),
(818, '', '2025-07-16 15:13:29'),
(819, '', '2025-07-16 15:14:20'),
(820, '', '2025-07-16 15:14:39'),
(821, '', '2025-07-16 15:23:08'),
(822, '', '2025-07-16 15:25:07'),
(823, '14:77:7A:05', '2025-07-16 15:36:53'),
(824, '14:77:7A:05', '2025-07-16 16:18:57'),
(825, '', '2025-07-16 16:19:37'),
(826, '', '2025-07-16 16:23:47'),
(827, '', '2025-07-16 16:24:58'),
(828, '', '2025-07-16 16:40:52'),
(829, '', '2025-07-16 16:47:49'),
(830, '', '2025-07-16 16:48:04'),
(831, '', '2025-07-16 16:48:12'),
(832, '', '2025-07-16 16:50:01'),
(833, '', '2025-07-16 16:50:10'),
(834, '', '2025-07-16 16:50:17'),
(835, '', '2025-07-16 16:59:37'),
(836, '', '2025-07-16 16:59:43'),
(837, '14:77:7A:05', '2025-07-16 16:59:53'),
(838, '', '2025-07-16 17:00:02'),
(839, '', '2025-07-16 17:05:08'),
(840, '', '2025-07-16 17:09:14'),
(841, '', '2025-07-17 08:41:17'),
(842, '1E:43:7B:05', '2025-07-17 08:46:56'),
(843, 'F3:68:36:1A', '2025-07-17 08:51:53'),
(844, 'F3:68:36:1A', '2025-07-17 08:52:10'),
(845, '1E:43:7B:05', '2025-07-17 08:52:19'),
(846, '1E:43:7B:05', '2025-07-17 08:52:23'),
(847, 'F3:68:36:1A', '2025-07-17 08:52:30'),
(848, '', '2025-07-17 08:52:55'),
(849, '', '2025-07-17 09:45:34'),
(850, '', '2025-07-17 09:45:55'),
(851, '', '2025-07-17 09:46:52'),
(852, '', '2025-07-17 09:47:09'),
(853, '', '2025-07-17 09:48:12'),
(854, '14:77:7A:05', '2025-07-17 09:48:17'),
(855, '', '2025-07-17 09:48:21'),
(856, '', '2025-07-17 09:51:17'),
(857, '', '2025-07-17 09:51:26'),
(858, '', '2025-07-17 09:51:31'),
(859, '', '2025-07-17 09:51:37'),
(860, '', '2025-07-17 09:54:38'),
(861, '', '2025-07-17 09:56:26'),
(862, '', '2025-07-20 16:54:35'),
(863, '', '2025-07-20 16:56:06'),
(864, '77:70:7B:05', '2025-07-20 16:56:15'),
(865, '', '2025-07-20 16:56:19'),
(866, '14:77:7A:05', '2025-07-20 16:56:32'),
(867, '', '2025-07-20 16:56:41'),
(868, '', '2025-07-20 16:59:29'),
(869, '', '2025-07-20 16:59:35'),
(870, '', '2025-07-20 16:59:41'),
(871, '', '2025-07-20 16:59:47');

-- --------------------------------------------------------

--
-- Table structure for table `users_logs`
--

CREATE TABLE `users_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `rfid` varchar(20) NOT NULL,
  `image_url` varchar(100) NOT NULL,
  `checkindate` date NOT NULL,
  `checkintime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users_logs`
--

INSERT INTO `users_logs` (`id`, `username`, `rfid`, `image_url`, `checkindate`, `checkintime`) VALUES
(1, 'Rudi', 'B23D221B', 'mages/02012021011428.jpg', '2022-07-09', '19:27:15'),
(2, 'Rudi', 'B23D221B', '', '2022-07-09', '19:27:28'),
(3, 'Wardana Adiaksa', 'A19E551B', '', '2022-07-09', '19:27:37'),
(4, 'Badrun Alam', 'F3C1D89A', '', '2022-07-09', '19:28:58'),
(5, 'Rudi', 'B23D221B', '', '2022-07-09', '19:50:45'),
(6, 'Wardana Adiaksa', 'A19E551B', '', '2022-07-09', '19:50:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absen`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_matkul` (`id_matkul`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_username` (`admin_username`);

--
-- Indexes for table `data_users`
--
ALTER TABLE `data_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `izin`
--
ALTER TABLE `izin`
  ADD PRIMARY KEY (`izin_id`),
  ADD KEY `izin karywanid to karyawanid` (`karyawan_id`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`jabatan_id`),
  ADD UNIQUE KEY `jabatan_nama` (`jabatan_nama`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`jadwal_id`),
  ADD KEY `jadwal jabatanid to jabatanid` (`jabatan_id`);

--
-- Indexes for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_matkul` (`id_matkul`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`karyawan_id`),
  ADD UNIQUE KEY `karyawan_rfid` (`karyawan_rfid`),
  ADD UNIQUE KEY `karyawan_nik` (`karyawan_nik`),
  ADD KEY `karyawan jabatanid to jabatanid` (`jabatan_id`);

--
-- Indexes for table `libur`
--
ALTER TABLE `libur`
  ADD PRIMARY KEY (`libur_id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`),
  ADD UNIQUE KEY `rfid_uid` (`rfid_uid`),
  ADD KEY `id_prodi` (`id_prodi`),
  ADD KEY `fk_mahasiswa_matkul` (`id_matkul`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id_matkul`),
  ADD KEY `id_prodi` (`id_prodi`);

--
-- Indexes for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id_prodi`);

--
-- Indexes for table `rekap`
--
ALTER TABLE `rekap`
  ADD PRIMARY KEY (`rekap_id`),
  ADD KEY `rekap karyawanid to karyawanid` (`karyawan_id`),
  ADD KEY `rekapjadwalfk` (`jadwal_id`);

--
-- Indexes for table `rfid_code`
--
ALTER TABLE `rfid_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rfid_terakhir`
--
ALTER TABLE `rfid_terakhir`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_logs`
--
ALTER TABLE `users_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=256;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_users`
--
ALTER TABLE `data_users`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `izin`
--
ALTER TABLE `izin`
  MODIFY `izin_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `jabatan_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `jadwal_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `karyawan_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `libur`
--
ALTER TABLE `libur`
  MODIFY `libur_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id_matkul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id_prodi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rekap`
--
ALTER TABLE `rekap`
  MODIFY `rekap_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rfid_code`
--
ALTER TABLE `rfid_code`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rfid_terakhir`
--
ALTER TABLE `rfid_terakhir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=872;

--
-- AUTO_INCREMENT for table `users_logs`
--
ALTER TABLE `users_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`) ON DELETE CASCADE;

--
-- Constraints for table `izin`
--
ALTER TABLE `izin`
  ADD CONSTRAINT `izin karywanid to karyawanid` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`karyawan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal jabatanid to jabatanid` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatan` (`jabatan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD CONSTRAINT `jadwal_kuliah_ibfk_1` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`);

--
-- Constraints for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `karyawan jabatanid to jabatanid` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatan` (`jabatan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `fk_mahasiswa_matkul` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `program_studi` (`id_prodi`) ON DELETE CASCADE;

--
-- Constraints for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `program_studi` (`id_prodi`) ON DELETE CASCADE;

--
-- Constraints for table `rekap`
--
ALTER TABLE `rekap`
  ADD CONSTRAINT `rekap karyawanid to karyawanid` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`karyawan_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rekapjadwalfk` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`jadwal_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
