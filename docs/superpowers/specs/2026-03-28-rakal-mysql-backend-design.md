# Rakal MySQL Backend & Admin Panel — Design Spec

## Overview

Migrate the Rakal static website from hardcoded HTML to a dynamic PHP + MySQL backend. Add a login-protected admin panel with rich text editing for managing blogs and services. All existing content is seeded into the database so the site looks identical after migration.

**Stack:** PHP 8+ (no framework), MySQL 8, PDO, TinyMCE (CDN), shared hosting (cPanel)

---

## Database Schema

### `services` table

| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT PK | |
| title | VARCHAR(255) | Service name (Arabic) |
| slug | VARCHAR(255) UNIQUE | URL-friendly identifier |
| icon | VARCHAR(100) | Material Symbols icon name |
| description | TEXT | Short description for cards |
| overview_content | LONGTEXT | Rich HTML for "Service Overview" section on detail page |
| full_content | LONGTEXT | Rich HTML for additional detail content (rendered unescaped, admin-authored) |
| color_scheme | ENUM('cyan','gold') | Card accent color |
| subservices | JSON | Array of subservice strings |
| stats | JSON | Key stats (e.g. +150 delivered, 24/7 support) |
| target_businesses | JSON | Array of {title, description, icon} |
| benefits | JSON | Array of {title, description, icon} |
| tech_stack | JSON | Array of technology names |
| workflow | JSON | Array of {title, description} for process steps section |
| faq | JSON | Array of {question, answer} |
| grid_col_span | TINYINT DEFAULT 1 | 1 or 2 for grid layout |
| sort_order | INT DEFAULT 0 | Display ordering |
| is_active | TINYINT(1) DEFAULT 1 | Show/hide toggle |
| created_at | TIMESTAMP DEFAULT CURRENT_TIMESTAMP | |
| updated_at | TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | |

### `blogs` table

| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT PK | |
| title | VARCHAR(255) | Article title (Arabic) |
| slug | VARCHAR(255) UNIQUE | URL-friendly identifier |
| excerpt | TEXT | Short preview text |
| content | LONGTEXT | Rich HTML body (TinyMCE, rendered unescaped) |
| author | VARCHAR(255) DEFAULT 'فريق رقال التقني' | Author display name |
| category | VARCHAR(100) | e.g. التحول الرقمي, الذكاء الاصطناعي |
| category_color | ENUM('cyan','gold') | Badge accent color |
| featured_image | VARCHAR(500) | Image path or null for placeholder |
| read_time | INT | Minutes to read |
| is_featured | TINYINT(1) DEFAULT 0 | Show as featured article (admin enforces single featured) |
| is_active | TINYINT(1) DEFAULT 1 | Published/draft toggle |
| published_at | DATETIME | Publish date |
| created_at | TIMESTAMP DEFAULT CURRENT_TIMESTAMP | |
| updated_at | TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | |

### `admins` table

| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT PK | |
| username | VARCHAR(100) UNIQUE | |
| password_hash | VARCHAR(255) | bcrypt via password_hash() |
| must_change_password | TINYINT(1) DEFAULT 1 | Force password change on first login |
| created_at | TIMESTAMP DEFAULT CURRENT_TIMESTAMP | |

---

## File Structure

```
/rkal/
├── .gitignore                # config/database.php, uploads/
├── .htaccess                 # Deny access to config/, sql/ + ErrorDocument 404
├── config/
│   ├── .htaccess             # Deny all
│   └── database.php          # DB connection (PDO singleton)
├── includes/
│   ├── header.php            # Shared <head>, navbar
│   ├── footer.php            # Shared footer, scripts
│   └── functions.php         # Helper functions
├── admin/
│   ├── login.php             # Admin login page
│   ├── logout.php            # Destroy session
│   ├── dashboard.php         # Overview stats
│   ├── blogs.php             # Blog list + delete
│   ├── blog-form.php         # Add/edit blog (TinyMCE)
│   ├── services.php          # Service list + delete
│   ├── service-form.php      # Add/edit service
│   └── includes/
│       ├── auth.php          # Session check guard
│       └── admin-header.php  # Admin layout (sidebar)
├── uploads/
│   └── blog/                 # Blog featured images
├── index.php                 # Homepage
├── about.php                 # About (static content)
├── services.php              # Services listing (from DB)
├── service-detail.php        # Single service (by slug)
├── blog.php                  # Blog listing (from DB)
├── blog-detail.php           # Single blog post (NEW)
├── solutions.php             # Solutions (static content)
├── contact.php               # Contact (static content)
├── 404.php                   # Custom 404 page (dark themed)
├── css/styles.css            # Unchanged
├── js/main.js                # Unchanged
└── sql/
    ├── .htaccess             # Deny all
    └── schema.sql            # DB creation + seed data
```

