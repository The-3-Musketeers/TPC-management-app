/* Create tables */

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
  `resume_file` VARCHAR(256),
  `mobile_number` VARCHAR(16),
  `job_offers` VARCHAR(32),
  `skype_Id` varchar(255) DEFAULT NULL,
  `gmail_Id` varchar(255) DEFAULT NULL,
  `emergency_number` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`data_id`)
);

CREATE TABLE `applications` (
  `application_id` INT AUTO_INCREMENT,
  `job_id` int(10) NOT NULL,
  `student_roll_number` VARCHAR(8),
  `application_status` VARCHAR(10) NOT NULL DEFAULT 'pending',
  `company_category` VARCHAR(2),
  `applied_on` DATETIME,
  PRIMARY KEY (`application_id`)
);

CREATE TABLE `recruiters` (
  `user_id` INT AUTO_INCREMENT,
  `join_date` DATETIME,
  `company_id` VARCHAR(8),
  `company_name` VARCHAR(64),
  `company_desc` VARCHAR(256),
  `company_category` VARCHAR(2),
  `company_type` VARCHAR(10),
  `turnover` VARCHAR(10),
  `scr_rounds` INT(2),
  `company_url` VARCHAR(256),
  `company_status` VARCHAR(10),
  `company_img` varchar(256) DEFAULT NULL,
  `hr_name_1` VARCHAR(32),
  `hr_email_1` VARCHAR(64),
  `hr_designation_1` VARCHAR(32),
  `hr_name_2` VARCHAR(32),
  `hr_email_2` VARCHAR(64),
  `hr_designation_2` VARCHAR(32),
  `hr_name_3` VARCHAR(32),
  `hr_email_3` VARCHAR(64),
  `hr_designation_3` VARCHAR(32),
  `access_token` VARCHAR(64),
  `password` VARCHAR(40),
  PRIMARY KEY (`user_id`),
  FULLTEXT(`company_name`)
);

CREATE TABLE `degree` (
 `degree_id` varchar(6),
 `degree_name` varchar(128) NOT NULL,
 PRIMARY KEY (`degree_id`)
);

CREATE TABLE `branch` (
 `branch_id` varchar(6),
 `branch_name` varchar(128) NOT NULL,
 PRIMARY KEY (`branch_id`)
);

CREATE TABLE `degree_branch` (
 `db_id` varchar(6),
 `degree_id` varchar(6),
 `branch_id` varchar(6),
 PRIMARY KEY (`db_id`),
 FOREIGN KEY (`degree_id`) REFERENCES `degree`(`degree_id`),
 FOREIGN KEY (`branch_id`) REFERENCES `branch`(`branch_id`)
);

CREATE TABLE `jobs` (
 `job_id` varchar(6),
 `job_position` varchar(256) NOT NULL,
 `job_status` VARCHAR(10) NOT NULL DEFAULT 'pending',
 `min_cpi` float DEFAULT NULL,
 `no_of_opening` int(5) DEFAULT NULL,
 `apply_by` varchar(20) DEFAULT NULL,
 `stipend` int(10) DEFAULT NULL,
 `ctc` int(10) DEFAULT NULL,
 `test_date` varchar(20) DEFAULT NULL,
 `job_desc` varchar(256) DEFAULT NULL,
 `created_on` date DEFAULT NULL,
 `company_id` VARCHAR(8) NOT NULL,
 `company_name` VARCHAR(256) NOT NULL,
 PRIMARY KEY (`job_id`),
 FULLTEXT(`job_position`),FULLTEXT(`company_name`)
);

CREATE TABLE `jobs_db` (
 `job_id` varchar(6),
 `db_id` varchar(6),
 PRIMARY KEY (`job_id`, `db_id`),
 FOREIGN KEY (`job_id`) REFERENCES `jobs`(`job_id`),
 FOREIGN KEY (`db_id`) REFERENCES `degree_branch`(`db_id`)
);
/* Add admin to students table */

INSERT INTO students (username, user_role, password, join_date) VALUES ('admin', 'admin', 'admin', NOW());
