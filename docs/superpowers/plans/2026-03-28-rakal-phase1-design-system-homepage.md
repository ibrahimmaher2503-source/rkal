# Phase 1: Design System + Homepage — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rebuild the Rakal homepage and create a shared design system (CSS + JS) for a premium Arabic-first Saudi tech company website.

**Architecture:** Static HTML + Tailwind CSS CDN + vanilla JS. Shared `css/styles.css` for custom classes, `js/main.js` for interactions (mobile drawer, FAQ accordion, navbar scroll, smooth scroll). Single `index.html` rebuilt from scratch with 11 sections.

**Tech Stack:** HTML5, Tailwind CSS (CDN with forms + container-queries plugins), vanilla JavaScript, Google Material Symbols Outlined, IBM Plex Sans Arabic / Space Grotesk / Manrope fonts.

**Spec:** `docs/superpowers/specs/2026-03-28-rakal-phase1-design-system-homepage-design.md`

---

## File Map

| File | Action | Responsibility |
|------|--------|---------------|
| `css/styles.css` | CREATE | Shared custom CSS classes (glass-panel, circuit-bg, sadu-pattern, tech-gradient, drawer, FAQ accordion, animations) |
| `js/main.js` | CREATE | Mobile drawer toggle, FAQ accordion, navbar scroll effect, smooth scroll |
| `index.html` | REWRITE | Full homepage with Tailwind config, navbar, 9 content sections, footer |

---

## Task 1: Create Shared CSS

**Files:**
- Create: `css/styles.css`

- [ ] **Step 1: Create `css/` directory**

Run: `mkdir -p css`

- [ ] **Step 2: Write `css/styles.css`**

```css
/* ===== Rakal Design System — Shared Styles ===== */

/* --- Base --- */
body {
  font-family: 'IBM Plex Sans Arabic', sans-serif;
}

.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  vertical-align: middle;
}

/* --- Design Tokens (CSS classes) --- */
.glass-panel {
  background: rgba(24, 30, 54, 0.6);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(0, 242, 255, 0.1);
}

.circuit-bg {
  background-image: radial-gradient(circle at 2px 2px, rgba(0, 242, 255, 0.05) 1px, transparent 0);
  background-size: 40px 40px;
}

.sadu-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l30 30-30 30L0 30z' fill='%23D4AF37' fill-opacity='0.03'/%3E%3C/svg%3E");
  background-size: 40px 40px;
}

.tech-gradient {
  background: linear-gradient(135deg, #00f2ff 0%, #4cd6ff 100%);
}

.gold-accent {
  border: 1px solid rgba(212, 175, 55, 0.3);
  box-shadow: 0 0 20px rgba(212, 175, 55, 0.1);
}

.glow-shadow {
  box-shadow: 0 0 30px rgba(0, 242, 255, 0.3);
}

.section-divider {
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(0, 242, 255, 0.2), transparent);
}

/* --- Navbar scroll state --- */
#navbar {
  transition: background-color 300ms ease, box-shadow 300ms ease;
}
#navbar.scrolled {
  background-color: rgba(11, 18, 41, 0.95) !important;
  box-shadow: 0 20px 40px rgba(0, 242, 255, 0.08);
}

/* --- Mobile Drawer --- */
.drawer-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  z-index: 55;
  opacity: 0;
  pointer-events: none;
  transition: opacity 300ms ease;
}
.drawer-overlay.active {
  opacity: 1;
  pointer-events: auto;
}

.drawer-panel {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  width: 80%;
  max-width: 320px;
  z-index: 60;
  transform: translateX(100%);
  transition: transform 300ms ease;
  overflow-y: auto;
}
.drawer-panel.active {
  transform: translateX(0);
}

/* --- FAQ Accordion --- */
.faq-answer {
  max-height: 0;
  overflow: hidden;
  transition: max-height 300ms ease;
}
.faq-item.active .faq-answer {
  max-height: 500px;
}
.faq-icon {
  transition: transform 300ms ease;
}
.faq-item.active .faq-icon {
  transform: rotate(180deg);
}

/* --- Animations --- */
@keyframes slow-bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}
.animate-slow-bounce {
  animation: slow-bounce 4s ease-in-out infinite;
}
```

- [ ] **Step 3: Verify file exists**

Run: `ls css/styles.css`
Expected: file listed

- [ ] **Step 4: Commit**

```bash
git add css/styles.css
git commit -m "feat: create shared CSS design system (glass-panel, circuit-bg, drawer, FAQ)"
```

---

## Task 2: Create Shared JavaScript

**Files:**
- Create: `js/main.js`

- [ ] **Step 1: Create `js/` directory**

Run: `mkdir -p js`

- [ ] **Step 2: Write `js/main.js`**

```javascript
/* ===== Rakal — Shared Interactions ===== */

document.addEventListener('DOMContentLoaded', () => {
  initMobileDrawer();
  initFaqAccordion();
  initNavbarScroll();
  initSmoothScroll();
});

/* --- Mobile Drawer --- */
function initMobileDrawer() {
  const hamburger = document.getElementById('hamburger-btn');
  const drawer = document.getElementById('mobile-drawer');
  const overlay = document.getElementById('drawer-overlay');
  if (!hamburger || !drawer || !overlay) return;

  // Get all focusable elements in drawer for focus trapping
  function getFocusableElements() {
    return drawer.querySelectorAll('a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
  }

  function openDrawer() {
    drawer.classList.add('active');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    hamburger.setAttribute('aria-expanded', 'true');
    drawer.setAttribute('aria-hidden', 'false');
    // Focus first link in drawer
    const focusable = getFocusableElements();
    if (focusable.length) focusable[0].focus();
  }

  function closeDrawer() {
    drawer.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
    hamburger.setAttribute('aria-expanded', 'false');
    drawer.setAttribute('aria-hidden', 'true');
    hamburger.focus();
  }

  hamburger.addEventListener('click', openDrawer);
  overlay.addEventListener('click', closeDrawer);

  // Close button inside drawer
  const closeBtn = document.getElementById('drawer-close-btn');
  if (closeBtn) closeBtn.addEventListener('click', closeDrawer);

  // Close on ESC + focus trap
  document.addEventListener('keydown', (e) => {
    if (!drawer.classList.contains('active')) return;

    if (e.key === 'Escape') {
      closeDrawer();
      return;
    }

    // Focus trap: Tab/Shift+Tab cycle within drawer
    if (e.key === 'Tab') {
      const focusable = getFocusableElements();
      if (!focusable.length) return;
      const first = focusable[0];
      const last = focusable[focusable.length - 1];
      if (e.shiftKey) {
        if (document.activeElement === first) {
          e.preventDefault();
          last.focus();
        }
      } else {
        if (document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }
    }
  });

  // Close drawer links on click (for anchor navigation)
  drawer.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      closeDrawer();
    });
  });
}

/* --- FAQ Accordion --- */
function initFaqAccordion() {
  const items = document.querySelectorAll('.faq-item');
  if (!items.length) return;

  items.forEach(item => {
    const btn = item.querySelector('.faq-btn');
    if (!btn) return;

    btn.addEventListener('click', () => {
      const isActive = item.classList.contains('active');

      // Close all
      items.forEach(i => {
        i.classList.remove('active');
        const b = i.querySelector('.faq-btn');
        if (b) b.setAttribute('aria-expanded', 'false');
      });

      // Open clicked (if wasn't already open)
      if (!isActive) {
        item.classList.add('active');
        btn.setAttribute('aria-expanded', 'true');
      }
    });

    // Keyboard: Enter/Space
    btn.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        btn.click();
      }
    });
  });
}

/* --- Navbar Scroll Effect --- */
function initNavbarScroll() {
  const navbar = document.getElementById('navbar');
  if (!navbar) return;

  function onScroll() {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll(); // Run once on load
}

/* --- Smooth Scroll --- */
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', (e) => {
      const href = anchor.getAttribute('href');
      if (href === '#') return; // skip placeholder links
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
}
```

