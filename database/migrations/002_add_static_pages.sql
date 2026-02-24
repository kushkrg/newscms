-- Migration: Add static pages (About Us, Contact Us, Terms & Conditions, Privacy Policy, Disclaimer)
-- Run: mysql -u root news_cms < database/migrations/002_add_static_pages.sql

-- Clear existing pages to avoid duplicates
DELETE FROM pages WHERE slug IN ('about', 'contact', 'terms-and-conditions', 'privacy-policy', 'disclaimer');

-- About Us
INSERT INTO pages (user_id, title, slug, content, template, status, meta_title, meta_description, sort_order, created_at, updated_at)
VALUES (
    1,
    'About Us',
    'about',
    '<div class="page-about">
<p>Welcome to <strong>NewsCMS</strong> — your trusted source for the latest news, tutorials, and in-depth articles on technology, web development, and software engineering.</p>

<h2>Our Mission</h2>
<p>We believe knowledge should be accessible to everyone. Our mission is to deliver high-quality, well-researched content that helps developers, tech enthusiasts, and professionals stay ahead in the ever-evolving world of technology.</p>

<h2>What We Cover</h2>
<p>From beginner-friendly tutorials to advanced development guides, we cover a wide range of topics including:</p>
<ul>
    <li><strong>Web Development</strong> — HTML, CSS, JavaScript, PHP, and modern frameworks</li>
    <li><strong>Programming</strong> — Best practices, design patterns, and coding tips</li>
    <li><strong>Software Engineering</strong> — Architecture, DevOps, and project management</li>
    <li><strong>Technology News</strong> — Latest trends and industry updates</li>
    <li><strong>Tools &amp; Resources</strong> — Reviews and recommendations for developers</li>
</ul>

<h2>Our Team</h2>
<p>Our content is crafted by experienced developers and writers who are passionate about technology and education. We take pride in the accuracy and depth of every article we publish.</p>

<h2>Get In Touch</h2>
<p>Have a question, suggestion, or want to contribute? We''d love to hear from you. Visit our <a href="/contact">Contact page</a> to get in touch with our team.</p>

<p>Thank you for reading. We''re glad you''re here.</p>
</div>',
    'default',
    'published',
    'About Us - NewsCMS',
    'Learn about NewsCMS, our mission, what we cover, and the team behind our technology articles and tutorials.',
    1,
    NOW(),
    NOW()
);

-- Contact Us
INSERT INTO pages (user_id, title, slug, content, template, status, meta_title, meta_description, sort_order, created_at, updated_at)
VALUES (
    1,
    'Contact Us',
    'contact',
    '<div class="page-contact">
<p>We''d love to hear from you! Whether you have a question, feedback, partnership inquiry, or just want to say hello — feel free to reach out to us.</p>

<h2>Get In Touch</h2>
<p>The best way to reach us is via email. We typically respond within 24-48 business hours.</p>

<div class="contact-info">
    <p><strong>Email:</strong> <a href="mailto:contact@newscms.com">contact@newscms.com</a></p>
    <p><strong>General Inquiries:</strong> <a href="mailto:info@newscms.com">info@newscms.com</a></p>
</div>

<h2>Advertising &amp; Partnerships</h2>
<p>Interested in advertising on our platform or exploring partnership opportunities? Reach out to us at <a href="mailto:partners@newscms.com">partners@newscms.com</a> and we''ll get back to you promptly.</p>

<h2>Content Submissions</h2>
<p>Are you a writer or developer who would like to contribute an article? We welcome guest posts on topics related to web development, programming, and technology. Send your pitch to <a href="mailto:editorial@newscms.com">editorial@newscms.com</a>.</p>

<h2>Report an Issue</h2>
<p>Found a bug, broken link, or inaccuracy in one of our articles? Please let us know so we can fix it. Email <a href="mailto:support@newscms.com">support@newscms.com</a> with details.</p>

<h2>Follow Us</h2>
<p>Stay connected with us on social media for the latest updates, articles, and announcements.</p>
</div>',
    'default',
    'published',
    'Contact Us - NewsCMS',
    'Get in touch with NewsCMS. Reach out for questions, feedback, partnerships, or content submissions.',
    2,
    NOW(),
    NOW()
);

