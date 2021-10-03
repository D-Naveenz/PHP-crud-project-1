-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2021 at 09:13 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendant`
--

CREATE TABLE `attendant` (
  `EmpNo` varchar(6) NOT NULL,
  `HourlyRate` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendant`
--

INSERT INTO `attendant` (`EmpNo`, `HourlyRate`) VALUES
('E0003', '50.05'),
('E0004', '60.00'),
('E0006', '40.50');

-- --------------------------------------------------------

--
-- Table structure for table `bed`
--

CREATE TABLE `bed` (
  `Bed_ID` varchar(5) NOT NULL,
  `Ward_ID` varchar(5) NOT NULL,
  `Availability` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bed`
--

INSERT INTO `bed` (`Bed_ID`, `Ward_ID`, `Availability`) VALUES
('B0001', 'WD001', 0),
('B0002', 'WD001', 0),
('B0003', 'WD002', 1),
('B0004', 'WD002', 1),
('B0005', 'WD003', 1),
('B0006', 'WD003', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cleaner`
--

CREATE TABLE `cleaner` (
  `EmpNo` varchar(6) NOT NULL,
  `ContractNo` int(10) NOT NULL,
  `Start_date` date NOT NULL,
  `End_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cleaner`
--

INSERT INTO `cleaner` (`EmpNo`, `ContractNo`, `Start_date`, `End_date`) VALUES
('E0003', 1000000001, '2020-01-01', '2022-01-01'),
('E0008', 1000000002, '2021-01-01', '2021-12-01'),
('E0014', 1000000003, '2019-05-08', '2021-12-20');

-- --------------------------------------------------------

--
-- Table structure for table `diagnosis`
--

CREATE TABLE `diagnosis` (
  `Diagnosis_Code` varchar(5) NOT NULL,
  `Diagnosis_Name` varchar(20) NOT NULL,
  `Description` varchar(50) NOT NULL,
  `Doctor_ID` varchar(6) NOT NULL,
  `Patient_ID` varchar(6) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `diagnosis`
--

INSERT INTO `diagnosis` (`Diagnosis_Code`, `Diagnosis_Name`, `Description`, `Doctor_ID`, `Patient_ID`, `Date`, `Time`) VALUES
('DG001', 'Cholesterol', 'Abnormal cholesterol levels', 'E0001', 'PT0001', '2021-09-01', '13:04:55'),
('DG002', 'Kidney Disease', 'Need kidney transplant or dialysis', 'E0007', 'PT0002', '2021-09-01', '08:04:55'),
('DG003', 'Eyes Disease', 'Need wear Specs', 'E0002', 'PT0003', '2021-08-31', '13:03:25');

-- --------------------------------------------------------

--
-- Table structure for table `diagnosticunit`
--

CREATE TABLE `diagnosticunit` (
  `Unit_ID` varchar(5) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `PCU_ID` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `diagnosticunit`
--

INSERT INTO `diagnosticunit` (`Unit_ID`, `Name`, `PCU_ID`) VALUES
('DIG01', 'Endoscopy', 'PCU01'),
('DIG02', 'X-ray', 'PCU02'),
('DIG03', 'Clinical Studies', 'PCU02'),
('DIG04', 'Radiodiagnosis', 'PCU01');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `EmpNo` varchar(6) NOT NULL,
  `DEA` varchar(6) NOT NULL,
  `Area_of_Speciality` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`EmpNo`, `DEA`, `Area_of_Speciality`) VALUES
('E0001', 'DEA001', 'Heart'),
('E0002', 'DEA003', 'Eyes'),
('E0005', 'DEA004', 'Kidney'),
('E0007', 'DEA002', 'Kidney');

-- --------------------------------------------------------

--
-- Table structure for table `drug`
--

CREATE TABLE `drug` (
  `Drug_Code` varchar(5) NOT NULL,
  `Name` varchar(40) NOT NULL,
  `Unit_Cost` decimal(4,2) NOT NULL,
  `Type` varchar(20) NOT NULL,
  `Treatment_Code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drug`
--

INSERT INTO `drug` (`Drug_Code`, `Name`, `Unit_Cost`, `Type`, `Treatment_Code`) VALUES
('D0001', 'Panadol', '0.60', 'Tablet', 'TR111'),
('D005', 'Cetrecine', '0.80', 'Liquid', 'TR112'),
('D1007', 'Asprine', '0.75', 'Tablet', 'TR004');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contact`
--

CREATE TABLE `emergency_contact` (
  `Patient_ID` varchar(6) NOT NULL,
  `FName` varchar(10) NOT NULL,
  `LName` varchar(20) NOT NULL,
  `Relationship` varchar(10) NOT NULL,
  `Address` varchar(40) NOT NULL,
  `ContactNo` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emergency_contact`
--

INSERT INTO `emergency_contact` (`Patient_ID`, `FName`, `LName`, `Relationship`, `Address`, `ContactNo`) VALUES
('PT0001', 'Chandana', 'Liyanage', 'Son', '400/4,Saranankara Road,Pamankada', 784534234),
('PT0002', 'Nirmala', 'Silva', 'Wife', '65/A,Halpita Road,Kahathuduwa', 712390992),
('PT0005', 'Sunil', 'Marasinghe', 'Father', 'No 35,Fort Road,Hambanthota.', 705634287);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EmpNo` varchar(6) NOT NULL,
  `Password` varchar(15) CHARACTER SET utf8 NOT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `ContactNo` int(10) DEFAULT NULL,
  `Working_status` varchar(40) DEFAULT NULL,
  `Type` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmpNo`, `Password`, `Name`, `Address`, `ContactNo`, `Working_status`, `Type`) VALUES
('E0001', '', 'Kamal Perera', '100/3.Mradana,Colombo', 772223223, 'Part-Time', 'Medical'),
('E0002', '', 'Sumudu Galmal', '20/5, Temple Road, Kelaniya', 721212212, 'Full-Time', 'Medical'),
('E0003', '', 'Sumudu Manaskantha', '101/B, Galwala Road, Siripura', 758871413, 'Part-Time', 'Non medical'),
('E0004', '', 'Sampath Perera', '220/B, Flower Road, Gampaha', 712334345, 'Full-Time', 'Non medical'),
('E0005', '', 'Nimal Fernando', '100/4, Nilwala Road, Polonnaruwa', 773452323, 'Part-Time', 'Medical'),
('E0006', '', 'Saman Kumara', '623/A, Hawlock Town, Colombo 05', 712334345, 'Part-Time', 'Non medical'),
('E0007', '', 'Kumudini Maheshika', '505/E, Saman Road, Kalutara', 762897687, 'Part-Time', 'Medical'),
('E0008', '', 'Kumari Rathnayake', '110/A, Galle Road, Moratuwa', 774563412, 'Part-Time', 'Non medical'),
('E0009', '', 'Niyomal Rangajeewa', '45/8,Uyanwatta,Nuwara.', 704523791, 'Part-Time', 'Medical'),
('E0010', 'q1w2e3r4', 'Kamal Perera', '120/3,Nawam mawatha,Colombo 05.', 705678294, 'Full-Time', 'Medical'),
('E0011', '', 'Malik Kumara', '45/2,Kumara Mw,Kurunegala.', 773419632, 'Full-Time', 'Medical'),
('E0012', '', 'Sarath Weerakumara', '67/2,Galle Road,Mahiyanganya.', 763419631, 'Part-Time', 'Medical'),
('E0013', '', 'Kumudu Gayashan', '31/1,Nuwara Road,Kegall.', 763412926, 'Full-Time', 'Non medical'),
('E0014', '', 'Sirimal Kodithuwakku', 'NO. 34,New lane,Galle.', 703425947, 'Part-Time', 'Non medical');

-- --------------------------------------------------------

--
-- Table structure for table `employee_assign`
--

CREATE TABLE `employee_assign` (
  `EmpNo` varchar(6) NOT NULL,
  `PCU_ID` varchar(5) NOT NULL,
  `Hours_Worked` int(2) NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_assign`
--

INSERT INTO `employee_assign` (`EmpNo`, `PCU_ID`, `Hours_Worked`, `Date`) VALUES
('E0001', 'PCU01', 12, '2021-08-26'),
('E0002', 'PCU03', 10, '2021-08-30'),
('E0003', 'PCU03', 10, '2021-08-28'),
('E0004', 'PCU01', 9, '2021-08-29'),
('E0005', 'PCU04', 8, '2021-08-31'),
('E0006', 'PCU02', 7, '2021-08-31'),
('E0007', 'PCU02', 10, '2021-08-27'),
('E0008', 'PCU04', 10, '2021-08-29');

-- --------------------------------------------------------

--
-- Table structure for table `in_patient`
--

CREATE TABLE `in_patient` (
  `Patient_ID` varchar(6) NOT NULL,
  `DOB` date NOT NULL,
  `Admitted_Date` date NOT NULL,
  `Admitted_Time` time NOT NULL,
  `Discharge_Date` date DEFAULT NULL,
  `Discharge_Time` time DEFAULT NULL,
  `PC_Doctor` varchar(6) NOT NULL,
  `Bed_ID` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `in_patient`
--

INSERT INTO `in_patient` (`Patient_ID`, `DOB`, `Admitted_Date`, `Admitted_Time`, `Discharge_Date`, `Discharge_Time`, `PC_Doctor`, `Bed_ID`) VALUES
('PT0001', '1991-09-01', '2021-08-28', '13:00:00', NULL, NULL, 'E0001', 'B0001'),
('PT0002', '1972-08-21', '2021-08-30', '18:20:00', NULL, NULL, 'E0007', 'B0002'),
('PT0005', '1995-04-20', '2021-07-03', '16:10:34', '2021-08-27', '15:30:50', 'E0007', 'B0003');

-- --------------------------------------------------------

--
-- Table structure for table `medicalstaff`
--

CREATE TABLE `medicalstaff` (
  `EmpNo` varchar(6) NOT NULL,
  `MCRegNo` varchar(5) NOT NULL,
  `JoinedDate` date NOT NULL,
  `ResignedDate` date DEFAULT NULL,
  `Type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medicalstaff`
--

INSERT INTO `medicalstaff` (`EmpNo`, `MCRegNo`, `JoinedDate`, `ResignedDate`, `Type`) VALUES
('E0001', 'MC001', '2021-01-01', '2021-09-01', 'Doctor'),
('E0002', 'MC002', '2019-01-01', NULL, 'Nurse'),
('E0005', 'MC004', '2020-09-01', '2021-09-01', 'Nurse'),
('E0007', 'MC003', '2020-01-01', NULL, 'Doctor'),
('E0009', 'MC005', '2020-05-14', NULL, 'Doctor'),
('E0010', 'MC006', '2019-08-24', NULL, 'Nurse'),
('E0011', 'MC007', '2018-05-07', NULL, 'Nurse'),
('E0012', 'MC008', '2016-06-27', NULL, 'Doctor');

-- --------------------------------------------------------

--
-- Table structure for table `nonmedical`
--

CREATE TABLE `nonmedical` (
  `EmpNo` varchar(6) NOT NULL,
  `Type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nonmedical`
--

INSERT INTO `nonmedical` (`EmpNo`, `Type`) VALUES
('E0003', 'Cleaner'),
('E0004', 'Attendant'),
('E0006', 'Attendant'),
('E0008', 'Cleaner'),
('E0013', 'Attendant'),
('E0014', 'Cleaner');

-- --------------------------------------------------------

--
-- Table structure for table `nurse`
--

CREATE TABLE `nurse` (
  `EmpNo` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nurse`
--

INSERT INTO `nurse` (`EmpNo`) VALUES
('E0002'),
('E0005'),
('E0010'),
('E0011');

-- --------------------------------------------------------

--
-- Table structure for table `out_patient`
--

CREATE TABLE `out_patient` (
  `Patient_ID` varchar(6) NOT NULL,
  `Arrived_Date` date NOT NULL,
  `Arrived_Time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `out_patient`
--

INSERT INTO `out_patient` (`Patient_ID`, `Arrived_Date`, `Arrived_Time`) VALUES
('PT0003', '2021-08-31', '13:03:25'),
('PT0004', '2021-09-01', '09:03:25'),
('PT0006', '2021-08-20', '15:40:05');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `Patient_ID` varchar(6) NOT NULL,
  `Name` varchar(40) NOT NULL,
  `Type` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`Patient_ID`, `Name`, `Type`) VALUES
('PT0001', 'Upali Silva', 'Inpatient'),
('PT0002', 'Sunimal Bandara', 'Inpatient'),
('PT0003', 'Anuhas Fernando', 'Outpatient'),
('PT0004', 'Manitha Abeysinghe', 'Outpatient'),
('PT0005', 'Nimal Kostha', 'Inpatient'),
('PT0006', 'Krishan Muthumala', 'Outpatient');

-- --------------------------------------------------------

--
-- Table structure for table `patient_insurance`
--

CREATE TABLE `patient_insurance` (
  `Patient_ID` varchar(6) NOT NULL,
  `Company_Name` varchar(20) NOT NULL,
  `Branch_Name` varchar(20) NOT NULL,
  `Address` varchar(40) NOT NULL,
  `ContactNo` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient_insurance`
--

INSERT INTO `patient_insurance` (`Patient_ID`, `Company_Name`, `Branch_Name`, `Address`, `ContactNo`) VALUES
('PT0001', 'Ceylinco Insurance', 'Thimbirigasyaya', '100/5,Thimbirigasyaya,Colombo', 1127867541),
('PT0002', 'AIA Insurence', 'Colombo', '7th floor, World Trade center, Colombo.', 114587238),
('PT0005', 'Sri Lanka Insurance', 'Matara', 'NO. 45,Sudassana Mw,Matara.', 417623873);

-- --------------------------------------------------------

--
-- Table structure for table `patient_record`
--

CREATE TABLE `patient_record` (
  `Patient_ID` varchar(6) NOT NULL,
  `Nurse_ID` varchar(6) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Weight` decimal(6,2) NOT NULL,
  `Blood_Pressure` varchar(7) NOT NULL,
  `Pulse` int(3) NOT NULL,
  `Temperature` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient_record`
--

INSERT INTO `patient_record` (`Patient_ID`, `Nurse_ID`, `Date`, `Time`, `Weight`, `Blood_Pressure`, `Pulse`, `Temperature`) VALUES
('PT0001', 'E0002', '2021-08-29', '12:53:28', '75.20', '60-110', 72, 37),
('PT0002', 'E0005', '2021-08-30', '11:53:28', '92.80', '90-150', 99, 39),
('PT0003', 'E0010', '2021-09-01', '09:30:30', '65.09', '70-115', 76, 38);

-- --------------------------------------------------------

--
-- Table structure for table `patient_symptoms`
--

CREATE TABLE `patient_symptoms` (
  `Patient_ID` varchar(6) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Symptom` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient_symptoms`
--

INSERT INTO `patient_symptoms` (`Patient_ID`, `Date`, `Time`, `Symptom`) VALUES
('PT0001', '2021-08-29', '12:53:28', 'Headache'),
('PT0002', '2021-08-03', '11:53:28', 'High Blood Preasure'),
('PT0003', '2021-09-01', '09:30:30', 'Poor Vision');

-- --------------------------------------------------------

--
-- Table structure for table `patient_treatment`
--

CREATE TABLE `patient_treatment` (
  `Doctor_ID` varchar(6) NOT NULL,
  `Patient_ID` varchar(6) NOT NULL,
  `Treatment_Code` varchar(5) NOT NULL,
  `Diagnosis_Code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient_treatment`
--

INSERT INTO `patient_treatment` (`Doctor_ID`, `Patient_ID`, `Treatment_Code`, `Diagnosis_Code`) VALUES
('E0001', 'PT0003', 'TR004', 'DG001'),
('E0002', 'PT0003', 'TR111', 'DG003'),
('E0007', 'PT0002', 'TR002', 'DG002');

-- --------------------------------------------------------

--
-- Table structure for table `pcu`
--

CREATE TABLE `pcu` (
  `PCU_ID` varchar(5) NOT NULL,
  `In-Charge` varchar(6) NOT NULL,
  `Type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pcu`
--

INSERT INTO `pcu` (`PCU_ID`, `In-Charge`, `Type`) VALUES
('PCU01', 'E0001', 'Diagnose Unit'),
('PCU02', 'E0007', 'Diagnose Unit'),
('PCU03', 'E0002', 'Ward'),
('PCU04', 'E0005', 'Ward');

-- --------------------------------------------------------

--
-- Table structure for table `supplies`
--

CREATE TABLE `supplies` (
  `Drug_Code` varchar(5) NOT NULL,
  `Reg_No` varchar(5) NOT NULL,
  `Supplied_Date` date NOT NULL,
  `Drug_Type` varchar(20) NOT NULL,
  `Unit_Cost` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Total_Cost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplies`
--

INSERT INTO `supplies` (`Drug_Code`, `Reg_No`, `Supplied_Date`, `Drug_Type`, `Unit_Cost`, `Quantity`, `Total_Cost`) VALUES
('D0001', 'R0005', '2021-09-12', 'Tablet', 234, 12, 12),
('D005', 'R1212', '2021-08-20', 'Liquid', 1, 20, 16),
('D1007', 'R3434', '2021-05-17', 'Tablet', 1, 36, 21);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `Test_Code` varchar(5) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Cost` int(11) NOT NULL,
  `Treatment_Code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`Test_Code`, `Name`, `Cost`, `Treatment_Code`) VALUES
('TE001', 'MRI scans.', 21000, 'TR001'),
('TE002', 'CT angiogram', 10100, 'TR002'),
('TE003', 'Eye Check', 1500, 'TR004');

-- --------------------------------------------------------

--
-- Table structure for table `treatment`
--

CREATE TABLE `treatment` (
  `Treatment_Code` varchar(5) NOT NULL,
  `Type` varchar(15) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treatment`
--

INSERT INTO `treatment` (`Treatment_Code`, `Type`, `Date`, `Time`) VALUES
('TR001', 'Test', '2021-08-31', '14:51:27'),
('TR002', 'Test', '2021-09-01', '09:51:27'),
('TR004', 'Drug', '2021-09-25', '06:38:00'),
('TR102', 'Drug', '2021-09-24', '18:22:00'),
('TR111', 'Drug', '2021-09-24', '18:28:00'),
('TR112', 'Drug', '2021-09-12', '12:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `Reg_No` varchar(5) NOT NULL,
  `Name` varchar(40) NOT NULL,
  `Address` varchar(40) NOT NULL,
  `ContactNo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`Reg_No`, `Name`, `Address`, `ContactNo`) VALUES
('R0005', 'Jagath Perera', 'No.300/E,Kandy Road,Gampaha', '0112729729'),
('R1212', 'Karunasena Mudalige', '101/A, Galle Road, Bambalapitiya', '0112123123'),
('R3434', 'Thilak Sampath', 'No 50, Galle Road.Matara.', '0414523872');

-- --------------------------------------------------------

--
-- Table structure for table `ward`
--

CREATE TABLE `ward` (
  `Ward_ID` varchar(5) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `PCU_ID` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ward`
--

INSERT INTO `ward` (`Ward_ID`, `Name`, `PCU_ID`) VALUES
('WD001', 'General', 'PCU03'),
('WD002', 'Special', 'PCU04'),
('WD003', 'ICU', 'PCU03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendant`
--
ALTER TABLE `attendant`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `bed`
--
ALTER TABLE `bed`
  ADD PRIMARY KEY (`Bed_ID`),
  ADD KEY `Ward_ID` (`Ward_ID`);

--
-- Indexes for table `cleaner`
--
ALTER TABLE `cleaner`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD PRIMARY KEY (`Diagnosis_Code`),
  ADD KEY `Doctor_ID` (`Doctor_ID`),
  ADD KEY `Patient_ID` (`Patient_ID`);

--
-- Indexes for table `diagnosticunit`
--
ALTER TABLE `diagnosticunit`
  ADD PRIMARY KEY (`Unit_ID`),
  ADD KEY `PCU_ID` (`PCU_ID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `drug`
--
ALTER TABLE `drug`
  ADD PRIMARY KEY (`Drug_Code`),
  ADD KEY `Treatment_Code` (`Treatment_Code`);

--
-- Indexes for table `emergency_contact`
--
ALTER TABLE `emergency_contact`
  ADD PRIMARY KEY (`Patient_ID`,`FName`,`Relationship`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `employee_assign`
--
ALTER TABLE `employee_assign`
  ADD PRIMARY KEY (`EmpNo`,`PCU_ID`,`Date`);

--
-- Indexes for table `in_patient`
--
ALTER TABLE `in_patient`
  ADD PRIMARY KEY (`Patient_ID`,`Admitted_Date`,`Admitted_Time`),
  ADD KEY `PC_Doctor` (`PC_Doctor`),
  ADD KEY `Bed_ID` (`Bed_ID`);

--
-- Indexes for table `medicalstaff`
--
ALTER TABLE `medicalstaff`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `nonmedical`
--
ALTER TABLE `nonmedical`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `nurse`
--
ALTER TABLE `nurse`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `out_patient`
--
ALTER TABLE `out_patient`
  ADD PRIMARY KEY (`Patient_ID`,`Arrived_Date`,`Arrived_Time`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`Patient_ID`);

--
-- Indexes for table `patient_insurance`
--
ALTER TABLE `patient_insurance`
  ADD PRIMARY KEY (`Patient_ID`,`Branch_Name`);

--
-- Indexes for table `patient_record`
--
ALTER TABLE `patient_record`
  ADD PRIMARY KEY (`Patient_ID`,`Date`,`Time`),
  ADD KEY `Nurse_ID` (`Nurse_ID`);

--
-- Indexes for table `patient_symptoms`
--
ALTER TABLE `patient_symptoms`
  ADD PRIMARY KEY (`Patient_ID`,`Date`,`Time`,`Symptom`);

--
-- Indexes for table `patient_treatment`
--
ALTER TABLE `patient_treatment`
  ADD PRIMARY KEY (`Doctor_ID`,`Diagnosis_Code`),
  ADD KEY `Patient_ID` (`Patient_ID`),
  ADD KEY `Treatment_Code` (`Treatment_Code`),
  ADD KEY `Diagnosis_Code` (`Diagnosis_Code`);

--
-- Indexes for table `pcu`
--
ALTER TABLE `pcu`
  ADD PRIMARY KEY (`PCU_ID`),
  ADD KEY `In-Charge` (`In-Charge`);

--
-- Indexes for table `supplies`
--
ALTER TABLE `supplies`
  ADD PRIMARY KEY (`Drug_Code`,`Reg_No`,`Supplied_Date`),
  ADD KEY `Reg_No` (`Reg_No`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`Test_Code`),
  ADD KEY `Treatment_Code` (`Treatment_Code`);

--
-- Indexes for table `treatment`
--
ALTER TABLE `treatment`
  ADD PRIMARY KEY (`Treatment_Code`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`Reg_No`);

--
-- Indexes for table `ward`
--
ALTER TABLE `ward`
  ADD PRIMARY KEY (`Ward_ID`),
  ADD KEY `PCU_ID` (`PCU_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendant`
--
ALTER TABLE `attendant`
  ADD CONSTRAINT `attendant_ibfk_1` FOREIGN KEY (`EmpNo`) REFERENCES `nonmedical` (`EmpNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bed`
--
ALTER TABLE `bed`
  ADD CONSTRAINT `bed_ibfk_1` FOREIGN KEY (`Ward_ID`) REFERENCES `ward` (`Ward_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cleaner`
--
ALTER TABLE `cleaner`
  ADD CONSTRAINT `cleaner_ibfk_1` FOREIGN KEY (`EmpNo`) REFERENCES `nonmedical` (`EmpNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD CONSTRAINT `diagnosis_ibfk_1` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctor` (`EmpNo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `diagnosis_ibfk_2` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `diagnosticunit`
--
ALTER TABLE `diagnosticunit`
  ADD CONSTRAINT `diagnosticunit_ibfk_1` FOREIGN KEY (`PCU_ID`) REFERENCES `pcu` (`PCU_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`EmpNo`) REFERENCES `medicalstaff` (`EmpNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `drug`
--
ALTER TABLE `drug`
  ADD CONSTRAINT `drug_ibfk_1` FOREIGN KEY (`Treatment_Code`) REFERENCES `treatment` (`Treatment_Code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `emergency_contact`
--
ALTER TABLE `emergency_contact`
  ADD CONSTRAINT `emergency_contact_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `in_patient` (`Patient_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `in_patient`
--
ALTER TABLE `in_patient`
  ADD CONSTRAINT `in_patient_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `in_patient_ibfk_2` FOREIGN KEY (`PC_Doctor`) REFERENCES `doctor` (`EmpNo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `in_patient_ibfk_3` FOREIGN KEY (`Bed_ID`) REFERENCES `bed` (`Bed_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `medicalstaff`
--
ALTER TABLE `medicalstaff`
  ADD CONSTRAINT `medicalstaff_ibfk_1` FOREIGN KEY (`EmpNo`) REFERENCES `employee` (`EmpNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nonmedical`
--
ALTER TABLE `nonmedical`
  ADD CONSTRAINT `nonmedical_ibfk_1` FOREIGN KEY (`EmpNo`) REFERENCES `employee` (`EmpNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nurse`
--
ALTER TABLE `nurse`
  ADD CONSTRAINT `nurse_ibfk_1` FOREIGN KEY (`EmpNo`) REFERENCES `medicalstaff` (`EmpNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `out_patient`
--
ALTER TABLE `out_patient`
  ADD CONSTRAINT `out_patient_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_insurance`
--
ALTER TABLE `patient_insurance`
  ADD CONSTRAINT `patient_insurance_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `in_patient` (`Patient_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_record`
--
ALTER TABLE `patient_record`
  ADD CONSTRAINT `patient_record_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_record_ibfk_2` FOREIGN KEY (`Nurse_ID`) REFERENCES `nurse` (`EmpNo`) ON UPDATE CASCADE;

--
-- Constraints for table `patient_symptoms`
--
ALTER TABLE `patient_symptoms`
  ADD CONSTRAINT `patient_symptoms_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `patient_record` (`Patient_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_treatment`
--
ALTER TABLE `patient_treatment`
  ADD CONSTRAINT `patient_treatment_ibfk_1` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctor` (`EmpNo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_treatment_ibfk_2` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_treatment_ibfk_4` FOREIGN KEY (`Treatment_Code`) REFERENCES `treatment` (`Treatment_Code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_treatment_ibfk_5` FOREIGN KEY (`Diagnosis_Code`) REFERENCES `diagnosis` (`Diagnosis_Code`);

--
-- Constraints for table `pcu`
--
ALTER TABLE `pcu`
  ADD CONSTRAINT `pcu_ibfk_1` FOREIGN KEY (`In-Charge`) REFERENCES `employee` (`EmpNo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `supplies`
--
ALTER TABLE `supplies`
  ADD CONSTRAINT `supplies_ibfk_1` FOREIGN KEY (`Drug_Code`) REFERENCES `drug` (`Drug_Code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `supplies_ibfk_2` FOREIGN KEY (`Reg_No`) REFERENCES `vendor` (`Reg_No`) ON UPDATE CASCADE;

--
-- Constraints for table `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `test_ibfk_1` FOREIGN KEY (`Treatment_Code`) REFERENCES `treatment` (`Treatment_Code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ward`
--
ALTER TABLE `ward`
  ADD CONSTRAINT `ward_ibfk_1` FOREIGN KEY (`PCU_ID`) REFERENCES `pcu` (`PCU_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
