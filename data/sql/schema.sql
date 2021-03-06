/*
Target Server Type    : MYSQL
Target Server Version : 50150
File Encoding         : 65001

Date: 2013-01-08 17:15:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `movie`
-- ----------------------------
DROP TABLE IF EXISTS `movie`;
CREATE TABLE `movie` (
  `movie_id` int(11) NOT NULL AUTO_INCREMENT,
  `ASIN` varchar(255) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `date_added` timestamp NULL DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`movie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `movie_image`
-- ----------------------------
DROP TABLE IF EXISTS `movie_image`;
CREATE TABLE `movie_image` (
  `image_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `width` int(5) NOT NULL,
  `height` int(5) NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `movie_id` (`movie_id`),
  CONSTRAINT `movie_image_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `user_movie_collection`
-- ----------------------------
DROP TABLE IF EXISTS `user_movie_collection`;
CREATE TABLE `user_movie_collection` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `movie_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`movie_id`),
  KEY `movie_id` (`movie_id`),
  CONSTRAINT `user_movie_collection_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `user_movie_collection_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`movie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
