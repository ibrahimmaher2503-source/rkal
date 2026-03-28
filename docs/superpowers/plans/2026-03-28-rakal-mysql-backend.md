# Rakal MySQL Backend & Admin Panel — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Migrate Rakal's static HTML site to PHP + MySQL with a CRUD admin panel for blogs and services.

**Architecture:** Pure PHP 8+ with PDO for MySQL. No frameworks. Shared `includes/` for header/footer/functions. Admin panel behind session-based auth. TinyMCE CDN for rich text editing.

**Tech Stack:** PHP 8+, MySQL 8, PDO, TinyMCE 6 (CDN), Tailwind CSS (CDN), shared hosting (cPanel)

**Spec:** `docs/superpowers/specs/2026-03-28-rakal-mysql-backend-design.md`

---

## File Map

| File | Responsibility |
|------|---------------|
| `sql/schema.sql` | CREATE TABLE + INSERT seed data for services, blogs, admins |
| `sql/.htaccess` | Deny all web access to SQL directory |
| `config/database.php` | PDO singleton connection to MySQL |
| `config/.htaccess` | Deny all web access to config directory |
| `.htaccess` | Root — ErrorDocument 404, deny config/sql |
| `.gitignore` | Exclude config/database.php and uploads/ |
| `includes/functions.php` | Helper functions: getDB, e(), toArabicNumerals, formatArabicDate, generateSlug, CSRF, deleteUpload |
| `includes/header.php` | Shared `<head>`, Tailwind config, navbar, mobile drawer. Receives `$pageTitle`, `$activePage` |
| `includes/footer.php` | Shared footer, `</body>` scripts |
| `index.php` | Homepage (renamed from .html, uses shared includes, content stays static) |
| `about.php` | About page (renamed, static content, shared includes) |
| `solutions.php` | Solutions page (renamed, static content, shared includes) |
| `contact.php` | Contact page (renamed, static content, shared includes) |
| `services.php` | Services listing — queries DB, renders card grid |
| `service-detail.php` | Single service by `?slug=` — queries DB, renders detail layout |
| `blog.php` | Blog listing — featured + card grid from DB, search/filter/pagination |
| `blog-detail.php` | Single blog post by `?slug=` — NEW page, renders full article |
| `404.php` | Custom 404 error page |
| `admin/includes/auth.php` | Session check guard, redirects to login if unauthenticated |
| `admin/includes/admin-header.php` | Admin layout: sidebar nav, dark theme wrapper |
| `admin/login.php` | Login form, password verification, forced password change |
| `admin/logout.php` | Destroy session, redirect to login |
| `admin/dashboard.php` | Stats overview: blog count, service count |
| `admin/blogs.php` | Blog list table with delete |
| `admin/blog-form.php` | Add/edit blog with TinyMCE, image upload |
| `admin/services.php` | Service list table with delete |
| `admin/service-form.php` | Add/edit service with TinyMCE, repeater fields |

---

### Task 1: Database Schema & Seed Data

**Files:**
- Create: `sql/schema.sql`
- Create: `sql/.htaccess`

- [ ] **Step 1: Create `sql/.htaccess`**

```
Deny from all
```

- [ ] **Step 2: Write `sql/schema.sql` — CREATE TABLE statements**

Create the database and 3 tables (`services`, `blogs`, `admins`) exactly matching the spec schema. Use `utf8mb4` charset, `InnoDB` engine. Include:

```sql
CREATE DATABASE IF NOT EXISTS rakal_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rakal_db;

CREATE TABLE services ( ... );  -- all columns from spec
CREATE TABLE blogs ( ... );     -- all columns from spec
CREATE TABLE admins ( ... );    -- all columns from spec
```

- [ ] **Step 3: Write seed data — 8 services**

INSERT all 8 services extracted from the current `services.html`. Each service needs:
- `title`, `slug`, `icon` (Material Symbols name), `description`, `color_scheme`, `subservices` (JSON array), `grid_col_span`, `sort_order`
- For "تطوير المواقع والمنصات" (slug: `tatwir-almawaqie-walminassat`): include full `overview_content`, `stats`, `target_businesses`, `benefits`, `tech_stack`, `workflow`, `faq` from `service-detail.html`
- For other 7 services: set `overview_content` to placeholder paragraph, leave `full_content`, `stats`, `target_businesses`, `benefits`, `tech_stack`, `workflow`, `faq` as empty JSON arrays `[]`