- [ ] **Step 3: Verify file exists**

Run: `ls js/main.js`
Expected: file listed

- [ ] **Step 4: Commit**

```bash
git add js/main.js
git commit -m "feat: create shared JS (mobile drawer, FAQ accordion, navbar scroll, smooth scroll)"
```

---

## Task 3: Create `index.html` — Boilerplate + Tailwind Config + Navbar

**Files:**
- Rewrite: `index.html`

This task creates the HTML shell with `<head>`, Tailwind config, skip link, navbar (desktop + mobile), mobile drawer, and opening `<main>` tag. Subsequent tasks append sections inside `<main>`.

- [ ] **Step 1: Write `index.html` with boilerplate, navbar, and drawer**

Write the complete file. The `<main>` tag is opened but only contains a placeholder comment. Sections will be added in subsequent tasks.

```html
<!DOCTYPE html>
<html class="dark" dir="rtl" lang="ar">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>ركال | حلول برمجية وطنية ذكية</title>
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
<a href="#hero" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:right-4 focus:z-[100] focus:bg-primary-container focus:text-on-primary-fixed focus:px-4 focus:py-2 focus:rounded-xl focus:font-bold">انتقل إلى المحتوى</a>

<!-- Navbar -->
<nav id="navbar" class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl border-b border-white/5 shadow-[0_20px_40px_rgba(0,242,255,0.08)]">
  <div class="flex flex-row-reverse justify-between items-center px-8 py-4 max-w-screen-2xl mx-auto text-sm font-medium">
    <!-- Logo (right/leading in RTL) -->
    <div class="flex items-center gap-3">
      <div class="text-2xl font-bold text-white tracking-tighter">رکال</div>
      <div class="h-6 w-[1px] bg-white/10 hidden md:block"></div>
      <div class="hidden md:flex items-center gap-1.5 text-[10px] text-sand-gold font-bold uppercase tracking-widest opacity-80">
        <span>Vision</span>
        <span class="bg-sand-gold/20 px-1 rounded">2030</span>
      </div>
    </div>
    <!-- Desktop Nav Links -->
    <div class="hidden md:flex flex-row-reverse gap-8 items-center">
      <a class="text-[#00f2ff] border-b-2 border-[#00f2ff] pb-1" href="index.html">الرئيسية</a>
      <a class="text-white/70 hover:text-white transition-colors" href="about.html">من نحن</a>
      <a class="text-white/70 hover:text-white transition-colors" href="services.html">خدماتنا</a>
      <a class="text-white/70 hover:text-white transition-colors" href="#">الحلول</a>
      <a class="text-white/70 hover:text-white transition-colors" href="#">المدونة</a>
      <a class="text-white/70 hover:text-white transition-colors" href="contact.html">تواصل معنا</a>
    </div>
    <!-- CTA Button (left/trailing in RTL) — hidden on mobile -->
    <button class="hidden md:block tech-gradient text-on-primary-fixed px-6 py-2.5 rounded-xl font-bold hover:shadow-[0_0_15px_rgba(0,242,255,0.3)] transition-all duration-300">
      اطلب عرض سعر
    </button>
    <!-- Hamburger (mobile only) -->
    <button id="hamburger-btn" class="md:hidden text-white p-2" aria-label="القائمة" aria-expanded="false">
      <span class="material-symbols-outlined text-2xl">menu</span>
    </button>
  </div>
</nav>

<!-- Mobile Drawer Overlay -->
<div id="drawer-overlay" class="drawer-overlay"></div>

<!-- Mobile Drawer Panel -->
<div id="mobile-drawer" class="drawer-panel bg-surface-container" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="flex flex-col h-full p-8">
    <!-- Drawer Header -->
    <div class="flex justify-between items-center mb-10">
      <div class="text-2xl font-bold text-white">رکال</div>
      <button id="drawer-close-btn" class="text-white/60 hover:text-white" aria-label="إغلاق القائمة">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <!-- Drawer Nav Links -->
    <nav class="flex flex-col gap-2 flex-1">
      <a href="index.html" class="text-[#00f2ff] font-bold py-3 px-4 rounded-xl bg-primary-container/5 text-lg">الرئيسية</a>
      <a href="about.html" class="text-white/70 hover:text-white py-3 px-4 rounded-xl hover:bg-white/5 transition-all text-lg">من نحن</a>
      <a href="services.html" class="text-white/70 hover:text-white py-3 px-4 rounded-xl hover:bg-white/5 transition-all text-lg">خدماتنا</a>
      <a href="#" class="text-white/70 hover:text-white py-3 px-4 rounded-xl hover:bg-white/5 transition-all text-lg">الحلول</a>
      <a href="#" class="text-white/70 hover:text-white py-3 px-4 rounded-xl hover:bg-white/5 transition-all text-lg">المدونة</a>
      <a href="contact.html" class="text-white/70 hover:text-white py-3 px-4 rounded-xl hover:bg-white/5 transition-all text-lg">تواصل معنا</a>
    </nav>
    <!-- Drawer CTA -->
    <div class="mt-auto space-y-6">
      <button class="w-full tech-gradient text-on-primary-fixed py-4 rounded-xl font-bold text-lg">
        اطلب عرض سعر
      </button>
      <!-- Social Icons -->
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

<main class="pt-20">
<!-- SECTIONS WILL BE ADDED IN SUBSEQUENT TASKS -->
</main>

<script src="js/main.js"></script>
</body>
</html>
```

- [ ] **Step 2: Open in browser and verify**

Open `index.html` in a browser. Verify:
- Dark navy background
- Navbar visible with logo, links, CTA button
- On mobile viewport (< 768px): only logo + hamburger visible
- Clicking hamburger opens drawer from right side
- Clicking overlay or pressing ESC closes drawer
- Sadu diamond pattern barely visible in background

- [ ] **Step 3: Commit**

```bash
git add index.html
git commit -m "feat: index.html boilerplate with Tailwind config, navbar, mobile drawer"
```

---

## Task 4: Hero Section

**Files:**
- Modify: `index.html` — insert inside `<main>`, replacing the placeholder comment

- [ ] **Step 1: Add hero section HTML**

Insert the following immediately after `<main class="pt-20">`, replacing the `<!-- SECTIONS WILL BE ADDED -->` comment:

