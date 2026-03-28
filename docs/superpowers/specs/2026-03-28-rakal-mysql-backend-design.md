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
| full_content | LONGTEXT | Rich HTML content for detail page |
| color_scheme | ENUM('cyan','gold') | Card accent color |
| subservices | JSON | Array of subservice strings |
| stats | JSON | Key stats (e.g. +150 delivered, 24/7 support) |
| target_businesses | JSON | Array of {title, description, icon} |
| benefits | JSON | Array of {title, description, icon} |
| tech_stack | JSON | Array of technology names |
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
| content | LONGTEXT | Rich HTML body (TinyMCE) |
| category | VARCHAR(100) | e.g. التحول الرقمي, الذكاء الاصطناعي |
| category_color | ENUM('cyan','gold') | Badge accent color |
| featured_image | VARCHAR(500) | Image path or null for placeholder |
| read_time | INT | Minutes to read |
| is_featured | TINYINT(1) DEFAULT 0 | Show as featured article |
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
| created_at | TIMESTAMP DEFAULT CURRENT_TIMESTAMP | |

---

## File Structure

```
/rkal/
├── config/
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
├── css/styles.css            # Unchanged
├── js/main.js                # Unchanged
└── sql/
    └── schema.sql            # DB creation + seed data
```

### File decisions

- All `.html` files renamed to `.php` so shared includes work everywhere
- `blog-detail.php` is a new page (currently no individual blog post view exists)
- `service-detail.php` becomes dynamic via `?slug=xxx` query parameter
- `solutions.php`, `about.php`, `contact.php` stay static — just renamed for include compatibility
- `uploads/blog/` stores featured images from admin

---

## Database Connection

**File:** `config/database.php`

- PDO singleton with `charset=utf8mb4` for full Arabic/emoji support
- Credentials stored in this file (added to `.gitignore`)
- Error mode: `PDO::ERRMODE_EXCEPTION`
- Default fetch mode: `PDO::FETCH_ASSOC`

---

## Security

| Threat | Mitigation |
|--------|-----------|
| SQL injection | PDO prepared statements with bound parameters — no string concatenation |
| XSS | All output escaped with `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')`. Blog body rendered with `html_entity_decode()` only in blog-detail.php |
| CSRF | Token generated per session, hidden field in every admin form, validated on POST |
| Password cracking | `password_hash()` with PASSWORD_BCRYPT, `password_verify()` on login |
| Session hijacking | `session_regenerate_id()` on login, 30-minute idle timeout |
| File upload attacks | Validate MIME type (JPEG/PNG/WebP only), max 2MB, rename with `uniqid()` |
| Unauthorized admin access | `admin/includes/auth.php` included at top of every admin page, redirects to login |

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

Receives `?slug=web-development`. Decodes JSON columns (subservices, stats, target_businesses, benefits, tech_stack, faq). Renders full detail layout. Returns 404 page if slug not found.

### `blog.php` (listing)

```sql
-- Featured
SELECT * FROM blogs WHERE is_featured = 1 AND is_active = 1 LIMIT 1

-- Cards
SELECT * FROM blogs WHERE is_active = 1 ORDER BY published_at DESC LIMIT 6
```

Featured article in hero section. Remaining articles as 6-card grid. Each links to `blog-detail.php?slug={slug}`.

### `blog-detail.php` (single — NEW page)

```sql
SELECT * FROM blogs WHERE slug = :slug AND is_active = 1
```

New page: article title, category badge, date, read time, featured image, rich HTML content body. Styled with existing design system (glass-panel, dark theme, RTL).

### `index.php` (homepage)

```sql
SELECT id, title, slug, icon, description, color_scheme
FROM services WHERE is_active = 1 ORDER BY sort_order LIMIT 3
```

Services overview section pulls top 3 from DB. Everything else stays static.

### Static pages

`about.php`, `solutions.php`, `contact.php` — renamed from `.html`, include shared header/footer, content stays hardcoded.

---

## Admin Panel

### Authentication

- Login form at `admin/login.php` styled with dark theme
- bcrypt password verification
- Session-based with 30-minute idle timeout
- Default admin seeded in `schema.sql` (username: `admin`, password: `admin123`)

### Dashboard (`admin/dashboard.php`)

- Sidebar navigation: dashboard, blogs, services, logout
- Quick stats: total blogs, total services
- Dark theme matching main site (glass-panel, cyan/gold accents)

### Blog Management

- **List** (`admin/blogs.php`): Table with title, category, date, status (active/draft), edit/delete actions
- **Form** (`admin/blog-form.php`): Add and edit
  - Fields: title, slug (auto-generated, editable), category (dropdown), category_color, excerpt, read_time, published_at, is_featured, is_active
  - TinyMCE (CDN) for content body — RTL configured, Arabic interface
  - Featured image upload with preview
  - CSRF token hidden field

### Service Management

- **List** (`admin/services.php`): Table with title, color, sort order, status, edit/delete
- **Form** (`admin/service-form.php`): Add and edit
  - Basic fields: title, slug, icon, description, color_scheme, grid_col_span, sort_order, is_active
  - TinyMCE for full_content
  - Dynamic repeater fields (add/remove rows via JS) for: subservices, stats, target_businesses, benefits, tech_stack, FAQ
  - CSRF token hidden field

### Admin Design

- Same dark theme as main site
- RTL layout throughout
- Responsive (works on mobile)

---

## Data Seeding

`sql/schema.sql` includes INSERT statements for:

- **8 services** — all content from current `services.html` and `service-detail.html`
- **7 blog posts** — featured article + 6 cards from current `blog.html`
- **1 default admin** — username: `admin`, password hash for `admin123`

Site looks identical after migration — zero content loss.

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
| `generateSlug($title)` | URL-safe slug from Arabic text |
| `truncateText($text, $length)` | Excerpt truncation |
| `e($text)` | Short alias for htmlspecialchars |
| `csrfField()` | Generates hidden CSRF input |
| `validateCsrf()` | Validates CSRF token on POST |

---

## What Does NOT Change

- `css/styles.css` — unchanged
- `js/main.js` — unchanged (existing interactions: drawer, accordion, scroll, reveal)
- Tailwind CDN config — unchanged
- Visual design — pages look identical, just powered by DB data
- About, solutions, contact page content — stays hardcoded
