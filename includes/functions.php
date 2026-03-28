<?php
require_once __DIR__ . '/../config/database.php';

// ---------------------------------------------------------------------------
// 0. Site base path (auto-detect from script location)
// ---------------------------------------------------------------------------
if (!defined('SITE_BASE')) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    // Walk up from includes/ or admin/ subdirectories to find the real base
    $base = rtrim(preg_replace('#/(includes|admin(/includes)?)$#', '', $scriptDir), '/');
    define('SITE_BASE', $base);
}

/** Return a full path relative to the site root. url('/about') → '/rkal/about' */
function url(string $path = '/'): string {
    if ($path === '/') return SITE_BASE . '/';
    return SITE_BASE . '/' . ltrim($path, '/');
}

// ---------------------------------------------------------------------------
// 1. Database singleton
// ---------------------------------------------------------------------------
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $pdo;
}

// ---------------------------------------------------------------------------
// 2. HTML escaping
// ---------------------------------------------------------------------------
function e(?string $text): string {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// ---------------------------------------------------------------------------
// 3. Arabic numerals
// ---------------------------------------------------------------------------
function toArabicNumerals(int|string $num): string {
    $western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $arabic  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    return str_replace($western, $arabic, (string) $num);
}

// ---------------------------------------------------------------------------
// 4. Format Arabic date
// ---------------------------------------------------------------------------
function formatArabicDate(string $datetime): string {
    $months = [
        1  => 'يناير',
        2  => 'فبراير',
        3  => 'مارس',
        4  => 'أبريل',
        5  => 'مايو',
        6  => 'يونيو',
        7  => 'يوليو',
        8  => 'أغسطس',
        9  => 'سبتمبر',
        10 => 'أكتوبر',
        11 => 'نوفمبر',
        12 => 'ديسمبر',
    ];
    $ts    = strtotime($datetime);
    $day   = toArabicNumerals((int) date('j', $ts));
    $month = $months[(int) date('n', $ts)];
    $year  = toArabicNumerals((int) date('Y', $ts));
    return "{$day} {$month} {$year}";
}

// ---------------------------------------------------------------------------
// 5. Generate URL slug (Arabic → Latin transliteration)
// ---------------------------------------------------------------------------
function generateSlug(string $title): string {
    $map = [
        'ا' => 'a',  'ب' => 'b',  'ت' => 't',  'ث' => 'th',
        'ج' => 'j',  'ح' => 'h',  'خ' => 'kh', 'د' => 'd',
        'ذ' => 'dh', 'ر' => 'r',  'ز' => 'z',  'س' => 's',
        'ش' => 'sh', 'ص' => 's',  'ض' => 'd',  'ط' => 't',
        'ظ' => 'z',  'ع' => 'a',  'غ' => 'gh', 'ف' => 'f',
        'ق' => 'q',  'ك' => 'k',  'ل' => 'l',  'م' => 'm',
        'ن' => 'n',  'ه' => 'h',  'و' => 'w',  'ي' => 'y',
        'ة' => 'h',  'ى' => 'a',  'ء' => 'a',  'أ' => 'a',
        'إ' => 'i',  'آ' => 'a',  'ؤ' => 'w',  'ئ' => 'y',
    ];
    $slug = strtr($title, $map);
    $slug = strtolower($slug);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s]+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// ---------------------------------------------------------------------------
// 6. Truncate text
// ---------------------------------------------------------------------------
function truncateText(string $text, int $length = 150): string {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '...';
}

// ---------------------------------------------------------------------------
// 7. CSRF field
// ---------------------------------------------------------------------------
function csrfField(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    $token = e($_SESSION['_csrf_token']);
    return '<input type="hidden" name="_csrf_token" value="' . $token . '">';
}

// ---------------------------------------------------------------------------
// 8. Validate CSRF
// ---------------------------------------------------------------------------
function validateCsrf(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $posted = $_POST['_csrf_token'] ?? '';
    $stored = $_SESSION['_csrf_token'] ?? '';
    if (!hash_equals($stored, $posted)) {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }
}

// ---------------------------------------------------------------------------
// 9. Delete upload file
// ---------------------------------------------------------------------------
function deleteUpload(string $path): void {
    if (str_starts_with($path, 'uploads/') && file_exists($path)) {
        unlink($path);
    }
}

// ---------------------------------------------------------------------------
// 10. Sanitize HTML (for TinyMCE content output)
// ---------------------------------------------------------------------------
function sanitizeHtml(string $html): string {
    // Strip script/iframe/object/embed tags and on* event attributes
    $html = preg_replace('#<(script|iframe|object|embed|applet|form|input|button|select|textarea|link|meta|style)[^>]*>.*?</\1>#si', '', $html);
    $html = preg_replace('#<(script|iframe|object|embed|applet|form|input|button|select|textarea|link|meta|style)[^>]*/?\s*>#si', '', $html);
    $html = preg_replace('#\s+on[a-z]+\s*=\s*["\'][^"\']*["\']#si', '', $html);
    $html = preg_replace('#\s+on[a-z]+\s*=\s*\S+#si', '', $html);
    // Strip javascript: and data: URLs in href/src attributes
    $html = preg_replace('#(href|src|action)\s*=\s*["\']?\s*javascript:#si', '$1="', $html);
    $html = preg_replace('#(href|src|action)\s*=\s*["\']?\s*data:#si', '$1="', $html);
    return $html;
}

// ---------------------------------------------------------------------------
// 11. Validate uploaded image (server-side MIME check)
// ---------------------------------------------------------------------------
function validateImageUpload(array $file, int $maxSize = 2097152): ?string {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return 'فشل رفع الملف';
    }
    if ($file['size'] > $maxSize) {
        return 'حجم الملف يتجاوز 2 ميجابايت';
    }
    // Verify actual image content using getimagesize
    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return 'الملف ليس صورة صالحة';
    }
    $allowedMimes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
    if (!in_array($imageInfo[2], $allowedMimes)) {
        return 'نوع الملف غير مدعوم (JPEG, PNG, WebP فقط)';
    }
    // Double-check with finfo if available
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
            return 'نوع الملف غير مدعوم';
        }
    }
    return null; // valid
}