```html
<!-- Hero Section -->
<section id="hero" class="relative min-h-[90vh] flex items-center overflow-hidden px-8">
  <!-- Background effects -->
  <div class="absolute inset-0 circuit-bg opacity-30"></div>
  <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-container/10 blur-[120px] rounded-full z-0"></div>
  <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-sand-gold/10 blur-[120px] rounded-full z-0"></div>

  <div class="max-w-screen-2xl mx-auto w-full grid md:grid-cols-2 gap-12 items-center relative z-10">
    <!-- Text Column (right in RTL) -->
    <div class="text-right space-y-8">
      <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-sand-gold/30 text-sand-gold text-sm font-bold">
        <span class="w-2 h-2 rounded-full bg-sand-gold animate-pulse"></span>
        نصنع المستقبل الرقمي لرؤية المملكة
      </div>
      <h1 class="text-5xl md:text-7xl font-headline font-bold leading-tight text-white">
        نبتكر حلولاً <span class="text-transparent bg-clip-text tech-gradient">تقنية ذكية</span> تدفع طموحك نحو ٢٠٣٠
      </h1>
      <p class="text-lg md:text-xl text-on-surface-variant max-w-2xl leading-relaxed">
        نطوّر المواقع، الأنظمة، التطبيقات، وحلول الذكاء الاصطناعي بأيدي وطنية وخبرات عالمية تضمن لشركتك التميز في قلب العاصمة الرياض.
      </p>
      <div class="flex flex-wrap gap-4 pt-4">
        <a href="contact.html" class="tech-gradient text-on-primary-fixed px-8 py-4 rounded-xl font-bold text-lg flex items-center gap-2 shadow-[0_10px_30px_rgba(0,242,255,0.2)] hover:translate-y-[-2px] transition-all">
          ابدأ مشروعك
          <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
        </a>
        <a href="#services" class="px-8 py-4 rounded-xl font-bold text-lg border border-outline-variant hover:bg-white/5 transition-all text-white">
          تصفح خدماتنا
        </a>
      </div>
    </div>

    <!-- Image Column (left in RTL) -->
    <div class="relative group hidden md:block">
      <div class="absolute inset-0 bg-sand-gold/5 rounded-full blur-3xl animate-pulse z-0"></div>
      <div class="relative glass-panel p-4 rounded-[2.5rem] border-white/5 shadow-2xl overflow-hidden">
        <img
          class="w-full h-[550px] object-cover rounded-[2rem] opacity-80 group-hover:opacity-100 transition-all duration-700"
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuC0Pz3BshD1sduTMLJuzPWYNqO_xtin3Mvk8i76X7qaP7T8_0fIqKuLZ8igjZDViNGc6FlA-NwAQHyUhrexYEXI9oQ7gKSjxb3THquslg3L7m8Ew1XjypTNy4NG0SQvQiMsyT5yE294Jk0KjzlXMAwLeanaSIBqJa2YHdr-p_13Mold54fOknZSFEE1sbSOxZWXaFOV0KNAUnkgUqZrg2_JB5ycSGl1XPgwWjRmxhvQK1ajV-SUv4fZxGfDFmHzRgktMoM04GZAXO5D"
          alt="أفق مدينة الرياض الحديث مع مركز الملك عبدالله المالي"
        />
        <!-- Floating badge: gold sparkle -->
        <div class="absolute top-12 right-12 glass-panel p-6 rounded-2xl border-sand-gold/30 shadow-2xl animate-slow-bounce z-20">
          <span class="material-symbols-outlined text-sand-gold text-4xl" aria-hidden="true">auto_awesome</span>
        </div>
        <!-- Floating badge: cyan data -->
        <div class="absolute bottom-12 left-12 glass-panel p-6 rounded-2xl border-primary-container/30 shadow-2xl z-20">
          <div class="flex gap-4 items-center">
            <div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center">
              <span class="material-symbols-outlined text-primary-container" aria-hidden="true">data_exploration</span>
            </div>
            <div>
              <div class="text-white font-bold">نمو رقمي وطني</div>
              <div class="text-xs text-on-surface-variant">تحليلات دقيقة للسوق المحلي</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Verify in browser**

- Hero fills ~90vh
- Gold badge pill visible at top of text
- Headline shows "تقنية ذكية" in cyan gradient
- Two CTA buttons visible
- Image visible on desktop with floating badges
- Image hidden on mobile
- Floating badges animate (slow bounce on gold sparkle)

- [ ] **Step 3: Commit**

```bash
git add index.html
git commit -m "feat: add hero section with dual CTA, floating badges, Riyadh skyline"
```

---

## Task 5: Stats Bar Section

**Files:**
- Modify: `index.html` — insert after hero section, before `</main>`

- [ ] **Step 1: Add stats section HTML**

Insert after the closing `</section>` of hero:

```html
<!-- Stats Bar -->
<section id="stats" class="py-24 px-8 bg-surface-container-low relative overflow-hidden">
  <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none">
    <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
      <path class="text-sand-gold" d="M0 100 L100 0 L100 100 Z" fill="currentColor"></path>
    </svg>
  </div>
  <div class="max-w-screen-2xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-8 relative z-10">
    <div class="text-center p-8 rounded-2xl bg-surface-container-highest/40 border border-white/5 hover:border-sand-gold/20 transition-all">
      <span class="material-symbols-outlined text-4xl text-sand-gold mb-4 block" aria-hidden="true">rocket_launch</span>
      <div class="text-4xl font-headline font-bold text-white mb-2">+٢٥٠</div>
      <div class="text-on-surface-variant">مشاريع في أنحاء المملكة</div>
    </div>
    <div class="text-center p-8 rounded-2xl bg-surface-container-highest/40 border border-white/5 hover:border-sand-gold/20 transition-all">
      <span class="material-symbols-outlined text-4xl text-primary-container mb-4 block" aria-hidden="true">domain</span>
      <div class="text-4xl font-headline font-bold text-white mb-2">+١٥</div>
      <div class="text-on-surface-variant">قطاعاً حيوياً</div>
    </div>
    <div class="text-center p-8 rounded-2xl bg-surface-container-highest/40 border border-white/5 hover:border-sand-gold/20 transition-all">
      <span class="material-symbols-outlined text-4xl text-sand-gold mb-4 block" aria-hidden="true">verified</span>
      <div class="text-4xl font-headline font-bold text-white mb-2">٪٩٩</div>
      <div class="text-on-surface-variant">نسبة رضا الشركاء</div>
    </div>
    <div class="text-center p-8 rounded-2xl bg-surface-container-highest/40 border border-white/5 hover:border-sand-gold/20 transition-all">
      <span class="material-symbols-outlined text-4xl text-primary-container mb-4 block" aria-hidden="true">schedule</span>
      <div class="text-4xl font-headline font-bold text-white mb-2">+١٠</div>
      <div class="text-on-surface-variant">سنوات خبرة في السوق</div>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Verify in browser**

- 4 stat cards in a row on desktop, 2x2 on mobile
- Gold triangle accent barely visible in background
- Icons alternate gold/cyan colors
- Arabic numerals display correctly

- [ ] **Step 3: Commit**

```bash
git add index.html
git commit -m "feat: add stats bar section with 4 stat cards"
```

---

## Task 6: Services Overview Section

**Files:**
- Modify: `index.html` — insert after stats section

- [ ] **Step 1: Add services section HTML**

Insert after stats section:

