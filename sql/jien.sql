-- MySQL dump 10.13  Distrib 5.5.25, for osx10.6 (i386)
--
-- Host: localhost    Database: jien
-- ------------------------------------------------------
-- Server version	5.5.25

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Authenticator`
--

DROP TABLE IF EXISTS `Authenticator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Authenticator` (
  `authenticator_id` int(11) NOT NULL AUTO_INCREMENT,
  `authenticator_user_id` int(11) DEFAULT NULL,
  `authenticator_secret` varchar(16) DEFAULT NULL,
  `authenticator_sms` varchar(64) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted` datetime DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`authenticator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Authenticator`
--

LOCK TABLES `Authenticator` WRITE;
/*!40000 ALTER TABLE `Authenticator` DISABLE KEYS */;
/*!40000 ALTER TABLE `Authenticator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Category`
--

DROP TABLE IF EXISTS `Category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `category` varchar(128) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `path` varchar(512) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`),
  KEY `path` (`path`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Category`
--

LOCK TABLES `Category` WRITE;
/*!40000 ALTER TABLE `Category` DISABLE KEYS */;
INSERT INTO `Category` VALUES (1,'Role','Admin',0,'1','2013-12-28 00:27:05','2013-12-27 16:27:05',NULL,1),(2,'Role','VIP',1,'1,2','2013-12-28 00:28:35','2013-12-27 16:28:35',NULL,1),(3,'Role','Guest',2,'1,2,3','2013-12-28 00:38:58','2013-12-27 16:38:58',NULL,1),(4,'User','Work',0,'4','2013-12-28 00:39:51','2013-12-27 16:39:51',NULL,1),(5,'User','Friends',0,'5','2013-12-28 00:40:01','2013-12-27 16:40:01',NULL,1),(6,'User','Tech',4,'4,6','2013-12-28 00:40:22','2013-12-27 16:40:28',NULL,1);
/*!40000 ALTER TABLE `Category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Datatype`
--

DROP TABLE IF EXISTS `Datatype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Datatype` (
  `datatype_id` int(11) NOT NULL AUTO_INCREMENT,
  `datatype` varchar(32) NOT NULL,
  `rank` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`datatype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Datatype`
--

LOCK TABLES `Datatype` WRITE;
/*!40000 ALTER TABLE `Datatype` DISABLE KEYS */;
INSERT INTO `Datatype` VALUES (3,'Editable',2,'2014-03-09 10:06:33','0000-00-00 00:00:00',NULL,1);
/*!40000 ALTER TABLE `Datatype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Editable`
--

DROP TABLE IF EXISTS `Editable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Editable` (
  `editable_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `type` varchar(32) NOT NULL,
  `content` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL,
  `deleted` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`editable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Editable`
--

LOCK TABLES `Editable` WRITE;
/*!40000 ALTER TABLE `Editable` DISABLE KEYS */;
INSERT INTO `Editable` VALUES (15,'index_heading','text','Welcome to the Jien Framework LIK OMG!','2014-03-08 23:25:57','2014-03-09 20:16:45','2014-03-09 16:28:31',0),(16,'index_subheading','text','The wordpress killer we <span style=\"font-weight: bold;\">have</span> been all waiting for ...','2014-03-08 23:29:52','2014-03-09 20:17:28','2014-03-09 16:28:31',0),(19,'index_signup','text','Signup for free','2014-03-08 23:45:31','2014-03-09 16:28:31','2014-03-09 16:28:31',0),(21,'index_img','image','https://www.filepicker.io/api/file/yRcuyPkIQjWon1iDPAYX','2014-03-09 16:02:29','2014-03-09 17:17:02','2014-03-09 13:03:24',0),(22,'index_content','wysiwyg','        \n    <div class=\"span6\">\n        <h4>Ease of use</h4>\n        <p>donec Id Elit Non Mi Porta Gravida At Eget Metus. Maecenas Faucibus Mollis Interdum.</p>\n\n        <h4>Lightweight</h4>\n        <p>morbi Leo Risus, Porta Ac Consectetur Ac, Vestibulum At Eros. Cras Mattis Consectetur Purus Sit Amet Fermentum.</p>\n\n        <h4>Fast Deployment</h4>\n        <p>maecenas Sed Diam Eget Risus Varius Blandit Sit Amet Non Magna.</p>\n    </div>\n\n    <div class=\"span6\">\n        <h4>100% Open Source</h4>\n        <p>donec Id Elit Non Mi Porta Gravida At Eget Metus. Maecenas Faucibus Mollis Interdum.</p>\n\n        <h4>Created By Talented Developers</h4>\n        <p>morbi Leo Risus, Porta Ac Consectetur Ac, Vestibulum At Eros. Cras Mattis Consectetur Purus Sit Amet Fermentum.</p>\n\n        <h4>Best CMS&nbsp;</h4>\n        <p>maecenas Sed Diam Eget Risus Varius Blandit Sit Amet Non Magna.</p>\n    </div>','2014-03-09 16:29:39','2014-03-09 20:30:21','2014-03-09 16:28:31',0),(23,'index_img','image','https://www.filepicker.io/api/file/yRcuyPkIQjWon1iDPAYX','2014-03-09 20:27:45','2014-03-09 17:17:02','2014-03-09 16:28:31',0),(24,'index_img','image','https://www.filepicker.io/api/file/yRcuyPkIQjWon1iDPAYX','2014-03-10 00:12:19','2014-03-09 19:37:48','2014-03-09 19:37:48',0),(25,'header_text','text','<a href=\"/\">Crazy ASS Honeybadger</a>','2014-03-10 03:06:56','2014-03-09 20:39:04','2014-03-09 20:39:04',0),(26,'index_subheading','text','The wordpress killer we <span style=\"font-weight: bold;\">have</span> been all waiting for ...','2014-03-10 03:12:39','2014-03-09 20:39:04','2014-03-09 20:39:04',0),(27,'index_heading','text','Welcome to the Jien Framework LIK OMG!','2014-03-10 03:16:16','2014-03-09 20:39:04','2014-03-09 20:39:04',0),(28,'footer_text','text','Â© Honeybadger 2014','2014-03-10 03:18:00','2014-03-09 20:39:04','2014-03-09 20:39:04',0),(29,'index_content','wysiwyg','        \n    <div class=\"span6\">\n        <h4>Ease of use</h4>\n        <p>donec Id Elit Non Mi Porta Gravida At Eget Metus. Maecenas Faucibus Mollis Interdum.</p>\n\n        <h4>Lightweight</h4>\n        <p>morbi Leo Risus, Porta Ac Consectetur Ac, Vestibulum At Eros. Cras Mattis Consectetur Purus Sit Amet Fermentum.</p>\n\n        <h4>Fast Deployment</h4>\n        <p>maecenas Sed Diam Eget Risus Varius Blandit Sit Amet Non Magna.</p>\n    </div>\n\n    <div class=\"span6\">\n        <h4>100% Open Source</h4>\n        <p>donec Id Elit Non Mi Porta Gravida At Eget Metus. Maecenas Faucibus Mollis Interdum.</p>\n\n        <h4>Created By Talented Developers</h4>\n        <p>morbi Leo Risus, Porta Ac Consectetur Ac, Vestibulum At Eros. Cras Mattis Consectetur Purus Sit Amet Fermentum.</p>\n\n        <h4>Best CMS&nbsp;</h4>\n        <p>maecenas Sed Diam Eget Risus Varius Blandit Sit Amet Non Magna.</p>\n    </div>','2014-03-10 03:18:04','2014-03-09 20:39:04','2014-03-09 20:39:04',0);
/*!40000 ALTER TABLE `Editable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Event`
--

DROP TABLE IF EXISTS `Event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Event` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `msg` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Event`
--

LOCK TABLES `Event` WRITE;
/*!40000 ALTER TABLE `Event` DISABLE KEYS */;
INSERT INTO `Event` VALUES (1,6,'went to work','work sucks','2014-01-17 00:12:37','0000-00-00 00:00:00',NULL,1);
/*!40000 ALTER TABLE `Event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Page`
--

DROP TABLE IF EXISTS `Page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `permalink` varchar(256) NOT NULL,
  `is_published` int(1) DEFAULT '1',
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Page`
--

LOCK TABLES `Page` WRITE;
/*!40000 ALTER TABLE `Page` DISABLE KEYS */;
INSERT INTO `Page` VALUES (1,0,0,'Who we are','<h3>Hello and welcome!</h3>I am Jien Framework.<br>I can do lot of <b>things</b><br><div><div>My passion is development.<br><br><br></div></div>','/about',1,'2014-03-09 10:05:48','2014-03-09 20:26:01',NULL,1),(2,0,0,'cool','<p></p><table class=\"table table-bordered\"><tbody><tr><td>gh</td><td>esei</td><td>hoho</td></tr><tr><td>sdds</td><td>sdf</td><td>sdf</td></tr><tr><td><br></td><td><br></td><td><br></td></tr></tbody></table><h4>asfasf</h4><blockquote>i am awesome</blockquote><p>just awesome</p><p></p>','/cool',1,'2014-03-09 10:28:09','2014-03-09 18:54:48','2014-03-09 11:54:48',0),(3,0,0,'Contact Us','<p>You can reach us at jaequery@gmail.com</p><p>Also feel free to check out my twitter feed @jaequery<br></p>','/contact-us',1,'2014-03-09 16:23:45','0000-00-00 00:00:00',NULL,1);
/*!40000 ALTER TABLE `Page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Provider`
--

DROP TABLE IF EXISTS `Provider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Provider` (
  `provider_id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` varchar(32) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL,
  `deleted` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`provider_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Provider`
--

LOCK TABLES `Provider` WRITE;
/*!40000 ALTER TABLE `Provider` DISABLE KEYS */;
INSERT INTO `Provider` VALUES (1,'website','2011-11-02 05:16:23','0000-00-00 00:00:00','0000-00-00 00:00:00',1),(2,'facebook','2011-11-02 05:16:48','0000-00-00 00:00:00','0000-00-00 00:00:00',1),(3,'twitter','2011-11-02 05:16:53','0000-00-00 00:00:00','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `Provider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Role`
--

DROP TABLE IF EXISTS `Role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(32) NOT NULL,
  `parent_id` tinyint(4) NOT NULL,
  `mptt_left` int(11) DEFAULT NULL,
  `mptt_right` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL,
  `deleted` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Role`
--

LOCK TABLES `Role` WRITE;
/*!40000 ALTER TABLE `Role` DISABLE KEYS */;
INSERT INTO `Role` VALUES (1,'guest',0,14,15,'2011-10-27 02:13:00','2014-03-09 09:23:38','0000-00-00 00:00:00',1),(2,'member',1,11,16,'2011-10-27 02:13:10','2014-03-09 09:23:38','0000-00-00 00:00:00',1),(3,'vip',2,10,17,'2011-10-27 02:13:41','2014-03-09 09:23:38','0000-00-00 00:00:00',1),(10,'moderator',3,9,18,'2011-10-27 02:13:47','2014-03-09 09:23:38','0000-00-00 00:00:00',1),(11,'admin',10,1,20,'2011-10-27 02:13:52','2014-03-09 09:23:38','0000-00-00 00:00:00',1),(19,'non paid',0,12,13,'2013-11-21 23:54:54','2014-03-09 09:23:16','0000-00-00 00:00:00',1),(22,'supervisor',0,8,19,'2013-11-21 23:55:38','2014-03-09 09:23:38','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `Role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL DEFAULT '1',
  `uid` bigint(20) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `username` varchar(64) NOT NULL,
  `password` varchar(60) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `email` varchar(64) NOT NULL,
  `birthday` date DEFAULT NULL,
  `b_fname` varchar(64) NOT NULL,
  `b_lname` varchar(64) NOT NULL,
  `b_addr1` varchar(128) NOT NULL,
  `b_addr2` varchar(128) NOT NULL,
  `b_city` varchar(128) NOT NULL,
  `b_state` varchar(2) NOT NULL,
  `b_zip` int(11) NOT NULL,
  `b_country` varchar(32) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `comment` text,
  `token` varchar(1024) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `accessed` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (5,1,0,11,'admin','$2a$08$LtbZ7x22f4uYzlBJz.2nBuIg2L5HiX0APDWPJZT0Tv1pkVvs6BYqS','male','admin@demo.com','1982-01-07','jiens','framework','123 abc','#101','abc city','CA',90000,'United States','1231231234','Test','','2011-10-11 15:40:41','2014-03-09 16:22:33','0000-00-00 00:00:00','2014-03-09 16:22:33',1),(6,1,0,2,'jaequery','$2a$08$TSwaO95L1nrV7UN6AJe4We.LGyQncidzZzQ/Psjf9pka6EbemYz1G','male','jaequery@gmail.coms','0000-00-00','jae','lee','','','','',0,'','','',NULL,'2014-01-17 08:08:29','2014-03-09 20:33:52',NULL,NULL,1);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ee`
--

DROP TABLE IF EXISTS `ee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ee` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `permalink` varchar(512) NOT NULL,
  `src` enum('none','file','html') NOT NULL DEFAULT 'file',
  `file` varchar(128) NOT NULL,
  `html` text NOT NULL,
  `rank` tinyint(4) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ee`
--

LOCK TABLES `ee` WRITE;
/*!40000 ALTER TABLE `ee` DISABLE KEYS */;
/*!40000 ALTER TABLE `ee` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-09 21:01:34
