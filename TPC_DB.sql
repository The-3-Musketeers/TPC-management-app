/* Create tables */

CREATE TABLE `students` (
  `join_date` DATETIME,
  `roll_number` VARCHAR(8),
  `username` VARCHAR(64),
  `user_role` VARCHAR(8),
  `webmail_id` VARCHAR(32),
  `password` VARCHAR(40),
  `access_token` VARCHAR(64),
  PRIMARY KEY (`roll_number`)
);

CREATE TABLE `students_data` (
  `roll_number` varchar(8) NOT NULL,
  `current_cpi` float DEFAULT NULL,
  `department` varchar(8) DEFAULT NULL,
  `course` varchar(8) DEFAULT NULL,
  `profile_pic` varchar(256) DEFAULT NULL,
  `resume_url` varchar(256) DEFAULT NULL,
  `resume_file` varchar(256) DEFAULT NULL,
  `mobile_number` varchar(16) DEFAULT NULL,
  `job_offers` varchar(32) DEFAULT NULL,
  `skype_Id` varchar(255) DEFAULT NULL,
  `gmail_Id` varchar(255) DEFAULT NULL,
  `emergency_number` varchar(16) DEFAULT NULL,
  `year_of_enroll` year(4) NOT NULL,
  PRIMARY KEY (`roll_number`),
  FOREIGN KEY (`roll_number`) REFERENCES students (`roll_number`)
);

CREATE TABLE `applications` (
  `application_id` INT AUTO_INCREMENT,
  `job_id` VARCHAR(6) NOT NULL,
  `student_roll_number` VARCHAR(8),
  `application_status` VARCHAR(10) NOT NULL DEFAULT 'pending',
  `applied_on` DATETIME,
  PRIMARY KEY (`application_id`),
  FOREIGN KEY (`job_id`) REFERENCES positions (`job_id`)
);

CREATE TABLE recruiters (
  company_id VARCHAR(8),
  join_date DATETIME,
  access_token VARCHAR(64),
  password VARCHAR(40),
  PRIMARY KEY (company_id)
);

CREATE TABLE recruiters_data (
  company_id VARCHAR(8),
  company_name VARCHAR(64),
  company_desc VARCHAR(256),
  company_category VARCHAR(2),
  company_type VARCHAR(10),
  turnover VARCHAR(10),
  scr_rounds INT(2),
  company_url VARCHAR(256),
  company_status VARCHAR(10),
  company_img varchar(256) DEFAULT NULL,
  hr_name_1 VARCHAR(32),
  hr_email_1 VARCHAR(64),
  hr_designation_1 VARCHAR(32),
  hr_name_2 VARCHAR(32),
  hr_email_2 VARCHAR(64),
  hr_designation_2 VARCHAR(32),
  hr_name_3 VARCHAR(32),
  hr_email_3 VARCHAR(64),
  hr_designation_3 VARCHAR(32),
  PRIMARY KEY (company_id),
  FOREIGN KEY (company_id) REFERENCES recruiters(company_id),
  FULLTEXT (company_name)
);

CREATE TABLE `positions` (
 `job_id` varchar(6) NOT NULL,
 `job_position` varchar(256) NOT NULL,
 `job_status` VARCHAR(10) NOT NULL DEFAULT 'pending',
 `course` varchar(256) DEFAULT NULL,
 `branch` varchar(256) DEFAULT NULL,
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
/* Add admin to students table */

INSERT INTO students (roll_number,username, user_role, password, join_date) VALUES ('admin','admin', 'admin', 'admin', NOW());
