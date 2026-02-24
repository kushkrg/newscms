USE news_cms;

-- Additional categories
INSERT INTO categories (name, slug, description) VALUES
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

-- Blog Posts (inspired by codingcush.com)

-- Post 1: Understanding the MVC Concept in PHP
INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, is_featured, reading_time_mins, view_count, published_at, schema_type) VALUES
(1, 2, 'Understanding the MVC Concept in PHP: A Comprehensive Guide',
'understanding-the-mvc-concept-in-php-a-comprehensive-guide',
'Discover the MVC (Model-View-Controller) concept in PHP with our comprehensive guide. Gain a deep understanding of this powerful architectural pattern and learn how to implement it in your PHP applications.',
'<h2>Introduction</h2>
<p>In the world of web development, maintaining a structured and organized codebase is crucial for building scalable and maintainable applications. The Model-View-Controller (MVC) concept provides a powerful architectural pattern that helps achieve precisely that. In this comprehensive guide, we will dive deep into the MVC concept in PHP, exploring its key components and how it can benefit your PHP development projects.</p>

<h2>Section 1: What is the MVC Concept?</h2>

<h3>1.1 Introducing the MVC Pattern</h3>
<p>The Model-View-Controller (MVC) pattern is a software architectural pattern that separates an application into three interconnected components:</p>
<ul>
<li><strong>Model</strong> — Handles data retrieval, storage, and business logic</li>
<li><strong>View</strong> — Displays data to users through HTML templates</li>
<li><strong>Controller</strong> — Handles user input and orchestrates actions between Model and View</li>
</ul>
<p>These three components work together to separate concerns and enhance code modularity, making your application easier to maintain and scale.</p>

<h3>1.2 Advantages of MVC</h3>
<ul>
<li>Improved code organization and maintainability</li>
<li>Separation of business logic from presentation logic</li>
<li>Reusability of code components</li>
<li>Facilitation of collaboration among developers</li>
<li>Easier testing and debugging</li>
</ul>

<h2>Section 2: Understanding Each Component</h2>

<h3>2.1 The Model</h3>
<p>The Model is responsible for handling data retrieval, storage, and manipulation. It implements business logic within the Model layer and communicates with the database. For example, a <code>Post</code> model might handle fetching blog posts, creating new posts, and validating post data.</p>

<h3>2.2 The View</h3>
<p>The View component displays data to users through HTML templates. It keeps the presentation logic separate from the business logic and utilizes PHP''s templating capabilities for dynamic content rendering. Views should never directly interact with the database.</p>

<h3>2.3 The Controller</h3>
<p>The Controller handles user input and orchestrates actions. It updates the Model and selects the appropriate View to render. Controllers act as the glue between Models and Views, processing requests and returning responses.</p>

<h2>Section 3: Implementing MVC in PHP</h2>

<h3>3.1 Choosing a PHP Framework</h3>
<p>Several popular PHP frameworks support MVC architecture, including:</p>
<ul>
<li><strong>Laravel</strong> — The most popular PHP framework with elegant syntax</li>
<li><strong>Symfony</strong> — A set of reusable PHP components</li>
<li><strong>CodeIgniter</strong> — A lightweight framework with a small footprint</li>
</ul>

<h3>3.2 Setting Up the Project Structure</h3>
<p>A typical MVC project structure looks like this:</p>
<pre><code>app/
├── Controllers/
├── Models/
└── Views/
config/
public/
routes/</code></pre>

<h3>3.3 Implementing Models, Views, and Controllers</h3>
<p>Start by creating your Models to handle data manipulation, then design your Views for presenting data, and finally develop Controllers to handle user interactions and control flow.</p>

<h2>Section 4: Best Practices and Tips</h2>

<h3>4.1 Ensuring Proper Separation of Concerns</h3>
<p>Keep distinct responsibilities in each MVC component. Avoid tight coupling and maintain code modularity. Never put database queries in your Views or HTML in your Models.</p>

<h3>4.2 Testing and Debugging</h3>
<p>Write unit tests for Models, Views, and Controllers separately. Use debugging tools and proper error handling to catch issues early in development.</p>

<h2>Conclusion</h2>
<p>By understanding the MVC concept in PHP, you unlock a powerful tool for building well-organized, scalable, and maintainable web applications. With a clear separation of concerns and modular code structure, you can enhance productivity, collaborate effectively, and adapt to evolving project requirements. Embrace the MVC pattern in your PHP development journey, and witness the positive impact it brings to your projects.</p>

<p>Remember, practice and hands-on experience are key to mastering the MVC concept in PHP. So, grab your favorite PHP framework, start coding, and explore the endless possibilities offered by this architectural pattern.</p>',
'published', 1, 8, 245, '2025-12-15 10:00:00', 'BlogPosting');

