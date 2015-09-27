-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Database: `backend`
--

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL,
  `origin_user_id` int(11) DEFAULT NULL,
  `destination_user_id` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=198647 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `origin_user_id`, `destination_user_id`) VALUES
(198646, 198610, 198615);

-- --------------------------------------------------------

--
-- Table structure for table `friend_invitations`
--

CREATE TABLE IF NOT EXISTS `friend_invitations` (
  `id` int(11) NOT NULL,
  `origin_user_id` int(11) DEFAULT NULL,
  `destination_user_id` int(11) DEFAULT NULL,
  `reason` text NOT NULL,
  `confirmed` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=19874 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friend_invitations`
--

INSERT INTO `friend_invitations` (`id`, `origin_user_id`, `destination_user_id`, `reason`, `confirmed`) VALUES
(19873, 198610, 198615, 'ADADADDA', 1),
(19871, 198618, 198610, 'TEST!!!', 1),
(19872, 198619, 198610, 'ICH BIN NEU', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roommembers`
--

CREATE TABLE IF NOT EXISTS `roommembers` (
  `id` int(11) NOT NULL,
  `origin_room_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=198667 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roommembers`
--

INSERT INTO `roommembers` (`id`, `origin_room_id`, `member_id`) VALUES
(198666, 198630, 198619),
(198665, 198630, 198615),
(198664, 198622, 198618),
(198663, 198653, 198615);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL,
  `name` text,
  `owner_user_id` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=198633 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `owner_user_id`) VALUES
(198618, '12345', 0),
(198619, '2222', 0),
(198621, 'test - 1', 198610),
(198622, 'test - 2', 198610),
(198624, 'test - 3', 198610),
(198625, 'Belongs to 11', 198615),
(198627, 'test - 4', 198610),
(198628, 'test - 5', 198610),
(198629, 'test - 6', 198610),
(198630, 'test - 7', 198610),
(198631, 'test - 8', 198610),
(198632, 'test - 9', 198610);

-- --------------------------------------------------------

--
-- Table structure for table `room_invitations`
--

CREATE TABLE IF NOT EXISTS `room_invitations` (
  `id` int(11) NOT NULL,
  `roomid` int(11) NOT NULL,
  `invitation_by` int(11) DEFAULT NULL,
  `invitation_to` int(11) DEFAULT NULL,
  `confirmed` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=198654 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room_invitations`
--

INSERT INTO `room_invitations` (`id`, `roomid`, `invitation_by`, `invitation_to`, `confirmed`) VALUES
(198653, 198622, 198610, 198615, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `title` text,
  `first_name` text,
  `last_name` text,
  `email` text,
  `md5pwd` text NOT NULL,
  `avatar_url` text NOT NULL,
  `sex` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=198620 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `title`, `first_name`, `last_name`, `email`, `md5pwd`, `avatar_url`, `sex`) VALUES
(198610, 'Dr.', 'Oliver', 'Bachmann', 'oliverbach@gmail.com', 'b6d767d2f8ed5d21a44b0e5886680cb9', 'avatar', 1),
(198618, '33', '44', '55', '22', 'b6d767d2f8ed5d21a44b0e5886680cb9', '', 0),
(198619, '44', '55', '66', '33', 'b6d767d2f8ed5d21a44b0e5886680cb9', '', 0),
(198615, '22', '33', '44', '11', 'b6d767d2f8ed5d21a44b0e5886680cb9', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_invitations`
--
ALTER TABLE `friend_invitations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roommembers`
--
ALTER TABLE `roommembers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_invitations`
--
ALTER TABLE `room_invitations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=198647;
--
-- AUTO_INCREMENT for table `friend_invitations`
--
ALTER TABLE `friend_invitations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19874;
--
-- AUTO_INCREMENT for table `roommembers`
--
ALTER TABLE `roommembers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=198667;
--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=198633;
--
-- AUTO_INCREMENT for table `room_invitations`
--
ALTER TABLE `room_invitations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=198654;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=198620;