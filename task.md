# News / Blog CMS — Core PHP
**Design:** Black & White, Modern, Typography-Focused
**Stack:** Core PHP (no frameworks), MySQL, Vanilla CSS/JS
**Priority:** SEO-First

---

## Core Features

### Frontend

| Feature | Details |
|---------|---------|
| Homepage | Hero with featured/pinned article, paginated article grid, category filter bar, trending sidebar, tag cloud, search bar |
| Single Post Page | Full article, author byline, reading time, TOC (auto from H2/H3), social share, related posts, prev/next nav, comments |
| Category Page | Title, description, article count, paginated grid |
| Tag Page | Tag name, article count, paginated grid |
| Author Page | Avatar, bio, social links, all articles (paginated) |
| Search Page | MySQL FULLTEXT search, result count, pagination, highlighted excerpts |
| Static Pages | About, Contact, Privacy Policy, Terms — managed via admin |
| Archive Pages | Monthly/yearly article listings |
| RSS Feed | RSS 2.0 global feed + per-category feeds |
| XML Sitemap | Sitemap index → post-sitemap.xml, category-sitemap.xml, page-sitemap.xml |
| 404 Page | Custom, logged to storage |

### Admin Panel

| Feature | Details |
|---------|---------|
| Dashboard | Stats cards (posts, comments, views, drafts), recent posts, pending comments, quick links |
| Posts CRUD | Rich editor (TinyMCE CDN), draft/publish/archive/schedule, featured toggle, featured image, SEO panel (meta title, meta desc, OG image, canonical), slug editor, reading time auto-calc, revision history (last 5) |
| Pages CRUD | Same editor as posts, template selector (default / full-width / sidebar) |
| Categories | CRUD, parent category, slug, meta title/description |
| Tags | CRUD, bulk delete unused |
| Media Library | Upload (JPG, PNG, WebP, GIF), GD resize to medium + thumb, file manager grid, delete, copy URL |
| Comments | List with status filters, approve/spam/trash, bulk actions, admin reply |
| Users | CRUD, roles (Super Admin / Editor / Author / Contributor), avatar, bio, social links |
| Settings | Site name, logo, posts-per-page, comments toggle, social links, analytics embed, robots.txt editor, SMTP, header/footer code injection |
| Redirects | Create 301/302 rules, auto-created when post slug changes, hit counter |

---

## SEO Features

- **SEO-Friendly Slugs** — auto-generated from title (lowercase, hyphens), unique-enforced in DB, editable, 301 redirect auto-created on slug change
- **Meta Tags** — `<title>`, `<meta name="description">`, `<link rel="canonical">`, `<meta name="robots">`
- **Open Graph** — `og:type`, `og:title`, `og:description`, `og:image`, `og:url`, `og:site_name`
- **Twitter Cards** — `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`
- **Article Meta** — `article:published_time`, `article:modified_time`, `article:author`, `article:section`, `article:tag`
- **JSON-LD Structured Data** — Article schema (per post), WebSite + SearchAction (homepage), BreadcrumbList (all inner pages)
- **Breadcrumbs** — Server-rendered HTML nav + matching JSON-LD
- **Pagination** — `<link rel="prev">` / `<link rel="next">` on all paginated pages
- **Canonical URLs** — Default = current URL, admin-overridable per post
- **XML Sitemap** — Sitemap index + child sitemaps with `<loc>`, `<lastmod>`, `<changefreq>`, `<priority>`
- **RSS 2.0** — Global and per-category feeds with `<guid>`, `<author>`, `<pubDate>`
- **robots.txt** — Editable via admin settings, served dynamically
- **Reading Time** — Calculated server-side, displayed on cards and article header

---

## Project Structure