```html
<!-- Services Overview -->
<section id="services" class="py-32 px-8">
  <div class="max-w-screen-2xl mx-auto">
    <div class="text-center mb-20 space-y-4">
      <h2 class="text-4xl md:text-5xl font-headline font-bold text-white">خدماتنا الرقمية المتكاملة</h2>
      <p class="text-on-surface-variant max-w-2xl mx-auto">نقدم حزمة من الخدمات التقنية التي تضمن تحولاً رقمياً آمناً وفق أعلى المعايير الوطنية.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <!-- 1. Web Platforms (large) -->
      <div class="md:col-span-2 glass-panel p-10 rounded-[2rem] hover:border-primary-container/40 transition-all duration-500 group overflow-hidden relative">
        <div class="relative z-10">
          <span class="material-symbols-outlined text-5xl text-primary-container mb-6 block" aria-hidden="true">web</span>
          <h3 class="text-2xl font-bold text-white mb-4">برمجة المواقع والمنصات الحكومية</h3>
          <p class="text-on-surface-variant mb-8 leading-relaxed">بناء منصات إلكترونية متقدمة متوافقة مع متطلبات هيئة الحكومة الرقمية وتجربة مستخدم متميزة.</p>
          <a href="services.html" class="text-primary-container flex items-center gap-2 group-hover:gap-4 transition-all font-bold">
            استكشف المزيد
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
          </a>
        </div>
        <div class="absolute -bottom-10 -left-10 opacity-10 group-hover:opacity-20 transition-opacity">
          <span class="material-symbols-outlined text-[12rem]" aria-hidden="true">code</span>
        </div>
      </div>
      <!-- 2. Mobile Apps -->
      <div class="glass-panel p-8 rounded-[2rem] hover:border-sand-gold/40 transition-all duration-500 text-right">
        <span class="material-symbols-outlined text-4xl text-sand-gold mb-6 block" aria-hidden="true">smartphone</span>
        <h3 class="text-xl font-bold text-white mb-3">تطبيقات الجوال</h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">تطبيقات ذكية تعزز تواصلك مع جمهورك في المملكة بسلاسة وأمان.</p>
      </div>
      <!-- 3. AI -->
      <div class="glass-panel p-8 rounded-[2rem] hover:border-primary-container/40 transition-all duration-500 text-right">
        <span class="material-symbols-outlined text-4xl text-primary-container mb-6 block" aria-hidden="true">precision_manufacturing</span>
        <h3 class="text-xl font-bold text-white mb-3">الذكاء الاصطناعي</h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">دمج تقنيات الـ AI لرفع كفاءة المنشآت الوطنية وتحليل البيانات الضخمة.</p>
      </div>
      <!-- 4. E-commerce -->
      <div class="glass-panel p-8 rounded-[2rem] hover:border-sand-gold/40 transition-all duration-500 text-right">
        <span class="material-symbols-outlined text-4xl text-sand-gold mb-6 block" aria-hidden="true">shopping_cart</span>
        <h3 class="text-xl font-bold text-white mb-3">المتاجر الإلكترونية</h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">حلول تجارة إلكترونية متكاملة مع بوابات دفع محلية وشركات شحن وطنية.</p>
      </div>
      <!-- 5. ERP (large) -->
      <div class="md:col-span-2 glass-panel p-10 rounded-[2rem] hover:border-primary-container/40 transition-all duration-500 group relative overflow-hidden">
        <div class="relative z-10">
          <span class="material-symbols-outlined text-5xl text-primary-container mb-6 block" aria-hidden="true">settings_suggest</span>
          <h3 class="text-2xl font-bold text-white mb-4">أنظمة إدارة الموارد (ERP)</h3>
          <p class="text-on-surface-variant mb-8 leading-relaxed">أنظمة مخصصة تتماشى مع القوانين والأنظمة المالية السعودية بدقة متناهية.</p>
          <a href="services.html" class="text-primary-container flex items-center gap-2 group-hover:gap-4 transition-all font-bold">
            استكشف المزيد
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
          </a>
        </div>
        <div class="absolute -bottom-10 -left-10 opacity-10 group-hover:opacity-20 transition-opacity">
          <span class="material-symbols-outlined text-[12rem]" aria-hidden="true">database</span>
        </div>
      </div>
      <!-- 6. Digital Marketing -->
      <div class="glass-panel p-8 rounded-[2rem] hover:border-sand-gold/40 transition-all duration-500 text-right">
        <span class="material-symbols-outlined text-4xl text-sand-gold mb-6 block" aria-hidden="true">search_insights</span>
        <h3 class="text-xl font-bold text-white mb-3">التسويق الرقمي</h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">إدارة حملاتك الرقمية بذكاء لاستهداف الجمهور المحلي في كافة مناطق المملكة.</p>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Verify in browser**

- Bento grid: Row 1 = [2-col][1-col][1-col], Row 2 = [1-col][2-col][1-col]
- Glass panel effect on cards
- Large cards have faint background icons
- Hover changes border color (cyan or gold alternating)
- Single column on mobile

- [ ] **Step 3: Commit**

```bash
git add index.html
git commit -m "feat: add services overview bento grid with 6 service cards"
```

---

## Task 7: Industries + Process Sections

**Files:**
- Modify: `index.html` — insert after services section

- [ ] **Step 1: Add industries section HTML**

```html
<!-- Industries / Sectors -->
<section id="industries" class="py-32 px-8 bg-surface-container-low/50">
  <div class="max-w-screen-2xl mx-auto">
    <div class="text-center mb-20 space-y-4">
      <h2 class="text-4xl md:text-5xl font-headline font-bold text-white">القطاعات التي نخدمها</h2>
      <p class="text-on-surface-variant max-w-2xl mx-auto">حلول رقمية مصممة لتلائم طبيعة كل قطاع في المملكة</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-primary-container/30 hover:translate-y-[-4px] transition-all">
        <div class="w-14 h-14 rounded-xl bg-primary-container/10 flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">domain</span>
        </div>
        <span class="text-sm font-bold text-white">الشركات</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-sand-gold/30 hover:translate-y-[-4px] transition-all">
        <div class="w-14 h-14 rounded-xl bg-sand-gold/10 flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">account_balance</span>
        </div>
        <span class="text-sm font-bold text-white">الجهات الحكومية</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-primary-container/30 hover:translate-y-[-4px] transition-all">
        <div class="w-14 h-14 rounded-xl bg-primary-container/10 flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">shopping_cart</span>
        </div>
        <span class="text-sm font-bold text-white">التجارة الإلكترونية</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-sand-gold/30 hover:translate-y-[-4px] transition-all">
        <div class="w-14 h-14 rounded-xl bg-sand-gold/10 flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">local_hospital</span>
        </div>
        <span class="text-sm font-bold text-white">العيادات والمراكز الطبية</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-primary-container/30 hover:translate-y-[-4px] transition-all">
        <div class="w-14 h-14 rounded-xl bg-primary-container/10 flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">school</span>
        </div>
        <span class="text-sm font-bold text-white">التعليم</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-sand-gold/30 hover:translate-y-[-4px] transition-all">
        <div class="w-14 h-14 rounded-xl bg-sand-gold/10 flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">apartment</span>
        </div>
        <span class="text-sm font-bold text-white">العقارات</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-primary-container/30 hover:translate-y-[-4px] transition-all">
        <div class="w-14 h-14 rounded-xl bg-primary-container/10 flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">restaurant</span>
        </div>
        <span class="text-sm font-bold text-white">المطاعم والكافيهات</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-sand-gold/30 hover:translate-y-[-4px] transition-all">
        <div class="w-14 h-14 rounded-xl bg-sand-gold/10 flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">local_shipping</span>
        </div>
        <span class="text-sm font-bold text-white">الخدمات اللوجستية</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-primary-container/30 hover:translate-y-[-4px] transition-all">
        <div class="w-14 h-14 rounded-xl bg-primary-container/10 flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">corporate_fare</span>
        </div>
        <span class="text-sm font-bold text-white">المؤسسات</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-sand-gold/30 hover:translate-y-[-4px] transition-all">
        <div class="w-14 h-14 rounded-xl bg-sand-gold/10 flex items-center justify-center mx-auto mb-4">
          <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">support_agent</span>
        </div>
        <span class="text-sm font-bold text-white">الاستشارات</span>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Add process section HTML**

