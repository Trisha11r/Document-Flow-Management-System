-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 08, 2017 at 03:51 PM
-- Server version: 5.5.54-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dbsys_db`
--
CREATE DATABASE IF NOT EXISTS `dbsys_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dbsys_db`;

-- --------------------------------------------------------

--
-- Table structure for table `DocumentFlow`
--

CREATE TABLE IF NOT EXISTS `DocumentFlow` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) NOT NULL,
  `Description` varchar(256) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the identity and description of all document flows of the system.' AUTO_INCREMENT=32 ;

--
-- Dumping data for table `DocumentFlow`
--

INSERT INTO `DocumentFlow` (`Id`, `Name`, `Description`) VALUES
(30, 'New Flow', 'new flow description'),
(31, 'new flow', 'testing type');

-- --------------------------------------------------------

--
-- Table structure for table `DocumentFlowContents`
--

CREATE TABLE IF NOT EXISTS `DocumentFlowContents` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserGroupId` int(11) NOT NULL,
  `DocumentFlowId` int(11) NOT NULL,
  `SeqNo` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_DocumentFlow_UserGroup1_idx` (`UserGroupId`),
  KEY `fk_DocumentFlowContents_DocumentFlow1_idx` (`DocumentFlowId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the identitites of the user groups which constitut a document flow and the sequence in which they appear in the flow. UserGroupId is the foreign key from UserGroup' AUTO_INCREMENT=63 ;

--
-- Dumping data for table `DocumentFlowContents`
--

INSERT INTO `DocumentFlowContents` (`Id`, `UserGroupId`, `DocumentFlowId`, `SeqNo`) VALUES
(59, 4, 30, 1),
(60, 5, 30, 2),
(61, 4, 31, 1),
(62, 5, 31, 2);

-- --------------------------------------------------------

--
-- Table structure for table `DocumentInstance`
--

CREATE TABLE IF NOT EXISTS `DocumentInstance` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `Description` varchar(256) NOT NULL,
  `DocumentTemplateId` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_DocumentInstance_DocumentTemplate1_idx` (`DocumentTemplateId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the names of all instances of a document type along with the document template identity. DocumentTemplateId is the foreign key form DocumentTemplate.' AUTO_INCREMENT=22 ;

--
-- Dumping data for table `DocumentInstance`
--

INSERT INTO `DocumentInstance` (`Id`, `Name`, `Description`, `DocumentTemplateId`) VALUES
(20, 'New doc 1', 'test document', 3),
(21, 'doc 1', 'test document', 4);

-- --------------------------------------------------------

--
-- Table structure for table `DocumentInstanceContents`
--

