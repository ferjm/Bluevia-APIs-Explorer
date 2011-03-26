SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `qht253`
--

-- --------------------------------------------------------

--
-- Table structure for table `method`
--

CREATE TABLE IF NOT EXISTS `method` (
  `method_id` int(11) NOT NULL auto_increment,
  `method_service_id` int(11) NOT NULL default '-1',
  `method_name` varchar(128) NOT NULL,
  `method_description` longtext,
  `method_version` varchar(64) NOT NULL default '1.0',
  `method_action` longtext NOT NULL,
  PRIMARY KEY  (`method_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `method`
--

INSERT INTO `method` (`method_id`, `method_service_id`, `method_name`, `method_description`, `method_version`, `method_action`) VALUES
(1, 1, 'get_request_token', 'Get OAuth Request Token', '1.0', 'clients/oauth/get_request_token.php'),
(2, 1, 'get_access_token', 'Get OAuth Access Token', '1.0', 'clients/oauth/get_access_token.php'),
(3, 2, 'send_sms', NULL, '1.0', 'clients/sms/send_sms.php'),
(4, 2, 'get_delivery_status', NULL, '1.0', 'clients/sms/get_delivery_status.php'),
(5, 2, 'get_received_sms', NULL, '1.0', 'clients/sms/get_received_sms.php');

-- --------------------------------------------------------

--
-- Table structure for table `parameters`
--

CREATE TABLE IF NOT EXISTS `parameters` (
  `param_id` int(11) NOT NULL auto_increment,
  `param_method_id` int(11) NOT NULL,
  `param_default_value` varchar(128) default NULL,
  `param_name` varchar(64) NOT NULL,
  `param_description` longtext,
  `param_html_component` varchar(64) NOT NULL,
  `param_multiple` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`param_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `parameters`
--

INSERT INTO `parameters` (`param_id`, `param_method_id`, `param_default_value`, `param_name`, `param_description`, `param_html_component`, `param_multiple`) VALUES
(1, 1, NULL, 'consumer_key', NULL, 'text', 0),
(2, 1, NULL, 'consumer_secret', NULL, 'text', 0),
(3, 1, NULL, 'callback_url', NULL, 'text', 0),
(4, 2, NULL, 'oauth_verifier', NULL, 'text', 0),
(5, 2, NULL, 'consumer_key', NULL, 'text', 0),
(6, 2, NULL, 'consumer_secret', NULL, 'text', 0),
(7, 3, NULL, 'address', NULL, 'text', 1),
(8, 3, NULL, 'message', NULL, 'text', 0),
(9, 3, NULL, 'originAddress', NULL, 'text', 0),
(10, 3, NULL, 'originAddressType', NULL, 'radio,phoneNumber,alias', 0),
(11, 3, NULL, 'addressType', NULL, 'radio,phoneNumber,alias', 1),
(12, 4, NULL, 'location', NULL, 'text', 0),
(13, 5, NULL, 'registrationId', NULL, 'text', 0);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `service_id` int(11) NOT NULL auto_increment,
  `service_name` varchar(64) NOT NULL,
  `service_logo` longtext,
  `service_description` longtext,
  `service_doc` longtext,
  PRIMARY KEY  (`service_id`),
  UNIQUE KEY `service_name` (`service_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `service_name`, `service_logo`, `service_description`, `service_doc`) VALUES
(1, 'OAuth', 'oauth.png', 'OAuth API', 'https://bluevia.com/en/knowledge/APIs.API-Guides.OAuth'),
(2, 'SMS', 'sms.png', 'SMS API', 'https://bluevia.com/en/knowledge/APIs.API-Guides.SMS'),
(3, 'User Context', 'directory.png', 'User Context API', 'https://bluevia.com/en/knowledge/APIs.API-Guides.GetUserInformation'),
(4, 'Advertising', 'advertising.png', 'Advertising API', 'https://bluevia.com/en/knowledge/APIs.API-Guides.Advertising');

