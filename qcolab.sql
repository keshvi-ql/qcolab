-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 12, 2025 at 10:53 AM
-- Server version: 8.0.39
-- PHP Version: 8.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qcolab`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` longtext,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` int NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `description`, `start_date`, `end_date`, `created_by`, `created`, `modified`, `deleted`) VALUES
(1, 'Event', 'This is Event', '2024-10-29', '2024-10-29', 1, '2024-10-28 12:57:03', '2024-10-29 04:56:54', 0),
(2, 'Diwali', 'This is Diwali Holidays', '2024-11-04', '2024-11-08', 1, '2024-10-29 03:43:47', '2024-11-05 05:12:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `balance_leaves`
--

DROP TABLE IF EXISTS `balance_leaves`;
CREATE TABLE IF NOT EXISTS `balance_leaves` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `t_balance_leaves` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `balance_leaves`
--

INSERT INTO `balance_leaves` (`id`, `user_id`, `t_balance_leaves`, `created`, `modified`) VALUES
(1, 9, 2, '2024-11-08 10:28:43', '2024-12-23 12:18:50'),
(2, 10, 4, '2024-11-08 10:28:43', '2024-12-23 12:18:50'),
(3, 11, 4, '2024-11-08 10:28:43', '2024-12-23 12:18:50'),
(4, 12, 3, '2024-11-08 10:28:43', '2024-12-23 12:18:50'),
(5, 13, 0, '2024-11-08 10:28:43', '2024-12-23 12:18:50');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

DROP TABLE IF EXISTS `bids`;
CREATE TABLE IF NOT EXISTS `bids` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url` text CHARACTER SET utf8mb4 NOT NULL,
  `source` int NOT NULL,
  `profile` int NOT NULL,
  `type` enum('fixed','hourly','monthly') CHARACTER SET utf8mb4 NOT NULL,
  `rate` decimal(11,2) NOT NULL,
  `created_by` int NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`id`, `url`, `source`, `profile`, `type`, `rate`, `created_by`, `created`, `modified`, `deleted`) VALUES
(1, 'https://tesla.com', 1, 2, 'monthly', 12.50, 1, '2024-10-25 04:54:53', '2024-11-06 09:55:16', 0),
(2, 'https://www.microsoft.com', 3, 1, 'hourly', 10.00, 1, '2024-10-25 05:33:53', '2024-10-25 05:38:42', 0),
(3, 'https://www.apple.com', 4, 2, 'fixed', 13.60, 1, '2024-10-25 11:59:39', '2024-10-25 11:59:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `bid_id` int DEFAULT NULL,
  `lead_no` varchar(11) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alt_email` varchar(255) DEFAULT NULL,
  `phone_no` varchar(10) DEFAULT NULL,
  `skype` varchar(100) DEFAULT NULL,
  `country` int DEFAULT NULL,
  `source` int DEFAULT NULL,
  `favorite` tinyint(1) NOT NULL DEFAULT '0',
  `note` text,
  `type` enum('client','lead') NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `client_converted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `bid_id`, `lead_no`, `first_name`, `last_name`, `email`, `alt_email`, `phone_no`, `skype`, `country`, `source`, `favorite`, `note`, `type`, `created`, `modified`, `deleted`, `client_converted_at`) VALUES