```html
<!-- Process / Workflow -->
<section id="process" class="py-32 px-8">
  <div class="max-w-screen-2xl mx-auto">
    <div class="text-center mb-20 space-y-4">
      <h2 class="text-4xl md:text-5xl font-headline font-bold text-white">كيف ننفذ مشروعك؟</h2>
      <p class="text-on-surface-variant max-w-2xl mx-auto">منهجية عمل واضحة تضمن جودة النتائج في كل مرحلة</p>
    </div>

    <!-- Desktop: Horizontal Timeline -->
    <div class="hidden lg:block">
      <!-- Connecting line -->
      <div class="relative mx-auto max-w-5xl">
        <div class="absolute top-6 left-0 right-0 h-[2px] bg-gradient-to-l from-primary-container via-primary-container/50 to-sand-gold z-0"></div>
        <div class="grid grid-cols-6 gap-4 relative z-10">
          <!-- Step 1 -->
          <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-primary-container/10 border border-primary-container/30 flex items-center justify-center text-primary-container font-bold text-lg mb-4">١</div>
            <span class="material-symbols-outlined text-primary-container text-2xl mb-2" aria-hidden="true">search</span>
            <h4 class="text-sm font-bold text-white mb-1">دراسة الاحتياج</h4>
            <p class="text-xs text-on-surface-variant">فهم متطلبات المشروع وأهدافه</p>
          </div>
          <!-- Step 2 -->
          <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-primary-container/10 border border-primary-container/30 flex items-center justify-center text-primary-container font-bold text-lg mb-4">٢</div>
            <span class="material-symbols-outlined text-primary-container text-2xl mb-2" aria-hidden="true">analytics</span>
            <h4 class="text-sm font-bold text-white mb-1">التحليل والتخطيط</h4>
            <p class="text-xs text-on-surface-variant">وضع خارطة الطريق التقنية</p>
          </div>
          <!-- Step 3 -->
          <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-primary-container/10 border border-primary-container/30 flex items-center justify-center text-primary-container font-bold text-lg mb-4">٣</div>
            <span class="material-symbols-outlined text-primary-container text-2xl mb-2" aria-hidden="true">design_services</span>
            <h4 class="text-sm font-bold text-white mb-1">تصميم تجربة المستخدم</h4>
            <p class="text-xs text-on-surface-variant">واجهات جذابة وسهلة الاستخدام</p>
          </div>
          <!-- Step 4 -->
          <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-sand-gold/10 border border-sand-gold/30 flex items-center justify-center text-sand-gold font-bold text-lg mb-4">٤</div>
            <span class="material-symbols-outlined text-sand-gold text-2xl mb-2" aria-hidden="true">code</span>
            <h4 class="text-sm font-bold text-white mb-1">التطوير والتنفيذ</h4>
            <p class="text-xs text-on-surface-variant">بناء المنتج بأحدث التقنيات</p>
          </div>
          <!-- Step 5 -->
          <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-sand-gold/10 border border-sand-gold/30 flex items-center justify-center text-sand-gold font-bold text-lg mb-4">٥</div>
            <span class="material-symbols-outlined text-sand-gold text-2xl mb-2" aria-hidden="true">verified_user</span>
            <h4 class="text-sm font-bold text-white mb-1">الاختبار وضمان الجودة</h4>
            <p class="text-xs text-on-surface-variant">فحص شامل قبل التسليم</p>
          </div>
          <!-- Step 6 -->
          <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-sand-gold/10 border border-sand-gold/30 flex items-center justify-center text-sand-gold font-bold text-lg mb-4">٦</div>
            <span class="material-symbols-outlined text-sand-gold text-2xl mb-2" aria-hidden="true">rocket_launch</span>
            <h4 class="text-sm font-bold text-white mb-1">الإطلاق والدعم المستمر</h4>
            <p class="text-xs text-on-surface-variant">متابعة ودعم بعد الإطلاق</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile: Vertical Timeline -->
    <div class="lg:hidden space-y-8 max-w-md mx-auto">
      <div class="flex gap-6 items-start">
        <div class="flex flex-col items-center flex-shrink-0">
          <div class="w-12 h-12 rounded-full bg-primary-container/10 border border-primary-container/30 flex items-center justify-center text-primary-container font-bold">١</div>
          <div class="w-[2px] h-16 bg-primary-container/20 mt-2"></div>
        </div>
        <div class="pt-2">
          <h4 class="font-bold text-white mb-1">دراسة الاحتياج</h4>
          <p class="text-sm text-on-surface-variant">فهم متطلبات المشروع وأهدافه</p>
        </div>
      </div>
      <div class="flex gap-6 items-start">
        <div class="flex flex-col items-center flex-shrink-0">
          <div class="w-12 h-12 rounded-full bg-primary-container/10 border border-primary-container/30 flex items-center justify-center text-primary-container font-bold">٢</div>
          <div class="w-[2px] h-16 bg-primary-container/20 mt-2"></div>
        </div>
        <div class="pt-2">
          <h4 class="font-bold text-white mb-1">التحليل والتخطيط</h4>
          <p class="text-sm text-on-surface-variant">وضع خارطة الطريق التقنية</p>
        </div>
      </div>
      <div class="flex gap-6 items-start">
        <div class="flex flex-col items-center flex-shrink-0">
          <div class="w-12 h-12 rounded-full bg-primary-container/10 border border-primary-container/30 flex items-center justify-center text-primary-container font-bold">٣</div>
          <div class="w-[2px] h-16 bg-gradient-to-b from-primary-container/20 to-sand-gold/20 mt-2"></div>
        </div>
        <div class="pt-2">
          <h4 class="font-bold text-white mb-1">تصميم تجربة المستخدم</h4>
          <p class="text-sm text-on-surface-variant">واجهات جذابة وسهلة الاستخدام</p>
        </div>
      </div>
      <div class="flex gap-6 items-start">
        <div class="flex flex-col items-center flex-shrink-0">
          <div class="w-12 h-12 rounded-full bg-sand-gold/10 border border-sand-gold/30 flex items-center justify-center text-sand-gold font-bold">٤</div>
          <div class="w-[2px] h-16 bg-sand-gold/20 mt-2"></div>
        </div>
        <div class="pt-2">
          <h4 class="font-bold text-white mb-1">التطوير والتنفيذ</h4>
          <p class="text-sm text-on-surface-variant">بناء المنتج بأحدث التقنيات</p>
        </div>
      </div>
      <div class="flex gap-6 items-start">
        <div class="flex flex-col items-center flex-shrink-0">
          <div class="w-12 h-12 rounded-full bg-sand-gold/10 border border-sand-gold/30 flex items-center justify-center text-sand-gold font-bold">٥</div>
          <div class="w-[2px] h-16 bg-sand-gold/20 mt-2"></div>
        </div>
        <div class="pt-2">
          <h4 class="font-bold text-white mb-1">الاختبار وضمان الجودة</h4>
          <p class="text-sm text-on-surface-variant">فحص شامل قبل التسليم</p>
        </div>
      </div>
      <div class="flex gap-6 items-start">
        <div class="flex flex-col items-center flex-shrink-0">
          <div class="w-12 h-12 rounded-full bg-sand-gold/10 border border-sand-gold/30 flex items-center justify-center text-sand-gold font-bold">٦</div>
        </div>
        <div class="pt-2">
          <h4 class="font-bold text-white mb-1">الإطلاق والدعم المستمر</h4>
          <p class="text-sm text-on-surface-variant">متابعة ودعم بعد الإطلاق</p>
        </div>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 3: Verify in browser**

Industries:
- 5 columns on desktop, 2 on mobile
- 10 sector cards, alternating cyan/gold icons
- Cards lift on hover

Process:
- Horizontal timeline on desktop with gradient connecting line
- Steps 1-3 cyan, 4-6 gold
- Vertical timeline on mobile
- Arabic numerals in circles

- [ ] **Step 4: Commit**

```bash
git add index.html
git commit -m "feat: add industries grid (10 sectors) and process timeline (6 steps)"
```

---

## Task 8: Saudi Market Value Section

**Files:**
- Modify: `index.html` — insert after process section

- [ ] **Step 1: Add saudi market value section HTML**

```html
<!-- Saudi Market Value -->
<section id="value" class="py-32 px-8 bg-surface-container-low/50 relative">
  <div class="absolute inset-0 sadu-pattern opacity-10 pointer-events-none"></div>
  <div class="max-w-screen-2xl mx-auto flex flex-col lg:flex-row gap-16 items-center relative z-10">
    <!-- Image Column (left in RTL = appears on left) -->
    <div class="w-full lg:w-1/2 relative order-2 lg:order-1">
      <div class="absolute -inset-4 border border-sand-gold/20 rounded-[3rem] -z-10 translate-x-4 translate-y-4"></div>
      <img
        class="rounded-[3rem] shadow-2xl border-4 border-white/5 object-cover w-full h-[600px]"
        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBy5uhrwQr6I7NNkaSKXwgs8DeaHfZPGC3Umaq9dyjlwDiyiSDxVSMG5-s_nSzKSLP6Vhg3BldK5lFz05j6M64-QLk7q8fWpp39aX7PiDsgRgYVAHG_DlL07khPxBo3Ec2MU0_YvipbgJNcL_Ogl9mG99_Sl69WdicPgsLUXACgYsT3-E9SxhNWyQuw4MIsbL83sknFoYvghxTLkE6sdyrdOt4n5sIHtEi1dnIcUvq_wKDT5DBvrtqzekAz2Q1Brnw795N8ah9KMQcd"
        alt="بيئة عمل تقنية حديثة في الرياض"
      />
    </div>
    <!-- Text Column (right in RTL = appears on right) -->
    <div class="w-full lg:w-1/2 space-y-10 text-right order-1 lg:order-2">
      <div class="space-y-4">
        <h2 class="text-4xl font-headline font-bold text-white">القيمة التي نضيفها لأعمالك في المملكة</h2>
        <div class="h-1 w-24 bg-sand-gold rounded-full"></div>
      </div>
      <div class="space-y-0">
        <!-- Value 1 -->
        <div class="flex items-start gap-4 py-6 border-b border-white/5 hover:translate-x-[-4px] transition-transform">
          <div class="w-12 h-12 rounded-xl bg-primary-container/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-primary-container" aria-hidden="true">rocket_launch</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-white mb-1">تسريع التحول الرقمي</h4>
            <p class="text-sm text-on-surface-variant">نختصر مراحل التحول بحلول جاهزة ومُخصصة لطبيعة عملك</p>
          </div>
        </div>
        <!-- Value 2 -->
        <div class="flex items-start gap-4 py-6 border-b border-white/5 hover:translate-x-[-4px] transition-transform">
          <div class="w-12 h-12 rounded-xl bg-sand-gold/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-sand-gold" aria-hidden="true">settings</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-white mb-1">تحسين كفاءة التشغيل</h4>
            <p class="text-sm text-on-surface-variant">أتمتة العمليات وتقليل التكاليف التشغيلية بحلول ذكية</p>
          </div>
        </div>
        <!-- Value 3 -->
        <div class="flex items-start gap-4 py-6 border-b border-white/5 hover:translate-x-[-4px] transition-transform">
          <div class="w-12 h-12 rounded-xl bg-primary-container/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-primary-container" aria-hidden="true">star</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-white mb-1">رفع جودة تجربة العميل</h4>
            <p class="text-sm text-on-surface-variant">واجهات وتطبيقات تُبهر عملاءك وتزيد ولاءهم لعلامتك</p>
          </div>
        </div>
        <!-- Value 4 -->
        <div class="flex items-start gap-4 py-6 border-b border-white/5 hover:translate-x-[-4px] transition-transform">
          <div class="w-12 h-12 rounded-xl bg-sand-gold/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-sand-gold" aria-hidden="true">trending_up</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-white mb-1">بناء أنظمة قابلة للنمو</h4>
            <p class="text-sm text-on-surface-variant">بنية تحتية مرنة تنمو مع توسع أعمالك دون إعادة بناء</p>
          </div>
        </div>
        <!-- Value 5 -->
        <div class="flex items-start gap-4 py-6 hover:translate-x-[-4px] transition-transform">
          <div class="w-12 h-12 rounded-xl bg-primary-container/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-primary-container" aria-hidden="true">domain</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-white mb-1">دعم الهوية الرقمية للمنشأة</h4>
            <p class="text-sm text-on-surface-variant">حضور رقمي احترافي يعكس مكانتك في السوق السعودي</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Verify in browser**