-- Post 2: Difference Between XML and JSON
INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, is_featured, reading_time_mins, view_count, published_at, schema_type) VALUES
(1, 2, 'Difference Between XML and JSON',
'difference-between-xml-and-json',
'Learn about the key dissimilarities between XML and JSON formats. Discover how XML and JSON differ in their structure, syntax, and usage, and gain insights into when to choose one over the other.',
'<h2>Introduction</h2>
<p>XML (Extensible Markup Language) and JSON (JavaScript Object Notation) are both popular data interchange formats used for storing and transmitting structured data. They are widely used in various applications, including web services, APIs, configuration files, and data storage.</p>

<h2>XML</h2>
<p>XML is a markup language that defines a set of rules for encoding documents in a format that is both human-readable and machine-readable. It uses tags to define elements and attributes to provide additional information about those elements. XML documents have a hierarchical structure and can represent complex data structures.</p>

<p>Here''s an example of an XML document:</p>
<pre><code>&lt;person&gt;
  &lt;name&gt;John Doe&lt;/name&gt;
  &lt;age&gt;30&lt;/age&gt;
  &lt;email&gt;john.doe@example.com&lt;/email&gt;
&lt;/person&gt;</code></pre>

<p>In this example, <code>&lt;person&gt;</code> is the root element, and it contains nested elements like <code>&lt;name&gt;</code>, <code>&lt;age&gt;</code>, and <code>&lt;email&gt;</code>. The data is enclosed within the opening and closing tags.</p>

<p>XML allows you to define your own tags and structure the data as per your requirements. It provides flexibility and is well-suited for representing documents with rich content or complex data models. However, XML can be verbose and may require more bandwidth for transmission due to its inherent markup overhead.</p>

<h2>JSON</h2>
<p>JSON is a lightweight data interchange format inspired by JavaScript object syntax. It is designed to be easy for both humans and machines to read and write. JSON represents data as key-value pairs and supports various data types, including strings, numbers, booleans, arrays, and objects.</p>

<p>Here''s an example of JSON:</p>
<pre><code>{
  "person": {
    "name": "John Doe",
    "age": 30,
    "email": "john.doe@example.com"
  }
}</code></pre>

<p>In this example, the data is represented using curly braces <code>{}</code> to denote an object. The object has a key-value pair where <code>"person"</code> is the key, and its value is another object with keys like <code>"name"</code>, <code>"age"</code>, and <code>"email"</code>.</p>

<p>JSON is known for its simplicity and conciseness. It is widely used in web development and RESTful APIs due to its compatibility with JavaScript and its lightweight nature. JSON data is generally smaller in size compared to equivalent XML data, making it more efficient for data transmission over networks.</p>

<h2>Key Differences</h2>
<table>
<thead>
<tr><th>Feature</th><th>XML</th><th>JSON</th></tr>
</thead>
<tbody>
<tr><td>Syntax</td><td>Uses tags and attributes</td><td>Uses key-value pairs</td></tr>
<tr><td>Readability</td><td>More verbose</td><td>More concise</td></tr>
<tr><td>Data Types</td><td>Everything is a string</td><td>Supports strings, numbers, booleans, arrays</td></tr>
<tr><td>Parsing</td><td>Requires XML parser</td><td>Native JavaScript support</td></tr>
<tr><td>Size</td><td>Larger file size</td><td>Smaller file size</td></tr>
</tbody>
</table>

<h2>Conclusion</h2>
<p>In summary, XML is a markup language that focuses on document structure and flexibility, while JSON is a lightweight data format primarily used for data interchange due to its simplicity and compactness. The choice between XML and JSON depends on the specific use case, compatibility requirements, and personal preference.</p>',
'published', 0, 5, 189, '2025-12-18 14:30:00', 'BlogPosting');