### File decisions

- All `.html` files renamed to `.php` so shared includes work everywhere
- `blog-detail.php` is a new page (currently no individual blog post view exists)
- `service-detail.php` becomes dynamic via `?slug=xxx` query parameter
- `solutions.php`, `about.php`, `contact.php` stay static — just renamed for include compatibility
- `uploads/blog/` stores featured images from admin
- `.htaccess` files in `config/` and `sql/` deny direct web access
- Root `.htaccess` includes `ErrorDocument 404 /404.php` for server-level 404s
- `.gitignore` excludes `config/database.php` and `uploads/` from version control
- `sql/schema.sql` stays in git (useful to version-control schema) — `.htaccess` prevents web access

### .gitignore contents

```
config/database.php
uploads/
```

---

## Database Connection

**File:** `config/database.php`

- PDO singleton with `charset=utf8mb4` for full Arabic/emoji support
- Credentials stored in this file (protected by `.htaccess`, excluded from git)
- Error mode: `PDO::ERRMODE_EXCEPTION`
- Default fetch mode: `PDO::FETCH_ASSOC`

---

## Security

| Threat | Mitigation |
|--------|-----------|
| SQL injection | PDO prepared statements with bound parameters — no string concatenation |
| XSS | All output escaped with `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')`. Rich HTML content (blog `content`, service `overview_content` and `full_content`) rendered unescaped — admin-authored only |
| CSRF | Token generated per session, hidden field in every admin form, validated on POST |
| Password cracking | `password_hash()` with PASSWORD_BCRYPT, `password_verify()` on login |
| Default password | `must_change_password` flag forces password change on first admin login |
| Session hijacking | `session_regenerate_id()` on login, 30-minute idle timeout |
| File upload attacks | Validate MIME type (JPEG/PNG/WebP only), max 2MB, rename with `uniqid()` |
| Unauthorized admin access | `admin/includes/auth.php` included at top of every admin page, redirects to login |
| Sensitive file exposure | `.htaccess` deny rules on `config/` and `sql/` directories |
| Orphaned uploads | When deleting a blog or replacing an image, delete the old file from `uploads/blog/` |

---

## Page Data Flow

### `services.php` (listing)

```sql
SELECT id, title, slug, icon, description, color_scheme, subservices, grid_col_span
FROM services WHERE is_active = 1 ORDER BY sort_order
```

Loops through results, renders card HTML. Each card links to `service-detail.php?slug={slug}`.

### `service-detail.php` (single)

```sql
SELECT * FROM services WHERE slug = :slug AND is_active = 1
```

Receives `?slug=web-development`. Decodes JSON columns (subservices, stats, target_businesses, benefits, workflow, tech_stack, faq). Renders full detail layout. If slug not found, sends HTTP 404 header and includes `404.php`.

**Note:** Only the "Web Development" service currently has full detail content. The other 7 services will be seeded with their card-level data (title, description, subservices) plus placeholder detail content. Full detail content can be added via the admin panel after launch.

### `blog.php` (listing)

```sql
-- Featured
SELECT * FROM blogs WHERE is_featured = 1 AND is_active = 1 ORDER BY published_at DESC LIMIT 1

-- Cards (exclude featured)
SELECT * FROM blogs WHERE is_active = 1 AND is_featured = 0
ORDER BY published_at DESC LIMIT 6
```

Featured article in hero section. Remaining articles as 6-card grid. Each links to `blog-detail.php?slug={slug}`.

**Search & filter:** Server-side via query parameters. Category filter: `?category=التحول+الرقمي` adds `AND category = :category` to query. Search: `?q=keyword` adds `AND (title LIKE :q OR excerpt LIKE :q)` using `%keyword%`. Both combine with pagination.

**Pagination:** `?page=2` with 6 posts per page. `LIMIT 6 OFFSET :offset`. Show "Load More" button if more posts exist (`COUNT(*) > offset + 6`).

### `blog-detail.php` (single — NEW page)

```sql
SELECT * FROM blogs WHERE slug = :slug AND is_active = 1
```

New page: article title, author name, category badge, date, read time, featured image, rich HTML content body. Styled with existing design system (glass-panel, dark theme, RTL). If slug not found, sends HTTP 404 and includes `404.php`.

### `index.php` (homepage)

The homepage services overview section stays hardcoded. It uses a distinct layout (mixed grid with col-span-2 cards, different markup from the services listing page) and only shows a curated preview. Keeping it static avoids layout complexity for a section that rarely changes.

Everything else stays static (hero, stats, process, CTA).

### `404.php` (error page)

