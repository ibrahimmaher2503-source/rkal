<?php
require_once __DIR__ . '/../config/database.php';

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