-- Post 3: The Key Skills of a Webflow Developer
INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, is_featured, reading_time_mins, view_count, published_at, schema_type) VALUES
(1, 4, 'The Key Skills of a Webflow Developer',
'the-key-skills-of-a-webflow-developer',
'As a Webflow developer, you''ll need a combination of technical, design, and problem-solving skills to build functional and visually appealing websites using the Webflow platform.',
'<h2>How to Become a Webflow Developer?</h2>
<p>As a Webflow developer, you''ll need a combination of technical, design, and problem-solving skills to build functional and visually appealing websites using the Webflow platform. Here are the key skills required:</p>

<h3>Webflow Proficiency</h3>
<p>You must have a deep understanding of the Webflow platform, its features, and functionalities. Familiarity with the Webflow Designer, interactions, responsive design, CMS (Content Management System), and E-commerce capabilities is essential.</p>

<h3>HTML/CSS</h3>
<p>While Webflow abstracts a lot of the coding, having a solid understanding of HTML and CSS will help you customize and fine-tune designs beyond what the visual builder provides.</p>

<h3>Responsive Web Design</h3>
<p>Websites today must be responsive, adapting to various screen sizes and devices. You should be proficient in designing and implementing responsive layouts to ensure a consistent user experience across devices.</p>

<h3>Design Skills</h3>
<p>Webflow developers often work closely with designers, so having a good eye for design, understanding user experience (UX), typography, color theory, and layout aesthetics is a valuable skill.</p>

<h3>JavaScript</h3>
<p>While Webflow provides a visual way to add interactions and animations, knowing JavaScript can help you extend the platform''s capabilities by adding custom interactions or integrating third-party services through custom code.</p>

<h3>Version Control (Git)</h3>
<p>Knowledge of version control systems like Git is important for collaborating with teams and managing code changes effectively.</p>

<h3>Web Performance Optimization</h3>
<p>Understanding techniques to optimize website performance, such as image optimization, asset minification, and lazy loading, will help you create faster-loading websites.</p>

<h3>Troubleshooting & Problem-solving</h3>
<p>Webflow developers encounter various challenges during development. The ability to troubleshoot issues and find solutions quickly is essential.</p>

<h3>Communication Skills</h3>
<p>Working as part of a team, you''ll need to effectively communicate with designers, clients, and other developers. Clear communication ensures everyone is on the same page and minimizes misunderstandings.</p>

<h3>Testing & Debugging</h3>
<p>Knowing how to test and debug websites across different browsers and devices is crucial to ensure compatibility and identify and fix any issues.</p>

<h3>SEO Basics</h3>
<p>Understanding Search Engine Optimization (SEO) principles will help you build websites that are search engine-friendly.</p>

<h3>Collaboration Tools</h3>
<p>Familiarity with collaboration tools like project management platforms, design tools (e.g., Adobe Creative Suite), and communication tools (e.g., Slack) will aid in working efficiently with teams.</p>

<p>Remember that technology and tools may evolve, so staying updated with the latest trends and advancements in Webflow and web development, in general, is essential for a successful career as a Webflow developer. Continuously learning and improving your skills will help you stay competitive in the field.</p>',
'published', 1, 6, 312, '2026-01-05 09:00:00', 'BlogPosting');

-- Post 4: 10 Exciting PHP Project Ideas for Beginners
INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, is_featured, reading_time_mins, view_count, published_at, schema_type) VALUES
(1, 5, '10 Exciting PHP Project Ideas for Beginners',
'10-exciting-php-project-ideas-for-beginners',
'10 exciting PHP project ideas tailored for beginners. Explore hands-on projects that will help you enhance your PHP programming skills, build practical applications, and gain valuable experience.',
'<p>As a beginner in PHP programming, it''s important to practice your skills and apply what you''ve learned in real-world scenarios. Engaging in projects not only reinforces your knowledge but also helps you develop problem-solving abilities and gain hands-on experience. In this blog, we present 10 exciting PHP project ideas tailored specifically for beginners.</p>

<h2>10 Cool PHP Project Ideas</h2>

<h3>1. Simple Contact Form</h3>
<p>Create a basic contact form using PHP that collects user input and sends an email. Learn about form validation, handling user input securely, and integrating PHP with HTML.</p>

<h3>2. User Registration and Login System</h3>
<p>Build a user registration and login system with PHP and MySQL. Develop features like user registration, login authentication, password encryption, and session management.</p>

<h3>3. Blogging Platform</h3>
<p>Develop a simple blogging platform using PHP and a database. Implement functionalities like user registration, creating blog posts, displaying posts, and adding comments.</p>

<h3>4. Task Manager</h3>
<p>Create a task manager application that allows users to create, update, and delete tasks. Practice CRUD operations (Create, Read, Update, Delete) and learn about database interactions.</p>

<h3>5. E-commerce Store</h3>
<p>Build a basic e-commerce store with PHP and MySQL. Implement features such as product listing, shopping cart functionality, and order management.</p>

<h3>6. Image Gallery</h3>
<p>Develop an image gallery using PHP where users can upload and view images. Explore image manipulation techniques and learn about file handling in PHP.</p>

<h3>7. Weather Forecast Application</h3>
<p>Create a weather forecast application that retrieves weather data from an API using PHP. Display the weather information based on user input, such as location or zip code.</p>

<h3>8. URL Shortener</h3>
<p>Build a URL shortening service using PHP. Learn about handling redirection, generating unique short URLs, and storing data in a database.</p>

<h3>9. Quiz Application</h3>
<p>Develop a quiz application where users can take quizzes and get their scores. Implement features like multiple-choice questions, scoring, and result display.</p>

<h3>10. Content Management System (CMS)</h3>
<p>Create a simple CMS using PHP and MySQL, allowing users to manage website content. Develop functionalities like creating pages, editing content, and user role management.</p>

<h2>Conclusion</h2>
<p>Embarking on PHP projects as a beginner is an excellent way to solidify your knowledge and gain practical experience. These 10 exciting project ideas provide a range of challenges, enabling you to practice fundamental PHP concepts while building functional applications. Remember to be creative, document your progress, and don''t shy away from seeking help from online resources and developer communities. Embrace these projects as opportunities to grow and excel on your journey to becoming a skilled PHP developer. Happy coding!</p>',
'published', 1, 7, 478, '2026-01-10 11:00:00', 'BlogPosting');