// ---------------------------------------------------------------------------
// 12. Generate secure filename for uploads
// ---------------------------------------------------------------------------
function secureFilename(string $originalName): string {
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $allowedExts)) {
        $ext = 'bin';
    }
    return bin2hex(random_bytes(16)) . '.' . $ext;
}

// ---------------------------------------------------------------------------
// 13. Login rate limiting (file-based)
// ---------------------------------------------------------------------------
function checkLoginRateLimit(string $ip, int $maxAttempts = 5, int $windowSeconds = 900): bool {
    $dir = sys_get_temp_dir() . '/rkal_login_attempts';
    if (!is_dir($dir)) { @mkdir($dir, 0700, true); }
    $file = $dir . '/' . md5($ip) . '.json';
    $attempts = [];
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if (is_array($data)) {
            // Keep only attempts within the window
            $cutoff = time() - $windowSeconds;
            $attempts = array_filter($data, fn($t) => $t > $cutoff);
        }
    }
    return count($attempts) >= $maxAttempts;
}

function recordLoginAttempt(string $ip): void {
    $dir = sys_get_temp_dir() . '/rkal_login_attempts';
    if (!is_dir($dir)) { @mkdir($dir, 0700, true); }
    $file = $dir . '/' . md5($ip) . '.json';
    $attempts = [];
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if (is_array($data)) {
            $cutoff = time() - 900;
            $attempts = array_filter($data, fn($t) => $t > $cutoff);
        }
    }
    $attempts[] = time();
    file_put_contents($file, json_encode(array_values($attempts)), LOCK_EX);
}

function clearLoginAttempts(string $ip): void {
    $dir = sys_get_temp_dir() . '/rkal_login_attempts';
    $file = $dir . '/' . md5($ip) . '.json';
    if (file_exists($file)) { @unlink($file); }
}

// ---------------------------------------------------------------------------
// 14. Render 404 page and exit
// ---------------------------------------------------------------------------
/**
 * Set 404 status, render the error page, and stop execution.
 * Centralises all not-found handling so every caller behaves identically.
 */
function render404(): never {
    http_response_code(404);
    require_once __DIR__ . '/../404.php';
    exit;
}
