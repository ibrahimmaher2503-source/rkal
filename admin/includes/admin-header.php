<?php
/**
 * Admin Layout Header
 *
 * Variables expected (set before including this file):
 *   $adminPageTitle  (string) – browser <title>, defaults to 'لوحة التحكم'
 *   $adminActivePage (string) – active sidebar key: 'dashboard', 'blogs', 'services'
 */

if (!isset($adminPageTitle))  $adminPageTitle  = 'لوحة التحكم';
if (!isset($adminActivePage)) $adminActivePage = '';

/**
 * Returns CSS classes for a sidebar nav link.
 */
function adminNavClass(string $page, string $active): string {
    return $active === $page
        ? 'flex items-center gap-3 px-4 py-2.5 rounded-xl bg-surface-container-high text-[#00dbe7] font-medium transition-all'
        : 'flex items-center gap-3 px-4 py-2.5 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-all';
}
?>
<!DOCTYPE html>
<html class="dark" dir="rtl" lang="ar">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($adminPageTitle ?? 'لوحة التحكم') ?> — ركال</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary-fixed": "#74f5ff",
            "surface-bright": "#323851",
            "inverse-on-surface": "#292f48",
            "on-primary-fixed-variant": "#004f54",
            "tertiary-fixed-dim": "#4cd6ff",
            "inverse-surface": "#dce1ff",
            "primary-container": "#00f2ff",
            "surface-dim": "#0b1229",
            "surface-container-high": "#222941",
            "on-error": "#690005",
            "primary-fixed-dim": "#00dbe7",
            "surface-variant": "#2d344c",
            "error": "#ffb4ab",
            "on-tertiary": "#003543",
            "on-tertiary-fixed-variant": "#004e60",
            "secondary-fixed-dim": "#bcc5ed",
            "surface-container-lowest": "#060d24",
            "surface-container-highest": "#2d344c",
            "on-surface-variant": "#b9cacb",
            "on-secondary-fixed-variant": "#3d4567",
            "on-primary": "#00363a",
            "surface-container": "#181e36",
            "secondary-container": "#3d4567",
            "outline": "#849495",
            "inverse-primary": "#00696f",
            "tertiary-fixed": "#b7eaff",
            "on-primary-fixed": "#002022",
            "secondary-fixed": "#dce1ff",
            "on-tertiary-fixed": "#001f28",
            "on-error-container": "#ffdad6",
            "on-surface": "#dce1ff",
            "on-secondary": "#262f4f",
            "surface": "#0b1229",
            "secondary": "#bcc5ed",
            "primary": "#e1fdff",
            "on-secondary-container": "#abb3db",
            "surface-tint": "#00dbe7",
            "surface-container-low": "#141a32",
            "tertiary": "#eef9ff",
            "on-background": "#dce1ff",
            "error-container": "#93000a",
            "outline-variant": "#3a494b",
            "tertiary-container": "#a0e5ff",
            "background": "#0b1229",
            "on-primary-container": "#006a71",
            "on-secondary-fixed": "#111a39",
            "on-tertiary-container": "#006881",
            "sand-gold": "#D4AF37"
          },
          fontFamily: {
            "headline": ["IBM Plex Sans Arabic", "sans-serif"],
            "body":     ["IBM Plex Sans Arabic", "sans-serif"],
            "label":    ["IBM Plex Sans Arabic", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.125rem",
            "lg": "0.25rem",
            "xl": "0.5rem"
          },
        },
      },
    }
  </script>
  <link rel="stylesheet" href="../css/styles.css"/>
  <style>
    /* Sidebar active link glow */
    .sidebar-active-glow {
      box-shadow: inset 0 0 0 1px rgba(0, 219, 231, 0.2), 0 0 12px rgba(0, 219, 231, 0.06);
    }
    /* Sidebar divider */
    .sidebar-divider {
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(0, 242, 255, 0.12), transparent);
    }
  </style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary-container/30 overflow-x-hidden">

