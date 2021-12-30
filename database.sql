-- Create database
-- For user management
CREATE DATABASE IF NOT EXISTS `login-management-jwt`;

-- Table for user management
CREATE TABLE IF NOT EXISTS `login-management-jwt`.`users`
(
    `id`       VARCHAR(255) NOT NULL,
    `name`     VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `login-management-jwt`.`users`
    RENAME COLUMN `id` TO `username`,
    MODIFY COLUMN `password` VARCHAR(255) NOT NULL AFTER `username`,
    RENAME COLUMN `name` TO `fullName`,
    ADD COLUMN `email` VARCHAR(255) NOT NULL AFTER `fullName`;

-- Create database
-- For testing purposes
CREATE DATABASE IF NOT EXISTS `login-management-test-jwt`;

CREATE TABLE IF NOT EXISTS `login-management-test-jwt`.`users`
(
    `id`       VARCHAR(255) NOT NULL,
    `name`     VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `login-management-test-jwt`.`users`
    RENAME COLUMN `id` TO `username`,
    MODIFY COLUMN `password` VARCHAR(255) NOT NULL AFTER `username`,
    RENAME COLUMN `name` TO `fullName`,
    ADD COLUMN `email` VARCHAR(255) NOT NULL AFTER `fullName`;

