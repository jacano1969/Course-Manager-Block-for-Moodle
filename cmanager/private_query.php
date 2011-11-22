<?php 









require_once("../../config.php");
global $CFG;

echo "installing table...";

$install_query = "
CREATE TABLE `amdl_cmanager_comments` (
  `id` int(20) NOT NULL auto_increment,
  `instanceid` varchar(20) default NULL,
  `createdbyid` int(20) default NULL,
  `dt` datetime default NULL,
  `message` varchar(300) default NULL,
  PRIMARY KEY  (`id`)
);



CREATE TABLE `amdl_cmanager_records` (
  `id` int(20) NOT NULL auto_increment,
  `createdbyid` int(20) default NULL,
  `modname` varchar(255) default NULL,
  `modcode` varchar(100) default NULL,
  `modmode` varchar(50) default NULL,
  `progname` varchar(200) default NULL,
  `progcode` varchar(200) default NULL,
  `year` varchar(100) default NULL,
  `area` varchar(300) default NULL,
  `otherinfo` varchar(300) default NULL,
  `cate` varchar(200) default NULL,
  `status` varchar(50) default NULL,
  `req_type` varchar(200) default NULL,
  `req_values` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
);



CREATE TABLE `amdl_cmanager_config` (
  `id` int(11) NOT NULL auto_increment,
  `varname` varchar(200) NOT NULL,
  `value` varchar(600) NOT NULL,
  PRIMARY KEY  (`id`)
);

";



echo $res = execute_sql($install_query, $feedback=true);

echo "install ok";

?>