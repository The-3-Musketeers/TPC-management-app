/* Create tables */

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
  `db_id` varchar(6),
  `profile_pic` varchar(256) DEFAULT NULL,
  `resume_url` varchar(256) DEFAULT NULL,
  `resume_file` varchar(256) DEFAULT NULL,
  `mobile_number` varchar(16) DEFAULT NULL,
  `job_offers` varchar(32) DEFAULT NULL,
  `skype_Id` varchar(255) DEFAULT NULL,
  `gmail_Id` varchar(255) DEFAULT NULL,
  `emergency_number` varchar(16) DEFAULT NULL,
  `year_of_enroll` year(4) NOT NULL,
  `final_accepted_offer` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`roll_number`),
  FOREIGN KEY (`roll_number`) REFERENCES students (`roll_number`)
);

CREATE TABLE company_category (
  id VARCHAR(6),
  name VARCHAR(8),
  PRIMARY KEY (id)
);

CREATE TABLE company_constraints (
  current_id VARCHAR(6),
  can_apply_id VARCHAR(6),
  num INT, /* Number of times student can apply to can_apply_id if selected in current_id */
  PRIMARY KEY (current_id, can_apply_id),
  FOREIGN KEY (current_id) REFERENCES company_category(id),
  FOREIGN KEY (can_apply_id) REFERENCES company_category(id)
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
  company_category_id VARCHAR(6),
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
  FOREIGN KEY (company_category_id) REFERENCES company_category(id)
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

CREATE TABLE `applications` (
  `application_id` INT AUTO_INCREMENT,
  `job_id` VARCHAR(6) NOT NULL,
  `student_roll_number` VARCHAR(8),
  `application_status` VARCHAR(10) NOT NULL DEFAULT 'pending',
  `applied_on` DATETIME,
  PRIMARY KEY (`application_id`),
  FOREIGN KEY (`job_id`) REFERENCES jobs (`job_id`)
);

CREATE TABLE archive_students_data (
  roll_number VARCHAR(8),
  username VARCHAR(64) NOT NULL,
  db_id VARCHAR(6) NOT NULL,
  mobile_number VARCHAR(16) DEFAULT NULL,
  gmail_Id VARCHAR(255) DEFAULT NULL,
  year_of_enroll year(4) NOT NULL,
  company_id VARCHAR(8) DEFAULT NULL,
  PRIMARY KEY(roll_number)
);

CREATE TABLE archive_company_visit_year (
  company_id VARCHAR(8) NOT NULL,
  year_visited year(4) NOT NULL,
  company_category VARCHAR(8) NOT NULL,
  PRIMARY KEY(company_id,year_visited)
);

/* Add admin to students table */

INSERT INTO students (roll_number,username, user_role, password, join_date) VALUES ('admin','admin', 'admin', 'admin', NOW());

/* Add current categories */

INSERT INTO `company_category` (`id`, `name`) VALUES ('Bdtl61', 'A1'), ('Hiakm6', 'B2'), ('KnAtiB', 'B1');
INSERT INTO `company_constraints` (`current_id`, `can_apply_id`, `num`) VALUES ('Hiakm6', 'Bdtl61', 2), ('Hiakm6', 'KnAtiB', 2), ('KnAtiB', 'Bdtl61', 2);

/* Add current categories */

INSERT INTO `degree` VALUES ('abc1', 'btech'), ('abc2', 'mtech'), ('abc3', 'phd');
INSERT INTO `branch` VALUES ('def1', 'CS'), ('def2', 'EE'), ('def3', 'ME');
INSERT INTO `degree_branch` VALUES ('xyz1', 'abc1', 'def1'), ('xyz2', 'abc1', 'def2'), ('xyz5', 'abc1', 'def3'), ('xyz3', 'abc2', 'def1'), ('xyz4', 'abc2', 'def2');