```
/blog-news-project-php/
│
├── public/                         <- Web root
│   ├── index.php                   <- Front controller / router entry
│   ├── .htaccess                   <- URL rewriting (all → index.php)
│   ├── robots.txt
│   ├── favicon.ico
│   ├── assets/
│   │   ├── css/
│   │   │   ├── main.css            <- Frontend styles
│   │   │   ├── admin.css           <- Admin panel styles
│   │   │   └── print.css
│   │   └── js/
│   │       ├── main.js             <- Vanilla JS (nav, TOC, share)
│   │       └── admin.js            <- Admin JS (slug, media modal)
│   └── uploads/
│       ├── images/
│       │   ├── original/
│       │   ├── medium/             <- 800px wide (GD)
│       │   └── thumb/              <- 400px wide (GD)
│       └── avatars/
│
├── app/
│   ├── Core/
│   │   ├── Router.php              <- URL routing + dispatch
│   │   ├── Request.php             <- $_GET/$_POST/$_FILES wrapper
│   │   ├── Response.php            <- Redirects, headers
│   │   ├── View.php                <- Template renderer (extract + include)
│   │   ├── Database.php            <- PDO singleton
│   │   ├── Session.php
│   │   ├── Auth.php                <- Login, logout, role checks
│   │   ├── Csrf.php                <- Token generation + validation
│   │   ├── Validator.php           <- Input validation rules
│   │   ├── Paginator.php
│   │   ├── Uploader.php            <- File upload + GD resize
│   │   ├── SEO.php                 <- Meta tags, JSON-LD, breadcrumbs
│   │   ├── Feed.php                <- RSS 2.0 generator
│   │   ├── Sitemap.php             <- XML sitemap generator
│   │   ├── Mailer.php              <- PHP mail() / SMTP wrapper
│   │   ├── Sanitizer.php           <- XSS helpers, slug generator
│   │   └── Logger.php              <- File-based error logging
│   │
│   ├── Models/
│   │   ├── Post.php
│   │   ├── Page.php
│   │   ├── Category.php
│   │   ├── Tag.php
│   │   ├── Comment.php
│   │   ├── User.php
│   │   ├── Media.php
│   │   ├── Setting.php
│   │   └── Redirect.php
│   │
│   ├── Controllers/
│   │   ├── Frontend/
│   │   │   ├── HomeController.php
│   │   │   ├── PostController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── TagController.php
│   │   │   ├── AuthorController.php
│   │   │   ├── SearchController.php
│   │   │   ├── PageController.php
│   │   │   ├── ArchiveController.php
│   │   │   ├── FeedController.php
│   │   │   └── SitemapController.php
│   │   └── Admin/
│   │       ├── AuthController.php
│   │       ├── DashboardController.php
│   │       ├── PostController.php
│   │       ├── PageController.php
│   │       ├── CategoryController.php
│   │       ├── TagController.php
│   │       ├── CommentController.php
│   │       ├── MediaController.php
│   │       ├── UserController.php
│   │       ├── SettingController.php
│   │       └── RedirectController.php
│   │
│   └── Middleware/
│       ├── AuthMiddleware.php
│       ├── RoleMiddleware.php
│       └── CsrfMiddleware.php
│
├── views/
│   ├── layouts/
│   │   ├── main.php                <- Frontend layout
│   │   ├── admin.php               <- Admin layout
│   │   └── minimal.php             <- Login page layout
│   ├── partials/
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── nav.php
│   │   ├── sidebar.php
│   │   ├── pagination.php
│   │   ├── post-card.php
│   │   ├── breadcrumb.php
│   │   └── flash-message.php
│   ├── frontend/
│   │   ├── home.php
│   │   ├── post.php
│   │   ├── category.php
│   │   ├── tag.php
│   │   ├── author.php
│   │   ├── search.php
│   │   ├── page.php
│   │   ├── archive.php
│   │   └── 404.php
│   └── admin/
│       ├── dashboard.php
│       ├── auth/login.php
│       ├── posts/ (index, create, edit)
│       ├── pages/ (index, create, edit)
│       ├── categories/ (index, form)
│       ├── tags/index.php
│       ├── comments/index.php
│       ├── media/index.php
│       ├── users/ (index, form)
│       ├── settings/index.php
│       └── redirects/index.php
│
├── config/
│   ├── config.php                  <- Constants, paths, limits
│   ├── database.php                <- DB connection factory
│   └── routes.php                  <- All route definitions
│
├── database/
│   ├── schema.sql                  <- All CREATE TABLE statements
│   ├── seed.sql                    <- Default settings + admin user
│   └── migrations/                 <- Numbered SQL migration files
│
├── storage/
│   ├── cache/                      <- File-based page cache
│   └── logs/app.log
│
├── bootstrap.php                   <- Autoloader, config, error handler
├── composer.json
└── .env                            <- DB creds, secrets (not committed)
```