The 8 services in order (from `services.html`):
1. تطوير المواقع والمنصات — icon: `web`, cyan, col-span-2, sort_order: 1
2. تطبيقات الجوال — icon: `smartphone`, gold, col-span-1, sort_order: 2
3. أنظمة ERP و CRM — icon: `settings_suggest`, cyan, col-span-1, sort_order: 3
4. المتاجر الإلكترونية — icon: `shopping_cart`, gold, col-span-1, sort_order: 4
5. حلول الذكاء الاصطناعي ��� icon: `precision_manufacturing`, cyan, col-span-2, sort_order: 5
6. تكامل الأنظمة و APIs — icon: `api`, gold, col-span-1, sort_order: 6
7. التسويق الرقمي و SEO — icon: `search_insights`, cyan, col-span-1, sort_order: 7
8. الصيانة والدعم التقني — icon: `support_agent`, gold, col-span-1, sort_order: 8

- [ ] **Step 4: Write seed data — 7 blog posts**

INSERT 7 blog posts from `blog.html`:

1. (FEATURED) كيف يُسهم التحول الرقمي في تحقيق رؤية ٢٠٣٠ — category: التحول الرقمي, cyan, 8 min, published: 2026-03-15, is_featured: 1
2. أفضل ممارسات التحول الرقمي للشركات السعودية — التحول الرقمي, cyan, 5 min, 2026-03-10
3. كيف يُغيّر الذكاء الاصطناعي مستقبل الأعمال في المملكة — الذكاء الاصطناعي, gold, 7 min, 2026-03-05
4. دليلك الشامل للأمن السيبراني وفق معايير NCA — أمن المعلومات, cyan, 6 min, 2026-02-28
5. مقارنة بين أطر عمل تطوير تطبيقات الجوال في ٢٠٢٦ — تطوير البرمجيات, gold, 8 min, 2026-02-20
6. التقنية المالية في المملكة: فرص النمو والتحديات — رؤية ٢٠٣٠, cyan, 4 min, 2026-02-15
7. بناء ثقافة الابتكار الرقمي داخل المؤسسات السعودية — التحول الرقمي, gold, 6 min, 2026-02-10

Each needs: title, slug (transliterated), excerpt (from HTML), content (placeholder `<p>محتوى المقال قريبًا...</p>`), author: `فريق رقال التقني`, category, category_color, read_time, published_at, is_featured, is_active: 1

- [ ] **Step 5: Write seed data — default admin**

```sql
-- WARNING: Change this password immediately after first login!
INSERT INTO admins (username, password_hash, must_change_password) VALUES
('admin', '$2y$10$...', 1);
```

Generate the bcrypt hash for `admin123` using PHP's `password_hash('admin123', PASSWORD_BCRYPT)`. Use a pre-computed hash: `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`

- [ ] **Step 6: Commit**

```bash
git add sql/
git commit -m "feat: add MySQL schema and seed data for services, blogs, admins"
```

---

### Task 2: Config, Security Files & Helper Functions

**Files:**
- Create: `config/database.php`
- Create: `config/.htaccess`
- Create: `.htaccess` (root)
- Create: `.gitignore`
- Create: `includes/functions.php`

- [ ] **Step 1: Create `config/.htaccess`**

```
Deny from all
```

- [ ] **Step 2: Create `config/database.php`**

PDO connection credentials only (no functions — `getDB()` lives in `includes/functions.php`):
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'rakal_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

- [ ] **Step 3: Create root `.htaccess`**

```apache
ErrorDocument 404 /404.php

# Deny direct access to sensitive directories (backup — each dir also has own .htaccess)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^config/ - [F,L]
    RewriteRule ^sql/ - [F,L]
</IfModule>
```

- [ ] **Step 4: Create `.gitignore`**

```
config/database.php
uploads/*
!uploads/blog/.gitkeep
```

- [ ] **Step 5: Create `includes/functions.php`**

At the top: `require_once __DIR__ . '/../config/database.php';` so callers only need to include `functions.php`.