-- Post 5: What is Salesforce?
INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, is_featured, reading_time_mins, view_count, published_at, schema_type) VALUES
(1, 3, 'What is Salesforce? A Complete Overview of the World''s Leading CRM',
'what-is-salesforce-complete-overview',
'Salesforce CRM is a SaaS product and the world-leading CRM tool with innovative features to manage your presales, sales, aftersales, and marketing activities.',
'<h2>What is Salesforce?</h2>
<p>Salesforce CRM is a SaaS product of Force.com. It is the world-leading CRM tool with the most innovative features to manage your presales, sales, aftersales, and marketing activities.</p>

<p>This was about the standard Salesforce CRM features which are easy to use, most innovative, and highly automated system which makes your crucial data to be managed in the most effective way. This data management on Salesforce is called practices in lead management, account management, contracts management, your sales pipeline, and forecasting in the most effective tracking, and customer support management.</p>

<p>Reporting and analytics was never so easy before since Salesforce started high-end reporting and dynamic dashboards — you can view your data in one place.</p>

<p>Now its open-end force.com platform gives you the freedom to develop, customize and configure your own system for any instance, not just the standard CRM functionality. You can develop your own applications like warehouse management system, recruitment and payroll management, project management, surveys, etc. — all this too without knowing any coding (programming skills). 80% customization and 20% coding make Salesforce the most efficient tool for any organization.</p>

<h2>Salesforce Provides</h2>
<ul>
<li><strong>Software as a Service (SaaS)</strong> — Ready-to-use applications accessible via the web</li>
<li><strong>Platform as a Service (PaaS)</strong> — Build custom applications on the Force.com platform</li>
<li><strong>Infrastructure as a Service (IaaS)</strong> — Scalable cloud infrastructure</li>
</ul>

<h2>Features and Benefits</h2>
<p>There are a lot of features and benefits that come with a Salesforce subscription:</p>
<ul>
<li><strong>Flexibility and Customization</strong> — Tailor the platform to your specific business needs</li>
<li><strong>Customer Information</strong> — Centralized customer data for better insights</li>
<li><strong>Time Management</strong> — Streamlined workflows and automated processes</li>
<li><strong>Cloud of Trust</strong> — Enterprise-grade security and reliability</li>
<li><strong>Team Collaboration</strong> — Built-in tools for team communication</li>
<li><strong>Account Arranging</strong> — Organized account and contact management</li>
</ul>

<h2>Why Choose Salesforce?</h2>
<p>Salesforce has revolutionized the way businesses manage customer relationships. With its cloud-based platform, companies of all sizes can access powerful CRM tools without the need for expensive on-premise infrastructure. The platform''s ecosystem includes thousands of third-party applications available through the AppExchange marketplace, making it infinitely extensible.</p>

<p>Whether you''re a small startup or a large enterprise, Salesforce offers scalable solutions that grow with your business. Its AI-powered features, including Einstein Analytics, provide predictive insights that help sales teams close deals faster and marketing teams target the right audiences.</p>',
'published', 0, 5, 156, '2026-01-15 08:30:00', 'BlogPosting');

