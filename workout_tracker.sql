-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2024 at 02:48 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `workout_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Muscle_Group` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`ID`, `Name`, `Muscle_Group`) VALUES
(2, 'Squat', 'Legs'),
(3, 'Deadlift', 'Back'),
(4, 'Pull-Up', 'Back'),
(5, 'Push-Up', 'Chest'),
(6, 'Bicep Curl', 'Arms'),
(7, 'Tricep Extension', 'Arms'),
(8, 'Shoulder Press', 'Shoulders'),
(9, 'Leg Press', 'Legs'),
(10, 'Calf Raise', 'Legs'),
(11, 'Lateral Raise', 'Shoulders'),
(12, 'Front Raise', 'Shoulders'),
(13, 'Bent Over Row', 'Back'),
(14, 'Lat Pulldown', 'Back'),
(15, 'Chest Fly', 'Chest'),
(16, 'Incline Bench Press', 'Chest'),
(17, 'Decline Bench Press', 'Chest'),
(18, 'Leg Curl', 'Legs'),
(19, 'Leg Extension', 'Legs'),
(20, 'Seated Row', 'Back'),
(21, 'Cable Crossover', 'Chest'),
(22, 'Hammer Curl', 'Arms'),
(23, 'Reverse Curl', 'Arms'),
(24, 'Skull Crusher', 'Arms'),
(25, 'Dumbbell Bench Press', 'Chest'),
(26, 'Dumbbell Fly', 'Chest'),
(27, 'Smith Machine Squat', 'Legs'),
(28, 'Romanian Deadlift', 'Legs'),
(29, 'Hip Thrust', 'Legs'),
(30, 'Glute Bridge', 'Legs'),
(31, 'Sumo Deadlift', 'Legs'),
(32, 'Arnold Press', 'Shoulders'),
(33, 'Face Pull', 'Shoulders'),
(34, 'Cable Lateral Raise', 'Shoulders'),
(35, 'Upright Row', 'Shoulders'),
(36, 'Dumbbell Row', 'Back'),
(37, 'Kettlebell Swing', 'Legs'),
(38, 'Bulgarian Split Squat', 'Legs'),
(39, 'Pistol Squat', 'Legs'),
(40, 'Single Leg Deadlift', 'Legs'),
(41, 'Overhead Press', 'Shoulders'),
(42, 'Cable Tricep Pushdown', 'Arms'),
(43, 'Cable Bicep Curl', 'Arms'),
(44, 'Dips', 'Chest'),
(45, 'Decline Push-Up', 'Chest'),
(46, 'Incline Push-Up', 'Chest'),
(47, 'Renegade Row', 'Back'),
(48, 'Goblet Squat', 'Legs'),
(49, 'Landmine Press', 'Shoulders'),
(53, 'Bench Press', 'Chest');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `ID` int(11) NOT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Day_of_Week` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`ID`, `User_ID`, `Name`, `Description`, `Day_of_Week`) VALUES
(1, NULL, 'Monday Workout', 'Workout for Monday', 'Monday'),
(2, NULL, 'Tuesday Workout', 'Workout for Tuesday ', 'Tuesday'),
(4, NULL, 'TraseRRR', 'test', 'Thursday'),
(5, 1, 'Super Plan', 'test', 'Monday'),
(6, 1, 'Monday Workout', 'test', 'Monday');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Join_Date` date DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Username`, `Email`, `Password`, `Join_Date`, `verification_code`, `is_verified`) VALUES
(1, 'TraseRRR', 'something@gmail.com', '$2y$10$70HQcijtNE4U6JTrq7HTMOC3Fm26NS6Tl.wHMRXgxxC/x3pchhaXe', '2024-07-02', NULL, 0),
(3, 'Karine', 'traserrrchannel@gmail.com', '$2y$10$XriwlGCLI3I5mCjEwu8O6OYuUc0R6/S1cB0.dh6oA2cSjTMpmHRNu', '2024-07-02', NULL, 0),
(4, 'Ivan', 'traserrr@gmail.com', '$2y$10$npnrVqWazsjDJjIvEnC7OOC48p/6AnDCi9FiinMGcgQDa3T8K7NOW', NULL, 'd28418e2b06fb99cd6b7528b7d64a221', 0);

-- --------------------------------------------------------

--
-- Table structure for table `workouts`
--

CREATE TABLE `workouts` (
  `ID` int(11) NOT NULL,
  `Plan_ID` int(11) NOT NULL,
  `Exercise_ID` int(11) NOT NULL,
  `Sets` int(11) NOT NULL,
  `Reps` int(11) NOT NULL,
  `Weight` float NOT NULL,
  `Progressive_Overloading_Strategy` int(11) NOT NULL,
  `Initial_Sets` int(11) NOT NULL DEFAULT 0,
  `Initial_Reps` int(11) NOT NULL DEFAULT 0,
  `Initial_Weight` float NOT NULL DEFAULT 0,
  `User_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workouts`
--

INSERT INTO `workouts` (`ID`, `Plan_ID`, `Exercise_ID`, `Sets`, `Reps`, `Weight`, `Progressive_Overloading_Strategy`, `Initial_Sets`, `Initial_Reps`, `Initial_Weight`, `User_ID`) VALUES
(4, 1, 2, 5, 6, 220, 3, 5, 5, 200, NULL),
(5, 1, 3, 5, 11, 150, 3, 5, 5, 150, NULL),
(6, 1, 4, 5, 10, 5, 3, 5, 10, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workout_sessions`
--