All helper functions:
- `getDB()` — PDO singleton using DB_* constants from `config/database.php`. Returns PDO instance with utf8mb4, ERRMODE_EXCEPTION, FETCH_ASSOC, emulate_prepares=false
- `e($text)` — `htmlspecialchars($text, ENT_QUOTES, 'UTF-8')`
- `toArabicNumerals($num)` — str_replace each digit 0-9 with ٠-٩
- `formatArabicDate($datetime)` — parse datetime, return "١٥ مارس ٢٠٢٦" using Arabic month names array
- `generateSlug($title)` — Arabic-to-Latin transliteration map, lowercase, replace spaces with hyphens, strip non-alphanumeric. Use a transliteration array mapping common Arabic chars to Latin equivalents (ا→a, ب→b, ت→t, ث→th, ج→j, ح→h, خ→kh, د→d, ذ→dh, ر→r, ز→z, س→s, ش→sh, ص→s, ض→d, ط→t, ظ→z, ع→a, غ→gh, ف→f, ق→q, ك→k, ل→l, م→m, ن→n, ه→h, و→w, ي→y, ة→h, ى→a, ء→a, أ→a, إ→i, آ→a, ؤ→w, ئ→y)
- `truncateText($text, $length = 150)` — mb_substr + '...'
- `csrfField()` — generate token, store in session, return hidden input HTML
- `validateCsrf()` — check POST `_csrf_token` matches session. On failure: set flash message, redirect back with `header('Location: ' . $_SERVER['HTTP_REFERER'])`, and `exit`. This ensures CSRF failure always halts execution.
- `deleteUpload($path)` — if file exists and is inside `uploads/`, unlink it

- [ ] **Step 6: Commit**

```bash
git add config/ .htaccess .gitignore includes/functions.php
git commit -m "feat: add database config, security files, and helper functions"
```

---

### Task 3: Shared Header & Footer Includes

**Files:**
- Create: `includes/header.php`
- Create: `includes/footer.php`

- [ ] **Step 1: Create `includes/header.php`**

Extract from current HTML pages (lines 1-151 of any page). Must:
- Accept `$pageTitle` variable (default: 'ركال | حلول برمجية وطنية ذكية')
- Accept `$activePage` variable (e.g., 'index', 'services', 'blog', etc.)
- Output `<!DOCTYPE html>` through end of mobile drawer `</div>` (line 151)
- Change all `.html` links to `.php` in navbar and drawer
- Use `$activePage` to set `active` class on correct nav-link and drawer-link
- Keep the full Tailwind config, fonts, Material Symbols, and `css/styles.css` link

- [ ] **Step 2: Create `includes/footer.php`**

Extract footer from current HTML (lines 564-619 of services.html). Must:
- Output from `<!-- Footer -->` through `</html>`
- Change all `.html` links to `.php` in footer
- Include `<script src="js/main.js"></script>`

- [ ] **Step 3: Commit**

```bash
git add includes/header.php includes/footer.php
git commit -m "feat: extract shared header and footer PHP includes"
```

---

### Task 4: Rename Static Pages to PHP

**Files:**
- Rename: `index.html` → `index.php`
- Rename: `about.html` → `about.php`
- Rename: `solutions.html` → `solutions.php`
- Rename: `contact.html` → `contact.php`
- Create: `404.php`

- [ ] **Step 1: Convert `index.php`**

Replace the duplicated head/navbar/drawer/footer with includes:
```php
<?php
$pageTitle = 'ركال | حلول برمجية وطنية ذكية';
$activePage = 'index';
require_once 'includes/header.php';
?>

<!-- Keep all <main> content as-is from index.html -->
<!-- Change all .html links to .php within the page content -->

<?php require_once 'includes/footer.php'; ?>
```

The `<main>` content stays identical — just the head/navbar/footer become includes. Update any `.html` links in the page body to `.php`.

- [ ] **Step 2: Convert `about.php`**

Same pattern: `$activePage = 'about'`, keep `<main>` content, use includes. Change `.html` → `.php` in links.

- [ ] **Step 3: Convert `solutions.php`**

Same pattern: `$activePage = 'solutions'`, keep `<main>` content, use includes.

- [ ] **Step 4: Convert `contact.php`**

Same pattern: `$activePage = 'contact'`, keep `<main>` content, use includes.

