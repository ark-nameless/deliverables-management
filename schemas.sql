SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `activities`;
DROP TABLE IF EXISTS `faculties`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `submitted_deliverables`;
DROP TABLE IF EXISTS `deliverables`;
DROP TABLE IF EXISTS `school_years`;
DROP TABLE IF EXISTS `semesters`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `admins` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `activities` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `activity_name` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `activity_date` DATE NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `faculties` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `faculty_id` VARCHAR(50) NOT NULL,
    `lastname` VARCHAR(50) NOT NULL,
    `firstname` VARCHAR(50) NOT NULL,
    `middlename` VARCHAR(50) NOT NULL,
    `address` TEXT NOT NULL,
    `contact_no` VARCHAR(20) NOT NULL,
    `username` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `notifications` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `faculty_id` INT NOT NULL,
    `text` text NOT NULL,
    `received_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    `status` INT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `submitted_deliverables` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `name` TEXT NOT NULL,
    `deliverable_id` INT NOT NULL,
    `description` TEXT NOT NULL,
    `faculty_id` INT NOT NULL,
    `semester_id` INT NOT NULL,
    `school_year_id` INT NOT NULL,
    `date_uploaded` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    `date_updated` DATETIME NOT NULL,
    `status` VARCHAR(20) NOT NULL,
    `remarks` TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `deliverables` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `name` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    `deadline` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `school_years` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `start_year` INT NOT NULL,
    `end_year` INT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `semesters` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `semester` VARCHAR(20) NOT NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `notifications` ADD FOREIGN KEY (`faculty_id`) REFERENCES `faculties`(`id`);
ALTER TABLE `submitted_deliverables` ADD FOREIGN KEY (`deliverable_id`) REFERENCES `deliverables`(`id`);
ALTER TABLE `submitted_deliverables` ADD FOREIGN KEY (`semester_id`) REFERENCES `semesters`(`id`);
ALTER TABLE `submitted_deliverables` ADD FOREIGN KEY (`school_year_id`) REFERENCES `school_years`(`id`);
ALTER TABLE `submitted_deliverables` ADD FOREIGN KEY (`faculty_id`) REFERENCES `faculties`(`id`);