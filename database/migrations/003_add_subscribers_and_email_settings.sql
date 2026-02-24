-- Migration 003: Add subscribers table and email configuration settings
-- Run: mysql -u root news_cms < database/migrations/003_add_subscribers_and_email_settings.sql

USE news_cms;

-- Subscribers table
CREATE TABLE IF NOT EXISTS subscribers (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email           VARCHAR(255) NOT NULL UNIQUE,
    name            VARCHAR(100) DEFAULT NULL,
    status          ENUM('active','unsubscribed') NOT NULL DEFAULT 'active',
    subscribed_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at DATETIME DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email log table (track sent emails)
CREATE TABLE IF NOT EXISTS email_logs (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subject         VARCHAR(500) NOT NULL,
    body            LONGTEXT NOT NULL,
    recipient_count INT UNSIGNED NOT NULL DEFAULT 0,
    sent_by         INT UNSIGNED DEFAULT NULL,
    sent_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sent_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email configuration settings
INSERT INTO settings (key_name, value, type, group_name, label) VALUES
    ('smtp_host', '', 'string', 'email', 'SMTP Host'),
    ('smtp_port', '587', 'string', 'email', 'SMTP Port'),
    ('smtp_user', '', 'string', 'email', 'SMTP Username'),
    ('smtp_pass', '', 'string', 'email', 'SMTP Password'),
    ('smtp_encryption', 'tls', 'string', 'email', 'Encryption (tls/ssl)'),
    ('smtp_from_email', 'noreply@example.com', 'string', 'email', 'From Email'),
    ('smtp_from_name', 'NewsCMS', 'string', 'email', 'From Name')
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Add some test subscribers
INSERT INTO subscribers (email, name, status) VALUES
    ('john.doe@example.com', 'John Doe', 'active'),
    ('jane.smith@example.com', 'Jane Smith', 'active'),
    ('reader@example.com', 'Avid Reader', 'active'),
    ('tech.fan@example.com', 'Tech Fan', 'active'),
    ('unsubbed@example.com', 'Former Reader', 'unsubscribed')
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;