- [ ] **Step 5: Create `404.php`**

Custom 404 page with dark theme. **Note:** This file is both directly accessed (via ErrorDocument 404) and `require`d by service-detail.php / blog-detail.php. When required from another file, the `require_once '404.php'` + `exit` happens BEFORE the calling file includes header.php, so 404.php correctly handles its own header/footer without double-inclusion.

```php
<?php
http_response_code(404);
$pageTitle = 'ركال | الصفحة غير موجودة';
$activePage = '';
require_once __DIR__ . '/includes/header.php';
?>
<main class="pt-20">
  <section class="min-h-[60vh] flex items-center justify-center circuit-bg">
    <div class="text-center px-8">
      <div class="text-8xl font-black text-primary-container mb-4">٤٠٤</div>
      <h1 class="text-3xl font-bold text-white mb-4">الصفحة غير موجودة</h1>
      <p class="text-on-surface-variant mb-8">عذرًا، الصفحة التي تبحث عنها غير متوفرة.</p>
      <a href="index.php" class="inline-flex items-center gap-2 tech-gradient text-on-primary-fixed px-8 py-3 rounded-xl font-bold">
        <span class="material-symbols-outlined">home</span>
        العودة للرئيسية
      </a>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
```

- [ ] **Step 6: Delete old `.html` files**

```bash
git rm index.html about.html solutions.html contact.html
```

- [ ] **Step 7: Commit**

```bash
git add index.php about.php solutions.php contact.php 404.php
git commit -m "feat: convert static pages to PHP with shared includes"
```

---

### Task 5: Dynamic Services Listing Page

**Files:**
- Create: `services.php` (replace `services.html`)

- [ ] **Step 1: Build `services.php`**

```php
<?php
require_once 'includes/functions.php';

$db = getDB();
$stmt = $db->query('SELECT id, title, slug, icon, description, color_scheme, subservices, grid_col_span FROM services WHERE is_active = 1 ORDER BY sort_order');
$services = $stmt->fetchAll();

$pageTitle = 'ركال | خدماتنا - حلول رقمية متكاملة';
$activePage = 'services';
require_once 'includes/header.php';
?>
```

Keep the hero section and category filter tabs as static HTML (same as current).

Replace the hardcoded service cards grid with a PHP loop:
```php
<?php foreach ($services as $service):
    $color = $service['color_scheme'];
    $isCyan = $color === 'cyan';
    $colSpan = $service['grid_col_span'] == 2 ? 'md:col-span-2' : '';
    $subservices = json_decode($service['subservices'], true) ?: [];
    // ... render card HTML using $service data
    // Use e() for all text output
    // Link: service-detail.php?slug=<?= e($service['slug']) ?>
endforeach; ?>
```

Each card must match the exact HTML structure from the current `services.html`:
- col-span-2 cards get the oversized background icon
- Color-conditional classes (cyan vs gold) for borders, shadows, icons, text
- Subservices rendered as `<ul>` list items with verified icon

Keep the CTA section at bottom as static HTML.

- [ ] **Step 2: Delete old `services.html`**

```bash
git rm services.html
```

- [ ] **Step 3: Commit**

```bash
git add services.php
git commit -m "feat: build dynamic services listing page from database"
```

---

### Task 6: Dynamic Service Detail Page

**Files:**
- Create: `service-detail.php` (replace `service-detail.html`)

- [ ] **Step 1: Build `service-detail.php`**

```php
<?php
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) { http_response_code(404); require_once '404.php'; exit; }

$db = getDB();
$stmt = $db->prepare('SELECT * FROM services WHERE slug = :slug AND is_active = 1');
$stmt->execute(['slug' => $slug]);
$service = $stmt->fetch();

if (!$service) { http_response_code(404); require_once '404.php'; exit; }

// Decode JSON columns
$subservices = json_decode($service['subservices'], true) ?: [];
$stats = json_decode($service['stats'], true) ?: [];
$targetBusinesses = json_decode($service['target_businesses'], true) ?: [];
$benefits = json_decode($service['benefits'], true) ?: [];
$techStack = json_decode($service['tech_stack'], true) ?: [];
$workflow = json_decode($service['workflow'], true) ?: [];
$faq = json_decode($service['faq'], true) ?: [];

$pageTitle = 'ركال | ' . e($service['title']);
$activePage = 'services';
require_once 'includes/header.php';
```

