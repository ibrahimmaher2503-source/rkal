<?php
require_once 'includes/functions.php';

$db = getDB();
$stmt = $db->query('SELECT id, title, slug, icon, description, color_scheme, subservices, grid_col_span FROM services WHERE is_active = 1 ORDER BY sort_order');
$services = $stmt->fetchAll();

$pageTitle = 'ركال | خدماتنا - حلول رقمية متكاملة';
$activePage = 'services';
require_once 'includes/header.php';
?>

<!-- 1. Hero Banner -->
<section id="services-hero" class="relative min-h-[50vh] md:min-h-[60vh] flex items-center overflow-hidden px-4 md:px-8 py-24 hero-mesh">
  <div class="absolute inset-0 circuit-bg opacity-30"></div>
  <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-container/10 blur-[120px] rounded-full z-0 animate-float-orb"></div>
  <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-sand-gold/8 blur-[100px] rounded-full z-0"></div>
  <div class="max-w-screen-2xl mx-auto w-full relative z-10 text-right space-y-10">
    <!-- Badge -->
    <div class="reveal reveal-delay-1 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary-container/10 border border-primary-container/20 text-[#00f2ff] text-sm font-bold">
      <span class="material-symbols-outlined text-base" aria-hidden="true">verified</span>
      الخدمات المؤسسية
    </div>
    <!-- Headline -->
    <h1 class="reveal reveal-delay-2 text-3xl sm:text-5xl md:text-7xl font-headline font-bold leading-tight text-white">
      حلول رقمية تدفع <span class="text-transparent bg-clip-text tech-gradient">التحول الوطني</span>
    </h1>
    <!-- Subtitle -->
    <p class="reveal reveal-delay-3 text-lg md:text-xl text-on-surface-variant max-w-3xl leading-relaxed">
      نقدم منظومة متكاملة من الخدمات التقنية المتخصصة المصممة خصيصاً للسوق السعودي، تشمل التحول الرقمي وتطوير البرمجيات والأمن السيبراني والحوسبة السحابية وأكثر.
    </p>
    <!-- Stats Row -->
    <div class="reveal reveal-delay-4 flex flex-wrap gap-8 pt-4">
      <div class="flex items-center gap-3">
        <span class="text-3xl font-black text-[#00f2ff]">+٨</span>
        <span class="text-on-surface-variant font-medium">خدمة متخصصة</span>
      </div>
      <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
      <div class="flex items-center gap-3">
        <span class="text-3xl font-black text-[#00f2ff]">+١٥٠</span>
        <span class="text-on-surface-variant font-medium">مشروع منجز</span>
      </div>
      <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
      <div class="flex items-center gap-3">
        <span class="text-3xl font-black text-sand-gold">٢٤/٧</span>
        <span class="text-on-surface-variant font-medium">دعم فني</span>
      </div>
    </div>
  </div>
</section>

