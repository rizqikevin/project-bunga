-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Agu 2025 pada 02.28
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sitibungacinta`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `clock_in` time DEFAULT NULL,
  `clock_out` time DEFAULT NULL,
  `status` enum('hadir','terlambat','izin','sakit') NOT NULL DEFAULT 'hadir',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `attendances`
--

INSERT INTO `attendances` (`id`, `user_id`, `date`, `clock_in`, `clock_out`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-08-23', '19:08:51', '19:10:05', 'terlambat', NULL, '2025-08-23 12:08:51', '2025-08-23 12:10:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2024_03_24_000001_create_employees_table', 1),
(3, '2024_03_24_200000_create_attendances_table', 1),
(4, '2024_03_24_create_add_employee_fields_to_users_table', 1),
(5, '2025_04_07_223500_create_qrcode_table', 1),
(6, '2025_06_21_195235_create_sessions_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `qrcode`
--

CREATE TABLE `qrcode` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `qr_id` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `qr_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `qrcode`
--

INSERT INTO `qrcode` (`id`, `name`, `qr_id`, `location`, `qr_path`, `created_at`, `updated_at`) VALUES
(1, 'Absensi 23 Aug 2025 10:44:20', 'JSP-20250823104420-b3f8ff', 'Office', 'qrcodes/JSP-20250823104420-b3f8ff.png', '2025-08-23 03:44:20', '2025-08-23 03:44:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `employee_id`, `name`, `position`, `email`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ADM001', 'Admin', 'Administrator', 'admin@gmail.com', '$2y$12$lGA21iR.WfBIGd50JregDOUBSFPrxiw/GpphtHMv3WrOrp3XtF2vW', 'admin', NULL, '2025-08-23 03:14:43', '2025-08-23 03:14:43'),
(2, 'EMP001', 'John Doe', 'Staff', 'emp001@company.com', '$2y$12$ZR.TPmmmhQtvlm4QSI2FAu0Di2M1CTYtSInmZAPj.NUFvRdnW/ZIm', 'employee', NULL, '2025-08-23 03:14:43', '2025-08-23 03:14:43'),
(3, 'K001', 'Siti Bunga Cinta', 'Head Office', 'K001@company.com', '$2y$12$0OYSBl6ClM/guxF0iQbJaekqJX2nXMnPCwIuMMSR/GDSzZXOM.Ape', 'employee', NULL, '2025-08-23 04:12:02', '2025-08-23 04:12:02'),
(4, 'k002', 'bunga', 'staff', 'k002@company.com', '$2y$12$SL/8YyLauZxv9psrTIc2LusGbOD2pAueZKIdZvlmpUu6sUhQwrnCi', 'employee', NULL, '2025-08-23 05:55:04', '2025-08-23 05:55:04'),
(5, 'k003', 'Gilang', 'HRD', 'k003@company.com', '$2y$12$Wid3V/R52pKAhTyveQV7nOKemRCWGlxLyrN3rPcjHPKKzcH0FYiHW', 'employee', NULL, '2025-08-23 12:45:36', '2025-08-23 12:45:36');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD KEY `employees_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `qrcode`
--
ALTER TABLE `qrcode`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qrcode_qr_id_unique` (`qr_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_employee_id_unique` (`employee_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `qrcode`
--
ALTER TABLE `qrcode`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