Render sections from `service-detail.html` but data-driven:
1. **Hero**: Breadcrumb (الرئيسية > خدماتنا > `$service['title']`), badge, title, subtitle from `$service['description']`
2. **Overview**: `$service['overview_content']` rendered unescaped (admin-authored HTML). Stats card from `$stats` array
3. **Target businesses**: Loop `$targetBusinesses` — each has `title`, `description`, `icon`
4. **Benefits**: Loop `$benefits` — each has `title`, `description`, `icon`
5. **Tech stack**: Loop `$techStack` — render as pill badges
6. **Workflow**: Loop `$workflow` — numbered timeline steps with `title`, `description`
7. **FAQ**: Loop `$faq` — accordion items with `question`, `answer`
8. **Full content**: If `$service['full_content']` is non-empty, render it unescaped after the structured sections (admin-authored rich HTML)

Only render a section if its data array is non-empty (allows placeholder services to skip sections gracefully).

- [ ] **Step 2: Delete old `service-detail.html`**

```bash
git rm service-detail.html
```

- [ ] **Step 3: Commit**

```bash
git add service-detail.php
git commit -m "feat: build dynamic service detail page from database"
```

---

### Task 7: Dynamic Blog Listing Page

**Files:**
- Create: `blog.php` (replace `blog.html`)

- [ ] **Step 1: Build `blog.php`**

```php
<?php
require_once 'includes/functions.php';

$db = getDB();

// Search & filter params
$category = $_GET['category'] ?? '';
$search = $_GET['q'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 6;
$offset = ($page - 1) * $perPage;

// Featured article
$stmt = $db->query('SELECT * FROM blogs WHERE is_featured = 1 AND is_active = 1 ORDER BY published_at DESC LIMIT 1');
$featured = $stmt->fetch();

// Build card query with optional filters
$where = 'WHERE is_active = 1 AND is_featured = 0';
$params = [];

if ($category) {
    $where .= ' AND category = :category';
    $params['category'] = $category;
}
if ($search) {
    $where .= ' AND (title LIKE :q OR excerpt LIKE :q)';
    $params['q'] = '%' . $search . '%';
}

// Count total for pagination
$countStmt = $db->prepare("SELECT COUNT(*) FROM blogs $where");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();

// Fetch page of cards — bind LIMIT/OFFSET as integers for security consistency
$sql = "SELECT * FROM blogs $where ORDER BY published_at DESC LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
foreach ($params as $key => $val) { $stmt->bindValue($key, $val); }
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

$hasMore = $total > ($offset + $perPage);
```

Render:
1. **Hero section** — static HTML (same as current)
2. **Search + category bar** — form with `action="blog.php"` method GET. Search input `name="q"`, category buttons as links `?category=التحول+الرقمي`. Active category highlighted.
3. **Featured article** — from `$featured` if exists. Link to `blog-detail.php?slug=`
4. **Article grid** — loop `$posts`, render card HTML matching current structure. Use `e()` for all output, `formatArabicDate()` for dates, `toArabicNumerals()` for read time.
5. **Load more** — if `$hasMore`, show button linking to `?page=<?= $page + 1 ?>` (preserving category/search params)

- [ ] **Step 2: Delete old `blog.html`**

```bash
git rm blog.html
```

- [ ] **Step 3: Commit**

```bash
git add blog.php
git commit -m "feat: build dynamic blog listing with search, filter, pagination"
```

---

### Task 8: Blog Detail Page (NEW)

**Files:**
- Create: `blog-detail.php`

- [ ] **Step 1: Build `blog-detail.php`**

```php
<?php
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) { http_response_code(404); require_once '404.php'; exit; }

$db = getDB();
$stmt = $db->prepare('SELECT * FROM blogs WHERE slug = :slug AND is_active = 1');
$stmt->execute(['slug' => $slug]);
$post = $stmt->fetch();

if (!$post) { http_response_code(404); require_once '404.php'; exit; }

$pageTitle = 'ركال | ' . e($post['title']);
$activePage = 'blog';
require_once 'includes/header.php';
```