-- Post 6: How to Connect PHP with MySQL Database
INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, is_featured, reading_time_mins, view_count, published_at, schema_type) VALUES
(1, 5, 'How to Connect PHP with MySQL Database',
'how-to-connect-php-with-mysql-database',
'Learn how to connect PHP with MySQL database in your project. A step-by-step guide covering mysqli_connect, PDO, and best practices for database connections.',
'<h2>Introduction</h2>
<p>Connecting PHP to a MySQL database is one of the fundamental skills every PHP developer needs to master. In this tutorial, we''ll walk through the process step by step.</p>

<h2>Code for MySQL Connection with PHP</h2>
<p>Here''s the basic code for connecting PHP to MySQL using mysqli:</p>

<pre><code>&lt;?php
define(''DB_SERVER'', ''localhost'');
define(''DB_USER'', ''root'');
define(''DB_PASS'', '''');
define(''DB_NAME'', ''dbname'');

$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?&gt;</code></pre>

<h3>Step 1: Define the Hostname</h3>
<p>In the code below, we define our hostname. For local development, you can use <code>localhost</code>. If you want to access another host, put that hostname or server IP here:</p>
<pre><code>define(''DB_SERVER'', ''localhost'');</code></pre>

<h3>Step 2: Define the Database Username</h3>
<p>This code sets the database username. For local development, we typically use <code>root</code>. But you can create any database username you want:</p>
<pre><code>define(''DB_USER'', ''root'');</code></pre>

<h3>Step 3: Define the Database Password</h3>
<p>Here we define the database password. For local development, you should leave it blank:</p>
<pre><code>define(''DB_PASS'', '''');</code></pre>

<h3>Step 4: Define the Database Name</h3>
<p>Here we define our database name:</p>
<pre><code>define(''DB_NAME'', ''dbname'');</code></pre>

<h3>Step 5: Create the Connection</h3>
<p>The <code>mysqli_connect()</code> function opens a new connection to the MySQL server:</p>
<pre><code>$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);</code></pre>

<h2>Using PDO (Recommended)</h2>
<p>For modern PHP applications, PDO (PHP Data Objects) is the recommended approach:</p>
<pre><code>&lt;?php
try {
    $pdo = new PDO(
        ''mysql:host=localhost;dbname=mydb;charset=utf8mb4'',
        ''root'',
        '''',
        [
            PDO::ATTR_ERRMODE =&gt; PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE =&gt; PDO::FETCH_ASSOC,
        ]
    );
    echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e-&gt;getMessage();
}
?&gt;</code></pre>

<h2>Best Practices</h2>
<ul>
<li>Always use prepared statements to prevent SQL injection</li>
<li>Store database credentials in environment variables, not in code</li>
<li>Use PDO over mysqli for better portability</li>
<li>Always handle connection errors gracefully</li>
<li>Close connections when they''re no longer needed</li>
</ul>',
'published', 0, 4, 267, '2026-01-20 15:00:00', 'BlogPosting');

-- Post 7: Top 6 Free Web Hosting Providers
INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, is_featured, reading_time_mins, view_count, published_at, schema_type) VALUES
(1, 6, 'Top 6 Free Web Hosting Providers for Beginners',
'top-6-free-web-hosting-providers',
'Free web hosting is a great option for those who are just starting out with their website. Discover the best free hosting providers and what they offer.',
'<h2>Introduction</h2>
<p>Free web hosting is a great option for those who are just starting out with their website journey. Whether you''re a student learning web development, a hobbyist building a personal site, or an entrepreneur testing a business idea, free hosting can be the perfect starting point.</p>

<h2>1. GitHub Pages</h2>
<p>GitHub Pages is an excellent free hosting option for static websites. It integrates seamlessly with Git version control and supports custom domains. Perfect for portfolio sites, documentation, and project pages.</p>
<ul>
<li>Free SSL certificate</li>
<li>Custom domain support</li>
<li>Jekyll integration for static sites</li>
<li>1GB storage limit</li>
</ul>

<h2>2. Netlify</h2>
<p>Netlify offers a generous free tier with continuous deployment from Git. It''s ideal for modern web projects built with frameworks like React, Vue, or static site generators.</p>
<ul>
<li>100GB bandwidth per month</li>
<li>Continuous deployment</li>
<li>Serverless functions</li>
<li>Form handling</li>
</ul>

<h2>3. Vercel</h2>
<p>Vercel is the company behind Next.js and offers excellent free hosting for frontend projects. It provides automatic HTTPS, global CDN, and seamless Git integration.</p>
<ul>
<li>100GB bandwidth per month</li>
<li>Serverless functions</li>
<li>Edge network deployment</li>
<li>Preview deployments</li>
</ul>

<h2>4. InfinityFree</h2>
<p>InfinityFree provides free PHP and MySQL hosting with no ads. It''s one of the few free hosts that supports server-side scripting, making it suitable for PHP projects.</p>
<ul>
<li>Unlimited disk space</li>
<li>Unlimited bandwidth</li>
<li>PHP and MySQL support</li>
<li>Free subdomain</li>
</ul>

<h2>5. 000WebHost</h2>
<p>Owned by Hostinger, 000WebHost offers free web hosting with PHP, MySQL, and a website builder. It''s beginner-friendly with a control panel for easy management.</p>
<ul>
<li>300MB disk space</li>
<li>3GB bandwidth</li>
<li>PHP and MySQL support</li>
<li>Website builder included</li>
</ul>

<h2>6. Cloudflare Pages</h2>
<p>Cloudflare Pages offers fast, secure hosting for static sites and JAMstack applications. With unlimited bandwidth and automatic builds from Git, it''s a powerful free option.</p>
<ul>
<li>Unlimited bandwidth</li>
<li>500 builds per month</li>
<li>Global CDN</li>
<li>Preview deployments</li>
</ul>

<h2>Conclusion</h2>
<p>Each of these free hosting providers has its strengths and limitations. For static sites, GitHub Pages, Netlify, and Vercel are excellent choices. For PHP-based projects, InfinityFree and 000WebHost are your best bets. Choose the one that best fits your project requirements and start building!</p>',
'published', 0, 6, 534, '2026-01-25 12:00:00', 'BlogPosting');

-- Post 8: How Bad Guys Hack Into Websites Using SQL Injection
INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, is_featured, reading_time_mins, view_count, published_at, schema_type) VALUES
(1, 2, 'How Bad Guys Hack Into Websites Using SQL Injection',
'how-bad-guys-hack-into-websites-using-sql-injection',
'Learn about SQL injection attacks — one of the most common web security vulnerabilities. Understand how they work and how to protect your applications.',
'<h2>What is SQL Injection?</h2>
<p>SQL Injection (SQLi) is one of the most common and dangerous web application vulnerabilities. It occurs when an attacker is able to insert or "inject" malicious SQL code into queries that an application sends to its database. This can lead to unauthorized access to sensitive data, data modification, or even complete system compromise.</p>

<h2>How Does SQL Injection Work?</h2>
<p>Consider a simple login form that checks credentials against a database:</p>
<pre><code>$query = "SELECT * FROM users WHERE email = ''" . $email . "'' AND password = ''" . $password . "''";</code></pre>

<p>If an attacker enters the following as the email:</p>
<pre><code>admin@example.com'' OR ''1''=''1</code></pre>

<p>The resulting query becomes:</p>
<pre><code>SELECT * FROM users WHERE email = ''admin@example.com'' OR ''1''=''1'' AND password = ''''</code></pre>

<p>Since <code>''1''=''1''</code> is always true, this query returns all users, potentially granting the attacker access to the admin account.</p>

<h2>Types of SQL Injection</h2>

<h3>1. Classic SQL Injection</h3>
<p>The attacker can directly see the results of the injected query in the application''s response. This is the most straightforward type.</p>

<h3>2. Blind SQL Injection</h3>
<p>The attacker cannot see the query results directly but can infer information based on the application''s behavior (e.g., different error messages or response times).</p>

<h3>3. Second-Order SQL Injection</h3>
<p>The malicious input is stored in the database and executed later when it''s used in a different query.</p>

<h2>How to Prevent SQL Injection</h2>

<h3>Use Prepared Statements</h3>
<p>The most effective defense against SQL injection is using prepared statements with parameterized queries:</p>
<pre><code>$stmt = $pdo-&gt;prepare("SELECT * FROM users WHERE email = ? AND password = ?");
$stmt-&gt;execute([$email, $password]);</code></pre>

<h3>Input Validation</h3>
<p>Always validate and sanitize user input. Use whitelisting where possible — only accept input that matches expected patterns.</p>

<h3>Use an ORM</h3>
<p>Object-Relational Mapping (ORM) libraries like Eloquent (Laravel) or Doctrine (Symfony) abstract database interactions and use parameterized queries by default.</p>

<h3>Least Privilege Principle</h3>
<p>Configure your database user accounts with the minimum permissions necessary. Don''t use the root account for your application.</p>

<h3>Web Application Firewall (WAF)</h3>
<p>Deploy a WAF to detect and block common SQL injection patterns before they reach your application.</p>

<h2>Conclusion</h2>
<p>SQL injection remains one of the top web security threats, but it''s entirely preventable. By using prepared statements, validating input, and following security best practices, you can protect your applications from these attacks. Always assume that user input is malicious and code defensively.</p>',
'published', 1, 7, 892, '2026-02-01 10:00:00', 'BlogPosting');

-- Post 9: GitHub - What is GitHub, Why It's Important
INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, is_featured, reading_time_mins, view_count, published_at, schema_type) VALUES
(1, 2, 'GitHub: What is GitHub and Why It''s Important for Developers',
'github-what-is-github-why-its-important',
'GitHub is the world''s largest platform for version control and collaboration. Learn what GitHub is, how it works, and why every developer should use it.',
'<h2>What is GitHub?</h2>
<p>GitHub is a web-based platform built on top of Git, the distributed version control system. It provides a collaborative environment where developers can host, review, and manage code repositories. With over 100 million developers worldwide, GitHub is the largest source code hosting platform in the world.</p>

<h2>Key Features of GitHub</h2>

<h3>Repositories</h3>
<p>A repository (or "repo") is the fundamental unit on GitHub. It contains all of your project''s files, along with the revision history of each file. Repositories can be public (visible to everyone) or private (restricted access).</p>

<h3>Branching and Merging</h3>
<p>GitHub makes it easy to create branches for developing features or fixing bugs in isolation. Once your changes are ready, you can merge them back into the main branch through a pull request.</p>

<h3>Pull Requests</h3>
<p>Pull requests are the heart of collaboration on GitHub. They let you tell others about changes you''ve pushed to a branch in a repository. Team members can review the changes, discuss modifications, and merge the code.</p>

<h3>Issues and Project Boards</h3>
<p>GitHub Issues provide a way to track bugs, feature requests, and tasks. Combined with Project Boards, they offer a powerful project management solution right within your repository.</p>

<h3>GitHub Actions</h3>
<p>GitHub Actions is a CI/CD platform that allows you to automate your build, test, and deployment pipelines. You can create workflows that run on every push, pull request, or on a schedule.</p>

<h3>GitHub Pages</h3>
<p>GitHub Pages lets you host static websites directly from a GitHub repository. It''s free and supports custom domains, making it perfect for project documentation and personal portfolios.</p>

<h2>Why GitHub is Important</h2>
<ul>
<li><strong>Version Control</strong> — Track every change to your codebase</li>
<li><strong>Collaboration</strong> — Work with developers worldwide</li>
<li><strong>Open Source</strong> — Contribute to millions of open-source projects</li>
<li><strong>Portfolio</strong> — Showcase your work to potential employers</li>
<li><strong>Community</strong> — Learn from and connect with other developers</li>
<li><strong>Integration</strong> — Connect with thousands of tools and services</li>
</ul>

<h2>Getting Started</h2>
<p>To get started with GitHub, create a free account at github.com. Install Git on your local machine, configure your identity, and create your first repository. The GitHub documentation and community guides are excellent resources for beginners.</p>

<h2>Conclusion</h2>
<p>GitHub has transformed the way developers collaborate and share code. Whether you''re a solo developer or part of a large team, GitHub provides the tools you need to manage your projects effectively. Start using GitHub today and join the world''s largest community of developers.</p>',
'published', 0, 6, 423, '2026-02-10 09:00:00', 'BlogPosting');

-- Post 10: WordPress Action Hooks vs Filter Hooks
INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, is_featured, reading_time_mins, view_count, published_at, schema_type) VALUES
(1, 4, 'What is the Difference Between Action Hook and Filter Hook in WordPress?',
'difference-between-action-hook-and-filter-hook-in-wordpress',
'Action Hooks are for executing actions at specific points, while Filter Hooks are for modifying data. Learn the key differences in this comprehensive guide.',
'<h2>Introduction</h2>
<p>WordPress hooks are one of the most powerful features of the WordPress platform. They allow developers to "hook into" WordPress at specific points to run their own code. There are two types of hooks: Action Hooks and Filter Hooks.</p>

<h2>What are Action Hooks?</h2>
<p>Action Hooks allow you to execute custom functions at specific points during WordPress execution. They are triggered at specific moments — for example, when a post is published, when a page loads, or when a user logs in.</p>

<h3>How to Use Action Hooks</h3>
<pre><code>// Add a custom function to the wp_head action
add_action(''wp_head'', ''my_custom_function'');

function my_custom_function() {
    echo ''&lt;!-- Custom code added to head --&gt;'';
}</code></pre>

<h3>Common Action Hooks</h3>
<ul>
<li><code>init</code> — Fires after WordPress has finished loading</li>
<li><code>wp_head</code> — Fires in the head section of the theme</li>
<li><code>wp_footer</code> — Fires in the footer section</li>
<li><code>save_post</code> — Fires when a post is saved</li>
<li><code>wp_login</code> — Fires when a user logs in</li>
</ul>

<h2>What are Filter Hooks?</h2>
<p>Filter Hooks allow you to modify data before it is sent to the database or the browser. Unlike actions, filters are meant to receive a value, modify it, and return it.</p>

<h3>How to Use Filter Hooks</h3>
<pre><code>// Modify the post title
add_filter(''the_title'', ''my_custom_title'');

function my_custom_title($title) {
    return ''Prefix: '' . $title;
}</code></pre>

<h3>Common Filter Hooks</h3>
<ul>
<li><code>the_content</code> — Filters the post content</li>
<li><code>the_title</code> — Filters the post title</li>
<li><code>excerpt_length</code> — Filters the excerpt length</li>
<li><code>body_class</code> — Filters the body CSS classes</li>
<li><code>wp_mail</code> — Filters the email parameters</li>
</ul>

<h2>Key Differences</h2>
<table>
<thead>
<tr><th>Feature</th><th>Action Hook</th><th>Filter Hook</th></tr>
</thead>
<tbody>
<tr><td>Purpose</td><td>Execute code at specific points</td><td>Modify and return data</td></tr>
<tr><td>Return Value</td><td>Not required</td><td>Must return a value</td></tr>
<tr><td>Function</td><td><code>add_action()</code></td><td><code>add_filter()</code></td></tr>
<tr><td>Data Flow</td><td>Does not receive/return data</td><td>Receives, modifies, returns data</td></tr>
<tr><td>Use Case</td><td>Sending emails, logging, enqueuing scripts</td><td>Changing titles, content, excerpts</td></tr>
</tbody>
</table>

<h2>Conclusion</h2>
<p>Understanding the difference between Action Hooks and Filter Hooks is essential for WordPress development. Actions are for doing things at specific points, while filters are for changing things. Master both, and you''ll be able to customize WordPress in powerful ways without ever modifying core files.</p>',
'published', 0, 5, 367, '2026-02-15 14:00:00', 'BlogPosting');