<!-- 2. Category Filter Tabs -->
<section class="py-12 px-4 md:px-8 bg-surface-container-low border-b border-white/5">
  <div class="max-w-screen-2xl mx-auto">
    <div class="flex flex-wrap gap-3 justify-center md:justify-start" role="tablist" aria-label="تصفية الخدمات">
      <button role="tab" aria-selected="true" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-primary-container/20 border border-primary-container/30 text-[#00f2ff] transition-all hover:bg-primary-container/30 focus:outline-none focus:ring-2 focus:ring-primary-container/50">
        جميع الخدمات
      </button>
      <button role="tab" aria-selected="false" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-surface-container-high border border-white/10 text-white/60 transition-all hover:bg-surface-container-highest hover:text-white focus:outline-none focus:ring-2 focus:ring-primary-container/50">
        التحول الرقمي
      </button>
      <button role="tab" aria-selected="false" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-surface-container-high border border-white/10 text-white/60 transition-all hover:bg-surface-container-highest hover:text-white focus:outline-none focus:ring-2 focus:ring-primary-container/50">
        تطوير البرمجيات
      </button>
      <button role="tab" aria-selected="false" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-surface-container-high border border-white/10 text-white/60 transition-all hover:bg-surface-container-highest hover:text-white focus:outline-none focus:ring-2 focus:ring-primary-container/50">
        الأمن السيبراني
      </button>
      <button role="tab" aria-selected="false" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-surface-container-high border border-white/10 text-white/60 transition-all hover:bg-surface-container-highest hover:text-white focus:outline-none focus:ring-2 focus:ring-primary-container/50">
        الحوسبة السحابية
      </button>
    </div>
  </div>
</section>

<!-- 3. Services Grid (Dynamic) -->
<section id="services-grid" class="py-24 px-4 md:px-8 section-glow">
  <div class="max-w-screen-2xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

      <?php foreach ($services as $service):
        $subservices  = json_decode($service['subservices'], true) ?: [];
        $isWide       = (int)$service['grid_col_span'] === 2;
        $isGold       = $service['color_scheme'] === 'gold';

        // Color tokens
        $colorText    = $isGold ? 'text-sand-gold'          : 'text-[#00f2ff]';
        $colorBg      = $isGold ? 'bg-sand-gold/10'         : 'bg-primary-container/10';
        $colorBorder  = $isGold ? 'border-sand-gold/20'     : 'border-primary-container/20';
        $hoverBorder  = $isGold ? 'hover:border-sand-gold/20'      : 'hover:border-primary-container/30';
        $hoverShadow  = $isGold
          ? 'hover:shadow-[0_0_40px_rgba(212,175,55,0.08)]'
          : 'hover:shadow-[0_0_40px_rgba(0,242,255,0.08)]';
        $colSpanClass = $isWide ? 'md:col-span-2' : '';
        $titleSize    = $isWide ? 'text-2xl' : 'text-xl';
      ?>

        <?php if ($isWide): ?>
        <!-- Wide card (col-span-2) -->
        <article class="reveal card-hover <?= $colSpanClass ?> glass-panel rounded-[2rem] p-8 relative overflow-hidden group <?= $hoverBorder ?> <?= $hoverShadow ?> transition-all duration-500">
          <!-- Oversized background icon -->
          <div class="absolute -bottom-4 -left-4 text-[10rem] <?= $colorText ?>/10 leading-none select-none pointer-events-none" aria-hidden="true">
            <span class="material-symbols-outlined" style="font-size:inherit;"><?= e($service['icon']) ?></span>
          </div>
          <div class="relative z-10">
            <div class="w-16 h-16 rounded-2xl <?= $colorBg ?> border <?= $colorBorder ?> flex items-center justify-center mb-6">
              <span class="material-symbols-outlined text-5xl <?= $colorText ?>" aria-hidden="true"><?= e($service['icon']) ?></span>
            </div>
            <h2 class="<?= $titleSize ?> font-bold text-white mb-3"><?= e($service['title']) ?></h2>
            <p class="text-white/60 text-sm leading-relaxed mb-6"><?= e($service['description']) ?></p>
            <?php if (!empty($subservices)): ?>
            <ul class="space-y-2.5 mb-8">
              <?php foreach ($subservices as $sub): ?>
              <li class="flex items-center gap-2.5 text-sm text-white/70">
                <span class="material-symbols-outlined text-base <?= $colorText ?>" aria-hidden="true">verified</span>
                <?= e($sub) ?>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <a href="service-detail.php?slug=<?= e($service['slug']) ?>" class="inline-flex items-center gap-2 <?= $colorText ?> text-sm font-bold hover:gap-4 transition-all duration-300">
              <span>اكتشف التفاصيل</span>
              <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
            </a>
          </div>
        </article>

        <?php else: ?>
        <!-- Standard card (col-span-1) -->
        <article class="reveal card-hover glass-panel rounded-[2rem] p-8 relative overflow-hidden group <?= $hoverBorder ?> <?= $hoverShadow ?> transition-all duration-500">
          <div class="relative z-10">
            <div class="w-16 h-16 rounded-2xl <?= $colorBg ?> border <?= $colorBorder ?> flex items-center justify-center mb-6">
              <span class="material-symbols-outlined text-5xl <?= $colorText ?>" aria-hidden="true"><?= e($service['icon']) ?></span>
            </div>
            <h2 class="<?= $titleSize ?> font-bold text-white mb-3"><?= e($service['title']) ?></h2>
            <p class="text-white/60 text-sm leading-relaxed mb-6"><?= e($service['description']) ?></p>
            <?php if (!empty($subservices)): ?>
            <ul class="space-y-2.5 mb-8">
              <?php foreach ($subservices as $sub): ?>
              <li class="flex items-center gap-2.5 text-sm text-white/70">
                <span class="material-symbols-outlined text-base <?= $colorText ?>" aria-hidden="true">verified</span>
                <?= e($sub) ?>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <a href="service-detail.php?slug=<?= e($service['slug']) ?>" class="inline-flex items-center gap-2 <?= $colorText ?> text-sm font-bold hover:gap-4 transition-all duration-300">
              <span>اكتشف التفاصيل</span>
              <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
            </a>
          </div>
        </article>
        <?php endif; ?>

      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- 4. CTA Block -->
<section id="services-cta" class="px-4 md:px-8 py-16 mb-16">
  <div class="max-w-7xl mx-auto">
    <div class="relative rounded-[3rem] bg-gradient-to-br from-surface-container-high to-surface-dim p-12 md:p-24 overflow-hidden border border-white/5 shadow-2xl">
      <div class="absolute inset-0 circuit-bg opacity-20 pointer-events-none"></div>
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-container/5 blur-[120px] rounded-full pointer-events-none"></div>
      <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-sand-gold/5 blur-[120px] rounded-full pointer-events-none"></div>
      <div class="relative z-10 text-center max-w-3xl mx-auto">
        <h2 class="text-2xl sm:text-4xl md:text-5xl font-headline font-bold mb-8 leading-tight text-white">
          ابدأ رحلة التميز الرقمي اليوم
        </h2>
        <p class="text-on-surface-variant text-lg mb-12 leading-relaxed">
          فريقنا التقني جاهز لتقديم الحل المثالي لاحتياجاتك. تواصل معنا الآن وابدأ رحلة التحول الرقمي بخبرة وطنية موثوقة.
        </p>
        <div class="flex flex-col md:flex-row justify-center gap-6">
          <a href="contact.php" class="tech-gradient text-on-primary-fixed px-12 py-5 rounded-2xl font-bold text-lg hover:shadow-[0_0_50px_rgba(0,242,255,0.4)] transition-all active:scale-95 inline-flex items-center justify-center gap-2">
            <span class="material-symbols-outlined" aria-hidden="true">chat</span>
            تواصل معنا
          </a>
          <a href="contact.php" class="bg-white/5 border border-white/10 text-white px-12 py-5 rounded-2xl font-bold text-lg hover:bg-white/10 transition-all inline-flex items-center justify-center gap-2">
            <span class="material-symbols-outlined" aria-hidden="true">description</span>
            اطلب عرض سعر
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