<!-- ===== Fixed Right Sidebar (RTL) ===== -->
<aside class="fixed top-0 right-0 h-full w-64 z-40 flex flex-col glass-panel border-l-0 border-r-0 border-t-0 border-b-0"
       style="background: rgba(18, 24, 46, 0.97); border-left: 1px solid rgba(0, 242, 255, 0.08);"
       aria-label="قائمة لوحة التحكم">

  <!-- Logo / Branding -->
  <div class="px-6 py-6 border-b border-outline-variant/30">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-surface-container-high border border-primary-container/20 flex items-center justify-center flex-shrink-0">
        <span class="text-xl font-bold text-[#00dbe7] font-headline" style="text-shadow: 0 0 16px rgba(0,219,231,0.5);" aria-hidden="true">ر</span>
      </div>
      <div>
        <p class="text-lg font-bold text-[#00dbe7] font-headline leading-tight" style="text-shadow: 0 0 16px rgba(0,219,231,0.4);">رکال</p>
        <p class="text-xs text-on-surface-variant tracking-wider">لوحة التحكم</p>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" aria-label="روابط لوحة التحكم">

    <a href="dashboard.php" class="<?= adminNavClass('dashboard', $adminActivePage) ?><?= $adminActivePage === 'dashboard' ? ' sidebar-active-glow' : '' ?>">
      <span class="material-symbols-outlined text-xl flex-shrink-0" aria-hidden="true">dashboard</span>
      <span>الرئيسية</span>
    </a>

    <a href="blogs.php" class="<?= adminNavClass('blogs', $adminActivePage) ?><?= $adminActivePage === 'blogs' ? ' sidebar-active-glow' : '' ?>">
      <span class="material-symbols-outlined text-xl flex-shrink-0" aria-hidden="true">article</span>
      <span>المدونة</span>
    </a>

    <a href="services.php" class="<?= adminNavClass('services', $adminActivePage) ?><?= $adminActivePage === 'services' ? ' sidebar-active-glow' : '' ?>">
      <span class="material-symbols-outlined text-xl flex-shrink-0" aria-hidden="true">design_services</span>
      <span>الخدمات</span>
    </a>

    <!-- Divider -->
    <div class="sidebar-divider my-3" role="separator" aria-hidden="true"></div>

    <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-on-surface-variant hover:bg-error-container/20 hover:text-error transition-all">
      <span class="material-symbols-outlined text-xl flex-shrink-0" aria-hidden="true">logout</span>
      <span>تسجيل الخروج</span>
    </a>

  </nav>

  <!-- Footer info -->
  <div class="px-5 py-4 border-t border-outline-variant/30">
    <p class="text-xs text-on-surface-variant/50 text-center">&copy; <?= date('Y') ?> ركال</p>
  </div>

</aside>

<!-- ===== Mobile Top Bar (visible only on small screens) ===== -->
<div class="md:hidden fixed top-0 left-0 right-0 z-40 flex items-center justify-between px-4 py-3"
     style="background: rgba(18, 24, 46, 0.97); border-bottom: 1px solid rgba(0, 242, 255, 0.08);">
  <span class="text-lg font-bold text-[#00dbe7] font-headline">رکال — لوحة التحكم</span>
  <button id="admin-mobile-menu-btn" class="text-on-surface-variant hover:text-on-surface p-1 rounded-xl transition-colors" aria-label="القائمة">
    <span class="material-symbols-outlined" aria-hidden="true">menu</span>
  </button>
</div>

<!-- Mobile Sidebar Drawer -->
<div id="admin-mobile-overlay" class="md:hidden fixed inset-0 bg-black/60 z-50 hidden" aria-hidden="true"></div>
<aside id="admin-mobile-drawer" class="md:hidden fixed top-0 right-0 h-full w-64 z-50 flex flex-col translate-x-full transition-transform duration-300"
       style="background: rgba(18, 24, 46, 0.99); border-left: 1px solid rgba(0, 242, 255, 0.08);"
       aria-label="قائمة لوحة التحكم (الجوال)">

  <div class="px-6 py-5 border-b border-outline-variant/30 flex items-center justify-between">
    <span class="text-lg font-bold text-[#00dbe7] font-headline">رکال</span>
    <button id="admin-mobile-close-btn" class="text-on-surface-variant hover:text-on-surface p-1 rounded-xl transition-colors" aria-label="إغلاق">
      <span class="material-symbols-outlined" aria-hidden="true">close</span>
    </button>
  </div>

  <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
    <a href="dashboard.php" class="<?= adminNavClass('dashboard', $adminActivePage) ?>">
      <span class="material-symbols-outlined text-xl flex-shrink-0" aria-hidden="true">dashboard</span>
      <span>الرئيسية</span>
    </a>
    <a href="blogs.php" class="<?= adminNavClass('blogs', $adminActivePage) ?>">
      <span class="material-symbols-outlined text-xl flex-shrink-0" aria-hidden="true">article</span>
      <span>المدونة</span>
    </a>
    <a href="services.php" class="<?= adminNavClass('services', $adminActivePage) ?>">
      <span class="material-symbols-outlined text-xl flex-shrink-0" aria-hidden="true">design_services</span>
      <span>الخدمات</span>
    </a>
    <div class="sidebar-divider my-3" role="separator" aria-hidden="true"></div>
    <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-on-surface-variant hover:bg-error-container/20 hover:text-error transition-all">
      <span class="material-symbols-outlined text-xl flex-shrink-0" aria-hidden="true">logout</span>
      <span>تسجيل الخروج</span>
    </a>
  </nav>

</aside>

<script>
  (function () {
    var btn     = document.getElementById('admin-mobile-menu-btn');
    var closeBtn = document.getElementById('admin-mobile-close-btn');
    var overlay = document.getElementById('admin-mobile-overlay');
    var drawer  = document.getElementById('admin-mobile-drawer');

    function openDrawer() {
      drawer.classList.remove('translate-x-full');
      overlay.classList.remove('hidden');
      drawer.removeAttribute('aria-hidden');
    }
    function closeDrawer() {
      drawer.classList.add('translate-x-full');
      overlay.classList.add('hidden');
      drawer.setAttribute('aria-hidden', 'true');
    }

    if (btn)      btn.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    if (overlay)  overlay.addEventListener('click', closeDrawer);
  })();
</script>

<!-- ===== Main Content Area ===== -->
<!-- mr-64 = offset right by sidebar width (RTL: sidebar is on the right) -->
<main class="mr-0 md:mr-64 min-h-screen p-6 md:p-8 pt-16 md:pt-8">
