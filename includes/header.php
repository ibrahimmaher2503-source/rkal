<?php
/**
 * Shared Header Include
 *
 * Variables:
 *   $pageTitle  (string) – <title> text, defaults to site name
 *   $activePage (string) – one of: index, about, services, solutions, blog, contact
 */

if (!isset($pageTitle))  $pageTitle  = 'ركال | حلول برمجية وطنية ذكية';
if (!isset($activePage)) $activePage = '';

/**
 * Escape helper — outputs HTML-safe string.
 * Defined here only if not already defined (so it won't clash if header is
 * included more than once in unit tests, etc.).
 */
if (!function_exists('e')) {
    function e(string $s): string {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

/**
 * Returns the CSS classes for a navbar link based on whether it is active.
 */
function navClass(string $page, string $activePage): string {
    return $activePage === $page
        ? 'nav-link-pill active'
        : 'nav-link-pill';
}

/**
 * Returns the CSS classes for a mobile drawer link based on whether it is active.
 */
function drawerClass(string $page, string $activePage): string {
    return $activePage === $page
        ? 'drawer-link active py-3 px-4 rounded-xl text-lg text-[#00f2ff]'
        : 'drawer-link py-3 px-4 rounded-xl text-lg text-white/60';
}
?>
<!DOCTYPE html>
<html class="dark" dir="rtl" lang="ar">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($pageTitle) ?></title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
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
            "body": ["IBM Plex Sans Arabic", "sans-serif"],
            "label": ["IBM Plex Sans Arabic", "sans-serif"]
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
  <link rel="stylesheet" href="css/styles.css"/>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary-container/30 overflow-x-hidden sadu-pattern">

<!-- Skip Link -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:right-4 focus:z-[100] focus:bg-primary-container focus:text-on-primary-fixed focus:px-4 focus:py-2 focus:rounded-xl focus:font-bold">انتقل إلى المحتوى</a>

<!-- Navbar -->
<nav id="navbar" class="fixed top-0 w-full z-50 backdrop-blur-2xl border-b border-white/[0.04] transition-all duration-500" style="background-color: #071426; box-shadow: 0 1px 40px rgba(0,242,255,0.04), 0 0 80px rgba(0,0,0,0.3);">
  <div class="flex justify-between items-center py-2.5 text-sm font-medium">
    <!-- Logo -->
    <a href="/" class="flex items-center gap-3 group">
      <img src="logo.png" alt="ركال" class="h-20 w-auto transition-transform duration-300 group-hover:scale-105" />
    </a>
    <!-- Desktop Nav Links -->
    <div class="hidden md:flex items-center">
      <div class="flex gap-1 items-center bg-surface-container/40 rounded-2xl px-2 py-1.5 border border-white/[0.04]">
        <a class="<?= navClass('index',     $activePage) ?>" href="/">الرئيسية</a>
        <a class="<?= navClass('about',     $activePage) ?>" href="/about">من نحن</a>
        <a class="<?= navClass('services',  $activePage) ?>" href="/services">خدماتنا</a>
        <a class="<?= navClass('solutions', $activePage) ?>" href="/solutions">الحلول</a>
        <a class="<?= navClass('blog',      $activePage) ?>" href="/blog">المدونة</a>
        <a class="<?= navClass('contact',   $activePage) ?>" href="/contact">تواصل معنا</a>
      </div>
    </div>
    <!-- CTA + Hamburger -->
    <div class="flex items-center gap-3">
      <button class="hidden md:flex items-center gap-2 cta-btn text-on-primary-fixed px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 hover:shadow-[0_0_30px_rgba(0,242,255,0.3)] hover:scale-[1.02]" style="background: linear-gradient(135deg, #00f2ff 0%, #00bcd4 100%);">
        <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        اطلب عرض سعر
      </button>
      <button id="hamburger-btn" class="md:hidden text-white/70 hover:text-white p-2.5 rounded-xl hover:bg-white/5 transition-all" aria-label="القائمة" aria-expanded="false">
        <span class="material-symbols-outlined text-2xl">menu</span>
      </button>
    </div>
  </div>
</nav>

<!-- Mobile Drawer Overlay -->
<div id="drawer-overlay" class="drawer-overlay"></div>

<!-- Mobile Drawer Panel -->
<div id="mobile-drawer" class="drawer-panel bg-surface-container" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="flex flex-col h-full p-8">
    <div class="flex justify-between items-center mb-10">
      <a href="/" class="flex items-center gap-3">
        <img src="logo.png" alt="ركال" class="h-20 w-auto" />
      </a>
      <button id="drawer-close-btn" class="text-white/60 hover:text-white w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center transition-all hover:bg-white/10" aria-label="إغلاق القائمة">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <nav class="flex flex-col gap-1 flex-1">
      <a href="/"          class="<?= drawerClass('index',     $activePage) ?>">الرئيسية</a>
      <a href="/about"     class="<?= drawerClass('about',     $activePage) ?>">من نحن</a>
      <a href="/services"  class="<?= drawerClass('services',  $activePage) ?>">خدماتنا</a>
      <a href="/solutions" class="<?= drawerClass('solutions', $activePage) ?>">الحلول</a>
      <a href="/blog"      class="<?= drawerClass('blog',      $activePage) ?>">المدونة</a>
      <a href="/contact"   class="<?= drawerClass('contact',   $activePage) ?>">تواصل معنا</a>
    </nav>
    <div class="mt-auto space-y-6">
      <button class="w-full tech-gradient text-on-primary-fixed py-4 rounded-xl font-bold text-lg">
        اطلب عرض سعر
      </button>
      <div class="flex justify-center gap-4">
        <a href="#" class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-white/60 hover:bg-primary-container hover:text-on-primary-container transition-all" aria-label="الموقع">
          <span class="material-symbols-outlined text-lg" aria-hidden="true">public</span>
        </a>
        <a href="#" class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-white/60 hover:bg-primary-container hover:text-on-primary-container transition-all" aria-label="البريد">
          <span class="material-symbols-outlined text-lg" aria-hidden="true">alternate_email</span>
        </a>
        <a href="#" class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-white/60 hover:bg-primary-container hover:text-on-primary-container transition-all" aria-label="الهاتف">
          <span class="material-symbols-outlined text-lg" aria-hidden="true">call</span>
        </a>
      </div>
    </div>
  </div>
</div>

<main id="main-content" class="pt-20">
