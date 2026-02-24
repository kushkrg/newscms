-- Add downloadable file support to posts
ALTER TABLE posts ADD COLUMN download_file VARCHAR(500) DEFAULT NULL AFTER canonical_url;
ALTER TABLE posts ADD COLUMN download_file_name VARCHAR(255) DEFAULT NULL AFTER download_file;