---

## Database Schema (12 Tables)

### `users`
```sql
id, name, email (UNIQUE), password_hash, role (ENUM: super_admin/editor/author/contributor),
slug (UNIQUE), bio, avatar, website, twitter, linkedin, is_active,
last_login_at, created_at, updated_at
```

### `categories`
```sql
id, parent_id (FK→categories), name, slug (UNIQUE), description,
meta_title, meta_description, post_count, sort_order, created_at, updated_at
```

### `tags`
```sql
id, name, slug (UNIQUE), post_count, created_at
```

### `posts`
```sql
id, user_id (FK), category_id (FK), title, slug (UNIQUE), excerpt, content (LONGTEXT),
featured_image, featured_image_alt, status (ENUM: draft/published/archived/scheduled),
is_featured, is_sticky, allow_comments, reading_time_mins, view_count, comment_count,
meta_title, meta_description, og_image, canonical_url,
schema_type (ENUM: Article/NewsArticle/BlogPosting),
published_at, scheduled_at, created_at, updated_at
-- FULLTEXT INDEX on (title, excerpt, content)
-- INDEX on (status, published_at), (category_id), (user_id)
```

### `post_tags` (pivot)
```sql
post_id (FK), tag_id (FK) — composite PRIMARY KEY
```

### `post_revisions`
```sql
id, post_id (FK), user_id (FK), title, content, excerpt, created_at
```

### `pages`
```sql
id, user_id (FK), title, slug (UNIQUE), content (LONGTEXT),
template (ENUM: default/full_width/sidebar), status (ENUM: draft/published),
meta_title, meta_description, og_image, sort_order, created_at, updated_at
```

### `comments`
```sql
id, post_id (FK), parent_id (FK→comments), user_id (FK nullable),
author_name, author_email, author_website, author_ip,
content, status (ENUM: pending/approved/spam/trash),
is_admin_reply, created_at, updated_at
```

### `media`
```sql
id, user_id (FK), filename, original_name, mime_type, file_size,
width, height, alt_text, caption,
path_original, path_medium, path_thumb, created_at
```

### `settings`
```sql
id, key_name (UNIQUE), value (LONGTEXT), type (ENUM: string/text/boolean/json/html),
group_name, label, updated_at
```

### `redirects`
```sql
id, from_path (UNIQUE), to_url, type (301/302), hit_count, is_active, created_at
```

### `post_views`
```sql
id, post_id (FK), visitor_hash, viewed_at
-- INDEX on (post_id, viewed_at)
```

---

## Route Map

