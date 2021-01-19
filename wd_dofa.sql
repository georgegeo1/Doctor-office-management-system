-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2018 at 07:54 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wd_dofa`
--
CREATE DATABASE IF NOT EXISTS `wd_dofa` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `wd_dofa`;

DELIMITER $$
--
-- Functions
--
DROP FUNCTION IF EXISTS `getDoctorAppCntOfPatient`$$
CREATE FUNCTION `getDoctorAppCntOfPatient` (`pi_doctor_id` INT, `pi_patient_id` INT) RETURNS INT(11) 
COMMENT 'Returns the number of (not cancelled) appointments of a specific patient (pi_patient_id) for a specific doctor (pi_doctor_id)'
BEGIN
 declare cnt int;

 select count(*) into cnt
 from appointment a
 where a.appointment_doctor_id = pi_doctor_id 
       and a.appointment_patient_id = pi_patient_id 
		 and a.appointment_cncl = '0';
       
 return cnt;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

DROP TABLE IF EXISTS `appointment`;
CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `appointment_patient_id` int(11) NOT NULL,
  `appointment_time` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appointment_doctor_id` int(11) NOT NULL,
  `appointment_cncl` varchar(1) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT 'The appointment''s cancel state (0.No, 1.Yes)',
  `appointment_cncl_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appointment_state` varchar(1) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT 'The appointment''s state (0. Pending, 1. Accepted, 2.Rejected, 3. Done)',
  `appointment_user_id` int(11) NOT NULL COMMENT 'The id of the system user who inserted the appointment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `appointments`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `appointments`;
