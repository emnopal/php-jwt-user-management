CREATE DATABASE IF NOT EXISTS `login-management-jwt`;
CREATE DATABASE IF NOT EXISTS `login-management-test-jwt`;

CREATE TABLE IF NOT EXISTS `login-management-jwt`.`users`
(
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `fullName` VARCHAR(255) NOT NULL,
    `email`    VARCHAR(255) NOT NULL,
    PRIMARY KEY (`username`),
    UNIQUE KEY `email_key` (`email`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `login-management-test-jwt`.`users`
(
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `fullName` VARCHAR(255) NOT NULL,
    `email`    VARCHAR(255) NOT NULL,
    PRIMARY KEY (`username`),
    UNIQUE KEY `email_key` (`email`)
) ENGINE = InnoDB;