### Frontend Routes

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/` | HomeController@index | Homepage |
| GET | `/page/{n}` | HomeController@paginate | Homepage pagination |
| GET | `/article/{slug}` | PostController@show | Single post |
| GET | `/category/{slug}` | CategoryController@show | Category listing |
| GET | `/category/{slug}/page/{n}` | CategoryController@paginate | Category paginated |
| GET | `/tag/{slug}` | TagController@show | Tag listing |
| GET | `/tag/{slug}/page/{n}` | TagController@paginate | Tag paginated |
| GET | `/author/{slug}` | AuthorController@show | Author profile |
| GET | `/author/{slug}/page/{n}` | AuthorController@paginate | Author posts |
| GET | `/search` | SearchController@index | Search results |
| GET | `/archive/{year}` | ArchiveController@year | Yearly archive |
| GET | `/archive/{year}/{month}` | ArchiveController@month | Monthly archive |
| GET | `/feed` | FeedController@rss | Global RSS |
| GET | `/feed/category/{slug}` | FeedController@category | Category RSS |
| GET | `/sitemap.xml` | SitemapController@index | Sitemap index |
| GET | `/post-sitemap.xml` | SitemapController@posts | Posts sitemap |
| GET | `/category-sitemap.xml` | SitemapController@categories | Categories sitemap |
| GET | `/page-sitemap.xml` | SitemapController@pages | Pages sitemap |
| POST | `/comments/store` | PostController@storeComment | Submit comment |
| GET | `/{slug}` | PageController@show | Static page (catch-all) |

### Admin Routes (all prefixed `/admin`)

| Method | URL | Controller | Description |
|--------|-----|------------|-------------|
| GET/POST | `/admin/login` | AuthController | Login |
| POST | `/admin/logout` | AuthController@logout | Logout |
| GET | `/admin` | DashboardController@index | Dashboard |
| GET/POST | `/admin/posts` | PostController | List / create |
| GET/POST | `/admin/posts/{id}/edit` | PostController@edit | Edit post |
| POST | `/admin/posts/{id}/delete` | PostController@delete | Delete post |
| GET/POST | `/admin/pages` | PageController | List / create |
| GET/POST | `/admin/pages/{id}/edit` | PageController@edit | Edit page |
| GET/POST | `/admin/categories` | CategoryController | List + CRUD |
| GET/POST | `/admin/tags` | TagController | List + CRUD + bulk delete |
| GET/POST | `/admin/comments` | CommentController | Moderation |
| GET/POST | `/admin/media` | MediaController | Library + upload |
| GET/POST | `/admin/users` | UserController | List / CRUD |
| GET/POST | `/admin/settings` | SettingController | Settings panel |
| GET/POST | `/admin/redirects` | RedirectController | Redirects CRUD |

---

## Security Implementation

| Threat | Mitigation |
|--------|-----------|
| SQL Injection | PDO only, prepared statements, `ATTR_EMULATE_PREPARES = false` |
| XSS | `h()` helper (`htmlspecialchars`), HTMLPurifier for rich content, tags stripped from comments |
| CSRF | `bin2hex(random_bytes(32))` token in session, validated on every admin POST |
| Auth bruteforce | Rate limit: 5 failures → 15-min IP lockout (tracked in DB or APCu) |
| Session hijacking | `session_regenerate_id(true)` on login, HTTP-only + Secure + SameSite=Strict cookies |
| File upload abuse | `finfo_file()` magic bytes check, randomized filenames, `.htaccess` disables PHP in `/uploads/` |
| Clickjacking | `X-Frame-Options: SAMEORIGIN` header |
| Comment spam | Honeypot field, min 2-second time check, rate limit 3/10min per IP, pending by default |
| Path traversal | Slugs validated against `^[a-z0-9\-]+$` before DB query |
| Info leakage | `display_errors = Off`, custom 404/500 pages, no stack traces to browser |

**HTTP Security Headers:**
```
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
Content-Security-Policy: default-src 'self'; img-src 'self' data:; ...
Permissions-Policy: geolocation=(), microphone=(), camera=()
```

**User Roles:**
- `super_admin` — full access including settings, code injection, user management
- `editor` — manage all posts/pages/comments/media
- `author` — create + manage own posts, upload media
- `contributor` — submit drafts only (no publish)

---

## Design System

### Color Palette

| Token | Value | Usage |
|-------|-------|-------|
| `--color-black` | `#0A0A0A` | Headings, primary text |
| `--color-white` | `#FAFAFA` | Page backgrounds |
| `--color-gray-100` | `#F2F2F2` | Card bg, code blocks |
| `--color-gray-200` | `#E4E4E4` | Borders, dividers |
| `--color-gray-400` | `#A3A3A3` | Secondary text, metadata |
| `--color-gray-600` | `#525252` | Body text |
| `--color-gray-800` | `#262626` | Subheadings |
| `--color-surface` | `#FFFFFF` | Card surfaces |