Custom 404 page styled with dark theme. Shows a message in Arabic with a link back to homepage. Used by `service-detail.php` and `blog-detail.php` when a slug is not found.

### Static pages

`about.php`, `solutions.php`, `contact.php` — renamed from `.html`, include shared header/footer, content stays hardcoded.

**Note:** `contact.php` form remains non-functional (decorative) in this phase. Wiring it to send emails or store submissions is a separate future task.

---

## Slug Generation

The `generateSlug()` function uses transliteration to create Latin URL-safe slugs from Arabic titles. Arabic characters are transliterated to their romanized equivalents. Example: "تطوير المواقع والمنصات" → `tatwir-almawaqie-walminassat`. This is better for shared hosting compatibility and standard URL handling. Slugs are auto-generated from titles but editable in the admin form.

---

## Admin Panel

### Authentication

- Login form at `admin/login.php` styled with dark theme
- bcrypt password verification
- Session-based with 30-minute idle timeout
- Default admin seeded in `schema.sql` (username: `admin`, password: `admin123`)
- **First login forced password change** via `must_change_password` flag

### Dashboard (`admin/dashboard.php`)

- Sidebar navigation: dashboard, blogs, services, logout
- Quick stats: total blogs, total services
- Dark theme matching main site (glass-panel, cyan/gold accents)
- Warning banner if `must_change_password` is still set

### Blog Management

- **List** (`admin/blogs.php`): Table with title, category, date, status (active/draft), featured badge, edit/delete actions
- **Delete:** Confirmation dialog (JS confirm). Hard-delete from DB. Deletes associated uploaded image from `uploads/blog/`.
- **Form** (`admin/blog-form.php`): Add and edit
  - Fields: title, slug (auto-generated, editable), author, category (dropdown), category_color, excerpt, read_time, published_at, is_featured, is_active
  - **Featured toggle:** When marking a post as featured, the server automatically un-features any previously featured post (enforces single featured)
  - TinyMCE (CDN) for content body — RTL configured, Arabic interface
  - Featured image upload with preview. Replacing an image deletes the old file.
  - CSRF token hidden field

### Service Management

- **List** (`admin/services.php`): Table with title, color, sort order, status, edit/delete
- **Delete:** Confirmation dialog (JS confirm). Hard-delete from DB.
- **Form** (`admin/service-form.php`): Add and edit
  - Basic fields: title, slug, icon, description, color_scheme, grid_col_span, sort_order, is_active
  - TinyMCE for overview_content and full_content
  - Dynamic repeater fields (add/remove rows via JS) for: subservices, stats, target_businesses, benefits, workflow, tech_stack, FAQ
  - CSRF token hidden field

### Admin Design

- Same dark theme as main site
- RTL layout throughout
- Responsive (works on mobile)

---

## Data Seeding

`sql/schema.sql` includes INSERT statements for:

- **8 services** — all card-level data from `services.html`. Full detail content for "Web Development" from `service-detail.html`. Other 7 services get placeholder detail content (overview, benefits, etc. can be filled in via admin).
- **7 blog posts** — featured article + 6 cards from `blog.html` (titles, excerpts, categories, dates, read times). Blog body content is placeholder since the current site has no full article pages.
- **1 default admin** — username: `admin`, password hash for `admin123`, `must_change_password = 1`

**Warning comment in schema.sql:** reminds to change default admin password immediately.

Site looks identical after migration — zero content loss on listing pages.

---

## Shared Includes

### `includes/header.php`

Extracted from current HTML: `<head>` with Tailwind CDN, fonts, meta tags, navbar markup. Receives optional `$pageTitle` variable.

### `includes/footer.php`

Footer markup + `<script>` tags for `js/main.js`. Closes `</body></html>`.

### `includes/functions.php`

| Function | Purpose |
|----------|---------|
| `getDB()` | Returns PDO singleton |
| `toArabicNumerals($num)` | Converts 123 → ١٢٣ |
| `formatArabicDate($datetime)` | Formats to "١٥ مارس ٢٠٢٦" |
| `generateSlug($title)` | Transliterates Arabic to Latin URL-safe slug |
| `truncateText($text, $length)` | Excerpt truncation |
| `e($text)` | Short alias for htmlspecialchars |
| `csrfField()` | Generates hidden CSRF input |
| `validateCsrf()` | Validates CSRF token on POST |
| `deleteUpload($path)` | Deletes a file from uploads/ if it exists |

---

## What Does NOT Change

- `css/styles.css` — unchanged
- `js/main.js` — unchanged (existing interactions: drawer, accordion, scroll, reveal)
- Tailwind CDN config — unchanged
- Visual design — pages look identical, just powered by DB data
- Homepage services section — stays hardcoded (distinct layout from services listing)
- About, solutions, contact page content — stays hardcoded