- 2-column layout on desktop (text right, image left in RTL)
- Gold underline under heading
- 5 value items with alternating cyan/gold icons
- Divider lines between items
- Image has gold offset border behind it
- Single column on mobile (text first, image below)

- [ ] **Step 3: Commit**

```bash
git add index.html
git commit -m "feat: add Saudi market value section with 5 business value items"
```

---

## Task 9: Trust Section

**Files:**
- Modify: `index.html` — insert after value section

- [ ] **Step 1: Add trust section HTML**

```html
<!-- Trust / Why Trust Us -->
<section id="trust" class="py-32 px-8">
  <div class="max-w-screen-2xl mx-auto">
    <div class="text-center mb-20 space-y-4">
      <h2 class="text-4xl md:text-5xl font-headline font-bold text-white">لماذا يثق بنا عملاؤنا؟</h2>
      <p class="text-on-surface-variant max-w-2xl mx-auto">التزامات حقيقية نقدمها لكل شريك نعمل معه</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Row 1: Cyan themed -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all">
        <div class="w-14 h-14 rounded-2xl bg-primary-container/10 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">handshake</span>
        </div>
        <h3 class="text-lg font-bold text-white mb-3">شفافية كاملة في كل مرحلة</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">نشارك العميل في كل قرار تقني ونوفر تقارير تقدم دورية واضحة تضمن الاطلاع الكامل.</p>
      </div>
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all">
        <div class="w-14 h-14 rounded-2xl bg-primary-container/10 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">lock</span>
        </div>
        <h3 class="text-lg font-bold text-white mb-3">سرية تامة لبياناتك ومشروعك</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">نلتزم باتفاقيات عدم إفشاء صارمة وأعلى معايير حماية المعلومات والبيانات.</p>
      </div>
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all">
        <div class="w-14 h-14 rounded-2xl bg-primary-container/10 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">forum</span>
        </div>
        <h3 class="text-lg font-bold text-white mb-3">تواصل مستمر مع فريق العمل</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">مدير مشروع مخصص وقنوات تواصل مفتوحة طوال فترة التنفيذ لضمان سلاسة العمل.</p>
      </div>
      <!-- Row 2: Gold themed -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-sand-gold/20 transition-all">
        <div class="w-14 h-14 rounded-2xl bg-sand-gold/10 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">bolt</span>
        </div>
        <h3 class="text-lg font-bold text-white mb-3">التزام بالمواعيد المتفق عليها</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">نعمل بجداول زمنية واقعية ونلتزم بتسليم كل مرحلة في موعدها المحدد.</p>
      </div>
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-sand-gold/20 transition-all">
        <div class="w-14 h-14 rounded-2xl bg-sand-gold/10 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">verified_user</span>
        </div>
        <h3 class="text-lg font-bold text-white mb-3">ضمان جودة شامل قبل التسليم</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">اختبارات دقيقة متعددة المراحل تضمن خلو المنتج من الأخطاء التقنية.</p>
      </div>
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-sand-gold/20 transition-all">
        <div class="w-14 h-14 rounded-2xl bg-sand-gold/10 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">support_agent</span>
        </div>
        <h3 class="text-lg font-bold text-white mb-3">دعم فني بعد الإطلاق</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">فريق دعم متخصص لضمان استمرارية عمل منتجك بكفاءة تامة بعد التسليم.</p>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Verify in browser**

- 3 columns on desktop, single column on mobile
- 6 trust cards total
- First 3 cards: cyan icon backgrounds
- Last 3 cards: gold icon backgrounds
- Cards have rounded corners and subtle hover border

- [ ] **Step 3: Commit**

```bash
git add index.html
git commit -m "feat: add trust section with 6 commitment cards (no fake testimonials)"
```

---

## Task 10: FAQ Section

**Files:**
- Modify: `index.html` — insert after trust section

- [ ] **Step 1: Add FAQ section HTML**

```html
<!-- FAQ Section -->
<section id="faq" class="py-32 px-8 bg-surface-container-low">
  <div class="max-w-3xl mx-auto">
    <div class="text-center mb-20 space-y-4">
      <h2 class="text-4xl md:text-5xl font-headline font-bold text-white">الأسئلة الشائعة</h2>
      <p class="text-on-surface-variant">إجابات واضحة على أكثر الاستفسارات التي تهم عملاءنا</p>
    </div>
    <div class="space-y-0">
      <!-- FAQ 1 -->
      <div class="faq-item border-b border-white/5">
        <button id="faq-btn-1" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-1">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">هل تقدمون حلولاً مخصصة حسب النشاط؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-1" class="faq-answer" role="region" aria-labelledby="faq-btn-1">
          <p class="pb-6 text-on-surface-variant leading-relaxed">نعم، كل مشروع يبدأ بدراسة تفصيلية لطبيعة النشاط والجمهور المستهدف. نصمم حلولاً مخصصة بالكامل تراعي خصوصية كل قطاع ومتطلباته التقنية والتشغيلية في السوق السعودي.</p>
        </div>
      </div>
      <!-- FAQ 2 -->
      <div class="faq-item border-b border-white/5">
        <button id="faq-btn-2" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-2">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">هل يمكن تطوير نظام داخلي خاص بالشركة؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-2" class="faq-answer" role="region" aria-labelledby="faq-btn-2">
          <p class="pb-6 text-on-surface-variant leading-relaxed">بالتأكيد. نصمم ونطور أنظمة داخلية متكاملة مبنية من الصفر حسب متطلبات شركتك وطبيعة عملياتها، سواء كانت أنظمة إدارة موارد أو أنظمة تشغيلية متخصصة.</p>
        </div>
      </div>
      <!-- FAQ 3 -->
      <div class="faq-item border-b border-white/5">
        <button id="faq-btn-3" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-3">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">هل توفرون دعمًا بعد الإطلاق؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-3" class="faq-answer" role="region" aria-labelledby="faq-btn-3">
          <p class="pb-6 text-on-surface-variant leading-relaxed">نقدم باقات دعم فني وصيانة دورية تضمن استمرارية عمل المنتج بكفاءة عالية. يشمل ذلك مراقبة الأداء، تحديثات الأمان، وإصلاح أي مشكلات فورياً.</p>
        </div>
      </div>
      <!-- FAQ 4 -->
      <div class="faq-item border-b border-white/5">
        <button id="faq-btn-4" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-4">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">هل يمكن ربط النظام مع خدمات أخرى؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-4" class="faq-answer" role="region" aria-labelledby="faq-btn-4">
          <p class="pb-6 text-on-surface-variant leading-relaxed">نبني أنظمة مفتوحة قابلة للتكامل مع بوابات الدفع المحلية، شركات الشحن، الأنظمة الحكومية، وأي خدمات خارجية عبر واجهات برمجة التطبيقات (APIs).</p>
        </div>
      </div>
      <!-- FAQ 5 -->
      <div class="faq-item border-b border-white/5">
        <button id="faq-btn-5" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-5">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">هل تقدمون تطبيقات iOS وAndroid؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-5" class="faq-answer" role="region" aria-labelledby="faq-btn-5">
          <p class="pb-6 text-on-surface-variant leading-relaxed">نطور تطبيقات أصلية ومتعددة المنصات بأحدث التقنيات، مع مراعاة تجربة المستخدم العربي وتوافق كامل مع متطلبات متاجر التطبيقات.</p>
        </div>
      </div>
      <!-- FAQ 6 -->
      <div class="faq-item">
        <button id="faq-btn-6" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-6">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">كيف تبدأون تنفيذ المشروع؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-6" class="faq-answer" role="region" aria-labelledby="faq-btn-6">
          <p class="pb-6 text-on-surface-variant leading-relaxed">نبدأ بجلسة استشارية مجانية لفهم احتياجاتك بالتفصيل، ثم نقدم عرضاً تفصيلياً يتضمن الحل المقترح والجدول الزمني والتكلفة، وبعد الموافقة نبدأ التنفيذ فوراً.</p>
        </div>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Verify in browser**