**No colors. Hierarchy via weight, size, and spacing only.**

### Typography

```css
--font-serif: 'Playfair Display', 'Georgia', serif;   /* Headings, pull quotes */
--font-sans:  'Inter', 'Helvetica Neue', sans-serif;  /* Body, UI */
--font-mono:  'JetBrains Mono', 'Fira Code', monospace;

/* Fluid type scale */
--text-sm:   clamp(0.875rem, 0.8rem + 0.3vw, 0.9375rem);
--text-base: clamp(1rem, 0.95rem + 0.25vw, 1.0625rem);
--text-xl:   clamp(1.25rem, 1.1rem + 0.75vw, 1.5rem);
--text-2xl:  clamp(1.5rem, 1.3rem + 1vw, 2rem);
--text-4xl:  clamp(2.25rem, 1.75rem + 2.5vw, 3.5rem);
--text-5xl:  clamp(2.75rem, 2rem + 4vw, 5rem);
```

**Rules:**
- Article title: `--font-serif`, `--text-5xl`, weight 700
- Body copy: `--font-sans`, `--text-base`, `--color-gray-600`, line-height 1.75
- Article prose: `max-width: 68ch`, `margin: 0 auto`
- Metadata (date, author): uppercase, tracked, `--text-sm`, `--color-gray-400`
- Blockquotes: serif, italic, `border-left: 3px solid #0A0A0A`

### Spacing (8pt Grid)
```css
--space-4: 1rem;  --space-8: 2rem;  --space-12: 3rem;
--space-16: 4rem; --space-20: 5rem; --space-24: 6rem;
```

### Key Components

**Post Card:**
- White card, `1px solid --color-gray-200`
- 16:9 thumbnail, category label (small caps), serif title (line-clamp: 2)
- Excerpt + author avatar (24px) + date + reading time
- Hover: `box-shadow: 0 4px 24px rgba(0,0,0,0.08)`, `translateY(-2px)`, `transition: 200ms ease`

**Navigation:**
- Sticky top, `background: rgba(250,250,250,0.95)`, `backdrop-filter: blur(8px)`
- Logo left (serif bold), links right (uppercase, tracked, hover border-bottom)
- Mobile: hamburger → full-screen menu

**Article Header:**
- Category above title (uppercase small caps)
- Title centered, `--font-serif`, `--text-5xl`
- Meta row: author + date + reading time + horizontal dividers
- Full-width featured image, `max-height: 500px`, `object-fit: cover`

**Admin Panel:**
- Sidebar: `--color-gray-100` bg, active item: black bg + white text
- Tables: zebra striping, sortable columns
- Inputs: full border, focus: `1px solid black`
- Buttons: Primary (black bg, white text) / Secondary (white bg, black border) / Danger (black bg, accent on text)

---

## Implementation Phases

### Phase 1 — Foundation
- Full folder structure
- Apache `.htaccess` / URL rewriting
- `bootstrap.php` — autoloader (PSR-4 via `spl_autoload_register`), `.env` parser, error handler
- `config/config.php` — all constants
- `app/Core/Database.php` — PDO singleton, prepared-statement enforcement
- `app/Core/Router.php` — regex routing, parameter extraction, dispatch
- `config/routes.php` — register all routes
- `app/Core/Request.php`, `Response.php`, `View.php`
- `database/schema.sql` — all 12 tables
- `database/seed.sql` — default settings, first Super Admin user

### Phase 2 — Admin Auth & Settings
- `app/Core/Session.php`, `Csrf.php`, `Auth.php`, `Validator.php`
- `app/Middleware/AuthMiddleware.php`, `RoleMiddleware.php`
- `Admin/AuthController` — login (rate-limited), logout
- `views/layouts/admin.php` — sidebar nav, flash message area
- `app/Models/Setting.php` + `Admin/SettingController`
- `app/Models/User.php` + `Admin/UserController`
- `app/Core/Uploader.php` — file validation, GD resize, secure rename

