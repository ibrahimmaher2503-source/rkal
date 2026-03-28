<?php
require_once 'includes/functions.php';

$db = getDB();
$stmt = $db->query('SELECT id, title, slug, icon, description, color_scheme, subservices, grid_col_span FROM services WHERE is_active = 1 ORDER BY sort_order');
$services = $stmt->fetchAll();

$pageTitle = 'ركال | خدماتنا - حلول رقمية متكاملة';
$activePage = 'services';
require_once 'includes/header.php';
$totalServices = count($services);
?>

<style>
  /* ── Services Page Enhancements ── */
  .srv-hero {
    min-height: 80vh;
    background:
      radial-gradient(ellipse 90% 60% at 30% 15%, rgba(0,242,255,0.08) 0%, transparent 50%),
      radial-gradient(ellipse 70% 50% at 80% 85%, rgba(212,175,55,0.05) 0%, transparent 50%),
      radial-gradient(ellipse 40% 40% at 50% 50%, rgba(76,214,255,0.03) 0%, transparent 60%);
  }

  /* Animated grid overlay */
  .srv-hero-grid {
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(0,242,255,0.03) 1px, transparent 1px),
      linear-gradient(90deg, rgba(0,242,255,0.03) 1px, transparent 1px);
    background-size: 60px 60px;
    mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, black 20%, transparent 70%);
    -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, black 20%, transparent 70%);
    animation: grid-drift 20s linear infinite;
  }
  @keyframes grid-drift {
    0% { background-position: 0 0; }
    100% { background-position: 60px 60px; }
  }

  /* Scan line effect in hero */
  .srv-scanline {
    position: absolute;
    left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent 0%, rgba(0,242,255,0.15) 20%, rgba(0,242,255,0.4) 50%, rgba(0,242,255,0.15) 80%, transparent 100%);
    animation: scanline 6s ease-in-out infinite;
    pointer-events: none;
  }
  @keyframes scanline {
    0%, 100% { top: 10%; opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    50% { top: 85%; }
  }

  /* Service card 3D perspective */
  .srv-card-wrap {
    perspective: 800px;
  }
  .srv-card {
    transform-style: preserve-3d;
    transition: transform 600ms cubic-bezier(0.22,1,0.36,1), box-shadow 600ms cubic-bezier(0.22,1,0.36,1);
  }
  .srv-card:hover {
    transform: translateY(-8px) rotateX(2deg) rotateY(-1deg);
  }

  /* Animated border trace on hover */
  .srv-card::before {
    content: '';
    position: absolute;
    inset: -1px;
    border-radius: inherit;
    background: conic-gradient(from var(--border-angle, 0deg), transparent 60%, var(--accent-color, #00f2ff) 80%, transparent 100%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    padding: 1px;
    opacity: 0;
    transition: opacity 500ms ease;
  }
  .srv-card:hover::before {
    opacity: 1;
    animation: border-trace 3s linear infinite;
  }
  @keyframes border-trace {
    0% { --border-angle: 0deg; }
    100% { --border-angle: 360deg; }
  }
  @property --border-angle {
    syntax: '<angle>';
    initial-value: 0deg;
    inherits: false;
  }

  /* Icon float on hover */
  .srv-card .srv-icon-box {
    transition: transform 500ms cubic-bezier(0.22,1,0.36,1), box-shadow 500ms ease;
  }
  .srv-card:hover .srv-icon-box {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 8px 30px rgba(0,242,255,0.15);
  }
  .srv-card[data-scheme="gold"]:hover .srv-icon-box {
    box-shadow: 0 8px 30px rgba(212,175,55,0.15);
  }

  /* Arrow slide on hover */
  .srv-card .srv-arrow {
    transition: transform 400ms cubic-bezier(0.22,1,0.36,1), gap 400ms ease;
  }
  .srv-card:hover .srv-arrow {
    transform: translateX(-6px);
  }

  /* Section glow divider */
  .srv-section-divider {
    position: relative;
    height: 1px;
    background: linear-gradient(90deg, transparent 5%, rgba(0,242,255,0.25) 30%, rgba(212,175,55,0.2) 70%, transparent 95%);
  }
  .srv-section-divider::after {
    content: '';
    position: absolute;
    top: -6px;
    left: 50%;
    transform: translateX(-50%);
    width: 12px;
    height: 12px;
    border: 2px solid rgba(0,242,255,0.3);
    border-radius: 50%;
    background: #0b1229;
  }

  /* Staggered card entrance */
  .srv-stagger-1 { transition-delay: 50ms; }
  .srv-stagger-2 { transition-delay: 120ms; }
  .srv-stagger-3 { transition-delay: 190ms; }
  .srv-stagger-4 { transition-delay: 260ms; }
  .srv-stagger-5 { transition-delay: 330ms; }
  .srv-stagger-6 { transition-delay: 400ms; }
  .srv-stagger-7 { transition-delay: 470ms; }
  .srv-stagger-8 { transition-delay: 540ms; }

  /* Metric card pulse */
  .metric-card {
    position: relative;
    overflow: hidden;
  }
  .metric-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(0,242,255,0.05) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 400ms ease;
  }
  .metric-card:hover::after {
    opacity: 1;
  }

  /* Subservice checkmark entrance */
  .srv-check {
    opacity: 0;
    transform: scale(0.5);
    transition: all 400ms cubic-bezier(0.22,1,0.36,1);
  }
  .srv-card:hover .srv-check {
    opacity: 1;
    transform: scale(1);
  }

  /* CTA floating particles */
  @keyframes cta-float-1 {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(30px, -20px) rotate(120deg); }
    66% { transform: translate(-20px, 15px) rotate(240deg); }
  }
  @keyframes cta-float-2 {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(-25px, 20px) rotate(-120deg); }
    66% { transform: translate(15px, -30px) rotate(-240deg); }
  }
