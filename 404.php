<?php
http_response_code(404);
$pageTitle = 'ركال | الصفحة غير موجودة';
$activePage = '';
require_once __DIR__ . '/includes/header.php';

// Attempt to extract the requested path for display
$requestedPath = '';
if (isset($_SERVER['REQUEST_URI'])) {
    $requestedPath = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $requestedPath = htmlspecialchars($requestedPath, ENT_QUOTES, 'UTF-8');
}
?>

  <!-- 404 Hero Section -->
  <section class="relative min-h-[80vh] flex items-center justify-center overflow-hidden circuit-bg error-scanline">
    <!-- Background orbs -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-container/6 blur-[120px] rounded-full animate-float-orb"></div>
    <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-sand-gold/5 blur-[100px] rounded-full"></div>
    <div class="absolute top-1/3 left-1/4 w-60 h-60 bg-primary-container/4 blur-[100px] rounded-full animate-float-orb" style="animation-delay: -3s;"></div>

    <!-- Floating particles -->
    <div class="error-particle" style="top:15%; right:20%; --duration:5s; --delay:0s; background:#00f2ff;"></div>
    <div class="error-particle" style="top:25%; right:70%; --duration:7s; --delay:1s; background:#D4AF37;"></div>
    <div class="error-particle" style="top:60%; right:15%; --duration:6s; --delay:0.5s; background:#4cd6ff;"></div>
    <div class="error-particle" style="top:70%; right:80%; --duration:8s; --delay:2s; background:#00f2ff;"></div>
    <div class="error-particle" style="top:40%; right:50%; --duration:5.5s; --delay:1.5s; background:#D4AF37;"></div>
    <div class="error-particle" style="top:80%; right:35%; --duration:7.5s; --delay:0.8s; background:#4cd6ff;"></div>

    <div class="relative z-10 text-center px-6 max-w-2xl mx-auto">

      <!-- Animated icon with pulse rings -->
      <div class="relative inline-flex items-center justify-center w-28 h-28 mb-8">
        <div class="error-ring"></div>
        <div class="error-ring"></div>
        <div class="error-ring"></div>
        <div class="relative z-10 w-20 h-20 rounded-2xl bg-surface-container-high/80 border border-white/10 flex items-center justify-center backdrop-blur-md">
          <span class="material-symbols-outlined text-4xl text-primary-container animate-pulse-glow" style="font-variation-settings: 'FILL' 1, 'wght' 300;">wifi_off</span>
        </div>
      </div>

      <!-- Glitch 404 number -->
      <div class="error-404-title text-[8rem] sm:text-[10rem] md:text-[12rem] font-black leading-none mb-2"
           data-text="٤٠٤"
           style="color: transparent; -webkit-text-stroke: 2px rgba(0,242,255,0.3); text-stroke: 2px rgba(0,242,255,0.3);">
        ٤٠٤
      </div>

      <!-- Heading -->
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4">الصفحة غير موجودة</h1>

      <!-- Description -->
      <p class="text-on-surface-variant text-base sm:text-lg mb-4 leading-relaxed max-w-lg mx-auto">
        عذرًا، الصفحة التي تبحث عنها غير متوفرة. ربما تم نقلها أو حذفها.
      </p>

      <?php if ($requestedPath && $requestedPath !== '404.php'): ?>
      <div class="inline-flex items-center gap-2 bg-surface-container-high/60 border border-white/5 rounded-xl px-4 py-2 mb-8 text-sm">
        <span class="material-symbols-outlined text-base text-error/60">link_off</span>
        <code class="text-on-surface-variant/70 direction-ltr" dir="ltr"><?= $requestedPath ?></code>
      </div>
      <?php else: ?>
      <div class="mb-8"></div>
      <?php endif; ?>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
        <a href="/"
           class="inline-flex items-center gap-2 tech-gradient text-on-primary-fixed px-8 py-3.5 rounded-xl font-bold text-sm transition-all duration-300 hover:shadow-[0_0_30px_rgba(0,242,255,0.3)] hover:scale-[1.02]">
          <span class="material-symbols-outlined text-lg">home</span>
          العودة للرئيسية
        </a>
        <button onclick="history.back()"
                class="inline-flex items-center gap-2 bg-surface-container-high/80 border border-white/10 text-white/80 hover:text-white px-8 py-3.5 rounded-xl font-bold text-sm transition-all duration-300 hover:border-white/20 hover:bg-surface-container-high">
          <span class="material-symbols-outlined text-lg">arrow_forward</span>
          الصفحة السابقة
        </button>
      </div>

      <!-- Section divider -->
      <div class="section-divider max-w-sm mx-auto mb-12"></div>

      <!-- Quick navigation cards -->
      <div class="text-sm text-on-surface-variant/60 font-bold uppercase tracking-widest mb-6">أو انتقل إلى</div>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-w-lg mx-auto">
        <a href="/services" class="error-nav-card glass-panel rounded-2xl p-4 text-center border border-white/5">
          <span class="error-nav-icon material-symbols-outlined text-2xl text-white/40 mb-2 block" style="font-variation-settings: 'FILL' 1;">code</span>
          <span class="text-sm text-white/70 font-medium">خدماتنا</span>
        </a>
        <a href="/solutions" class="error-nav-card glass-panel rounded-2xl p-4 text-center border border-white/5">
          <span class="error-nav-icon material-symbols-outlined text-2xl text-white/40 mb-2 block" style="font-variation-settings: 'FILL' 1;">lightbulb</span>
          <span class="text-sm text-white/70 font-medium">الحلول</span>
        </a>
        <a href="/blog" class="error-nav-card glass-panel rounded-2xl p-4 text-center border border-white/5">
          <span class="error-nav-icon material-symbols-outlined text-2xl text-white/40 mb-2 block" style="font-variation-settings: 'FILL' 1;">article</span>
          <span class="text-sm text-white/70 font-medium">المدونة</span>
        </a>
        <a href="/about" class="error-nav-card glass-panel rounded-2xl p-4 text-center border border-white/5">
          <span class="error-nav-icon material-symbols-outlined text-2xl text-white/40 mb-2 block" style="font-variation-settings: 'FILL' 1;">groups</span>
          <span class="text-sm text-white/70 font-medium">من نحن</span>
        </a>
        <a href="/contact" class="error-nav-card glass-panel rounded-2xl p-4 text-center border border-white/5">
          <span class="error-nav-icon material-symbols-outlined text-2xl text-white/40 mb-2 block" style="font-variation-settings: 'FILL' 1;">mail</span>
          <span class="text-sm text-white/70 font-medium">تواصل معنا</span>
        </a>
        <a href="/" class="error-nav-card glass-panel rounded-2xl p-4 text-center border border-white/5">
          <span class="error-nav-icon material-symbols-outlined text-2xl text-white/40 mb-2 block" style="font-variation-settings: 'FILL' 1;">home</span>
          <span class="text-sm text-white/70 font-medium">الرئيسية</span>
        </a>
      </div>

    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