-- Terms and Conditions
INSERT INTO pages (user_id, title, slug, content, template, status, meta_title, meta_description, sort_order, created_at, updated_at)
VALUES (
    1,
    'Terms and Conditions',
    'terms-and-conditions',
    '<div class="page-legal">
<p><em>Last updated: January 2025</em></p>

<p>Welcome to NewsCMS. By accessing and using this website, you agree to comply with and be bound by the following terms and conditions. Please read them carefully before using our services.</p>

<h2>1. Acceptance of Terms</h2>
<p>By accessing this website, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you must not use this website.</p>

<h2>2. Intellectual Property Rights</h2>
<p>Unless otherwise stated, NewsCMS and/or its licensors own the intellectual property rights for all material on this website. All intellectual property rights are reserved. You may access content from NewsCMS for your personal use, subject to restrictions set in these terms.</p>
<p>You must not:</p>
<ul>
    <li>Republish material from this website without proper attribution</li>
    <li>Sell, rent, or sub-license material from this website</li>
    <li>Reproduce, duplicate, or copy material for commercial purposes</li>
    <li>Redistribute content from this website without written consent</li>
</ul>

<h2>3. User Comments &amp; Contributions</h2>
<p>Certain parts of this website offer users the opportunity to post comments and exchange opinions. NewsCMS does not screen or moderate comments before they appear on the website. Comments reflect the views of the person who posts them, not those of NewsCMS.</p>
<p>To the extent permitted by applicable laws, NewsCMS shall not be liable for the comments or for any liability, damages, or expenses caused as a result of any use or posting of comments on this website.</p>

<h2>4. Hyperlinking to Our Content</h2>
<p>Organizations may link to our website without prior written approval, provided the link is not deceptive, does not falsely imply sponsorship or endorsement, and fits within the context of the linking party''s site.</p>

<h2>5. Content Liability</h2>
<p>We shall not be held responsible for any content that appears on your website. You agree to protect and defend us against all claims arising from content on your website. No links should appear on any website that may be interpreted as libelous, obscene, or criminal.</p>

<h2>6. Disclaimer</h2>
<p>To the maximum extent permitted by applicable law, we exclude all representations, warranties, and conditions relating to our website and the use of this website. Nothing in this disclaimer will limit or exclude liability for death or personal injury, fraud or fraudulent misrepresentation, or any liabilities that may not be excluded under applicable law.</p>

<h2>7. Changes to Terms</h2>
<p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on this website. Your continued use of the website constitutes acceptance of the revised terms.</p>

<h2>8. Governing Law</h2>
<p>These terms and conditions are governed by and construed in accordance with applicable laws, and you irrevocably submit to the exclusive jurisdiction of the courts in that location.</p>
</div>',
    'default',
    'published',
    'Terms and Conditions - NewsCMS',
    'Read the terms and conditions for using NewsCMS, including intellectual property rights, user contributions, and content liability.',
    3,
    NOW(),
    NOW()
);

