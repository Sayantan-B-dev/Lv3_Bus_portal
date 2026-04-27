CREATE DATABASE IF NOT EXISTS `bus_portal`;
USE `bus_portal`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



-- USERS Required for User.php model

CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `google_id` VARCHAR(255) DEFAULT NULL,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `avatar_url` TEXT DEFAULT NULL,
    `role` ENUM('admin','editor','viewer') NOT NULL DEFAULT 'viewer',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `last_login_at` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_email` (`email`),
    UNIQUE KEY `uq_users_google_id` (`google_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- ACTIVITY LOG

CREATE TABLE `activity_log` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `action` VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(50) DEFAULT NULL,
    `entity_id` INT UNSIGNED DEFAULT NULL,
    `details` LONGTEXT DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),

    CONSTRAINT `fk_activity_user`
        FOREIGN KEY (`user_id`)
        REFERENCES `users`(`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- CITIES

CREATE TABLE `cities` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `city_name` VARCHAR(100) NOT NULL,
    `state_region` VARCHAR(100) DEFAULT NULL,
    `country` VARCHAR(100) NOT NULL DEFAULT 'India',
    `country_code` CHAR(2) NOT NULL DEFAULT 'IN',
    `currency` VARCHAR(10) NOT NULL DEFAULT 'INR',
    `timezone` VARCHAR(60) NOT NULL DEFAULT 'Asia/Kolkata',
    `center_lat` DECIMAL(10,7) NOT NULL,
    `center_lng` DECIMAL(10,7) NOT NULL,
    `default_zoom` TINYINT DEFAULT 12,
    `osm_relation_id` BIGINT DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- ROUTES

CREATE TABLE `routes` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `city_id` INT UNSIGNED NOT NULL,
    `route_number` VARCHAR(20) NOT NULL,
    `source` VARCHAR(120) NOT NULL,
    `destination` VARCHAR(120) NOT NULL,
    `route_type` ENUM('AC','Express','Normal','Night','Mini') 
        NOT NULL DEFAULT 'Normal',
    `frequency_mins` TINYINT UNSIGNED NOT NULL DEFAULT 20,
    `first_bus_time` TIME NOT NULL,
    `last_bus_time` TIME NOT NULL,
    `total_distance_km` DECIMAL(6,2) NOT NULL DEFAULT 0.00,
    `description` TEXT DEFAULT NULL,
    `osm_relation_id` BIGINT DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),

    UNIQUE KEY `uq_city_route` (`city_id`, `route_number`),

    CONSTRAINT `fk_routes_city`
        FOREIGN KEY (`city_id`)
        REFERENCES `cities`(`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- STOPS

CREATE TABLE `stops` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `city_id` INT UNSIGNED NOT NULL,
    `stop_name` VARCHAR(150) NOT NULL,
    `stop_code` VARCHAR(20) DEFAULT NULL,
    `latitude` DECIMAL(10,7) DEFAULT NULL,
    `longitude` DECIMAL(10,7) DEFAULT NULL,
    `landmark` VARCHAR(200) DEFAULT NULL,
    `zone` VARCHAR(50) DEFAULT NULL,
    `osm_node_id` BIGINT DEFAULT NULL,
    `is_terminal` TINYINT(1) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),

    INDEX `idx_stop_code` (`stop_code`),

    CONSTRAINT `fk_stops_city`
        FOREIGN KEY (`city_id`)
        REFERENCES `cities`(`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- ROUTE STOPS

CREATE TABLE `route_stops` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `route_id` INT UNSIGNED NOT NULL,
    `stop_id` INT UNSIGNED NOT NULL,
    `stop_order` TINYINT UNSIGNED NOT NULL,
    `distance_from_start_km` DECIMAL(6,2) NOT NULL DEFAULT 0.00,
    `arrival_time_offset_mins` SMALLINT UNSIGNED DEFAULT NULL,
    `is_major_stop` TINYINT(1) DEFAULT 0,

    PRIMARY KEY (`id`),

    UNIQUE KEY `uq_route_stop_order` (`route_id`, `stop_order`),
    UNIQUE KEY `uq_route_stop` (`route_id`, `stop_id`),

    CONSTRAINT `fk_route_stops_route`
        FOREIGN KEY (`route_id`)
        REFERENCES `routes`(`id`)
        ON DELETE CASCADE,

    CONSTRAINT `fk_route_stops_stop`
        FOREIGN KEY (`stop_id`)
        REFERENCES `stops`(`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- FARES

CREATE TABLE `fares` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `route_id` INT UNSIGNED NOT NULL,
    `min_km` DECIMAL(5,2) NOT NULL,
    `max_km` DECIMAL(5,2) NOT NULL,
    `fare_amount` DECIMAL(6,2) NOT NULL,
    `passenger_type` ENUM('General','Student','Senior') 
        DEFAULT 'General',

    PRIMARY KEY (`id`),

    CONSTRAINT `fk_fares_route`
        FOREIGN KEY (`route_id`)
        REFERENCES `routes`(`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- OAUTH TOKENS

CREATE TABLE `oauth_tokens` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `access_token` TEXT NOT NULL,
    `refresh_token` TEXT DEFAULT NULL,
    `token_type` VARCHAR(50) DEFAULT 'Bearer',
    `expires_at` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),

    CONSTRAINT `fk_oauth_user`
        FOREIGN KEY (`user_id`)
        REFERENCES `users`(`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- OPTIONAL DEFAULT ADMIN SEED

INSERT INTO users (
    name,
    email,
    role
) VALUES (
    'Admin User',
    'admin@busportal.com',
    'admin'
);


COMMIT;