CREATE TABLE `appointments` (
`appointment_id` int(11)
,`appointment_patient_id` int(11)
,`appointment_time` varchar(5)
,`appointment_date` varchar(10)
,`appointment_reason` varchar(255)
,`appointment_doctor_id` int(11)
,`appointment_cncl` varchar(1)
,`appointment_cncl_reason` varchar(255)
,`appointment_state` varchar(1)
,`doctor_firstname` varchar(255)
,`doctor_lastname` varchar(255)
,`user_firstname` varchar(255)
,`user_lastname` varchar(255)
,`appointment_cncl_descr` varchar(3)
,`appointment_state_descr` varchar(9)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `available_worktimes`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `available_worktimes`;
CREATE TABLE `available_worktimes` (
`doctor_worktime_id` int(11)
,`doctor_worktime_date` date
,`doctor_worktime_time` varchar(5)
,`doctor_worktime_doctor_id` int(11)
,`doctor_specialization` varchar(255)
,`doctor_lastname` varchar(255)
,`doctor_firstname` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

DROP TABLE IF EXISTS `doctor`;
CREATE TABLE `doctor` (
  `doctor_id` int(11) NOT NULL,
  `doctor_firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_specialization` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_cv` varchar(4000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_photo` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `doctors`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `doctors`;
CREATE TABLE `doctors` (
`doctor_id` int(11)
,`doctor_firstname` varchar(255)
,`doctor_lastname` varchar(255)
,`doctor_specialization` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_office`
--

DROP TABLE IF EXISTS `doctor_office`;
CREATE TABLE `doctor_office` (
  `doctor_office_id` int(11) NOT NULL DEFAULT '1',
  `doctor_office_contact_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_office_contact_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_office_contact_zip` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_office_contact_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_office_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `doctor_office`
--

INSERT INTO `doctor_office` (`doctor_office_id`, `doctor_office_contact_address`, `doctor_office_contact_phone`, `doctor_office_contact_zip`, `doctor_office_contact_email`, `doctor_office_name`) VALUES
(1, 'Lampraki 45', '3242334232', '34526', 'dofawpp@hotmail.com', 'The Medical Center');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_worktime`
--

DROP TABLE IF EXISTS `doctor_worktime`;
CREATE TABLE `doctor_worktime` (
  `doctor_worktime_id` int(11) NOT NULL,
  `doctor_worktime_date` date NOT NULL,
  `doctor_worktime_time` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `doctor_worktime_doctor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Doctors working hours (each record represents a valid doctor''s working hour)';

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `options_id` int(11) NOT NULL DEFAULT '1',
  `options_mail_host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `options_mail_username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `options_mail_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Generic system options';

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`options_id`, `options_mail_host`, `options_mail_username`, `options_mail_password`) VALUES
(1, 'smtp.live.com', 'dofawpp@hotmail.com', 'dofap@ssw0rd');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'User type (0.patient, 1.secretary, 2.administrator)',
  `user_active` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT 'User state (0. disabled, 1. active)',
  `user_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_zip` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='System users';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_password`, `user_firstname`, `user_lastname`, `user_type`, `user_active`, `user_address`, `user_phone`, `user_zip`, `user_email`) VALUES
(10, 'admin', 'admin', 'Admin', 'User', '2', '1', '', '', '', ''),
(11, 'power', 'power', 'Powser', 'User', '1', '1', '', '', '', ''),
(12, 'user1', 'user1', 'Demo', 'User1', '0', '1', '', '', '', ''),
(14, 'user2', 'user2', 'Demo', 'User2', '0', '1', '', '', '', '');

-- --------------------------------------------------------

--
-- Stand-in structure for view `users`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `users`;
CREATE TABLE `users` (
`user_id` int(11)
,`user_name` varchar(50)
,`user_password` varchar(50)
,`user_firstname` varchar(255)
,`user_lastname` varchar(255)
,`user_active` varchar(1)
,`user_active_descr` varchar(3)
,`user_type` varchar(1)
,`user_type_descr` varchar(12)
);

-- --------------------------------------------------------

--
-- Structure for view `appointments`
--
DROP TABLE IF EXISTS `appointments`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `appointments`  AS  select `appointment`.`appointment_id` AS `appointment_id`,`appointment`.`appointment_patient_id` AS `appointment_patient_id`,`appointment`.`appointment_time` AS `appointment_time`,date_format(`appointment`.`appointment_date`,'%d/%m/%Y') AS `appointment_date`,`appointment`.`appointment_reason` AS `appointment_reason`,`appointment`.`appointment_doctor_id` AS `appointment_doctor_id`,`appointment`.`appointment_cncl` AS `appointment_cncl`,`appointment`.`appointment_cncl_reason` AS `appointment_cncl_reason`,`appointment`.`appointment_state` AS `appointment_state`,`doc`.`doctor_firstname` AS `doctor_firstname`,`doc`.`doctor_lastname` AS `doctor_lastname`,`usr`.`user_firstname` AS `user_firstname`,`usr`.`user_lastname` AS `user_lastname`,(case when (`appointment`.`appointment_cncl` = '0') then 'NO' else 'YES' end) AS `appointment_cncl_descr`,(case when (`appointment`.`appointment_state` = '0') then 'PENDING' when (`appointment`.`appointment_state` = '1') then 'ACCEPTED' when (`appointment`.`appointment_state` = '2') then 'REJECTED' else 'COMPLETED' end) AS `appointment_state_descr` from ((`appointment` join `doctor` `doc` on((`appointment`.`appointment_doctor_id` = `doc`.`doctor_id`))) join `user` `usr` on((`appointment`.`appointment_patient_id` = `usr`.`user_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `available_worktimes`
--
DROP TABLE IF EXISTS `available_worktimes`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `available_worktimes`  AS  select `dw`.`doctor_worktime_id` AS `doctor_worktime_id`,`dw`.`doctor_worktime_date` AS `doctor_worktime_date`,`dw`.`doctor_worktime_time` AS `doctor_worktime_time`,`dw`.`doctor_worktime_doctor_id` AS `doctor_worktime_doctor_id`,`d`.`doctor_specialization` AS `doctor_specialization`,`d`.`doctor_lastname` AS `doctor_lastname`,`d`.`doctor_firstname` AS `doctor_firstname` from (`doctor_worktime` `dw` join `doctor` `d` on((`dw`.`doctor_worktime_doctor_id` = `d`.`doctor_id`))) where ((not(exists(select 1 from `appointment` `a` where ((`a`.`appointment_doctor_id` = `dw`.`doctor_worktime_doctor_id`) and (`a`.`appointment_date` = `dw`.`doctor_worktime_date`) and (`a`.`appointment_time` = `dw`.`doctor_worktime_time`) and (`a`.`appointment_cncl` = '0'))))) and (`dw`.`doctor_worktime_date` >= sysdate())) ;

-- --------------------------------------------------------

--
-- Structure for view `doctors`
--
DROP TABLE IF EXISTS `doctors`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `doctors`  AS  select `doctor`.`doctor_id` AS `doctor_id`,`doctor`.`doctor_firstname` AS `doctor_firstname`,`doctor`.`doctor_lastname` AS `doctor_lastname`,`doctor`.`doctor_specialization` AS `doctor_specialization` from `doctor` ;

-- --------------------------------------------------------

--
-- Structure for view `users`
--
DROP TABLE IF EXISTS `users`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `users`  AS  select `user`.`user_id` AS `user_id`,`user`.`user_name` AS `user_name`,`user`.`user_password` AS `user_password`,`user`.`user_firstname` AS `user_firstname`,`user`.`user_lastname` AS `user_lastname`,`user`.`user_active` AS `user_active`,(case when (`user`.`user_active` = '0') then 'NO' else 'YES' end) AS `user_active_descr`,`user`.`user_type` AS `user_type`,(case when (`user`.`user_type` = '0') then 'PATIENT' when (`user`.`user_type` = '1') then 'SECRETARY' else 'ADMINISTRATOR' end) AS `user_type_descr` from `user` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `appointment_doctor_fk` (`appointment_doctor_id`),
  ADD KEY `appointment_patient_fk` (`appointment_patient_id`),
  ADD KEY `appointment_user_fk` (`appointment_user_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `doctor_office`
--
ALTER TABLE `doctor_office`
  ADD PRIMARY KEY (`doctor_office_id`);

--
-- Indexes for table `doctor_worktime`
--
ALTER TABLE `doctor_worktime`
  ADD PRIMARY KEY (`doctor_worktime_id`),
  ADD UNIQUE KEY `doctor_worktime_uk` (`doctor_worktime_doctor_id`,`doctor_worktime_date`,`doctor_worktime_time`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`options_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name_uq` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_worktime`
--
ALTER TABLE `doctor_worktime`
  MODIFY `doctor_worktime_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_doctor_fk` FOREIGN KEY (`appointment_doctor_id`) REFERENCES `doctor` (`doctor_id`),
  ADD CONSTRAINT `appointment_patient_fk` FOREIGN KEY (`appointment_patient_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `appointment_user_fk` FOREIGN KEY (`appointment_user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `doctor_worktime`
--
ALTER TABLE `doctor_worktime`
  ADD CONSTRAINT `doctor_worktime_doctor_fk` FOREIGN KEY (`doctor_worktime_doctor_id`) REFERENCES `doctor` (`doctor_id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
