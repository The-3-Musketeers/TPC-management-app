CREATE TABLE `students` (
  `user_id` INT AUTO_INCREMENT,
  `join_date` DATETIME,
  `roll_number` VARCHAR(8),
  `username` VARCHAR(64),
  `user_role` VARCHAR(8),
  `webmail_id` VARCHAR(32),
  `password` VARCHAR(40),
  `access_token` VARCHAR(64),
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
  `mobile_number` VARCHAR(16),
  PRIMARY KEY (`data_id`)
);

CREATE TABLE `recruiters` (
  `user_id` INT AUTO_INCREMENT,
  `join_date` DATETIME,
  `company_id` VARCHAR(8),
  `company_name` VARCHAR(64),
  `company_category` VARCHAR(2),
  `hr_name` VARCHAR(32),
  `hr_email` VARCHAR(64),
  `access_token` VARCHAR(64),
  `password` VARCHAR(40),
  PRIMARY KEY (`user_id`)
);