Layout (styled with existing design system):
1. **Hero**: Breadcrumb (الرئيسية > المدونة > title), category badge, publish date, read time
2. **Featured image**: If `$post['featured_image']` exists render `<img>`, else render placeholder (circuit pattern + icon, same style as blog listing)
3. **Article body**: Author row (avatar placeholder + author name + read time). Then `$post['content']` rendered unescaped (TinyMCE HTML)
4. **Back link**: "العودة إلى المدونة" linking to `blog.php`

Style: max-width container (max-w-4xl), glass-panel for article card, dark theme, RTL. Article content styled with Tailwind Typography-like defaults via a `.blog-content` wrapper class (add to `css/styles.css`): headings white, paragraphs text-on-surface-variant, links cyan, lists styled.

- [ ] **Step 2: Add `.blog-content` styles to `css/styles.css`**

Append to `css/styles.css`:
```css
/* Blog article content */
.blog-content h1, .blog-content h2, .blog-content h3 { color: #fff; font-weight: 700; margin: 1.5em 0 0.5em; }
.blog-content h2 { font-size: 1.5rem; }
.blog-content h3 { font-size: 1.25rem; }
.blog-content p { color: #b9cacb; line-height: 1.8; margin-bottom: 1em; }
.blog-content a { color: #00f2ff; text-decoration: underline; }
.blog-content ul, .blog-content ol { color: #b9cacb; padding-right: 1.5em; margin-bottom: 1em; }
.blog-content li { margin-bottom: 0.5em; }
.blog-content img { border-radius: 1rem; margin: 1.5em 0; max-width: 100%; }
.blog-content blockquote { border-right: 4px solid #00f2ff; padding-right: 1em; color: #b9cacb; font-style: italic; margin: 1.5em 0; }
```

- [ ] **Step 3: Commit**

```bash
git add blog-detail.php css/styles.css
git commit -m "feat: build blog detail page with styled article content"
```

---

### Task 9: Admin Auth System

**Files:**
- Create: `admin/includes/auth.php`
- Create: `admin/login.php`
- Create: `admin/logout.php`

- [ ] **Step 1: Create `admin/includes/auth.php`**

Session guard included at top of every admin page:
```php
<?php
session_start();

// 30-minute idle timeout
$timeout = 30 * 60;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}
$_SESSION['last_activity'] = time();

// Check auth
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Force password change
if ($_SESSION['must_change_password'] && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header('Location: login.php?change_password=1');
    exit;
}
```

- [ ] **Step 2: Create `admin/login.php`**

Login page with dark theme styling. Must call `session_start()` at the top (this page does NOT include `auth.php`). Handles:
1. Normal login form (POST username + password)
2. Password change form (POST old_password + new_password + confirm_password) — shown when `must_change_password` is set
3. On successful login: `session_regenerate_id(true)`, set `$_SESSION['admin_id']`, `$_SESSION['admin_username']`, `$_SESSION['must_change_password']`
4. On password change: validate old password, hash new with `password_hash()`, UPDATE admins SET `password_hash`, `must_change_password = 0`
5. CSRF protection on both forms

Style: centered card on dark background, glass-panel, Rakal branding, RTL.

- [ ] **Step 3: Create `admin/logout.php`**

```php
<?php
session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit;
```

- [ ] **Step 4: Commit**

```bash
git add admin/includes/auth.php admin/login.php admin/logout.php
git commit -m "feat: add admin authentication with session management"
```

---

### Task 10: Admin Layout & Dashboard

**Files:**
- Create: `admin/includes/admin-header.php`
- Create: `admin/dashboard.php`

- [ ] **Step 1: Create `admin/includes/admin-header.php`**

Admin layout wrapper. Outputs:
- Full HTML `<head>` with Tailwind CDN, fonts, styles (same config as main site)
- Sidebar nav (RTL): logo "رکال — لوحة التحكم", links to dashboard.php, blogs.php, services.php, logout.php
- Main content area wrapper `<main class="mr-64 p-8">` (sidebar is fixed right in RTL)
- Responsive: sidebar collapses on mobile to hamburger
- Accepts `$adminPageTitle` variable
- Dark theme matching main site (surface colors, glass-panel sidebar)

- [ ] **Step 2: Create `admin/dashboard.php`**

