--
-- Table structure for table `method`
--

CREATE TABLE IF NOT EXISTS `method` (
  `method_id` int(11) NOT NULL auto_increment,
  `method_service_id` int(11) NOT NULL default '-1',
  `method_name` varchar(128) NOT NULL,
  `method_description` longtext,
  PRIMARY KEY  (`method_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


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
  PRIMARY KEY  (`param_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Table structure for table `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(64) NOT NULL,
  PRIMARY KEY  (`service_id`),
  UNIQUE KEY `service_name` (`service_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