(1, NULL, NULL, 'Jaydeep', 'Chauhan', 'jaydeep@gmail.com', '', '234567898', '', 231, 1, 1, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque aliquid ullam sapiente iusto? Cupiditate sunt asperiores a iusto ducimus velit?\r\n', 'client', '2024-10-24 05:02:40', '2024-10-25 06:45:55', 0, NULL),
(2, NULL, NULL, 'Jenish', NULL, 'jenish@gmail.com', '', '864563446', '', 159, 2, 0, '', 'client', '2024-10-25 06:47:05', '2024-10-25 06:54:37', 0, NULL),
(3, NULL, '000001', 'Yash', NULL, 'yash@gmail.com', '', '', '', 14, 4, 1, '', 'lead', '2024-10-25 06:54:16', '2024-10-25 09:49:41', 0, NULL),
(4, NULL, '000002', 'Smit', NULL, 'smit@gmail.com', '', '65565656', '', 71, 1, 0, '', 'lead', '2024-10-25 07:17:00', '2024-10-25 09:45:03', 0, NULL),
(5, NULL, '000003', 'Kajal', '', '', '', '', '', 16, 4, 0, '', 'lead', '2024-10-25 11:08:37', '2024-10-25 11:08:37', 0, NULL),
(6, 2, '000004', 'Dhruvi', '', '', '', '', '', 111, 3, 0, '', 'client', '2024-10-25 11:33:27', '2024-11-06 09:42:58', 0, '2024-11-06 09:42:58');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`) VALUES
(1, 'AF', 'Afghanistan'),
(2, 'AL', 'Albania'),
(3, 'DZ', 'Algeria'),
(4, 'AS', 'American Samoa'),
(5, 'AD', 'Andorra'),
(6, 'AO', 'Angola'),
(7, 'AI', 'Anguilla'),
(8, 'AQ', 'Antarctica'),
(9, 'AG', 'Antigua and Barbuda'),
(10, 'AR', 'Argentina'),
(11, 'AM', 'Armenia'),
(12, 'AW', 'Aruba'),
(13, 'AU', 'Australia'),
(14, 'AT', 'Austria'),
(15, 'AZ', 'Azerbaijan'),
(16, 'BS', 'Bahamas'),
(17, 'BH', 'Bahrain'),
(18, 'BD', 'Bangladesh'),
(19, 'BB', 'Barbados'),
(20, 'BY', 'Belarus'),
(21, 'BE', 'Belgium'),
(22, 'BZ', 'Belize'),
(23, 'BJ', 'Benin'),
(24, 'BM', 'Bermuda'),
(25, 'BT', 'Bhutan'),
(26, 'BO', 'Bolivia'),
(27, 'BA', 'Bosnia and Herzegovina'),
(28, 'BW', 'Botswana'),
(29, 'BV', 'Bouvet Island'),
(30, 'BR', 'Brazil'),
(31, 'IO', 'British Indian Ocean Territory'),
(32, 'BN', 'Brunei Darussalam'),
(33, 'BG', 'Bulgaria'),
(34, 'BF', 'Burkina Faso'),
(35, 'BI', 'Burundi'),
(36, 'KH', 'Cambodia'),
(37, 'CM', 'Cameroon'),
(38, 'CA', 'Canada'),
(39, 'CV', 'Cape Verde'),
(40, 'KY', 'Cayman Islands'),
(41, 'CF', 'Central African Republic'),
(42, 'TD', 'Chad'),
(43, 'CL', 'Chile'),
(44, 'CN', 'China'),
(45, 'CX', 'Christmas Island'),
(46, 'CC', 'Cocos (Keeling) Islands'),
(47, 'CO', 'Colombia'),
(48, 'KM', 'Comoros'),
(49, 'CD', 'Democratic Republic of the Congo'),
(50, 'CG', 'Republic of Congo'),
(51, 'CK', 'Cook Islands'),
(52, 'CR', 'Costa Rica'),
(53, 'HR', 'Croatia (Hrvatska)'),
(54, 'CU', 'Cuba'),
(55, 'CY', 'Cyprus'),
(56, 'CZ', 'Czech Republic'),
(57, 'DK', 'Denmark'),
(58, 'DJ', 'Djibouti'),
(59, 'DM', 'Dominica'),
(60, 'DO', 'Dominican Republic'),
(61, 'TL', 'East Timor'),
(62, 'EC', 'Ecuador'),
(63, 'EG', 'Egypt'),
(64, 'SV', 'El Salvador'),
(65, 'GQ', 'Equatorial Guinea'),
(66, 'ER', 'Eritrea'),
(67, 'EE', 'Estonia'),
(68, 'ET', 'Ethiopia'),
(69, 'FK', 'Falkland Islands (Malvinas)'),
(70, 'FO', 'Faroe Islands'),
(71, 'FJ', 'Fiji'),
(72, 'FI', 'Finland'),
(73, 'FR', 'France'),
(74, 'FX', 'France, Metropolitan'),
(75, 'GF', 'French Guiana'),
(76, 'PF', 'French Polynesia'),
(77, 'TF', 'French Southern Territories'),
(78, 'GA', 'Gabon'),
(79, 'GM', 'Gambia'),
(80, 'GE', 'Georgia'),
(81, 'DE', 'Germany'),
(82, 'GH', 'Ghana'),
(83, 'GI', 'Gibraltar'),
(84, 'GG', 'Guernsey'),
(85, 'GR', 'Greece'),
(86, 'GL', 'Greenland'),
(87, 'GD', 'Grenada'),
(88, 'GP', 'Guadeloupe'),
(89, 'GU', 'Guam'),
(90, 'GT', 'Guatemala'),
(91, 'GN', 'Guinea'),
(92, 'GW', 'Guinea-Bissau'),
(93, 'GY', 'Guyana'),
(94, 'HT', 'Haiti'),
(95, 'HM', 'Heard and Mc Donald Islands'),
(96, 'HN', 'Honduras'),
(97, 'HK', 'Hong Kong'),
(98, 'HU', 'Hungary'),
(99, 'IS', 'Iceland'),
(100, 'IN', 'India'),
(101, 'IM', 'Isle of Man'),
(102, 'ID', 'Indonesia'),
(103, 'IR', 'Iran (Islamic Republic of)'),
(104, 'IQ', 'Iraq'),
(105, 'IE', 'Ireland'),
(106, 'IL', 'Israel'),
(107, 'IT', 'Italy'),
(108, 'CI', 'Ivory Coast'),
(109, 'JE', 'Jersey'),
(110, 'JM', 'Jamaica'),
(111, 'JP', 'Japan'),
(112, 'JO', 'Jordan'),
(113, 'KZ', 'Kazakhstan'),
(114, 'KE', 'Kenya'),
(115, 'KI', 'Kiribati'),
(116, 'KP', 'Korea, Democratic People\'s Republic of'),
(117, 'KR', 'Korea, Republic of'),
(118, 'XK', 'Kosovo'),
(119, 'KW', 'Kuwait'),
(120, 'KG', 'Kyrgyzstan'),
(121, 'LA', 'Lao People\'s Democratic Republic'),
(122, 'LV', 'Latvia'),
(123, 'LB', 'Lebanon'),
(124, 'LS', 'Lesotho'),
(125, 'LR', 'Liberia'),
(126, 'LY', 'Libyan Arab Jamahiriya'),
(127, 'LI', 'Liechtenstein'),
(128, 'LT', 'Lithuania'),
(129, 'LU', 'Luxembourg'),
(130, 'MO', 'Macau'),
(131, 'MK', 'North Macedonia'),
(132, 'MG', 'Madagascar'),
(133, 'MW', 'Malawi'),
(134, 'MY', 'Malaysia'),
(135, 'MV', 'Maldives'),
(136, 'ML', 'Mali'),
(137, 'MT', 'Malta'),
(138, 'MH', 'Marshall Islands'),
(139, 'MQ', 'Martinique'),
(140, 'MR', 'Mauritania'),
(141, 'MU', 'Mauritius'),
(142, 'YT', 'Mayotte'),
(143, 'MX', 'Mexico'),
(144, 'FM', 'Micronesia, Federated States of'),
(145, 'MD', 'Moldova, Republic of'),
(146, 'MC', 'Monaco'),
(147, 'MN', 'Mongolia'),
(148, 'ME', 'Montenegro'),
(149, 'MS', 'Montserrat'),
(150, 'MA', 'Morocco'),
(151, 'MZ', 'Mozambique'),
(152, 'MM', 'Myanmar'),
(153, 'NA', 'Namibia'),
(154, 'NR', 'Nauru'),
(155, 'NP', 'Nepal'),
(156, 'NL', 'Netherlands'),
(157, 'AN', 'Netherlands Antilles'),
(158, 'NC', 'New Caledonia'),
(159, 'NZ', 'New Zealand'),
(160, 'NI', 'Nicaragua'),
(161, 'NE', 'Niger'),
(162, 'NG', 'Nigeria'),
(163, 'NU', 'Niue'),
(164, 'NF', 'Norfolk Island'),
(165, 'MP', 'Northern Mariana Islands'),
(166, 'NO', 'Norway'),
(167, 'OM', 'Oman'),
(168, 'PK', 'Pakistan'),
(169, 'PW', 'Palau'),
(170, 'PS', 'Palestine'),
(171, 'PA', 'Panama'),
(172, 'PG', 'Papua New Guinea'),
(173, 'PY', 'Paraguay'),
(174, 'PE', 'Peru'),
(175, 'PH', 'Philippines'),
(176, 'PN', 'Pitcairn'),
(177, 'PL', 'Poland'),
(178, 'PT', 'Portugal'),
(179, 'PR', 'Puerto Rico'),
(180, 'QA', 'Qatar'),
(181, 'RE', 'Reunion'),
(182, 'RO', 'Romania'),
(183, 'RU', 'Russian Federation'),
(184, 'RW', 'Rwanda'),
(185, 'KN', 'Saint Kitts and Nevis'),
(186, 'LC', 'Saint Lucia'),
(187, 'VC', 'Saint Vincent and the Grenadines'),
(188, 'WS', 'Samoa'),
(189, 'SM', 'San Marino'),
(190, 'ST', 'Sao Tome and Principe'),
(191, 'SA', 'Saudi Arabia'),
(192, 'SN', 'Senegal'),
(193, 'RS', 'Serbia'),
(194, 'SC', 'Seychelles'),
(195, 'SL', 'Sierra Leone'),
(196, 'SG', 'Singapore'),
(197, 'SK', 'Slovakia'),
(198, 'SI', 'Slovenia'),
(199, 'SB', 'Solomon Islands'),
(200, 'SO', 'Somalia'),
(201, 'ZA', 'South Africa'),
(202, 'GS', 'South Georgia South Sandwich Islands'),
(203, 'SS', 'South Sudan'),
(204, 'ES', 'Spain'),
(205, 'LK', 'Sri Lanka'),
(206, 'SH', 'St. Helena'),
(207, 'PM', 'St. Pierre and Miquelon'),
(208, 'SD', 'Sudan'),
(209, 'SR', 'Suriname'),
(210, 'SJ', 'Svalbard and Jan Mayen Islands'),
(211, 'SZ', 'Eswatini'),
(212, 'SE', 'Sweden'),
(213, 'CH', 'Switzerland'),
(214, 'SY', 'Syrian Arab Republic'),
(215, 'TW', 'Taiwan'),
(216, 'TJ', 'Tajikistan'),
(217, 'TZ', 'Tanzania, United Republic of'),
(218, 'TH', 'Thailand'),
(219, 'TG', 'Togo'),
(220, 'TK', 'Tokelau'),
(221, 'TO', 'Tonga'),
(222, 'TT', 'Trinidad and Tobago'),
(223, 'TN', 'Tunisia'),
(224, 'TR', 'Turkey'),
(225, 'TM', 'Turkmenistan'),
(226, 'TC', 'Turks and Caicos Islands'),
(227, 'TV', 'Tuvalu'),
(228, 'UG', 'Uganda'),
(229, 'UA', 'Ukraine'),
(230, 'AE', 'United Arab Emirates'),
(231, 'GB', 'United Kingdom'),
(232, 'US', 'United States'),
(233, 'UM', 'United States minor outlying islands'),
(234, 'UY', 'Uruguay'),
(235, 'UZ', 'Uzbekistan'),
(236, 'VU', 'Vanuatu'),
(237, 'VA', 'Vatican City State'),
(238, 'VE', 'Venezuela'),
(239, 'VN', 'Vietnam'),
(240, 'VG', 'Virgin Islands (British)'),
(241, 'VI', 'Virgin Islands (U.S.)'),
(242, 'WF', 'Wallis and Futuna Islands'),
(243, 'EH', 'Western Sahara'),
(244, 'YE', 'Yemen'),
(245, 'ZM', 'Zambia'),
(246, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `name` mediumtext,
  `subject` mediumtext,
  `message` longtext,
  `placeholders` longtext,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `slug`, `name`, `subject`, `message`, `placeholders`, `created`, `modified`) VALUES
(1, 'forgot-password', 'Forgot Password', 'Reset Password Instructions', '<h3 style=\"font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-weight: 400; line-height: 1.53846; color: rgb(51, 51, 51); margin-top: 20px; margin-bottom: 10px; font-size: 21px; letter-spacing: -0.015em; text-align: justify;\"><span style=\"font-size: 14pt;\">Hello {firstname} {lastname},</span></h3><p style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px; text-align: justify;\">Someone, hopefully, you, has requested to reset the password for your&nbsp;{company_name} account with email&nbsp;<b>{email}</b>.</p><p style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; text-align: justify;\"><span style=\"color: inherit; font-family: inherit;\">If you did not perform this request, you can safely ignore this email&nbsp;</span>and your password will remain the same.&nbsp;<span style=\"color: inherit; font-family: inherit;\">Otherwise, click the link below to complete the process.</span></p><p style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; text-align: justify;\"><a href=\"{reset_password_url}\" target=\"_blank\" style=\"color: rgb(30, 136, 229); cursor: pointer; outline: 0px; font-family: inherit;\">Reset Password</a></p><p style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; text-align: justify;\">Please note that this link is valid for next 1 hour only. You won\'t be able to change the password after the link gets expired.</p><p style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px;\"></p><p style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px; text-align: justify;\"><span style=\"color: inherit; font-family: inherit;\">Thank you,</span></p><p><span style=\"color: inherit; font-family: inherit; font-size: 13px;\"></span></p><p style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px; text-align: justify;\"><span style=\"color: inherit; font-family: inherit;\">{company_name}</span></p>', 'a:6:{s:11:\"{firstname}\";s:14:\"User Firstname\";s:10:\"{lastname}\";s:13:\"User Lastname\";s:7:\"{email}\";s:10:\"User Email\";s:20:\"{reset_password_url}\";s:18:\"Reset Password URL\";s:17:\"{email_signature}\";s:15:\"Email Signature\";s:14:\"{company_name}\";s:12:\"Company Name\";}', '2024-10-01 07:31:21', '2024-10-01 12:24:10'),
(4, 'welcome-email', 'Welcome Email', 'Welcome to {company_name}', '<h1 style=\"margin: 20px 0px 10px; font-weight: 400; line-height: 1.53846; font-size: 25px; scrollbar-color: var(--gray-600) var(--gray-300); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; color: rgb(51, 51, 51); letter-spacing: -0.015em;\"><span style=\"scrollbar-color: var(--gray-600) var(--gray-300); font-weight: 800;\">Dear {firstname} {lastname}</span></h1><p style=\"margin-bottom: var(--spacer-2); scrollbar-color: var(--gray-600) var(--gray-300);\"><br style=\"scrollbar-color: var(--gray-600) var(--gray-300); color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px;\"><span style=\"scrollbar-color: var(--gray-600) var(--gray-300); color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px;\">You can log in to your account using the following link:</span><br style=\"scrollbar-color: var(--gray-600) var(--gray-300); color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px;\"><br style=\"scrollbar-color: var(--gray-600) var(--gray-300); color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px;\"><span style=\"scrollbar-color: var(--gray-600) var(--gray-300); color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px;\">If you did not request this, please contact support.</span><br style=\"scrollbar-color: var(--gray-600) var(--gray-300); color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px;\"><br style=\"scrollbar-color: var(--gray-600) var(--gray-300); color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px;\"><span style=\"scrollbar-color: var(--gray-600) var(--gray-300); color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px;\">Click the link below to login your account:</span></p><p style=\"margin-bottom: var(--spacer-2); scrollbar-color: var(--gray-600) var(--gray-300);\"><a href=\"http://localhost/qcolab/{login_url}\" target=\"_blank\" style=\"scrollbar-color: var(--gray-600) var(--gray-300);\">Login to your account</a><br style=\"scrollbar-color: var(--gray-600) var(--gray-300);\"><br style=\"scrollbar-color: var(--gray-600) var(--gray-300);\"><span style=\"scrollbar-color: var(--gray-600) var(--gray-300);\">Username:&nbsp;</span>{username}<br style=\"scrollbar-color: var(--gray-600) var(--gray-300);\"><span style=\"scrollbar-color: var(--gray-600) var(--gray-300);\">Password:&nbsp;</span>{password}<br style=\"scrollbar-color: var(--gray-600) var(--gray-300);\"></p><p style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; scrollbar-color: var(--gray-600) var(--gray-300); color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px;\">Best regards,<br style=\"scrollbar-color: var(--gray-600) var(--gray-300);\">{company_name}</p>', 'a:7:{s:11:\"{firstname}\";s:14:\"User Firstname\";s:10:\"{lastname}\";s:13:\"User Lastname\";s:7:\"{email}\";s:10:\"User Email\";s:11:\"{login_url}\";s:9:\"Login URL\";s:10:\"{username}\";s:8:\"Username\";s:10:\"{password}\";s:8:\"Password\";s:14:\"{company_name}\";s:12:\"Company Name\";}', '2024-10-04 06:41:04', '2024-10-04 06:48:43'),
(5, 'general_notification', 'General Notification', '{EVENT_TITLE}', NULL, 'a:3:{s:11:\"{APP_TITLE}\";s:9:\"App Title\";s:13:\"{EVENT_TITLE}\";s:11:\"EVENT TITLE\";s:15:\"{EVENT_DETAILS}\";s:12:\"EVENT Detail\";}', '2024-11-25 05:07:47', '2024-11-25 05:07:47'),
(6, 'salary-slip', 'Salary Slip', 'Salary Slip {month}', '<h3 style=\"margin-top: 20px; margin-bottom: 10px; line-height: 1.53846; scrollbar-color: var(--gray-600) var(--gray-300);\"><span style=\"color: rgb(0, 0, 0); font-family: sans-serif; font-size: 14px; font-weight: 400; letter-spacing: normal; background-color: var(--card-bg);\">Hello {firstname}</span><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 21px; font-weight: 400; letter-spacing: -0.015em;\">,</span></h3><p style=\"margin-top: 20px; margin-bottom: 10px; font-weight: 400; line-height: 1.53846; font-size: 21px; scrollbar-color: var(--gray-600) var(--gray-300); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; color: rgb(51, 51, 51); letter-spacing: -0.015em; text-align: justify;\"><span style=\"color: rgb(0, 0, 0); font-family: sans-serif; font-size: 14px; letter-spacing: normal;\">Please find the attached salary slip for {month}</span>.</p><p style=\"margin-top: 20px; margin-bottom: 10px; font-weight: 400; line-height: 1.53846; scrollbar-color: var(--gray-600) var(--gray-300); font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; color: rgb(51, 51, 51); text-align: justify;\"><span style=\"font-size: var(--body-font-size); letter-spacing: -0.015em; color: rgb(0, 0, 0); font-family: sans-serif; background-color: var(--card-bg); font-weight: var(--body-font-weight);\">Thank you</span><span style=\"color: rgb(0, 0, 0); font-family: sans-serif; background-color: var(--card-bg); font-weight: var(--body-font-weight); font-size: 13px;\">.</span></p>', 'a:2:{s:11:\"{firstname}\";s:14:\"User Firstname\";s:7:\"{month}\";s:5:\"Month\";}', '2024-11-25 05:15:37', '2024-11-25 05:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `general_files`
--

DROP TABLE IF EXISTS `general_files`;
CREATE TABLE IF NOT EXISTS `general_files` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_name` text NOT NULL,
  `file_id` text,
  `description` text,
  `file_size` decimal(10,2) NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `uploaded_by` int NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `general_files`
--

INSERT INTO `general_files` (`id`, `file_name`, `file_id`, `description`, `file_size`, `user_id`, `uploaded_by`, `deleted`, `created`, `modified`) VALUES
(49, '66e3c9506eed1-780575.png', '', 'test1', 28484.00, 1, 1, 0, '2024-09-13 05:10:48', '2024-09-13 05:10:48'),
(50, '66e3c950812e9-barcode-90ef6a01171b.webp', '', 'test2', 208.00, 1, 1, 0, '2024-09-13 05:10:48', '2024-09-13 05:10:48');

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
CREATE TABLE IF NOT EXISTS `holidays` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `title`, `start_date`, `end_date`, `created`, `modified`, `deleted`) VALUES
(1, 'Holiday', '2024-10-17', '2024-10-17', '2024-10-28 04:19:58', '2024-10-28 04:43:47', 0),
(2, 'Diwali', '2024-10-31', '2024-11-03', '2024-10-28 04:21:10', '2024-10-28 04:21:10', 0),
(3, 'Holi', '2024-11-07', '2024-11-07', '2024-10-28 05:58:36', '2024-11-07 10:35:51', 0),
(4, 'Christmas', '2024-12-25', '2024-12-25', '2024-10-28 05:59:24', '2024-10-28 05:59:24', 0),
(5, 'Oct', '2024-10-07', '2024-10-07', '2024-11-08 11:47:09', '2024-11-08 11:47:09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lead_profiles`
--

DROP TABLE IF EXISTS `lead_profiles`;
CREATE TABLE IF NOT EXISTS `lead_profiles` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lead_profiles`
--

INSERT INTO `lead_profiles` (`id`, `title`, `created`, `modified`) VALUES
(1, 'Suresh', '2024-10-24 10:51:24', '2024-10-25 05:23:01'),
(2, 'Sanat', '2024-10-25 05:23:08', '2024-10-25 05:23:08'),
(3, 'Hitesh', '2024-10-25 05:23:14', '2024-10-25 05:23:14');

-- --------------------------------------------------------

--
-- Table structure for table `lead_sources`
--

DROP TABLE IF EXISTS `lead_sources`;
CREATE TABLE IF NOT EXISTS `lead_sources` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lead_sources`
--

INSERT INTO `lead_sources` (`id`, `title`, `created`, `modified`) VALUES
(1, 'Google', '2024-10-24 10:03:36', '2024-10-24 10:03:36'),
(2, 'Facebook', '2024-10-24 10:04:10', '2024-10-24 10:04:10'),
(3, 'Twitter', '2024-10-24 10:04:22', '2024-10-24 10:04:22'),
(4, 'Youtube', '2024-10-24 10:04:32', '2024-10-24 10:04:32');

-- --------------------------------------------------------

--
-- Table structure for table `lead_statuses`
--

DROP TABLE IF EXISTS `lead_statuses`;
CREATE TABLE IF NOT EXISTS `lead_statuses` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `color` varchar(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lead_statuses`
--

INSERT INTO `lead_statuses` (`id`, `title`, `color`, `created`, `modified`) VALUES
(1, 'Notification', '#c1bc2f', '2024-10-24 09:26:38', '2024-10-24 10:16:30');

-- --------------------------------------------------------

--
-- Table structure for table `leave_applications`
--

DROP TABLE IF EXISTS `leave_applications`;
CREATE TABLE IF NOT EXISTS `leave_applications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leave_type_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_hours` decimal(7,2) NOT NULL,
  `total_days` decimal(5,2) NOT NULL,
  `half_day_type` varchar(50) DEFAULT NULL,
  `applicant_id` int NOT NULL,
  `reason` mediumtext NOT NULL,
  `status` enum('pending','approved','rejected','canceled') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL,
  `created_by` int NOT NULL,
  `checked_at` datetime DEFAULT NULL,
  `checked_by` int NOT NULL DEFAULT '0',
  `files` text,
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `leave_type_id` (`leave_type_id`),
  KEY `checked_by` (`checked_by`),
  KEY `applicant_id` (`applicant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leave_applications`
--

INSERT INTO `leave_applications` (`id`, `leave_type_id`, `start_date`, `end_date`, `total_hours`, `total_days`, `half_day_type`, `applicant_id`, `reason`, `status`, `created_at`, `created_by`, `checked_at`, `checked_by`, `files`, `deleted`) VALUES
(1, 1, '2024-11-04', '2024-11-13', 64.00, 8.00, NULL, 9, 'I just want to leave', 'canceled', '2024-11-07 11:57:26', 0, '2024-12-09 12:18:47', 9, '', 0),
(2, 1, '2024-10-16', '2024-10-16', 8.00, 1.00, NULL, 9, 'dsdsdsdsd', 'approved', '2024-11-08 10:46:31', 0, '2024-11-08 10:46:47', 1, '', 0),
(3, 1, '2024-10-01', '2024-10-08', 48.00, 6.00, NULL, 9, 'ygrtgdfgdf', 'approved', '2024-11-08 11:28:01', 0, '2024-11-08 11:29:29', 1, '', 0),
(4, 1, '2024-10-24', '2024-10-24', 4.00, 0.50, 'post_lunch', 9, 'fgfgfgf', 'approved', '2024-11-08 11:28:32', 0, '2024-11-08 11:29:22', 1, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

DROP TABLE IF EXISTS `leave_types`;
CREATE TABLE IF NOT EXISTS `leave_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `color` varchar(7) NOT NULL,
  `description` text,
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `title`, `status`, `color`, `description`, `deleted`) VALUES
(1, 'Casual Leave', 'active', '#f1c40f', 'this is test', 0),
(3, 'festival leave', 'active', '#2d9cdb', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `module` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `entity_id` int DEFAULT NULL,
  `created_by` int NOT NULL,
  `message` longtext CHARACTER SET utf8mb3 NOT NULL,
  `created_at` datetime NOT NULL,
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `module`, `entity_id`, `created_by`, `message`, `created_at`, `deleted`) VALUES
(14, 'partial_leave_application_submitted', 'partial-leaves', 1, 9, 'Partial Leave applied Successfully', '2024-11-07 11:56:27', 0),
(15, 'partial_leave_application_submitted', 'partial-leaves', 2, 9, 'Partial Leave applied Successfully', '2024-11-07 11:56:42', 0),
(16, 'leave_application_submitted', 'leave-applications', 1, 9, 'Leave applied Successfully', '2024-11-07 11:57:26', 0),
(17, 'leave_approved', 'leave-applications', 1, 1, 'Leave Approved', '2024-11-07 11:57:41', 0),
(18, 'partial_leave_approved', 'partial-leaves', 2, 1, 'Partial Leave Approved', '2024-11-07 11:57:47', 0),
(19, 'partial_leave_approved', 'partial-leaves', 1, 1, 'Partial Leave Approved', '2024-11-07 11:57:54', 0),
(20, 'leave_application_submitted', 'leave-applications', 2, 9, 'Leave applied Successfully', '2024-11-08 10:46:31', 0),
(21, 'leave_approved', 'leave-applications', 2, 1, 'Leave Approved', '2024-11-08 10:46:47', 0),
(22, 'leave_application_submitted', 'leave-applications', 3, 9, 'Leave applied Successfully', '2024-11-08 11:28:01', 0),
(23, 'leave_application_submitted', 'leave-applications', 4, 9, 'Leave applied Successfully', '2024-11-08 11:28:19', 0),
(24, 'leave_approved', 'leave-applications', 4, 1, 'Leave Approved', '2024-11-08 11:29:22', 0),
(25, 'leave_approved', 'leave-applications', 3, 1, 'Leave Approved', '2024-11-08 11:29:29', 0),
(26, 'leave_approved', 'leave-applications', 1, 1, 'Leave Approved', '2024-12-09 10:15:48', 0),
(27, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 10:31:39', 0),
(28, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 10:48:12', 0),
(29, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 10:48:49', 0),
(30, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 10:55:36', 0),
(31, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 11:00:28', 0),
(32, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 11:02:06', 0),
(33, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 11:03:20', 0),
(34, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 11:04:06', 0),
(35, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 11:05:12', 0),
(36, 'leave_approved', 'leave-applications', 1, 1, 'Leave Approved', '2024-12-09 11:05:38', 0),
(37, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 11:09:33', 0),
(38, 'leave_canceled', 'leave-applications', 1, 9, 'Leave Canceled', '2024-12-09 11:09:52', 0),
(39, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-09 11:41:02', 0),
(40, 'leave_canceled', 'leave-applications', 1, 9, 'Leave Canceled', '2024-12-09 11:41:52', 0),
(41, 'leave_canceled', 'leave-applications', 1, 9, 'Leave Canceled', '2024-12-09 12:18:47', 0),
(42, 'partial_leave_approved', 'partial-leaves', 1, 1, 'Partial Leave Approved', '2024-12-10 03:45:57', 0),
(43, 'leave_canceled', 'leave-applications', 1, 1, 'Leave Canceled', '2024-12-10 03:50:24', 0),
(44, 'leave_canceled', 'leave-applications', 1, 9, 'Leave Canceled', '2024-12-10 03:51:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notification_recipients`
--

DROP TABLE IF EXISTS `notification_recipients`;
CREATE TABLE IF NOT EXISTS `notification_recipients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `notification_id` int NOT NULL,
  `user_id` int NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_notification_id` (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `notification_recipients`
--

INSERT INTO `notification_recipients` (`id`, `notification_id`, `user_id`, `is_read`, `read_at`, `deleted`) VALUES
(1, 14, 1, 1, '2024-11-07 11:57:54', 0),
(2, 14, 9, 0, NULL, 0),
(3, 15, 1, 1, '2024-11-07 11:57:47', 0),
(4, 15, 9, 0, NULL, 0),
(5, 16, 1, 1, '2024-11-07 11:57:41', 0),
(6, 16, 9, 0, NULL, 0),
(7, 17, 9, 0, NULL, 0),
(8, 18, 9, 0, NULL, 0),
(9, 19, 9, 0, NULL, 0),
(10, 20, 1, 1, '2024-11-08 10:46:47', 0),
(11, 20, 9, 0, NULL, 0),
(12, 21, 9, 0, NULL, 0),
(13, 22, 1, 1, '2024-11-08 11:29:29', 0),
(14, 22, 9, 1, '2024-11-08 11:28:37', 0),
(15, 23, 1, 1, '2024-11-08 11:29:22', 0),
(16, 23, 9, 1, '2024-11-08 11:28:58', 0),
(17, 24, 9, 0, NULL, 0),
(18, 25, 9, 0, NULL, 0),
(19, 26, 9, 0, NULL, 0),
(20, 27, 9, 0, NULL, 0),
(21, 28, 9, 0, NULL, 0),
(22, 29, 9, 0, NULL, 0),
(23, 30, 9, 0, NULL, 0),
(24, 31, 9, 0, NULL, 0),
(25, 32, 9, 0, NULL, 0),
(26, 33, 9, 0, NULL, 0),
(27, 34, 9, 0, NULL, 0),
(28, 35, 9, 0, NULL, 0),
(29, 36, 9, 0, NULL, 0),
(30, 37, 9, 0, NULL, 0),
(31, 38, 9, 0, NULL, 0),
(32, 39, 9, 0, NULL, 0),
(33, 40, 9, 0, NULL, 0),
(34, 41, 9, 0, NULL, 0),
(35, 42, 9, 0, NULL, 0),
(36, 43, 9, 0, NULL, 0),
(37, 44, 9, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

DROP TABLE IF EXISTS `notification_settings`;
CREATE TABLE IF NOT EXISTS `notification_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `module` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `enable_email` tinyint(1) NOT NULL DEFAULT '0',
  `enable_system` tinyint(1) NOT NULL DEFAULT '0',
  `notify_to_team_members` text CHARACTER SET utf8mb3,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `notification_settings`
--

INSERT INTO `notification_settings` (`id`, `type`, `module`, `enable_email`, `enable_system`, `notify_to_team_members`, `deleted`) VALUES
(1, 'leave_application_submitted', 'leave-applications', 1, 1, '1,9', 0),
(2, 'leave_approved', 'leave-applications', 1, 1, NULL, 0),
(3, 'leave_assigned', 'leave-applications', 0, 1, NULL, 0),
(4, 'leave_rejected', 'leave-applications', 0, 0, NULL, 0),
(5, 'leave_canceled', 'leave-applications', 0, 0, NULL, 0),
(6, 'partial_leave_application_submitted', 'partial-leaves', 1, 1, '1,9', 0),
(7, 'partial_leave_approved', 'partial-leaves', 1, 1, NULL, 0),
(8, 'partial_leave_assigned', 'partial-leaves', 0, 1, NULL, 0),
(9, 'partial_leave_rejected', 'partial-leaves', 0, 0, NULL, 0),
(10, 'partial_leave_canceled', 'partial-leaves', 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `partial_leaves`
--

DROP TABLE IF EXISTS `partial_leaves`;
CREATE TABLE IF NOT EXISTS `partial_leaves` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_hours` decimal(7,2) NOT NULL,
  `applicant_id` int NOT NULL,
  `reason` mediumtext NOT NULL,
  `status` enum('pending','approved','rejected','canceled') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL,
  `created_by` int NOT NULL,
  `checked_at` datetime DEFAULT NULL,
  `checked_by` int NOT NULL,
  `files` text,
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `partial_leaves`
--

INSERT INTO `partial_leaves` (`id`, `start_date`, `end_date`, `total_hours`, `applicant_id`, `reason`, `status`, `created_at`, `created_by`, `checked_at`, `checked_by`, `files`, `deleted`) VALUES
(1, '2024-11-07', '2024-11-07', 0.50, 9, 'sssss', 'canceled', '2024-11-07 11:56:27', 0, '2024-12-10 03:51:33', 9, '', 0),
(2, '2024-11-08', '2024-11-08', 1.50, 9, 'ddfdfdf', 'approved', '2024-11-07 11:56:42', 0, '2024-11-07 11:57:47', 1, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pauses`
--

DROP TABLE IF EXISTS `pauses`;
CREATE TABLE IF NOT EXISTS `pauses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `time_log_id` int NOT NULL,
  `pause_time` datetime NOT NULL,
  `resume_time` datetime DEFAULT NULL,
  `pause_duration` time DEFAULT '00:00:00',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `time_log_id` (`time_log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pauses`
--

INSERT INTO `pauses` (`id`, `time_log_id`, `pause_time`, `resume_time`, `pause_duration`, `created`, `modified`) VALUES
(1, 1, '2024-10-15 12:35:43', '2024-10-15 12:35:51', '00:00:08', '2024-10-15 12:35:43', '2024-10-15 12:35:51'),
(2, 2, '2024-10-16 09:31:53', '2024-10-16 09:31:54', '00:00:01', '2024-10-16 09:31:53', '2024-10-16 09:31:54'),
(3, 3, '2024-10-23 10:54:41', '2024-10-23 10:54:45', '00:00:04', '2024-10-23 10:54:41', '2024-10-23 10:54:45'),
(4, 3, '2024-10-25 03:49:28', '2024-10-25 03:49:29', '00:00:01', '2024-10-25 03:49:29', '2024-10-25 03:49:29'),
(5, 4, '2024-10-28 04:44:29', '2024-10-28 04:44:29', '00:00:00', '2024-10-28 04:44:29', '2024-10-28 04:44:29'),
(6, 5, '2024-10-29 10:33:48', '2024-10-29 10:33:49', '00:00:01', '2024-10-29 10:33:48', '2024-10-29 10:33:49'),
(7, 5, '2024-10-29 10:33:50', '2024-10-29 10:33:52', '00:00:02', '2024-10-29 10:33:50', '2024-10-29 10:33:52'),
(8, 7, '2024-10-30 04:11:58', '2024-10-30 04:12:04', '00:00:06', '2024-10-30 04:11:58', '2024-11-04 12:16:57'),
(9, 11, '2024-11-04 11:38:53', '2024-11-04 11:38:57', '00:00:04', '2024-11-04 11:38:53', '2024-11-04 11:38:57'),
(10, 10, '2024-11-04 11:39:56', '2024-11-04 11:39:59', '00:00:03', '2024-11-04 11:39:56', '2024-11-04 11:39:59'),
(11, 12, '2024-11-05 04:47:21', '2024-11-05 04:49:48', '00:02:27', '2024-11-05 04:47:21', '2024-11-05 04:49:48'),
(12, 13, '2024-11-05 04:50:41', '2024-11-05 04:51:03', '00:00:22', '2024-11-05 04:50:41', '2024-11-05 04:51:03'),
(13, 12, '2024-11-05 04:51:43', '2024-11-05 04:54:07', '00:02:24', '2024-11-05 04:51:43', '2024-11-05 04:54:07'),
(14, 13, '2024-11-05 04:52:40', '2024-11-05 04:54:08', '00:01:28', '2024-11-05 04:52:40', '2024-11-05 04:54:08'),
(15, 12, '2024-11-05 04:56:23', '2024-11-05 04:56:45', '00:00:22', '2024-11-05 04:56:23', '2024-11-05 04:56:45'),
(16, 13, '2024-11-05 07:37:10', '2024-11-05 08:21:57', '00:44:47', '2024-11-05 07:37:10', '2024-11-05 08:21:57'),
(17, 12, '2024-11-05 07:37:17', '2024-11-05 08:21:47', '00:44:30', '2024-11-05 07:37:17', '2024-11-05 08:21:47'),
(18, 16, '2024-11-07 07:28:13', '2024-11-07 08:24:38', '00:56:25', '2024-11-07 07:28:13', '2024-11-07 08:24:38');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

DROP TABLE IF EXISTS `payroll`;
CREATE TABLE IF NOT EXISTS `payroll` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `month` varchar(25) NOT NULL,
  `total_working_days` double NOT NULL,
  `days_present` double NOT NULL,
  `paid_leaves` double NOT NULL,
  `unpaid_leaves` double NOT NULL,
  `deduction_of_leaves` decimal(7,2) NOT NULL,
  `basic_salary` decimal(7,2) NOT NULL,
  `total_balance_leaves` double NOT NULL,
  `net_payable` decimal(7,2) NOT NULL,
  `employee_code` varchar(100) DEFAULT NULL,
  `pan_number` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account_number` varchar(100) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `user_id`, `month`, `total_working_days`, `days_present`, `paid_leaves`, `unpaid_leaves`, `deduction_of_leaves`, `basic_salary`, `total_balance_leaves`, `net_payable`, `employee_code`, `pan_number`, `bank_name`, `bank_account_number`, `created`, `modified`) VALUES
(1, 9, 'October-2024', 23.5, 16, 1, 6.5, 2765.96, 10000.00, 0, 6834.04, 'QL2', 'abcde12345', 'IcIcI bank', '123456789124', '2024-11-25 09:42:33', '2024-11-25 10:39:58'),
(2, 10, 'October-2024', 23.5, 23.5, 0, 0, 0.00, 25000.00, 2, 25000.00, 'QL3', 'abcde12345', 'IcIcI bank', '123456789124', '2024-11-25 09:42:33', '2024-11-25 09:42:33'),
(3, 11, 'October-2024', 23.5, 23.5, 0, 0, 0.00, 19000.00, 2, 19000.00, 'QL4', 'abcde12345', 'IcIcI bank', '123456789124', '2024-11-25 09:42:33', '2024-11-25 09:42:33'),
(4, 12, 'October-2024', 23.5, 23.5, 0, 0, 0.00, 25000.00, 1, 25000.00, 'QL5', 'abcde12345', 'IcIcI bank', '123456789124', '2024-11-25 09:42:33', '2024-11-25 09:42:33'),
(5, 13, 'October-2024', 25, 25, 0, 0, 0.00, 4000.00, 0, 3000.00, 'QL6', 'abcde12345', 'IcIcI bank', '123456789124', '2024-11-25 09:42:33', '2024-11-25 09:42:33'),
(6, 9, 'November-2024', 21.5, 21.5, 0, 0, 0.00, 10000.00, 1, 9500.00, 'QL2', 'abcde12345', 'IcIcI bank', '123456789124', '2024-12-23 12:18:50', '2024-12-23 12:18:50'),
(7, 10, 'November-2024', 21.5, 21.5, 0, 0, 0.00, 25000.00, 3, 25000.00, 'QL3', 'abcde12345', 'IcIcI bank', '123456789124', '2024-12-23 12:18:50', '2024-12-23 12:18:50'),
(8, 11, 'November-2024', 21.5, 21.5, 0, 0, 0.00, 19000.00, 3, 19000.00, 'QL4', 'abcde12345', 'IcIcI bank', '123456789124', '2024-12-23 12:18:50', '2024-12-23 12:18:50'),
(9, 12, 'November-2024', 21.5, 21.5, 0, 0, 0.00, 25000.00, 2, 25000.00, 'QL5', 'abcde12345', 'IcIcI bank', '123456789124', '2024-12-23 12:18:50', '2024-12-23 12:18:50'),
(10, 13, 'November-2024', 23.5, 23.5, 0, 0, 0.00, 4000.00, 0, 3000.00, 'QL6', 'abcde12345', 'IcIcI bank', '123456789124', '2024-12-23 12:18:50', '2024-12-23 12:18:50');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_deductions`
--

DROP TABLE IF EXISTS `payroll_deductions`;
CREATE TABLE IF NOT EXISTS `payroll_deductions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `payroll_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `amount` decimal(7,2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payroll_deductions`
--

INSERT INTO `payroll_deductions` (`id`, `payroll_id`, `title`, `amount`, `created`, `modified`, `deleted`) VALUES
(1, 1, 'Security Deposit Amount', 500.00, '2024-11-25 09:42:33', '2024-11-25 09:42:33', 0),
(2, 5, 'Security Deposit Amount', 1000.00, '2024-11-25 09:42:33', '2024-11-25 09:42:33', 0),
(3, 1, 'Deduction', 200.00, '2024-11-25 10:39:41', '2024-11-25 10:39:41', 0),
(4, 6, 'Security Deposit Amount', 500.00, '2024-12-23 12:18:50', '2024-12-23 12:18:50', 0),
(5, 10, 'Security Deposit Amount', 1000.00, '2024-12-23 12:18:50', '2024-12-23 12:18:50', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_earnings`
--

DROP TABLE IF EXISTS `payroll_earnings`;
CREATE TABLE IF NOT EXISTS `payroll_earnings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `payroll_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `amount` decimal(7,2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payroll_earnings`
--

INSERT INTO `payroll_earnings` (`id`, `payroll_id`, `title`, `amount`, `created`, `modified`, `deleted`) VALUES
(1, 1, 'Earning', 300.00, '2024-11-25 10:39:57', '2024-11-25 10:39:57', 0);

-- --------------------------------------------------------

--
-- Table structure for table `phinxlog`
--

DROP TABLE IF EXISTS `phinxlog`;
CREATE TABLE IF NOT EXISTS `phinxlog` (
  `version` bigint NOT NULL,
  `migration_name` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `phinxlog`
--

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(20240829063143, 'CreateUsers', '2024-08-30 00:06:19', '2024-08-30 00:06:19', 0),
(20240829072343, 'CreateRoles', '2024-08-30 00:06:19', '2024-08-30 00:06:19', 0),
(20240829072717, 'CreatePermissions', '2024-08-30 00:06:19', '2024-08-30 00:06:19', 0),
(20240829072729, 'CreateRolePermissions', '2024-08-30 00:06:19', '2024-08-30 00:06:19', 0),
(20240829092145, 'CreateSettings', '2024-08-30 00:06:19', '2024-08-30 00:06:19', 0),
(20240829124706, 'DropPermissions', '2024-08-30 00:06:19', '2024-08-30 00:06:19', 0),
(20240829125603, 'ModifyRolePermissions', '2024-08-30 00:06:19', '2024-08-30 00:06:19', 0),
(20240903043148, 'CreateGeneralFiles', '2024-09-06 06:06:56', '2024-09-06 06:06:56', 0),
(20240910092233, 'AddGenderToUsers', '2024-09-10 03:53:11', '2024-09-10 03:53:11', 0),
(20240919121808, 'AddFieldsToUsers', '2024-09-19 07:09:38', '2024-09-19 07:09:38', 0),
(20241001041555, 'CreateEmailTemplates', '2024-09-30 23:06:58', '2024-09-30 23:06:59', 0),
(20241023110708, 'CreateClients', '2024-10-23 06:02:23', '2024-10-23 06:02:23', 0),
(20241023112021, 'CreateCountries', '2024-10-23 06:02:23', '2024-10-23 06:02:23', 0),
(20241024060018, 'CreateLeadStatuses', '2024-10-24 00:35:44', '2024-10-24 00:35:44', 0),
(20241024060047, 'CreateLeadSources', '2024-10-24 00:35:44', '2024-10-24 00:35:44', 0),
(20241024103826, 'CreateLeadProfiles', '2024-10-24 05:09:14', '2024-10-24 05:09:14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_no` varchar(11) DEFAULT NULL,
  `status_id` int NOT NULL,
  `client_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `url` text,
  `start_date` date NOT NULL,
  `deadline` date DEFAULT NULL,
  `type` enum('fixed','hourly','monthly') NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_no`, `status_id`, `client_id`, `title`, `description`, `url`, `start_date`, `deadline`, `type`, `created_by`, `created`, `modified`, `deleted`) VALUES
(1, '000004', 2, 6, 'QColab', 'de v4refcrfgd', '', '2024-11-11', NULL, 'fixed', 1, '2024-11-06 12:22:59', '2024-11-26 04:33:44', 0),
(2, NULL, 3, 1, 'Turbo-Builder', 'ssdsdsds', 'https://www.microsoft.com/', '2024-11-18', NULL, 'hourly', 1, '2024-11-06 12:33:32', '2024-11-26 04:33:41', 0),
(3, NULL, 1, 2, '8 Bloq', 'df rvdf redf', 'https://tesla.com', '2024-11-13', NULL, 'monthly', 1, '2024-11-06 12:35:25', '2024-11-26 06:38:14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

DROP TABLE IF EXISTS `project_members`;
CREATE TABLE IF NOT EXISTS `project_members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `project_id` int NOT NULL,
  `is_leader` tinyint DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`id`, `user_id`, `project_id`, `is_leader`, `deleted`) VALUES
(1, 1, 1, 1, 0),
(2, 13, 1, 0, 0),
(3, 1, 2, 1, 0),
(4, 12, 2, 0, 0),
(5, 1, 3, 1, 0),
(6, 10, 3, 0, 0),
(7, 11, 3, 0, 0),
(8, 12, 3, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `project_statuses`
--

DROP TABLE IF EXISTS `project_statuses`;
CREATE TABLE IF NOT EXISTS `project_statuses` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `color` varchar(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_statuses`
--

INSERT INTO `project_statuses` (`id`, `title`, `color`, `created`, `modified`, `deleted`) VALUES
(1, 'In Progress', '#f1c40f', '2024-10-25 12:56:58', '2024-11-06 09:46:09', 0),
(2, 'Completed', '#83c340', '2024-11-06 09:46:18', '2024-11-06 09:46:18', 0),
(3, 'On Hold', '#37b4e1', '2024-11-06 09:46:25', '2024-11-06 09:46:29', 0),
(4, 'Cancelled', '#e74c3c', '2024-11-06 09:46:51', '2024-11-06 09:46:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Admin', '2024-08-30 05:36:46', '2024-11-26 06:58:36'),
(87, 'Staff', '2024-09-19 11:50:55', '2024-11-07 04:36:58');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_id` (`role_id`,`controller`,`action`)
) ENGINE=InnoDB AUTO_INCREMENT=3814 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `controller`, `action`) VALUES
(3692, 1, 'Announcements', 'add'),
(3694, 1, 'Announcements', 'delete'),
(3693, 1, 'Announcements', 'edit'),
(3690, 1, 'Announcements', 'index'),
(3691, 1, 'Announcements', 'view'),
(3697, 1, 'Bids', 'add'),
(3698, 1, 'Bids', 'convertToLead'),
(3700, 1, 'Bids', 'delete'),
(3699, 1, 'Bids', 'edit'),
(3695, 1, 'Bids', 'index'),
(3696, 1, 'Bids', 'view'),
(3703, 1, 'Clients', 'add'),
(3705, 1, 'Clients', 'delete'),
(3704, 1, 'Clients', 'edit'),
(3701, 1, 'Clients', 'index'),
(3702, 1, 'Clients', 'view'),
(3709, 1, 'Dashboard', 'add'),
(3711, 1, 'Dashboard', 'delete'),
(3710, 1, 'Dashboard', 'edit'),
(3706, 1, 'Dashboard', 'index'),
(3707, 1, 'Dashboard', 'saveStickyNote'),
(3708, 1, 'Dashboard', 'view'),
(3713, 1, 'EmailTemplates', 'edit'),
(3712, 1, 'EmailTemplates', 'index'),
(3716, 1, 'Holidays', 'add'),
(3718, 1, 'Holidays', 'delete'),
(3717, 1, 'Holidays', 'edit'),
(3714, 1, 'Holidays', 'index'),
(3715, 1, 'Holidays', 'view'),
(3721, 1, 'LeadProfiles', 'add'),
(3723, 1, 'LeadProfiles', 'delete'),
(3722, 1, 'LeadProfiles', 'edit'),
(3719, 1, 'LeadProfiles', 'index'),
(3720, 1, 'LeadProfiles', 'view'),
(3726, 1, 'Leads', 'add'),
(3728, 1, 'Leads', 'convertToClient'),
(3729, 1, 'Leads', 'delete'),
(3727, 1, 'Leads', 'edit'),
(3724, 1, 'Leads', 'index'),
(3725, 1, 'Leads', 'view'),
(3732, 1, 'LeadSources', 'add'),
(3734, 1, 'LeadSources', 'delete'),
(3733, 1, 'LeadSources', 'edit'),
(3730, 1, 'LeadSources', 'index'),
(3731, 1, 'LeadSources', 'view'),
(3737, 1, 'LeadStatuses', 'add'),
(3739, 1, 'LeadStatuses', 'delete'),
(3738, 1, 'LeadStatuses', 'edit'),
(3735, 1, 'LeadStatuses', 'index'),
(3736, 1, 'LeadStatuses', 'view'),
(3741, 1, 'LeaveApplications', 'add'),
(3744, 1, 'LeaveApplications', 'delete'),
(3742, 1, 'LeaveApplications', 'deleteFiles'),
(3740, 1, 'LeaveApplications', 'index'),
(3743, 1, 'LeaveApplications', 'updateStatus'),
(3746, 1, 'LeaveTypes', 'add'),
(3747, 1, 'LeaveTypes', 'delete'),
(3745, 1, 'LeaveTypes', 'index'),
(3749, 1, 'Notifications', 'countNotifications'),
(3748, 1, 'Notifications', 'fetchNotifications'),
(3750, 1, 'Notifications', 'index'),
(3751, 1, 'Notifications', 'saveNotificationSettings'),
(3754, 1, 'PartialLeaves', 'add'),
(3757, 1, 'PartialLeaves', 'delete'),
(3755, 1, 'PartialLeaves', 'deleteFiles'),
(3752, 1, 'PartialLeaves', 'index'),
(3756, 1, 'PartialLeaves', 'updateStatus'),
(3753, 1, 'PartialLeaves', 'view'),
(3768, 1, 'Payroll', 'add'),
(3764, 1, 'Payroll', 'addDeduction'),
(3763, 1, 'Payroll', 'addEarning'),
(3770, 1, 'Payroll', 'delete'),
(3769, 1, 'Payroll', 'edit'),
(3766, 1, 'Payroll', 'export'),
(3760, 1, 'Payroll', 'gerenatePayroll'),
(3758, 1, 'Payroll', 'index'),
(3762, 1, 'Payroll', 'payrollPdf'),
(3765, 1, 'Payroll', 'sendMail'),
(3759, 1, 'Payroll', 'setSession'),
(3761, 1, 'Payroll', 'showPayroll'),
(3767, 1, 'Payroll', 'view'),
(3773, 1, 'Projects', 'add'),
(3774, 1, 'Projects', 'addMember'),
(3776, 1, 'Projects', 'delete'),
(3777, 1, 'Projects', 'deleteMember'),
(3775, 1, 'Projects', 'edit'),
(3771, 1, 'Projects', 'index'),
(3772, 1, 'Projects', 'view'),
(3780, 1, 'ProjectStatuses', 'add'),
(3782, 1, 'ProjectStatuses', 'delete'),
(3781, 1, 'ProjectStatuses', 'edit'),
(3778, 1, 'ProjectStatuses', 'index'),
(3779, 1, 'ProjectStatuses', 'view'),
(3784, 1, 'Roles', 'add'),
(3786, 1, 'Roles', 'delete'),
(3785, 1, 'Roles', 'edit'),
(3783, 1, 'Roles', 'index'),
(3788, 1, 'Settings', 'add'),
(3787, 1, 'Settings', 'index'),
(3791, 1, 'Technologies', 'add'),
(3793, 1, 'Technologies', 'delete'),
(3792, 1, 'Technologies', 'edit'),
(3789, 1, 'Technologies', 'index'),
(3790, 1, 'Technologies', 'view'),
(3797, 1, 'TimeCards', 'add'),
(3799, 1, 'TimeCards', 'delete'),
(3798, 1, 'TimeCards', 'edit'),
(3794, 1, 'TimeCards', 'index'),
(3795, 1, 'TimeCards', 'setSession'),
(3796, 1, 'TimeCards', 'view'),
(3810, 1, 'Users', 'add'),
(3812, 1, 'Users', 'delete'),
(3805, 1, 'Users', 'deleteFile'),
(3803, 1, 'Users', 'deleteTempFiles'),
(3811, 1, 'Users', 'edit'),
(3809, 1, 'Users', 'index'),
(3807, 1, 'Users', 'saveCropProfileImage'),
(3804, 1, 'Users', 'saveUploadedFiles'),
(3813, 1, 'Users', 'updateStatus'),
(3800, 1, 'Users', 'upload'),
(3802, 1, 'Users', 'uploadMultipleTempFiles'),
(3806, 1, 'Users', 'uploadProfileImage'),
(3801, 1, 'Users', 'uploadTempFiles'),
(3808, 1, 'Users', 'view'),
(2366, 87, 'Dashboard', 'index'),
(2368, 87, 'LeaveApplications', 'add'),
(2371, 87, 'LeaveApplications', 'delete'),
(2369, 87, 'LeaveApplications', 'deleteFiles'),
(2367, 87, 'LeaveApplications', 'index'),
(2370, 87, 'LeaveApplications', 'updateStatus'),
(2373, 87, 'Notifications', 'countNotifications'),
(2372, 87, 'Notifications', 'fetchNotifications'),
(2374, 87, 'Notifications', 'index'),
(2375, 87, 'Notifications', 'saveNotificationSettings'),
(2378, 87, 'PartialLeaves', 'add'),
(2381, 87, 'PartialLeaves', 'delete'),
(2379, 87, 'PartialLeaves', 'deleteFiles'),
(2376, 87, 'PartialLeaves', 'index'),
(2380, 87, 'PartialLeaves', 'updateStatus'),
(2377, 87, 'PartialLeaves', 'view'),
(2385, 87, 'TimeCards', 'add'),
(2387, 87, 'TimeCards', 'delete'),
(2386, 87, 'TimeCards', 'edit'),
(2382, 87, 'TimeCards', 'index'),
(2383, 87, 'TimeCards', 'setSession'),
(2384, 87, 'TimeCards', 'view'),
(2393, 87, 'Users', 'deleteFile'),
(2391, 87, 'Users', 'deleteTempFiles'),
(2397, 87, 'Users', 'edit'),
(2396, 87, 'Users', 'index'),
(2395, 87, 'Users', 'saveCropProfileImage'),
(2392, 87, 'Users', 'saveUploadedFiles'),
(2388, 87, 'Users', 'upload'),
(2390, 87, 'Users', 'uploadMultipleTempFiles'),
(2394, 87, 'Users', 'uploadProfileImage'),
(2389, 87, 'Users', 'uploadTempFiles');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created`, `modified`) VALUES
(1, 'company_name', 'Queueloop Solutions LLP', '2024-09-10 04:27:31', '2024-11-07 05:52:25'),
(2, 'company_email', 'queueloop@gmail.com', '2024-09-10 04:27:31', '2024-11-07 05:52:25'),
(3, 'date_format', 'jS F, Y', '2024-09-10 04:27:31', '2024-11-07 05:52:25'),
(4, 'time_format', 'h:i A', '2024-09-10 04:27:31', '2024-11-07 05:52:25'),
(5, 'smtp_encryption', 'ssl', '2024-09-10 04:27:31', '2024-11-07 05:52:25'),
(6, 'log_activity', '1', '2024-09-10 04:27:31', '2024-11-07 05:52:25');

-- --------------------------------------------------------

--
-- Table structure for table `technologies`
--

DROP TABLE IF EXISTS `technologies`;
CREATE TABLE IF NOT EXISTS `technologies` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `technologies`
--

INSERT INTO `technologies` (`id`, `title`, `created`, `modified`, `deleted`) VALUES
(1, 'PHP', '2024-10-26 03:52:10', '2024-11-22 11:17:00', 0),
(2, 'JavaScript', '2024-10-26 03:52:21', '2024-10-26 03:52:31', 0),
(3, 'Laravel', '2024-10-26 04:00:44', '2024-10-26 04:00:44', 0);

-- --------------------------------------------------------

--
-- Table structure for table `time_logs`
--

DROP TABLE IF EXISTS `time_logs`;
CREATE TABLE IF NOT EXISTS `time_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` date NOT NULL,
  `clock_in_time` datetime NOT NULL,
  `clock_out_time` datetime DEFAULT NULL,
  `total_work_duration` varchar(255) DEFAULT '00:00:00',
  `status` enum('active','paused','completed') DEFAULT 'active',
  `note` text,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `time_logs`
--

INSERT INTO `time_logs` (`id`, `user_id`, `date`, `clock_in_time`, `clock_out_time`, `total_work_duration`, `status`, `note`, `created`, `modified`) VALUES
(1, 9, '2024-10-15', '2024-10-15 12:35:05', '2024-10-16 04:04:59', '15:29:46', 'completed', 'fdfdsfdsfds', '2024-10-15 12:35:05', '2024-10-29 05:34:16'),
(2, 1, '2024-10-16', '2024-10-16 04:05:10', '2024-10-16 09:32:11', '05:27:00', 'completed', 'Time Log and sidebar done', '2024-10-16 04:05:10', '2024-10-16 09:32:11'),
(3, 9, '2024-10-23', '2024-10-23 10:54:34', '2024-10-25 03:49:40', '26:29:46', 'completed', 'ythgc', '2024-10-23 10:54:34', '2024-10-29 06:18:16'),
(4, 1, '2024-10-25', '2024-10-25 03:49:55', '2024-10-28 04:44:32', '00:54:37', 'completed', 'hh', '2024-10-25 03:49:55', '2024-10-28 04:44:32'),
(5, 1, '2024-10-29', '2024-10-29 03:40:37', '2024-10-29 10:33:55', '06:53:15', 'completed', 'gfgfg', '2024-10-29 03:40:37', '2024-10-29 10:33:55'),
(7, 9, '2024-10-29', '2024-10-29 06:29:36', '2024-10-30 04:12:00', '21:42:24', 'completed', 'ddd', '2024-10-29 06:29:36', '2024-10-30 04:12:00'),
(8, 1, '2024-10-30', '2024-10-30 03:41:15', '2024-11-04 03:40:52', '23:59:37', 'completed', '676rttyy6rt6y', '2024-10-30 03:41:16', '2024-11-04 03:40:52'),
(9, 9, '2024-10-30', '2024-10-30 04:12:01', '2024-11-04 03:44:13', '23:32:12', 'completed', 'eee', '2024-10-30 04:12:01', '2024-11-04 03:44:13'),
(10, 1, '2024-11-04', '2024-11-04 03:45:02', '2024-11-05 03:43:25', '23:58:20', 'completed', 'ffff', '2024-11-04 03:45:02', '2024-11-05 03:43:26'),
(11, 9, '2024-11-04', '2024-11-04 03:48:09', '2024-11-05 03:44:28', '23:56:15', 'completed', 'gfdgdfgf', '2024-11-04 03:48:09', '2024-11-05 03:44:28'),
(12, 1, '2024-11-05', '2024-11-05 03:43:49', '2024-11-05 10:51:32', '06:18:00', 'completed', 'rfddrzsv23er re', '2024-11-05 03:43:49', '2024-11-05 10:51:32'),
(13, 9, '2024-11-05', '2024-11-05 03:44:47', '2024-11-05 10:51:51', '06:20:27', 'completed', 'df dsvzcwex', '2024-11-05 03:44:47', '2024-11-05 10:51:51'),
(14, 1, '2024-11-06', '2024-11-06 09:16:45', '2024-11-06 12:54:43', '03:37:55', 'completed', 'SDD', '2024-11-06 09:16:45', '2024-11-06 12:54:43'),
(15, 9, '2024-11-06', '2024-11-06 09:16:50', '2024-11-06 12:55:16', '03:38:26', 'completed', 'dsd', '2024-11-06 09:16:50', '2024-11-06 12:55:16'),
(16, 1, '2024-11-07', '2024-11-07 04:37:12', '2024-11-07 08:24:43', '02:51:06', 'completed', 'ssdsd', '2024-11-07 04:37:12', '2024-11-07 08:24:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `phone_no` varchar(10) DEFAULT NULL,
  `alt_phone_no` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `is_trainee` tinyint(1) NOT NULL DEFAULT '0',
  `remember_me_token` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `token_requested_at` datetime DEFAULT NULL,
  `password_updated_at` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `role_id` int NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `alt_email` varchar(255) DEFAULT NULL,
  `address` text,
  `alt_address` text,
  `skype` varchar(100) DEFAULT NULL,
  `employee_code` varchar(50) DEFAULT NULL,
  `pan_no` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account_no` varchar(100) DEFAULT NULL,
  `security_deposit_amount` decimal(10,2) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `increment_month` varchar(50) DEFAULT NULL,
  `is_bde` tinyint(1) NOT NULL DEFAULT '0',
  `sticky_note` longtext,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `is_admin`, `phone_no`, `alt_phone_no`, `dob`, `profile_image`, `email`, `password`, `job_title`, `is_trainee`, `remember_me_token`, `token`, `email_verified_at`, `token_requested_at`, `password_updated_at`, `last_login_at`, `status`, `role_id`, `gender`, `alt_email`, `address`, `alt_address`, `skype`, `employee_code`, `pan_no`, `bank_name`, `bank_account_no`, `security_deposit_amount`, `salary`, `date_of_joining`, `increment_month`, `is_bde`, `sticky_note`, `created`, `modified`, `deleted`) VALUES
(1, 'Admin', '', 'Admin', 1, '1234567890', '0987654321', '2004-11-07', '672b0e69b66ae.png', 'admin@gmail.com', '$2y$10$LVJQKRJlMr1R0DtRYWk.IeT2PkB6AwJnLx1U967EpWs5RYoicWFUa', 'Admin', 0, NULL, NULL, NULL, NULL, NULL, '2025-01-22 11:44:08', 1, 1, 'male', '', '', '', '', 'QL1', '', '', '', NULL, NULL, NULL, '', 0, 'Sticky Notes completed', '2024-08-30 05:36:46', '2025-01-22 11:44:08', 0),
(9, 'Test', '', 'Tester', 0, '1234567891', '', '2004-11-16', NULL, 'test@gmail.com', '$2y$10$LVJQKRJlMr1R0DtRYWk.IeT2PkB6AwJnLx1U967EpWs5RYoicWFUa', 'Software Developer', 0, NULL, NULL, '2024-09-19 11:51:26', NULL, '2024-10-01 12:25:06', '2024-12-10 03:51:24', 1, 87, 'male', 'jen@gmail.com', 'test address', 'alternate test address', 'test@data.com', 'QL2', 'abcde12345', 'IcIcI bank', '123456789124', 500.00, 10000.00, NULL, 'February', 0, NULL, '2024-09-19 11:51:26', '2024-12-10 03:51:24', 0),
(10, 'Jenish', '', 'Modi', 0, '5645678921', '', '2000-12-13', '672b0e69b66ae.png', 'jenish@gmail.com', '$2y$10$LVJQKRJlMr1R0DtRYWk.IeT2PkB6AwJnLx1U967EpWs5RYoicWFUa', 'Software Developer', 0, NULL, NULL, '2024-09-19 11:51:26', NULL, '2024-10-01 12:25:06', '2024-11-07 12:49:19', 1, 87, 'male', 'ys@gmail.com', 'test address', 'alternate test address', 'jenish@data.com', 'QL3', 'abcde12345', 'IcIcI bank', '123456789124', NULL, 25000.00, '2021-06-01', 'February', 0, NULL, '2024-09-19 11:51:26', '2024-12-23 07:05:27', 0),
(11, 'Yash', '', 'Bundheliya', 0, '564563224', '', '2001-08-18', NULL, 'yash.queueloop@gmail.com', '$2y$10$LVJQKRJlMr1R0DtRYWk.IeT2PkB6AwJnLx1U967EpWs5RYoicWFUa', 'Software Developer', 0, NULL, NULL, '2024-09-19 11:51:26', NULL, '2024-10-01 12:25:06', '2024-11-07 12:49:19', 1, 87, 'male', 'ys@gmail.com', 'test address', 'alternate test address', 'yash@data.com', 'QL4', 'abcde12345', 'IcIcI bank', '123456789124', NULL, 19000.00, '2022-02-01', 'February', 0, NULL, '2024-09-19 11:51:26', '2024-11-25 05:32:51', 0),
(12, 'Riya', '', 'Gajera', 0, '564563224', '', '2004-07-21', NULL, 'riya.queueloop@gmail.com', '$2y$10$LVJQKRJlMr1R0DtRYWk.IeT2PkB6AwJnLx1U967EpWs5RYoicWFUa', 'Software Developer', 0, NULL, NULL, '2024-09-19 11:51:26', NULL, '2024-10-01 12:25:06', '2024-11-07 12:49:19', 1, 87, 'female', 'ry@gmail.com', 'test address', 'alternate test address', 'riya@data.com', 'QL5', 'abcde12345', 'IcIcI bank', '123456789124', NULL, 25000.00, '2022-07-01', 'February', 0, NULL, '2024-09-19 11:51:26', '2024-11-25 06:56:57', 0),
(13, 'Smit', '', 'Kukadiya', 0, '564563224', '', '2004-04-08', NULL, 'sanat.queueloop@gmail.com', '$2y$10$LVJQKRJlMr1R0DtRYWk.IeT2PkB6AwJnLx1U967EpWs5RYoicWFUa', 'Trainee Software Developer', 1, NULL, NULL, '2024-09-19 11:51:26', NULL, '2024-10-01 12:25:06', '2024-11-07 12:49:19', 1, 87, 'male', 'sm@gmail.com', 'test address', 'alternate test address', 'riya@data.com', 'QL6', 'abcde12345', 'IcIcI bank', '123456789124', 1000.00, 4000.00, '2024-07-01', 'February', 0, NULL, '2024-09-19 11:51:26', '2024-11-25 05:35:00', 0),
(14, 'Sanat', NULL, 'Pipaliya', 0, '2345678123', NULL, NULL, NULL, 'sanat@gmail.com', '$2y$10$MvAqIONdZJ9WlYZCoO5wq..xQSuLnKGIzCUzDRPswy1eKYbetvXBC', NULL, 0, NULL, NULL, '2024-12-23 09:20:51', NULL, NULL, NULL, 1, 87, 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2024-12-23 09:20:51', '2024-12-23 09:20:51', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  ADD CONSTRAINT `fk_notification_id` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
