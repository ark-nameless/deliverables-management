SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `activities`;
DROP TABLE IF EXISTS `faculties`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `submitted_deliverables`;
DROP TABLE IF EXISTS `deliverables`;
DROP TABLE IF EXISTS `school_years`;
DROP TABLE IF EXISTS `semesters`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `reports`;
DROP TABLE IF EXISTS `downloadable_contents`;
DROP TABLE IF EXISTS `messages`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `admins` (
    `user_id` INT NOT NULL,
    `profile_img` TEXT NOT NULL
);

CREATE TABLE `activities` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `activity_name` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `activity_date` DATE NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `faculties` (
    `user_id` INT NOT NULL,
    `faculty_id` VARCHAR(50) NOT NULL,
    `lastname` VARCHAR(50) NOT NULL,
    `firstname` VARCHAR(50) NOT NULL,
    `middlename` VARCHAR(50) NOT NULL,
    `address` TEXT NOT NULL,
    `contact_no` VARCHAR(20) NOT NULL,
    `profile_img` TEXT NOT NULL
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
    `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `status` VARCHAR(20) NOT NULL DEFAULT 'Pending',
    `remarks` TEXT NOT NULL,
    `ext` TEXT NOT NULL,
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

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `reports` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `faculty_id` INT NOT NULL,
    `activity_id` INT NOT NULL,
    `attachment` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    `remarks` TEXT NOT NULL,
    `viewed` BOOLEAN NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `downloadable_contents` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `filename` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    `ext` TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `messages` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `from` INT NOT NULL,
    `to` INT NOT NULL,
    `subject` TEXT NOT NULL,
    `contents` TEXT NOT NULL,
    `sent_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `admins` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);
ALTER TABLE `faculties` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);
ALTER TABLE `submitted_deliverables` ADD FOREIGN KEY (`deliverable_id`) REFERENCES `deliverables`(`id`);
ALTER TABLE `submitted_deliverables` ADD FOREIGN KEY (`school_year_id`) REFERENCES `school_years`(`id`);
ALTER TABLE `submitted_deliverables` ADD FOREIGN KEY (`semester_id`) REFERENCES `semesters`(`id`);
ALTER TABLE `submitted_deliverables` ADD FOREIGN KEY (`faculty_id`) REFERENCES `faculties`(`user_id`);
ALTER TABLE `reports` ADD FOREIGN KEY (`activity_id`) REFERENCES `activities`(`id`);
ALTER TABLE `reports` ADD FOREIGN KEY (`faculty_id`) REFERENCES `faculties`(`user_id`);
ALTER TABLE `messages` ADD FOREIGN KEY (`from`) REFERENCES `users`(`id`);
ALTER TABLE `messages` ADD FOREIGN KEY (`to`) REFERENCES `users`(`id`);






INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('macmac', 'marka6988@gmail.com', '$2y$10$CiSyJn4zzcEcd7f4aS56vuIltLMxLqEqAezrtspcDGf3XlBt0CjSS', 'admin');