-- Post-Tags associations
INSERT INTO post_tags (post_id, tag_id) VALUES
-- Post 1 (MVC in PHP): PHP, MVC, Tutorial
(1, 1), (1, 3), (1, 12),
-- Post 2 (XML vs JSON): XML, JSON, Tutorial
(2, 7), (2, 8), (2, 12),
-- Post 3 (Webflow Developer): Webflow, Web Design, JavaScript
(3, 6), (3, 5), (3, 4),
-- Post 4 (PHP Project Ideas): PHP, Beginners, MySQL, Tutorial
(4, 1), (4, 11), (4, 2), (4, 12),
-- Post 5 (Salesforce): Salesforce, CRM
(5, 9), (5, 10),
-- Post 6 (PHP MySQL Connection): PHP, MySQL, Database, Tutorial, Beginners
(6, 1), (6, 2), (6, 13), (6, 12), (6, 11),
-- Post 7 (Web Hosting): Web Hosting, Beginners
(7, 16), (7, 11),
-- Post 8 (SQL Injection): MySQL, Database, PHP
(8, 2), (8, 13), (8, 1),
-- Post 9 (GitHub): GitHub, Tutorial
(9, 15), (9, 12),
-- Post 10 (WordPress Hooks): WordPress, PHP, Tutorial
(10, 14), (10, 1), (10, 12);

-- Update category post counts
UPDATE categories SET post_count = (SELECT COUNT(*) FROM posts WHERE category_id = categories.id AND status = 'published');

-- Update tag post counts
UPDATE tags SET post_count = (SELECT COUNT(*) FROM post_tags WHERE tag_id = tags.id);
