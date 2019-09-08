CREATE TABLE `stapp_user` (
  `user_id` INT AUTO_INCREMENT,
  `username` VARCHAR(32),
  `password` VARCHAR(40),
  `email` VARCHAR(256),
  `join_date` DATETIME,
  `first_name` VARCHAR(32),
  `last_name` VARCHAR(32),
  `birthdate` DATE,
  `country` VARCHAR(2),
  PRIMARY KEY (`user_id`)
);