CREATE TABLE `workout_sessions` (
  `ID` int(11) NOT NULL,
  `Workout_ID` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `Weight` float NOT NULL,
  `Reps` int(11) NOT NULL,
  `Sets` int(11) NOT NULL,
  `Notes` text DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workout_sessions`
--

INSERT INTO `workout_sessions` (`ID`, `Workout_ID`, `Date`, `Weight`, `Reps`, `Sets`, `Notes`, `User_ID`) VALUES
(57, 4, '2024-07-02 14:08:19', 200, 6, 5, 'Applied overload', NULL),
(58, 4, '2024-07-02 14:08:26', 200, 7, 5, 'Applied overload', NULL),
(59, 4, '2024-07-02 14:08:28', 200, 8, 5, 'Applied overload', NULL),
(60, 4, '2024-07-02 14:08:29', 200, 9, 5, 'Applied overload', NULL),
(61, 4, '2024-07-02 14:08:30', 200, 10, 5, 'Applied overload', NULL),
(62, 4, '2024-07-02 14:08:32', 200, 11, 5, 'Applied overload', NULL),
(63, 4, '2024-07-02 14:08:33', 200, 12, 5, 'Applied overload', NULL),
(64, 4, '2024-07-02 14:08:35', 205, 5, 5, 'Applied overload', NULL),
(65, 5, '2024-07-02 14:18:49', 150, 6, 5, 'Applied overload', NULL),
(68, 5, '2024-07-02 14:26:53', 150, 7, 5, 'Applied overload', NULL),
(69, 5, '2024-07-02 14:26:54', 150, 8, 5, 'Applied overload', NULL),
(70, 5, '2024-07-02 14:26:54', 150, 9, 5, 'Applied overload', NULL),
(71, 5, '2024-07-02 14:26:55', 150, 10, 5, 'Applied overload', NULL),
(72, 5, '2024-07-02 14:26:55', 150, 11, 5, 'Applied overload', NULL),
(73, 4, '2024-07-02 15:29:22', 215, 6, 5, 'Applied overload', NULL),
(74, 4, '2024-07-02 15:29:23', 215, 7, 5, 'Applied overload', NULL),
(75, 4, '2024-07-02 15:29:24', 215, 8, 5, 'Applied overload', NULL),
(76, 6, '2024-07-02 18:00:54', 0, 11, 5, 'Applied overload', NULL),
(77, 6, '2024-07-02 18:00:56', 0, 12, 5, 'Applied overload', NULL),
(78, 6, '2024-07-02 18:00:58', 5, 10, 5, 'Applied overload', NULL),
(79, 4, '2024-07-02 18:01:07', 215, 9, 5, 'Applied overload', NULL),
(80, 4, '2024-07-02 18:01:09', 215, 10, 5, 'Applied overload', NULL),
(81, 4, '2024-07-02 18:01:10', 215, 11, 5, 'Applied overload', NULL),
(82, 4, '2024-07-02 18:01:12', 215, 12, 5, 'Applied overload', NULL),
(83, 4, '2024-07-02 18:01:19', 220, 5, 5, 'Applied overload', NULL),
(84, 4, '2024-07-02 18:01:29', 220, 6, 5, 'Applied overload', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_user` (`User_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `workouts`
--
ALTER TABLE `workouts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Plan_ID` (`Plan_ID`),
  ADD KEY `Exercise_ID` (`Exercise_ID`);

--
-- Indexes for table `workout_sessions`
--
ALTER TABLE `workout_sessions`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Workout_ID` (`Workout_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `workout_sessions`
--
ALTER TABLE `workout_sessions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plans`
--
ALTER TABLE `plans`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `workouts`
--
ALTER TABLE `workouts`
  ADD CONSTRAINT `workouts_ibfk_1` FOREIGN KEY (`Plan_ID`) REFERENCES `plans` (`ID`),
  ADD CONSTRAINT `workouts_ibfk_2` FOREIGN KEY (`Exercise_ID`) REFERENCES `exercises` (`ID`);

--
-- Constraints for table `workout_sessions`
--
ALTER TABLE `workout_sessions`
  ADD CONSTRAINT `workout_sessions_ibfk_1` FOREIGN KEY (`Workout_ID`) REFERENCES `workouts` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