-- Privacy Policy
INSERT INTO pages (user_id, title, slug, content, template, status, meta_title, meta_description, sort_order, created_at, updated_at)
VALUES (
    1,
    'Privacy Policy',
    'privacy-policy',
    '<div class="page-legal">
<p><em>Last updated: January 2025</em></p>

<p>At NewsCMS, we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website.</p>

<h2>1. Information We Collect</h2>
<p>We may collect information about you in a variety of ways, including:</p>

<h3>Personal Data</h3>
<p>When you voluntarily provide it, such as when you leave a comment, subscribe to our newsletter, or contact us, we may collect:</p>
<ul>
    <li>Name</li>
    <li>Email address</li>
    <li>Website URL (optional)</li>
    <li>Any other information you choose to provide</li>
</ul>

<h3>Automatically Collected Data</h3>
<p>When you access our website, we may automatically collect certain information, including:</p>
<ul>
    <li>IP address</li>
    <li>Browser type and version</li>
    <li>Operating system</li>
    <li>Referring URLs</li>
    <li>Pages viewed and time spent on pages</li>
    <li>Date and time of visits</li>
</ul>

<h2>2. How We Use Your Information</h2>
<p>We may use the information we collect in the following ways:</p>
<ul>
    <li>To operate and maintain our website</li>
    <li>To improve user experience and website functionality</li>
    <li>To send newsletters and updates (with your consent)</li>
    <li>To respond to your comments, questions, and requests</li>
    <li>To monitor and analyze usage trends</li>
    <li>To protect against unauthorized access and abuse</li>
</ul>

<h2>3. Cookies</h2>
<p>Our website may use cookies — small text files stored on your device — to enhance your browsing experience. You can control cookie settings through your browser preferences. Disabling cookies may limit some features of the website.</p>

<h2>4. Third-Party Services</h2>
<p>We may use third-party services such as analytics providers and advertising networks. These services may collect information about your browsing activity across different websites. We do not control these third parties and recommend reviewing their privacy policies.</p>

<h2>5. Data Security</h2>
<p>We implement reasonable security measures to protect your personal information. However, no method of transmission over the Internet or electronic storage is 100% secure, and we cannot guarantee absolute security.</p>

<h2>6. Your Rights</h2>
<p>Depending on your location, you may have the right to:</p>
<ul>
    <li>Access the personal data we hold about you</li>
    <li>Request correction or deletion of your data</li>
    <li>Object to or restrict our processing of your data</li>
    <li>Withdraw consent at any time</li>
</ul>
<p>To exercise any of these rights, please contact us at <a href="mailto:privacy@newscms.com">privacy@newscms.com</a>.</p>

<h2>7. Children''s Privacy</h2>
<p>Our website is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13.</p>

<h2>8. Changes to This Policy</h2>
<p>We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated revision date. We encourage you to review this policy periodically.</p>

<h2>9. Contact Us</h2>
<p>If you have questions or concerns about this Privacy Policy, please contact us at <a href="mailto:privacy@newscms.com">privacy@newscms.com</a>.</p>
</div>',
    'default',
    'published',
    'Privacy Policy - NewsCMS',
    'Read the NewsCMS privacy policy to understand how we collect, use, and protect your personal information.',
    4,
    NOW(),
    NOW()
);

-- Disclaimer
INSERT INTO pages (user_id, title, slug, content, template, status, meta_title, meta_description, sort_order, created_at, updated_at)
VALUES (
    1,
    'Disclaimer',
    'disclaimer',
    '<div class="page-legal">
<p><em>Last updated: January 2025</em></p>

<p>The information provided on NewsCMS is for general informational and educational purposes only. While we strive to keep the information up-to-date and accurate, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability, or availability of the website or the information, products, services, or related graphics contained on the website.</p>

<h2>1. No Professional Advice</h2>
<p>The content on this website is not intended to be a substitute for professional advice. The articles, tutorials, and guides published here are for educational purposes and reflect the views and experiences of their authors. Always seek the advice of qualified professionals regarding specific questions or concerns.</p>

<h2>2. Code &amp; Tutorial Disclaimer</h2>
<p>Code examples, tutorials, and technical guides are provided "as is" without warranty of any kind. While we test our code examples, we cannot guarantee they will work in every environment or that they are free from errors. You are responsible for testing and validating any code before using it in a production environment.</p>

<h2>3. External Links</h2>
<p>This website may contain links to external websites that are not provided or maintained by NewsCMS. We do not guarantee the accuracy, relevance, timeliness, or completeness of any information on these external websites. The inclusion of any link does not necessarily imply a recommendation or endorsement of the views expressed within them.</p>

<h2>4. Limitation of Liability</h2>
<p>In no event shall NewsCMS be liable for any loss or damage including, without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</p>

<h2>5. Affiliate Links &amp; Advertising</h2>
<p>Some articles on this website may contain affiliate links. This means we may earn a small commission if you make a purchase through those links, at no additional cost to you. We only recommend products and services that we believe provide value to our readers. Affiliate relationships do not influence our editorial content.</p>

<h2>6. Fair Use</h2>
<p>This website may contain copyrighted material, the use of which has not always been specifically authorized by the copyright owner. We make such material available for commentary, criticism, education, and research purposes. We believe this constitutes "fair use" of any such copyrighted material as provided for in applicable copyright law.</p>

<h2>7. Changes to This Disclaimer</h2>
<p>We reserve the right to modify or replace this disclaimer at any time. Changes will be effective immediately upon posting. It is your responsibility to check this page periodically for updates.</p>

<h2>8. Contact</h2>
<p>If you have any questions about this disclaimer, please contact us at <a href="mailto:contact@newscms.com">contact@newscms.com</a>.</p>
</div>',
    'default',
    'published',
    'Disclaimer - NewsCMS',
    'Read the NewsCMS disclaimer regarding the accuracy of information, code examples, external links, and limitation of liability.',
    5,
    NOW(),
    NOW()
);