- 6 FAQ items stacked vertically
- Clicking a question expands its answer (smooth transition)
- Clicking another question closes the first and opens the new one
- Expand icon rotates 180deg when open
- Question text highlights cyan on hover

- [ ] **Step 3: Commit**

```bash
git add index.html
git commit -m "feat: add FAQ accordion section with 6 questions"
```

---

## Task 11: CTA Block + Footer

**Files:**
- Modify: `index.html` — insert CTA after FAQ, then footer after `</main>`

- [ ] **Step 1: Add CTA block HTML**

Replace the existing `</main>` tag with the following CTA section + closing `</main>` tag + footer. The result is: FAQ section → CTA section → `</main>` → footer → `</body>`.

Insert the CTA immediately before `</main>`, replacing `</main>` with:

```html
<!-- CTA Block -->
<section id="cta" class="px-8 py-16 mb-16">
  <div class="max-w-7xl mx-auto">
    <div class="relative rounded-[3rem] bg-gradient-to-br from-surface-container-high to-surface-dim p-12 md:p-24 overflow-hidden border border-white/5 shadow-2xl">
      <!-- Background effects -->
      <div class="absolute inset-0 circuit-bg opacity-20 pointer-events-none"></div>
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-container/5 blur-[120px] rounded-full"></div>
      <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-sand-gold/5 blur-[120px] rounded-full"></div>
      <!-- Content -->
      <div class="relative z-10 text-center max-w-3xl mx-auto">
        <h2 class="text-4xl md:text-5xl font-headline font-bold mb-8 leading-tight text-white">جاهز لبناء حل رقمي احترافي يخدم أعمالك في المملكة؟</h2>
        <p class="text-on-surface-variant text-lg mb-12">فريقنا التقني مستعد لتحويل تحدياتك إلى فرص نمو حقيقية. ابدأ اليوم.</p>
        <div class="flex flex-col md:flex-row justify-center gap-6">
          <a href="contact.html" class="tech-gradient text-on-primary-fixed px-12 py-5 rounded-2xl font-bold text-lg hover:shadow-[0_0_50px_rgba(0,242,255,0.4)] transition-all active:scale-95">تحدث معنا الآن</a>
          <a href="contact.html" class="bg-white/5 border border-white/10 text-white px-12 py-5 rounded-2xl font-bold text-lg hover:bg-white/10 transition-all">اطلب عرض سعر</a>
        </div>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Add footer HTML after `</main>`**

The CTA section above ends inside `<main>`. The `</main>` closing tag should come right after it, then the footer. The full sequence at the bottom of the file should be: `...CTA section...</section></main><footer>...</footer><script src="js/main.js"></script></body></html>`. Add this footer immediately after `</main>`:

```html
</main>