```php
<?php
require_once 'includes/auth.php';
require_once '../includes/functions.php';

$db = getDB();
$blogCount = $db->query('SELECT COUNT(*) FROM blogs')->fetchColumn();
$serviceCount = $db->query('SELECT COUNT(*) FROM services')->fetchColumn();

$adminPageTitle = 'لوحة التحكم';
require_once 'includes/admin-header.php';
```

Dashboard content:
- Warning banner if `$_SESSION['must_change_password']`
- Two stat cards: total blogs, total services
- Dark themed, glass-panel cards with icons

- [ ] **Step 3: Commit**

```bash
git add admin/includes/admin-header.php admin/dashboard.php
git commit -m "feat: add admin layout and dashboard page"
```

---

### Task 11: Admin Blog Management

**Files:**
- Create: `admin/blogs.php`
- Create: `admin/blog-form.php`
- Create: `uploads/blog/.gitkeep`

- [ ] **Step 1: Create `uploads/blog/.gitkeep`**

Empty file to ensure the directory exists in git.

- [ ] **Step 2: Create `admin/blogs.php`**

Blog list page:
```php
<?php
require_once 'includes/auth.php';
require_once '../includes/functions.php';

$db = getDB();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    validateCsrf();
    $stmt = $db->prepare('SELECT featured_image FROM blogs WHERE id = :id');
    $stmt->execute(['id' => $_POST['delete_id']]);
    $blog = $stmt->fetch();
    if ($blog && $blog['featured_image']) {
        deleteUpload('../' . $blog['featured_image']);
    }
    $stmt = $db->prepare('DELETE FROM blogs WHERE id = :id');
    $stmt->execute(['id' => $_POST['delete_id']]);
    header('Location: blogs.php?deleted=1');
    exit;
}

$stmt = $db->query('SELECT * FROM blogs ORDER BY published_at DESC');
$blogs = $stmt->fetchAll();
```

Render: table with columns (title, category, date, status badge, featured badge, actions: edit link + delete form with confirm).

- [ ] **Step 3: Create `admin/blog-form.php`**

Add/edit form:
```php
<?php
require_once 'includes/auth.php';
require_once '../includes/functions.php';

$db = getDB();
$id = $_GET['id'] ?? null;
$blog = null;

if ($id) {
    $stmt = $db->prepare('SELECT * FROM blogs WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $blog = $stmt->fetch();
}
```

On POST:
1. Validate CSRF
2. Collect fields from `$_POST`
3. Auto-generate slug from title if empty: `generateSlug($title)`
4. Handle featured image upload: ensure `uploads/blog/` exists (`if (!is_dir('../uploads/blog')) mkdir('../uploads/blog', 0755, true);`), validate MIME (image/jpeg, image/png, image/webp), max 2MB, rename `uniqid() . '.' . ext`, save to `uploads/blog/`. If replacing, delete old file.
5. If `is_featured` is checked: `UPDATE blogs SET is_featured = 0 WHERE is_featured = 1` first
6. INSERT or UPDATE based on whether `$id` exists
7. Redirect to `blogs.php?saved=1`

Form HTML:
- Fields: title (text), slug (text, auto-filled via JS), author (text, default value), category (select dropdown), category_color (radio: cyan/gold), excerpt (textarea), read_time (number), published_at (datetime-local), is_featured (checkbox), is_active (checkbox)
- TinyMCE for `content`: load from CDN `https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js`, init with `directionality: 'rtl'`, `language: 'ar'`, plugins: link image lists table code, toolbar with formatting options. **Note:** `no-api-key` triggers a domain warning; obtain a free key from tiny.cloud before production deployment.
- Featured image upload with preview (show current image if editing)
- CSRF hidden field
- Submit button

JS on page:
- Auto-slug generation: on title input, call transliteration logic (or use a simple fetch to a PHP endpoint, but simpler to do client-side with a basic Arabic→Latin map)

- [ ] **Step 4: Commit**

```bash
git add admin/blogs.php admin/blog-form.php uploads/blog/.gitkeep
git commit -m "feat: add admin blog management (list, add, edit, delete)"
```

---

### Task 12: Admin Service Management

**Files:**
- Create: `admin/services.php`
- Create: `admin/service-form.php`

- [ ] **Step 1: Create `admin/services.php`**

