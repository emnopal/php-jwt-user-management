-- Create database
-- For user management
CREATE DATABASE IF NOT EXISTS `login-management`;

-- Table for user management
CREATE TABLE IF NOT EXISTS `login-management`.`users`
(
    `id`       VARCHAR(255) NOT NULL,
    `name`     VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `login-management`.`users`
    RENAME COLUMN `id` TO `username`,
    MODIFY COLUMN `password` VARCHAR(255) NOT NULL AFTER `username`,
    RENAME COLUMN `name` TO `fullName`,
    ADD COLUMN `email` VARCHAR(255) NOT NULL AFTER `fullName`;

SHOW CREATE TABLE `login-management`.`users`;

-- Table for session management
CREATE TABLE IF NOT EXISTS `login-management`.`sessions`
(
    `id`      VARCHAR(255) NOT NULL,
    `user_id` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

SHOW CREATE TABLE `login-management`.`sessions`;

-- Foreign key between users and sessions
ALTER TABLE `login-management`.`sessions`
    ADD CONSTRAINT `fk_sessions_users`
        FOREIGN KEY (`user_id`)
            REFERENCES `login-management`.`users` (`username`);

-- Create database
-- For testing purposes
CREATE DATABASE IF NOT EXISTS `login-management-test`;

CREATE TABLE IF NOT EXISTS `login-management-test`.`users`
(
    `id`       VARCHAR(255) NOT NULL,
    `name`     VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `login-management-test`.`users`
    RENAME COLUMN `id` TO `username`,
    MODIFY COLUMN `password` VARCHAR(255) NOT NULL AFTER `username`,
    RENAME COLUMN `name` TO `fullName`,
    ADD COLUMN `email` VARCHAR(255) NOT NULL AFTER `fullName`;

SHOW CREATE TABLE `login-management-test`.`users`;

CREATE TABLE IF NOT EXISTS `login-management-test`.`sessions`
(
    `id`      VARCHAR(255) NOT NULL,
    `user_id` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `login-management-test`.`sessions`
    ADD CONSTRAINT fk_sessions_users
        FOREIGN KEY (`user_id`)
            REFERENCES `login-management-test`.`users` (`username`);

