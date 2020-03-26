USE appdb;

CREATE TABLE event_stream (
    `id` BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `aggregate_id` CHAR(36) NOT NULL,
    `aggregate_version` INT(11) UNSIGNED NOT NULL,
    `event_type` VARCHAR(100) NOT NULL,
    `payload` JSON NOT NULL,
    `created_at` DATETIME NOT NULL,
    UNIQUE KEY `unique_aggregate_version` (`aggregate_id`,`aggregate_version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;