### Phase 3 — Content Management
- All models: Category, Tag, Post, Page, Media
- `Admin/CategoryController`, `TagController` (with bulk delete)
- `Admin/MediaController` — upload endpoint, grid view, delete
- `Admin/PostController` — full CRUD: list (filters + search), create/edit (TinyMCE, media modal, SEO panel, slug auto-gen, reading time calc, tag tokenizer, scheduling), revision storage
- `Admin/PageController` — same pattern, simpler
- JS: slug auto-generation from title on keyup, manual override
- `Admin/DashboardController` — aggregate stats

### Phase 4 — Frontend Pages
- `public/assets/css/main.css` — full design system (reset, custom properties, typography, layout, card, nav, sidebar, article, tags, pagination, footer, responsive)
- `views/layouts/main.php` — HTML shell + dynamic `<head>`
- All `views/partials/`
- All frontend controllers: Home, Post (with view counter, related posts, TOC via DOMDocument), Category, Tag, Author, Search (FULLTEXT), Page, Archive
- All frontend views
- `public/assets/js/main.js` — mobile nav, TOC scroll spy, copy URL, smooth scroll

### Phase 5 — Comments System
- `PostController@storeComment` — honeypot, rate limit, timing check, CSRF, validation, sanitization, store as `pending`, email notification
- Threaded comment rendering (PHP recursive)
- `Admin/CommentController` — list with filters, approve/spam/delete, bulk actions

### Phase 6 — SEO Layer
- `app/Core/SEO.php` — `buildMetaTags()`, `buildStructuredData()`, `buildBreadcrumbs()`
- Integrate SEO output into `<head>` via `View.php`
- `app/Core/Feed.php` + `FeedController` — valid RSS 2.0 with correct `Content-Type`
- `app/Core/Sitemap.php` + `SitemapController` — sitemap index + child sitemaps, file cache 24h
- Redirect checking in router dispatch cycle (before 404)
- Canonical URL logic, pagination `prev`/`next` links

### Phase 7 — Performance & Polish
- File-based HTML cache for homepage + category pages (5-min TTL, invalidate on publish/update)
- WebP conversion on image upload (GD if PHP supports)
- `print.css` — clean article print layout
- 404 logging (path + referrer)
- `app/Core/Logger.php` — timestamped file logging
- Accessibility audit: alt text, form labels, H1 once per page, ARIA landmarks, focus indicators
- Security hardening pass: review all output points
- Deployment checklist (see below)

---

## External Dependencies (Composer — minimal)

```json
{
  "require": {
    "ezyang/htmlpurifier": "^4.16",
    "phpmailer/phpmailer": "^6.8"
  },
  "autoload": {
    "psr-4": { "App\\": "app/" }
  }
}
```

- **HTMLPurifier** — safe rendering of TinyMCE rich text content
- **PHPMailer** — reliable SMTP email for comment notifications + contact form

All routing, templating, ORM, validation, session handling = **custom PHP**.

---

## Deployment Checklist

- [ ] Point web server document root to `/public/`
- [ ] Run `database/schema.sql` then `database/seed.sql` on production DB
- [ ] Set all `.env` values (DB creds, base URL, SMTP, app key)
- [ ] Set `display_errors = 0` in PHP config
- [ ] Ensure `/storage/` and `/public/uploads/` are writable (`chmod 755`)
- [ ] Confirm `/public/uploads/.htaccess` disables PHP execution
- [ ] Enable HTTPS, configure HSTS header
- [ ] Configure SMTP for email delivery
- [ ] Verify sitemap at `/sitemap.xml`
- [ ] Verify RSS at `/feed`
- [ ] Verify admin login + role restrictions
- [ ] Run a Lighthouse / PageSpeed audit
- [ ] Submit sitemap to Google Search Console

---

*Last updated: 2026-02-24*
