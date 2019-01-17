CREATE TABLE `students` (
  `user_id` INT AUTO_INCREMENT,
  `join_date` DATETIME,
  `roll_number` VARCHAR(8),
  `username` VARCHAR(64),
  `webmail_id` VARCHAR(32),
  `password` VARCHAR(40),
  PRIMARY KEY (`user_id`)
);

CREATE TABLE `students_data` (
  `data_id` INT AUTO_INCREMENT,
  `roll_number` VARCHAR(8),
  `current_cpi` FLOAT(4),
  `department` VARCHAR(8),
  `course` VARCHAR(8),
  `profile_pic` VARCHAR(256),
  `resume_url` VARCHAR(256),
  PRIMARY KEY (`data_id`)
);
