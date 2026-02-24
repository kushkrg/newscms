-- NewsCMS Database Schema
-- Run: mysql -u root news_cms < database/schema.sql

CREATE DATABASE IF NOT EXISTS news_cms
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE news_cms;

-- Users
CREATE TABLE IF NOT EXISTS users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    role            ENUM('super_admin','editor','author','contributor') NOT NULL DEFAULT 'author',
    slug            VARCHAR(150) NOT NULL UNIQUE,
    bio             TEXT,
    avatar          VARCHAR(255),
    website         VARCHAR(255),
    twitter         VARCHAR(100),
    linkedin        VARCHAR(100),
    is_active       TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at   DATETIME,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id       INT UNSIGNED DEFAULT NULL,
    name            VARCHAR(150) NOT NULL,
    slug            VARCHAR(175) NOT NULL UNIQUE,
    description     TEXT,
    meta_title      VARCHAR(160),
    meta_description VARCHAR(320),
    post_count      INT UNSIGNED NOT NULL DEFAULT 0,
    sort_order      INT NOT NULL DEFAULT 0,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tags
CREATE TABLE IF NOT EXISTS tags (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    slug            VARCHAR(120) NOT NULL UNIQUE,
    post_count      INT UNSIGNED NOT NULL DEFAULT 0,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Posts
CREATE TABLE IF NOT EXISTS posts (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id             INT UNSIGNED NOT NULL,
    category_id         INT UNSIGNED,
    title               VARCHAR(300) NOT NULL,
    slug                VARCHAR(350) NOT NULL UNIQUE,
    excerpt             TEXT,
    content             LONGTEXT NOT NULL,
    featured_image      VARCHAR(255),
    featured_image_alt  VARCHAR(300),
    status              ENUM('draft','published','archived','scheduled') NOT NULL DEFAULT 'draft',
    is_featured         TINYINT(1) NOT NULL DEFAULT 0,
    is_sticky           TINYINT(1) NOT NULL DEFAULT 0,
    allow_comments      TINYINT(1) NOT NULL DEFAULT 1,
    reading_time_mins   TINYINT UNSIGNED DEFAULT 1,
    view_count          INT UNSIGNED NOT NULL DEFAULT 0,
    comment_count       INT UNSIGNED NOT NULL DEFAULT 0,
    meta_title          VARCHAR(160),
    meta_description    VARCHAR(320),
    og_image            VARCHAR(255),
    canonical_url       VARCHAR(500),
    download_file       VARCHAR(500),
    download_file_name  VARCHAR(255),
    schema_type         ENUM('Article','NewsArticle','BlogPosting') NOT NULL DEFAULT 'Article',
    published_at        DATETIME,
    scheduled_at        DATETIME,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FULLTEXT INDEX ft_search (title, excerpt, content),
    INDEX idx_status_published (status, published_at),
    INDEX idx_category (category_id),
    INDEX idx_user (user_id),
    INDEX idx_featured (is_featured, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Post-Tags Pivot
CREATE TABLE IF NOT EXISTS post_tags (
    post_id     INT UNSIGNED NOT NULL,
    tag_id      INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Post Revisions
CREATE TABLE IF NOT EXISTS post_revisions (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id     INT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    title       VARCHAR(300) NOT NULL,
    content     LONGTEXT NOT NULL,
    excerpt     TEXT,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_post_id (post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pages
CREATE TABLE IF NOT EXISTS pages (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id             INT UNSIGNED NOT NULL,
    title               VARCHAR(300) NOT NULL,
    slug                VARCHAR(350) NOT NULL UNIQUE,
    content             LONGTEXT NOT NULL,
    template            ENUM('default','full_width','sidebar') NOT NULL DEFAULT 'default',
    status              ENUM('draft','published') NOT NULL DEFAULT 'draft',
    meta_title          VARCHAR(160),
    meta_description    VARCHAR(320),
    og_image            VARCHAR(255),
    sort_order          INT NOT NULL DEFAULT 0,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comments
CREATE TABLE IF NOT EXISTS comments (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id         INT UNSIGNED NOT NULL,
    parent_id       INT UNSIGNED DEFAULT NULL,
    user_id         INT UNSIGNED DEFAULT NULL,
    author_name     VARCHAR(100) NOT NULL,
    author_email    VARCHAR(255) NOT NULL,
    author_website  VARCHAR(255),
    author_ip       VARCHAR(45),
    content         TEXT NOT NULL,
    status          ENUM('pending','approved','spam','trash') NOT NULL DEFAULT 'pending',
    is_admin_reply  TINYINT(1) NOT NULL DEFAULT 0,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_post_status (post_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Media
CREATE TABLE IF NOT EXISTS media (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    filename        VARCHAR(255) NOT NULL,
    original_name   VARCHAR(255) NOT NULL,
    mime_type       VARCHAR(100) NOT NULL,
    file_size       INT UNSIGNED NOT NULL,
    width           SMALLINT UNSIGNED,
    height          SMALLINT UNSIGNED,
    alt_text        VARCHAR(300),
    caption         TEXT,
    path_original   VARCHAR(500) NOT NULL,
    path_medium     VARCHAR(500),
    path_thumb      VARCHAR(500),
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_mime (mime_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings
CREATE TABLE IF NOT EXISTS settings (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key_name    VARCHAR(100) NOT NULL UNIQUE,
    value       LONGTEXT,
    type        ENUM('string','text','boolean','json','html') NOT NULL DEFAULT 'string',
    group_name  VARCHAR(50) NOT NULL DEFAULT 'general',
    label       VARCHAR(150),
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_group (group_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Redirects
CREATE TABLE IF NOT EXISTS redirects (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    from_path   VARCHAR(500) NOT NULL UNIQUE,
    to_url      VARCHAR(500) NOT NULL,
    type        SMALLINT NOT NULL DEFAULT 301,
    hit_count   INT UNSIGNED NOT NULL DEFAULT 0,
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Post Views (lightweight analytics)
CREATE TABLE IF NOT EXISTS post_views (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id     INT UNSIGNED NOT NULL,
    visitor_hash VARCHAR(64),
    viewed_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    INDEX idx_post_date (post_id, viewed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