<!-- Footer -->
<footer class="w-full rounded-t-[3rem] bg-[#0b1229] border-t border-white/5 shadow-[0_-10px_40px_rgba(0,0,0,0.5)]">
  <div class="sadu-pattern">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-12 px-12 py-20 text-right max-w-screen-2xl mx-auto">
      <!-- Brand Column -->
      <div class="space-y-6">
        <div class="text-4xl font-black text-white">رکال</div>
        <p class="text-sm leading-relaxed text-white/50">شركة سعودية رائدة متخصصة في حلول التحول الرقمي والهندسة البرمجية المتقدمة. ملتزمون بتمكين رؤية المملكة ٢٠٣٠ من خلال التميز التقني.</p>
        <div class="flex gap-4 justify-end">
          <a href="#" class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-white/60 hover:bg-primary-container hover:text-on-primary-container transition-all" aria-label="الموقع">
            <span class="material-symbols-outlined" aria-hidden="true">public</span>
          </a>
          <a href="#" class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-white/60 hover:bg-primary-container hover:text-on-primary-container transition-all" aria-label="البريد">
            <span class="material-symbols-outlined" aria-hidden="true">alternate_email</span>
          </a>
          <a href="#" class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-white/60 hover:bg-primary-container hover:text-on-primary-container transition-all" aria-label="الهاتف">
            <span class="material-symbols-outlined" aria-hidden="true">call</span>
          </a>
        </div>
      </div>
      <!-- Quick Links -->
      <div>
        <h4 class="text-white font-bold mb-8 text-xl">روابط سريعة</h4>
        <ul class="space-y-5 text-sm">
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="index.html">الرئيسية</a></li>
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="about.html">من نحن</a></li>
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="services.html">خدماتنا</a></li>
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="#">الحلول</a></li>
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="contact.html">تواصل معنا</a></li>
        </ul>
      </div>
      <!-- Services Links -->
      <div>
        <h4 class="text-white font-bold mb-8 text-xl">خدماتنا</h4>
        <ul class="space-y-5 text-sm">
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="services.html">تطوير المواقع</a></li>
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="services.html">تطبيقات الجوال</a></li>
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="services.html">أنظمة ERP</a></li>
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="services.html">المتاجر الإلكترونية</a></li>
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="services.html">الذكاء الاصطناعي</a></li>
          <li><a class="text-white/60 hover:text-[#00f2ff] hover:mr-2 inline-block transition-all duration-300" href="services.html">التسويق الرقمي</a></li>
        </ul>
      </div>
      <!-- Newsletter -->
      <div class="space-y-6">
        <h4 class="text-white font-bold text-xl">النشرة التقنية</h4>
        <p class="text-sm text-white/60 leading-relaxed">ابقَ على اطلاع بأحدث تقنيات التحول الرقمي في المملكة.</p>
        <form class="flex flex-col gap-4" onsubmit="return false;">
          <input class="bg-surface-container-highest border border-white/5 rounded-2xl px-5 py-4 text-white placeholder:text-white/20 focus:ring-2 focus:ring-primary-container outline-none transition-all" placeholder="البريد الإلكتروني المؤسسي" type="email"/>
          <button type="button" class="bg-primary-container text-on-primary-container py-4 rounded-2xl font-bold hover:shadow-[0_0_25px_rgba(0,242,255,0.25)] transition-all">اشترك الآن</button>
        </form>
      </div>
    </div>
    <!-- Bottom Bar -->
    <div class="border-t border-white/5 py-10 px-12 max-w-screen-2xl mx-auto flex flex-col md:flex-row-reverse justify-between items-center gap-6 text-xs text-white/30 uppercase tracking-widest font-bold">
      <p>جميع الحقوق محفوظة لشركة ركال ٢٠٢٤ © المملكة العربية السعودية</p>
      <div class="flex gap-8">
        <a class="hover:text-[#00f2ff] transition-colors" href="#">الخصوصية</a>
        <a class="hover:text-[#00f2ff] transition-colors" href="#">الشروط</a>
        <a class="hover:text-[#00f2ff] transition-colors" href="#">الكوكيز</a>
      </div>
    </div>
  </div>
</footer>
```

- [ ] **Step 3: Verify there is exactly one `</main>` tag**

Search the file for `</main>`. There must be exactly one, positioned after the CTA section and before the footer. If Task 3's original `</main>` is still present, remove the duplicate.

- [ ] **Step 4: Verify in browser**

CTA:
- Rounded card with gradient background
- Circuit pattern overlay visible
- Cyan and gold blur orbs in corners
- Two buttons centered
- Responsive on mobile (buttons stack)

Footer:
- Rounded top corners
- 4-column grid (brand, links, services, newsletter)
- Social icons hover to cyan
- Links hover to cyan with slight slide
- Newsletter input and button present
- Bottom bar with copyright and legal links
- Single column on mobile

- [ ] **Step 5: Commit**

```bash
git add index.html
git commit -m "feat: add CTA block and footer with newsletter, links, social icons"
```

---

## Task 12: Final QA Pass

**Files:**
- May modify: `index.html`, `css/styles.css`, `js/main.js`

This task is a full verification pass. Open `index.html` in a browser and check every item below. Fix any issues found.

- [ ] **Step 1: Desktop verification (1024px+ viewport)**

Check each section top to bottom:
- [ ] Navbar: logo right, links center, CTA left, sticky on scroll, bg darkens on scroll
- [ ] Hero: 2-column, gradient text, floating badges animate, both CTAs visible
- [ ] Stats: 4 columns, gold/cyan alternating icons, Arabic numerals
- [ ] Services: bento grid (2+1+1 / 1+2+1), glass effect, hover borders
- [ ] Industries: 5 columns × 2 rows = 10 cards, hover lift
- [ ] Process: horizontal timeline, gradient line, 6 steps, cyan→gold transition
- [ ] Value: 2-column, image with gold offset border, 5 items with dividers
- [ ] Trust: 3 columns × 2 rows, cyan row then gold row
- [ ] FAQ: click to expand, one-at-a-time, icon rotates
- [ ] CTA: gradient card, circuit overlay, 2 buttons
- [ ] Footer: 4 columns, social icons, newsletter form, bottom bar

- [ ] **Step 2: Mobile verification (375px viewport)**

- [ ] Hamburger visible, opens drawer from right
- [ ] Drawer: links stacked, CTA at bottom, close on overlay/ESC
- [ ] Hero: single column, no image, buttons stack
- [ ] Stats: 2×2 grid
- [ ] Services: single column
- [ ] Industries: 2 columns
- [ ] Process: vertical timeline (not horizontal)
- [ ] Value: single column (text first, image below)
- [ ] Trust: single column
- [ ] FAQ: full width, works correctly
- [ ] Footer: single column

- [ ] **Step 3: Accessibility checks**

- [ ] Skip link appears on Tab press
- [ ] FAQ buttons have aria-expanded
- [ ] Drawer has role="dialog" and aria-modal
- [ ] All decorative icons have aria-hidden="true"
- [ ] Images have alt text in Arabic
- [ ] Tab navigation works through all interactive elements

- [ ] **Step 4: Fix any issues found**

Make targeted fixes. Do not restructure — only fix what's broken.

- [ ] **Step 5: Final commit**

```bash
git add -A
git commit -m "fix: final QA pass — responsive, accessibility, and polish fixes"
```

---

## Summary

| Task | Description | Files |
|------|-------------|-------|
| 1 | Shared CSS design system | `css/styles.css` |
| 2 | Shared JavaScript interactions | `js/main.js` |
| 3 | HTML boilerplate + Tailwind config + Navbar + Drawer | `index.html` |
| 4 | Hero section | `index.html` |
| 5 | Stats bar | `index.html` |
| 6 | Services overview bento grid | `index.html` |
| 7 | Industries grid + Process timeline | `index.html` |
| 8 | Saudi market value section | `index.html` |
| 9 | Trust section | `index.html` |
| 10 | FAQ accordion | `index.html` |
| 11 | CTA block + Footer | `index.html` |
| 12 | Final QA pass | all files |