</style>

<!-- 1. Cinematic Hero -->
<section class="srv-hero relative flex items-center overflow-hidden px-4 md:px-8 py-28 md:py-36">
  <div class="srv-hero-grid"></div>
  <div class="srv-scanline z-20"></div>
  <div class="circuit-bg absolute inset-0 opacity-20"></div>

  <!-- Floating orbs -->
  <div class="absolute -top-32 right-[10%] w-[500px] h-[500px] bg-primary-container/8 blur-[150px] rounded-full z-0 animate-float-orb"></div>
  <div class="absolute -bottom-40 left-[5%] w-[400px] h-[400px] bg-sand-gold/6 blur-[120px] rounded-full z-0" style="animation: float-orb 10s ease-in-out infinite reverse;"></div>
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary-container/3 blur-[200px] rounded-full z-0"></div>

  <div class="max-w-screen-2xl mx-auto w-full relative z-10 text-right">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

      <!-- Right: Text content -->
      <div class="lg:col-span-7 space-y-8">
        <!-- Badge -->
        <div class="reveal reveal-delay-1 inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-primary-container/8 border border-primary-container/15 text-[#00f2ff] text-sm font-bold backdrop-blur-sm">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00f2ff] opacity-60"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#00f2ff]"></span>
          </span>
          الخدمات المؤسسية المتخصصة
        </div>

        <!-- Headline -->
        <h1 class="reveal reveal-delay-2 text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-headline font-black leading-[1.1] text-white">
          نبني
          <span class="relative inline-block">
            <span class="text-transparent bg-clip-text" style="background: linear-gradient(135deg, #00f2ff 0%, #4cd6ff 40%, #00dbe7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">المستقبل</span>
            <span class="absolute -bottom-2 right-0 left-0 h-1 rounded-full" style="background: linear-gradient(90deg, #00f2ff, #4cd6ff, transparent);"></span>
          </span>
          <br>الرقمي للمملكة
        </h1>

        <!-- Subtitle -->
        <p class="reveal reveal-delay-3 text-lg md:text-xl text-on-surface-variant max-w-2xl leading-relaxed">
          منظومة متكاملة من الحلول التقنية المتخصصة، مصممة للسوق السعودي، تشمل التحول الرقمي وتطوير البرمجيات والأمن السيبراني والحوسبة السحابية.
        </p>

        <!-- Action buttons -->
        <div class="reveal reveal-delay-4 flex flex-wrap gap-4 pt-2">
          <a href="#services-grid" class="inline-flex items-center gap-2 tech-gradient text-on-primary-fixed px-8 py-4 rounded-2xl font-bold text-base hover:shadow-[0_0_40px_rgba(0,242,255,0.3)] transition-all active:scale-[0.97]">
            <span>استكشف خدماتنا</span>
            <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_downward</span>
          </a>
          <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-white px-8 py-4 rounded-2xl font-bold text-base hover:bg-white/10 hover:border-white/20 transition-all">
            <span>اطلب استشارة</span>
            <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_back</span>
          </a>
        </div>
      </div>

      <!-- Left: Metrics panel -->
      <div class="lg:col-span-5 reveal reveal-delay-4">
        <div class="glass-panel rounded-[2rem] p-8 space-y-6 border border-white/5">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-2 h-2 rounded-full bg-[#00f2ff] animate-pulse"></div>
            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">إحصائيات مباشرة</span>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="metric-card bg-surface-container rounded-2xl p-5 text-center space-y-1 border border-white/5 transition-all duration-300 hover:border-primary-container/20">
              <div class="text-3xl font-black text-[#00f2ff]" data-count="<?= $totalServices ?>" data-prefix="+" data-arabic="true">+<?= toArabicNumerals($totalServices) ?></div>
              <div class="text-xs text-on-surface-variant font-medium">خدمة متخصصة</div>
            </div>
            <div class="metric-card bg-surface-container rounded-2xl p-5 text-center space-y-1 border border-white/5 transition-all duration-300 hover:border-sand-gold/20">
              <div class="text-3xl font-black text-sand-gold" data-count="150" data-prefix="+" data-arabic="true">+١٥٠</div>
              <div class="text-xs text-on-surface-variant font-medium">مشروع منجز</div>
            </div>
            <div class="metric-card bg-surface-container rounded-2xl p-5 text-center space-y-1 border border-white/5 transition-all duration-300 hover:border-primary-container/20">
              <div class="text-3xl font-black text-[#00f2ff]" data-count="50" data-prefix="+" data-arabic="true">+٥٠</div>
              <div class="text-xs text-on-surface-variant font-medium">عميل مؤسسي</div>
            </div>
            <div class="metric-card bg-surface-container rounded-2xl p-5 text-center space-y-1 border border-white/5 transition-all duration-300 hover:border-sand-gold/20">
              <div class="text-3xl font-black text-sand-gold">٢٤/٧</div>
              <div class="text-xs text-on-surface-variant font-medium">دعم فني متواصل</div>
            </div>
          </div>

          <!-- Trust strip -->
          <div class="flex items-center gap-3 pt-2 border-t border-white/5">
            <span class="material-symbols-outlined text-sand-gold text-lg" aria-hidden="true" style="font-variation-settings: 'FILL' 1;">verified</span>
            <span class="text-xs text-on-surface-variant">شريك معتمد لدعم التحول الرقمي الوطني</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Glow divider -->
<div class="srv-section-divider mx-4 md:mx-8"></div>

<!-- 2. Services Grid -->
<section id="services-grid" class="py-24 md:py-32 px-4 md:px-8 relative">
  <!-- Section background glow -->
  <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-primary-container/4 blur-[150px] rounded-full pointer-events-none"></div>

  <div class="max-w-screen-2xl mx-auto relative z-10">
    <!-- Section header -->
    <div class="text-center mb-16 md:mb-20 space-y-5">
      <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-white/5 text-on-surface-variant text-xs font-bold uppercase tracking-widest">
        <span class="material-symbols-outlined text-sm text-[#00f2ff]" aria-hidden="true">grid_view</span>
        منظومة الخدمات
      </div>
      <h2 class="reveal text-3xl sm:text-4xl md:text-5xl font-headline font-black text-white leading-tight">
        خدمات مصممة لكل <span class="text-[#00f2ff]">تحدٍّ رقمي</span>
      </h2>
      <p class="reveal text-on-surface-variant text-lg max-w-2xl mx-auto leading-relaxed">
        اختر من بين خدماتنا المتخصصة التي تغطي كافة جوانب التحول الرقمي
      </p>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">

      <?php foreach ($services as $index => $service):
        $subservices  = json_decode($service['subservices'], true) ?: [];
        $isWide       = (int)$service['grid_col_span'] === 2;
        $isGold       = $service['color_scheme'] === 'gold';

        $colorText    = $isGold ? 'text-sand-gold'            : 'text-[#00f2ff]';
        $colorBg      = $isGold ? 'bg-sand-gold/10'           : 'bg-primary-container/10';
        $colorBorder  = $isGold ? 'border-sand-gold/15'       : 'border-primary-container/15';
        $accentHex    = $isGold ? '#D4AF37'                   : '#00f2ff';
        $colSpanClass = $isWide ? 'md:col-span-2'             : '';
        $titleSize    = $isWide ? 'text-2xl md:text-3xl'      : 'text-xl md:text-2xl';
        $stagger      = 'srv-stagger-' . min($index + 1, 8);
      ?>

        <div class="srv-card-wrap <?= $colSpanClass ?>">
          <article
            class="reveal <?= $stagger ?> srv-card glass-panel rounded-[2rem] p-8 md:p-10 relative overflow-hidden group"
            data-scheme="<?= $isGold ? 'gold' : 'cyan' ?>"
            style="--accent-color: <?= $accentHex ?>;"
          >
            <!-- Background decoration -->
            <?php if ($isWide): ?>
            <div class="absolute -bottom-8 -left-8 text-[12rem] leading-none select-none pointer-events-none opacity-[0.04] group-hover:opacity-[0.08] transition-opacity duration-700" aria-hidden="true">
              <span class="material-symbols-outlined" style="font-size:inherit; font-variation-settings: 'FILL' 1;"><?= e($service['icon']) ?></span>
            </div>
            <?php endif; ?>

            <!-- Corner glow -->
            <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full blur-[60px] opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"
                 style="background: <?= $accentHex ?>; opacity: 0;"
                 onmouseenter=""
            ></div>

            <div class="relative z-10">
              <!-- Icon -->
              <div class="srv-icon-box w-16 h-16 rounded-2xl <?= $colorBg ?> border <?= $colorBorder ?> flex items-center justify-center mb-7">
                <span class="material-symbols-outlined text-4xl <?= $colorText ?>" aria-hidden="true" style="font-variation-settings: 'FILL' 0, 'wght' 300;"><?= e($service['icon']) ?></span>
              </div>

              <!-- Title -->
              <h2 class="<?= $titleSize ?> font-headline font-bold text-white mb-3 group-hover:text-white/95 transition-colors"><?= e($service['title']) ?></h2>

              <!-- Description -->
              <p class="text-on-surface-variant text-sm leading-relaxed mb-7 max-w-xl"><?= e($service['description']) ?></p>

              <!-- Subservices -->
              <?php if (!empty($subservices)): ?>
              <ul class="space-y-3 mb-8">
                <?php foreach (array_slice($subservices, 0, $isWide ? 6 : 4) as $si => $sub): ?>
                <li class="flex items-center gap-3 text-sm text-white/70">
                  <span class="srv-check flex-shrink-0 w-5 h-5 rounded-md <?= $colorBg ?> border <?= $colorBorder ?> flex items-center justify-center" style="opacity:1;transform:none;">
                    <span class="material-symbols-outlined text-xs <?= $colorText ?>" aria-hidden="true">check</span>
                  </span>
                  <?= e($sub) ?>
                </li>
                <?php endforeach; ?>
                <?php if (count($subservices) > ($isWide ? 6 : 4)): ?>
                <li class="text-xs <?= $colorText ?> font-bold pr-8">
                  +<?= toArabicNumerals(count($subservices) - ($isWide ? 6 : 4)) ?> خدمات أخرى
                </li>
                <?php endif; ?>
              </ul>
              <?php endif; ?>

              <!-- CTA link -->
              <a href="<?= url('/services/' . e($service['slug'])) ?>"
                 class="srv-arrow inline-flex items-center gap-2.5 <?= $colorText ?> text-sm font-bold group/link">
                <span class="relative">
                  اكتشف التفاصيل
                  <span class="absolute bottom-0 right-0 left-0 h-px opacity-0 group-hover/link:opacity-100 transition-opacity" style="background: <?= $accentHex ?>;"></span>
                </span>
                <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
              </a>
            </div>
          </article>
        </div>

      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- Glow divider -->
<div class="srv-section-divider mx-4 md:mx-8"></div>

<!-- 3. Why Us Section -->
<section class="py-24 md:py-32 px-4 md:px-8 relative overflow-hidden">
  <div class="absolute -top-40 left-[20%] w-[500px] h-[500px] bg-sand-gold/4 blur-[150px] rounded-full pointer-events-none"></div>

  <div class="max-w-screen-2xl mx-auto relative z-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

      <!-- Right: Text -->
      <div class="space-y-8">
        <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sand-gold/10 border border-sand-gold/15 text-sand-gold text-xs font-bold uppercase tracking-widest">
          <span class="material-symbols-outlined text-sm" aria-hidden="true">diamond</span>
          لماذا ركال
        </div>
        <h2 class="reveal text-3xl sm:text-4xl md:text-5xl font-headline font-black text-white leading-tight">
          خبرة وطنية بمعايير<br><span class="text-sand-gold">عالمية</span>
        </h2>
        <p class="reveal text-on-surface-variant text-lg leading-relaxed max-w-xl">
          نجمع بين الفهم العميق للسوق السعودي وأحدث التقنيات العالمية لتقديم حلول تتجاوز التوقعات وتحقق نتائج ملموسة.
        </p>
      </div>

      <!-- Left: Feature cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <?php
        $whyItems = [
          ['icon' => 'speed', 'title' => 'سرعة التنفيذ', 'desc' => 'منهجية Agile تضمن تسليم سريع دون التنازل عن الجودة', 'color' => 'cyan'],
          ['icon' => 'shield', 'title' => 'أمان متقدم', 'desc' => 'معايير أمنية صارمة وامتثال كامل للأنظمة المحلية', 'color' => 'gold'],
          ['icon' => 'support_agent', 'title' => 'دعم مستمر', 'desc' => 'فريق دعم متخصص متاح على مدار الساعة لخدمتك', 'color' => 'gold'],
          ['icon' => 'trending_up', 'title' => 'نتائج قابلة للقياس', 'desc' => 'تقارير أداء دورية ومؤشرات نجاح واضحة لكل مشروع', 'color' => 'cyan'],
        ];
        foreach ($whyItems as $wi => $item):
          $isCyan = $item['color'] === 'cyan';
          $wColorText = $isCyan ? 'text-[#00f2ff]' : 'text-sand-gold';
          $wColorBg   = $isCyan ? 'bg-primary-container/8' : 'bg-sand-gold/8';
          $wBorder    = $isCyan ? 'border-primary-container/10' : 'border-sand-gold/10';
        ?>
        <div class="reveal srv-stagger-<?= $wi + 1 ?> glass-panel card-hover rounded-2xl p-6 space-y-4 border border-white/5 hover:<?= $wBorder ?> transition-all duration-300">
          <div class="w-11 h-11 rounded-xl <?= $wColorBg ?> flex items-center justify-center">
            <span class="material-symbols-outlined text-xl <?= $wColorText ?>" aria-hidden="true" style="font-variation-settings: 'FILL' 1;"><?= e($item['icon']) ?></span>
          </div>
          <h3 class="text-white font-bold text-lg"><?= e($item['title']) ?></h3>
          <p class="text-on-surface-variant text-sm leading-relaxed"><?= e($item['desc']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>

<!-- 4. CTA Block -->
<section class="px-4 md:px-8 py-16 mb-16 relative">
  <div class="max-w-5xl mx-auto">
    <div class="relative rounded-[3rem] overflow-hidden border border-white/5 shadow-2xl">
      <!-- Background layers -->
      <div class="absolute inset-0 bg-gradient-to-br from-surface-container-high via-surface-container to-surface-dim"></div>
      <div class="absolute inset-0 circuit-bg opacity-15"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-primary-container/6 blur-[100px] rounded-full" style="animation: cta-float-1 12s ease-in-out infinite;"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-sand-gold/5 blur-[100px] rounded-full" style="animation: cta-float-2 14s ease-in-out infinite;"></div>

      <!-- Content -->
      <div class="relative z-10 p-12 md:p-20 text-center">
        <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary-container/10 border border-primary-container/15 text-[#00f2ff] text-xs font-bold uppercase tracking-widest mb-8">
          <span class="material-symbols-outlined text-sm" aria-hidden="true">rocket_launch</span>
          ابدأ الآن
        </div>
        <h2 class="reveal text-3xl sm:text-4xl md:text-5xl font-headline font-bold mb-6 leading-tight text-white">
          جاهز لنقل أعمالك إلى<br>المستوى التالي؟
        </h2>
        <p class="reveal text-on-surface-variant text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
          فريقنا التقني جاهز لتحليل احتياجاتك وتقديم الحل المثالي. تواصل معنا واحصل على استشارة مجانية.
        </p>
        <div class="reveal flex flex-col sm:flex-row justify-center gap-5">
          <a href="<?= url('/contact') ?>" class="inline-flex items-center justify-center gap-2 tech-gradient text-on-primary-fixed px-10 py-4.5 rounded-2xl font-bold text-lg hover:shadow-[0_0_50px_rgba(0,242,255,0.35)] transition-all active:scale-[0.97]">
            <span class="material-symbols-outlined" aria-hidden="true">chat</span>
            تواصل معنا
          </a>
          <a href="<?= url('/contact') ?>" class="inline-flex items-center justify-center gap-2 bg-white/5 border border-white/10 text-white px-10 py-4.5 rounded-2xl font-bold text-lg hover:bg-white/10 hover:border-white/20 transition-all">
            <span class="material-symbols-outlined" aria-hidden="true">description</span>
            اطلب عرض سعر
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