Service list page (same pattern as `admin/blogs.php`):
- Handle POST delete with CSRF validation
- Query all services ordered by sort_order
- Render table: title, color_scheme, sort_order, status, actions (edit/delete)

- [ ] **Step 2: Create `admin/service-form.php`**

Add/edit form. On POST:
1. Validate CSRF
2. Collect basic fields from `$_POST`
3. Auto-generate slug from title if empty
4. Collect repeater fields — these come as arrays from the form:
   - `subservices[]` → JSON encode as string array
   - `stats[label][]`, `stats[value][]` → JSON encode as `[{label, value}, ...]`
   - `target_businesses[title][]`, `target_businesses[description][]`, `target_businesses[icon][]` → JSON encode
   - `benefits[title][]`, `benefits[description][]`, `benefits[icon][]` → JSON encode
   - `workflow[title][]`, `workflow[description][]` → JSON encode
   - `tech_stack[]` → JSON encode as string array
   - `faq[question][]`, `faq[answer][]` → JSON encode as `[{question, answer}, ...]`
5. INSERT or UPDATE
6. Redirect to `services.php?saved=1`

Form HTML:
- Basic fields: title, slug, icon (text input with hint "Material Symbols name"), description (textarea), color_scheme (radio), grid_col_span (select: 1 or 2), sort_order (number), is_active (checkbox)
- TinyMCE for overview_content and full_content (two editors)
- **Repeater sections** with JS add/remove:
  - Each repeater: a container `<div>` with "Add" button. Each row has input fields + "Remove" button. JS clones a template row on "Add", removes row on "Remove".
  - Subservices: single text input per row
  - Stats: label + value per row
  - Target businesses: title + description + icon per row
  - Benefits: title + description + icon per row
  - Workflow: title + description per row
  - Tech stack: single text input per row
  - FAQ: question + answer (textarea) per row
- CSRF hidden field

- [ ] **Step 3: Commit**

```bash
git add admin/services.php admin/service-form.php
git commit -m "feat: add admin service management with repeater fields"
```

---

### Task 13: Final Cleanup & Link Updates

**Files:**
- Modify: all remaining `.html` references across the codebase

- [ ] **Step 1: Search for any remaining `.html` references**

Check all `.php` files for any lingering `.html` links that weren't caught:
```bash
grep -r '\.html' --include="*.php" .
```

Fix any found references to point to `.php`.

- [ ] **Step 2: Verify all old `.html` files are removed**

```bash
ls *.html
```

Should return nothing. If any remain, `git rm` them.

- [ ] **Step 3: Verify the uploads directory structure**

```bash
ls -la uploads/blog/
```

Ensure `.gitkeep` exists and directory is writable.

- [ ] **Step 4: Commit any remaining fixes**

```bash
git add -A
git commit -m "fix: clean up remaining .html references and verify file structure"
```

---

### Task 14: Manual Testing Checklist

This task is not code — it's a verification checklist. Run through in a local PHP environment (XAMPP/WAMP/Laragon):

- [ ] **Step 1: Import database**

Import `sql/schema.sql` into MySQL. Verify 8 services, 7 blogs, 1 admin exist.

- [ ] **Step 2: Test public pages**

- `index.php` loads with correct layout
- `services.php` shows 8 service cards from DB
- `service-detail.php?slug=tatwir-almawaqie-walminassat` shows full detail page
- `service-detail.php?slug=nonexistent` shows 404
- `blog.php` shows featured article + 6 cards
- `blog.php?category=التحول+الرقمي` filters correctly
- `blog.php?q=ذكاء` search works
- `blog-detail.php?slug=...` shows article
- `about.php`, `solutions.php`, `contact.php` load correctly
- Navigation links all work (no broken .html refs)

- [ ] **Step 3: Test admin panel**

- `admin/login.php` — login with admin/admin123
- Forced password change on first login
- Dashboard shows correct counts
- Create/edit/delete a blog post
- TinyMCE loads and works in RTL
- Image upload works
- Create/edit/delete a service
- Repeater fields add/remove correctly
- Logout works
- Session timeout after 30 min idle

- [ ] **Step 4: Final commit**

```bash
git add -A
git commit -m "feat: complete Rakal PHP + MySQL backend migration"
```
