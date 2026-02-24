USE news_cms;

-- Default admin user (password: admin123)
INSERT INTO users (name, email, password_hash, role, slug, bio) VALUES
('Admin', 'admin@example.com', '$2y$12$HRfxHX7cZEnmSMW.X9mOMOjtqccl3oKpBV7aL3d3gmmhdTWIOfTLq', 'super_admin', 'admin', 'Site administrator');

-- Default settings
INSERT INTO settings (key_name, value, type, group_name, label) VALUES
('site_name', 'NewsCMS', 'string', 'general', 'Site Name'),
('site_tagline', 'Your source for the latest news', 'string', 'general', 'Tagline'),
('site_logo', '', 'string', 'general', 'Logo URL'),
('site_favicon', '', 'string', 'general', 'Favicon URL'),
('posts_per_page', '12', 'string', 'general', 'Posts Per Page'),
('comments_enabled', '1', 'boolean', 'general', 'Enable Comments'),
('comments_moderation', '1', 'boolean', 'general', 'Moderate Comments'),
('social_twitter', '', 'string', 'social', 'Twitter URL'),
('social_facebook', '', 'string', 'social', 'Facebook URL'),
('social_linkedin', '', 'string', 'social', 'LinkedIn URL'),
('social_github', '', 'string', 'social', 'GitHub URL'),
('analytics_code', '', 'html', 'advanced', 'Analytics Script'),
('custom_header_code', '', 'html', 'advanced', 'Custom Header Code'),
('custom_footer_code', '', 'html', 'advanced', 'Custom Footer Code'),
('robots_txt', 'User-agent: *\nDisallow: /admin/\nDisallow: /storage/\nSitemap: {APP_URL}/sitemap.xml', 'text', 'seo', 'Robots.txt Content');

-- Default categories
INSERT INTO categories (name, slug, description) VALUES
('General', 'general', 'General articles and news'),
('Technology', 'technology', 'Technology news and insights'),
('Business', 'business', 'Business and finance articles'),
('Web Development', 'web-development', 'Articles about web development technologies and practices'),
('PHP Projects', 'php-projects', 'PHP project tutorials and ideas'),
('Software', 'software', 'Software tools, platforms and reviews');

-- Tags
INSERT INTO tags (name, slug) VALUES
('PHP', 'php'),
('MySQL', 'mysql'),
('MVC', 'mvc'),
('JavaScript', 'javascript'),
('Web Design', 'web-design'),
('Webflow', 'webflow'),
('XML', 'xml'),
('JSON', 'json'),
('Salesforce', 'salesforce'),
('CRM', 'crm'),
('Beginners', 'beginners'),
('Tutorial', 'tutorial'),
('Database', 'database'),
('WordPress', 'wordpress'),
('GitHub', 'github'),
('Web Hosting', 'web-hosting');

-- Sample blog posts (content inspired by codingcush.com)
-- See database/migrations/001_add_test_blog_posts.sql for the full content inserts
