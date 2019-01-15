CREATE TABLE `students` (
  `user_id` INT AUTO_INCREMENT,
  `join_date` DATETIME,
  `roll_number` VARCHAR(8),
  `username` VARCHAR(64),
  `webmail_id` VARCHAR(32),
  `password` VARCHAR(40),
  PRIMARY KEY (`user_id`)
);