CREATE TABLE IF NOT EXISTS `DocumentInstanceContents` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Value` text,
  `Remarks` varchar(256) DEFAULT NULL,
  `DocumentInstanceId` int(11) NOT NULL,
  `SeqNo` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_DocumentInstanceContents_DocumentInstance1_idx` (`DocumentInstanceId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the data conetent of each document instance and associated remarks, if any. DocumenIinstanceId is the foreign key form DocumentInstance.' AUTO_INCREMENT=4595 ;

--
-- Dumping data for table `DocumentInstanceContents`
--

INSERT INTO `DocumentInstanceContents` (`Id`, `Value`, `Remarks`, `DocumentInstanceId`, `SeqNo`) VALUES
(4591, 'Hello this is HR', 'This text is wrong', 20, 1),
(4592, '', '', 20, 2),
(4593, 'hello', 'this is inaccurate', 21, 1),
(4594, '', '', 21, 2);

-- --------------------------------------------------------

--
-- Table structure for table `DocumentInstanceToDocumentFlow`
--

CREATE TABLE IF NOT EXISTS `DocumentInstanceToDocumentFlow` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DocumentInstanceId` int(11) NOT NULL,
  `DocumentTypeToDocumentFlowId` int(11) NOT NULL,
  `CurrentUserGroupId` int(11) DEFAULT NULL,
  `CurrentSeqNo` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_DocumentInstanceToDocumentFlow_DocumentInstance1_idx` (`DocumentInstanceId`),
  KEY `fk_DocumentInstanceToDocumentFlow_DocumentTypeToDocumentFlo_idx` (`DocumentTypeToDocumentFlowId`),
  KEY `fk_DocumentInstanceToDocumentFlow_UserGroup1_idx` (`CurrentUserGroupId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the details of which document flow is associated with each document instance.' AUTO_INCREMENT=22 ;

--
-- Dumping data for table `DocumentInstanceToDocumentFlow`
--

INSERT INTO `DocumentInstanceToDocumentFlow` (`Id`, `DocumentInstanceId`, `DocumentTypeToDocumentFlowId`, `CurrentUserGroupId`, `CurrentSeqNo`) VALUES
(20, 20, 22, 4, 1),
(21, 21, 23, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `DocumentTemplate`
--

CREATE TABLE IF NOT EXISTS `DocumentTemplate` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) NOT NULL,
  `Description` varchar(256) NOT NULL,
  `DocumentTypeId` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_DocumentTemplate_DocumentType1_idx` (`DocumentTypeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the identity of a document template and the document type is corresponds to. DocumentTypeId is the foreign key from DocumentType.' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `DocumentTemplate`
--

INSERT INTO `DocumentTemplate` (`Id`, `Name`, `Description`, `DocumentTypeId`) VALUES
(3, 'Default', 'Default Template', 10),
(4, 'Default', 'Default Template', 11);

-- --------------------------------------------------------

--
-- Table structure for table `DocumentTemplateContents`
--

CREATE TABLE IF NOT EXISTS `DocumentTemplateContents` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TopMargin` double NOT NULL,
  `LeftMargin` double NOT NULL,
  `Width` double NOT NULL,
  `Height` double NOT NULL,
  `DocumentTemplateId` int(11) NOT NULL,
  `SeqNo` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_DocumentTemplateContents_DocumentTemplate1_idx` (`DocumentTemplateId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores the display details (height, width, left-offset, top-offset) of each element in the template. DocumentTemplateId is the foreign key fromDocumentTemplate.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `DocumentType`
--

CREATE TABLE IF NOT EXISTS `DocumentType` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) NOT NULL,
  `Description` varchar(256) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the names of all available document types.' AUTO_INCREMENT=12 ;

--
-- Dumping data for table `DocumentType`
--

INSERT INTO `DocumentType` (`Id`, `Name`, `Description`) VALUES
(10, 'Application', 'Test application'),
(11, 'test', 'tesing');

-- --------------------------------------------------------

--
-- Table structure for table `DocumentTypeContents`
--

CREATE TABLE IF NOT EXISTS `DocumentTypeContents` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UnitDocumentTypeId` int(11) NOT NULL,
  `DocumentTypeId` int(11) NOT NULL,
  `SeqNo` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_DocumentTypeContents_UnitDocumentType1_idx` (`UnitDocumentTypeId`),
  KEY `fk_DocumentTypeContents_DocumentType1_idx` (`DocumentTypeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the identitites of all unit document types which make up each document type. UnitDocumentTypeId and DocumentTypeId are foreign keys from UnitDocumentType andDocumentType respectively.' AUTO_INCREMENT=29 ;

--
-- Dumping data for table `DocumentTypeContents`
--

INSERT INTO `DocumentTypeContents` (`Id`, `UnitDocumentTypeId`, `DocumentTypeId`, `SeqNo`) VALUES
(25, 13, 10, 1),
(26, 14, 10, 2),
(27, 13, 11, 1),
(28, 14, 11, 2);

-- --------------------------------------------------------

--
-- Table structure for table `DocumentTypeToDocumentFlow`
--

CREATE TABLE IF NOT EXISTS `DocumentTypeToDocumentFlow` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DocumentTypeId` int(11) NOT NULL,
  `DocumentFlowId` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_DocumentTypeToDocumentFlow_DocumentType1_idx` (`DocumentTypeId`),
  KEY `fk_DocumentTypeToDocumentFlow_DocumentFlow1_idx` (`DocumentFlowId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the information of which document type can be associated with which document flow. DocumentTypeId and DocumentFlowId are foreign keys from DocuemntType and DocumentFlow respectively.' AUTO_INCREMENT=24 ;

--
-- Dumping data for table `DocumentTypeToDocumentFlow`
--

INSERT INTO `DocumentTypeToDocumentFlow` (`Id`, `DocumentTypeId`, `DocumentFlowId`) VALUES
(22, 10, 30),
(23, 11, 31);

-- --------------------------------------------------------

--
-- Table structure for table `UnitDataType`
--

CREATE TABLE IF NOT EXISTS `UnitDataType` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the names of all unit datatypes supported by the system. This happens to be the same list as is supported by all standard DBMS.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `UnitDataType`
--

INSERT INTO `UnitDataType` (`Id`, `Name`) VALUES
(2, 'string');

-- --------------------------------------------------------

--
-- Table structure for table `UnitDocumentType`
--

CREATE TABLE IF NOT EXISTS `UnitDocumentType` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) NOT NULL,
  `Description` varchar(256) NOT NULL,
  `UnitDataTypeId` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_UnitDocumentType_UnitDataType1_idx` (`UnitDataTypeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the name of each unit document type and its corresponding unit document type.' AUTO_INCREMENT=15 ;

--
-- Dumping data for table `UnitDocumentType`
--

INSERT INTO `UnitDocumentType` (`Id`, `Name`, `Description`, `UnitDataTypeId`) VALUES
(13, 'Text1', 'Text details', 2),
(14, 'Body', 'Body text', 2);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `Id` int(11) NOT NULL,
  `FirstName` varchar(32) NOT NULL,
  `MiddleName` varchar(32) DEFAULT NULL,
  `LastName` varchar(32) NOT NULL,
  `PhoneNumber` varchar(16) NOT NULL,
  `Address1` varchar(128) NOT NULL,
  `Address2` varchar(128) DEFAULT NULL,
  `City` varchar(128) NOT NULL,
  `State` varchar(128) NOT NULL,
  `Country` varchar(128) NOT NULL,
  `PinCode` varchar(16) NOT NULL,
  `Department` varchar(32) NOT NULL,
  `Designation` varchar(32) NOT NULL,
  `Photograph` varchar(2048) NOT NULL,
  `DateTimeAdded` datetime NOT NULL,
  `DateTimeModified` datetime NOT NULL,
  `IsVerified` tinyint(1) NOT NULL,
  `VerifiedByUserGroupID` int(11) DEFAULT NULL,
  `VerifiedDateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores the details of all users registered into the system along with the time and identity of the admin who verified his application.';

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`Id`, `FirstName`, `MiddleName`, `LastName`, `PhoneNumber`, `Address1`, `Address2`, `City`, `State`, `Country`, `PinCode`, `Department`, `Designation`, `Photograph`, `DateTimeAdded`, `DateTimeModified`, `IsVerified`, `VerifiedByUserGroupID`, `VerifiedDateTime`) VALUES
(10, 'Anik', NULL, 'Dutta', '9009009009', 'Kolkata', NULL, 'Kolkata', 'WB', 'Ind', '700000', 'Finance', 'Officer', '', '2017-03-15 00:00:00', '2017-03-15 00:00:00', 1, NULL, NULL),
(12, 'Shilpa', NULL, 'Sharma', '8008008008', 'Kolkata', NULL, 'Kolkata', 'WB', 'Ind', '700007', 'HR', 'Manager', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `UserGroup`
--

CREATE TABLE IF NOT EXISTS `UserGroup` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) NOT NULL,
  `Description` varchar(256) NOT NULL,
  `UserManipulationPerms` tinyint(1) NOT NULL,
  `DocumentTypeManipulationPerms` tinyint(1) NOT NULL,
  `DocumentFlowManipulationPerms` tinyint(1) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the name and description of available user groups.' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `UserGroup`
--

INSERT INTO `UserGroup` (`Id`, `Name`, `Description`, `UserManipulationPerms`, `DocumentTypeManipulationPerms`, `DocumentFlowManipulationPerms`) VALUES
(4, 'HR', 'Human Resource Group', 1, 1, 1),
(5, 'Finance', 'Financial Management', 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `UserToUserGroup`
--

CREATE TABLE IF NOT EXISTS `UserToUserGroup` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) NOT NULL,
  `UserGroupId` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_UserToUserGroup_User_idx` (`UserId`),
  KEY `fk_UserToUserGroup_UserGroup1_idx` (`UserGroupId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Stores the identitites of users in each user group. UserId is a foreign key from User.' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `UserToUserGroup`
--

INSERT INTO `UserToUserGroup` (`Id`, `UserId`, `UserGroupId`) VALUES
(5, 12, 4),
(6, 10, 5);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `DocumentFlowContents`
--
ALTER TABLE `DocumentFlowContents`
  ADD CONSTRAINT `fk_DocumentFlowContents_DocumentFlow1` FOREIGN KEY (`DocumentFlowId`) REFERENCES `DocumentFlow` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DocumentFlow_UserGroup1` FOREIGN KEY (`UserGroupId`) REFERENCES `UserGroup` (`Id`);

--
-- Constraints for table `DocumentInstance`
--
ALTER TABLE `DocumentInstance`
  ADD CONSTRAINT `fk_DocumentInstance_DocumentTemplate1` FOREIGN KEY (`DocumentTemplateId`) REFERENCES `DocumentTemplate` (`Id`);

--
-- Constraints for table `DocumentInstanceContents`
--
ALTER TABLE `DocumentInstanceContents`
  ADD CONSTRAINT `fk_DocumentInstanceContents_DocumentInstance1` FOREIGN KEY (`DocumentInstanceId`) REFERENCES `DocumentInstance` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `DocumentInstanceToDocumentFlow`
--
ALTER TABLE `DocumentInstanceToDocumentFlow`
  ADD CONSTRAINT `fk_DocumentInstanceToDocumentFlow_DocumentInstance1` FOREIGN KEY (`DocumentInstanceId`) REFERENCES `DocumentInstance` (`Id`),
  ADD CONSTRAINT `fk_DocumentInstanceToDocumentFlow_DocumentTypeToDocumentFlow1` FOREIGN KEY (`DocumentTypeToDocumentFlowId`) REFERENCES `DocumentTypeToDocumentFlow` (`Id`),
  ADD CONSTRAINT `fk_DocumentInstanceToDocumentFlow_UserGroup1` FOREIGN KEY (`CurrentUserGroupId`) REFERENCES `UserGroup` (`Id`);

--
-- Constraints for table `DocumentTemplate`
--
ALTER TABLE `DocumentTemplate`
  ADD CONSTRAINT `fk_DocumentTemplate_DocumentType1` FOREIGN KEY (`DocumentTypeId`) REFERENCES `DocumentType` (`Id`);

--
-- Constraints for table `DocumentTemplateContents`
--
ALTER TABLE `DocumentTemplateContents`
  ADD CONSTRAINT `fk_DocumentTemplateContents_DocumentTemplate1` FOREIGN KEY (`DocumentTemplateId`) REFERENCES `DocumentTemplate` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `DocumentTypeContents`
--
ALTER TABLE `DocumentTypeContents`
  ADD CONSTRAINT `fk_DocumentTypeContents_DocumentType1` FOREIGN KEY (`DocumentTypeId`) REFERENCES `DocumentType` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DocumentTypeContents_UnitDocumentType1` FOREIGN KEY (`UnitDocumentTypeId`) REFERENCES `UnitDocumentType` (`Id`);

--
-- Constraints for table `DocumentTypeToDocumentFlow`
--
ALTER TABLE `DocumentTypeToDocumentFlow`
  ADD CONSTRAINT `fk_DocumentTypeToDocumentFlow_DocumentFlow1` FOREIGN KEY (`DocumentFlowId`) REFERENCES `DocumentFlow` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DocumentTypeToDocumentFlow_DocumentType1` FOREIGN KEY (`DocumentTypeId`) REFERENCES `DocumentType` (`Id`);

--
-- Constraints for table `UnitDocumentType`
--
ALTER TABLE `UnitDocumentType`
  ADD CONSTRAINT `fk_UnitDocumentType_UnitDataType1` FOREIGN KEY (`UnitDataTypeId`) REFERENCES `UnitDataType` (`Id`);

--
-- Constraints for table `UserToUserGroup`
--
ALTER TABLE `UserToUserGroup`
  ADD CONSTRAINT `fk_UserToUserGroup_User` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`),
  ADD CONSTRAINT `fk_UserToUserGroup_UserGroup1` FOREIGN KEY (`UserGroupId`) REFERENCES `UserGroup` (`Id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
