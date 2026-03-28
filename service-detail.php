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
$subservices      = json_decode($service['subservices'],      true) ?: [];
$stats            = json_decode($service['stats'],            true) ?: [];
$targetBusinesses = json_decode($service['target_businesses'], true) ?: [];
$benefits         = json_decode($service['benefits'],         true) ?: [];
$techStack        = json_decode($service['tech_stack'],       true) ?: [];
$workflow         = json_decode($service['workflow'],         true) ?: [];
$faq              = json_decode($service['faq'],              true) ?: [];

// Fetch related services (other active services, excluding current, limit 3)
$relStmt = $db->prepare(
    'SELECT id, title, slug, icon, description, color_scheme
     FROM services
     WHERE is_active = 1 AND id != :id
     ORDER BY sort_order
     LIMIT 3'
);
$relStmt->execute(['id' => $service['id']]);
$relatedServices = $relStmt->fetchAll();

$pageTitle  = 'ركال | ' . e($service['title']);
$activePage = 'services';
require_once 'includes/header.php';

$isGold = ($service['color_scheme'] ?? 'cyan') === 'gold';
$accentHex = $isGold ? '#D4AF37' : '#00f2ff';
$accentHex2 = $isGold ? '#e8c84a' : '#4cd6ff';
?>

<style>
  /* ── Service Detail Enhancements ── */

  /* Hero cinematic */
  .sd-hero {
    min-height: 70vh;
    background:
      radial-gradient(ellipse 80% 50% at 60% 30%, <?= $accentHex ?>0d 0%, transparent 50%),
      radial-gradient(ellipse 60% 40% at 20% 70%, rgba(212,175,55,0.04) 0%, transparent 50%);
  }

  /* Animated grid for hero */
  .sd-hero-grid {
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(<?= $accentHex ?>08 1px, transparent 1px),
      linear-gradient(90deg, <?= $accentHex ?>08 1px, transparent 1px);
    background-size: 80px 80px;
    mask-image: radial-gradient(ellipse 60% 50% at 50% 50%, black 10%, transparent 60%);
    -webkit-mask-image: radial-gradient(ellipse 60% 50% at 50% 50%, black 10%, transparent 60%);
  }

  /* Floating hero icon */
  .sd-hero-icon {
    font-size: 15rem;
    line-height: 1;
    opacity: 0.04;
    position: absolute;
    left: 5%;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    animation: float-orb 10s ease-in-out infinite;
  }

  /* Stats counter ring */
  .sd-stat-ring {
    position: relative;
  }
  .sd-stat-ring::before {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: inherit;
    background: conic-gradient(from 0deg, <?= $accentHex ?>30, transparent 70%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    padding: 2px;
  }

  /* Target business card hover rotation */
  .sd-target-card {
    transition: all 500ms cubic-bezier(0.22,1,0.36,1);
  }
  .sd-target-card:hover {
    transform: translateY(-6px);
    border-color: <?= $accentHex ?>40;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3), 0 0 30px <?= $accentHex ?>10;
  }
  .sd-target-card:hover .sd-target-icon {
    transform: scale(1.1);
    box-shadow: 0 0 25px <?= $accentHex ?>20;
  }
  .sd-target-icon {
    transition: all 400ms cubic-bezier(0.22,1,0.36,1);
  }

  /* Benefits card numbered index */
  .sd-benefit-idx {
    position: absolute;
    top: -1px;
    right: -1px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 800;
    border-radius: 0 1.5rem 0 1rem;
    background: <?= $accentHex ?>15;
    color: <?= $accentHex ?>;
    border-bottom: 1px solid <?= $accentHex ?>20;
    border-left: 1px solid <?= $accentHex ?>20;
  }

  /* Benefit card hover */
  .sd-benefit-card {
    transition: all 500ms cubic-bezier(0.22,1,0.36,1);
  }
  .sd-benefit-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
  }

  /* Tech stack scroll */
  .sd-tech-track {
    display: flex;
    gap: 1rem;
    animation: tech-scroll 25s linear infinite;
    width: max-content;
  }
  .sd-tech-track:hover {
    animation-play-state: paused;
  }
  @keyframes tech-scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }

  /* Workflow timeline enhanced */
  .sd-timeline-line {
    position: absolute;
    right: 23px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, <?= $accentHex ?> 0%, <?= $accentHex ?>50 50%, transparent 100%);
  }
  .sd-timeline-dot {
    position: relative;
    z-index: 10;
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 1.1rem;
    color: #0b1229;
    background: linear-gradient(135deg, <?= $accentHex ?>, <?= $accentHex2 ?>);
    box-shadow: 0 0 20px <?= $accentHex ?>50, 0 0 40px <?= $accentHex ?>20;
    transition: all 400ms ease;
  }
  .sd-timeline-step:hover .sd-timeline-dot {
    box-shadow: 0 0 30px <?= $accentHex ?>70, 0 0 60px <?= $accentHex ?>30;
    transform: scale(1.1);
  }
  .sd-timeline-step:hover .sd-timeline-card {
    border-color: <?= $accentHex ?>30;
    transform: translateX(-4px);
  }
  .sd-timeline-card {
    transition: all 400ms cubic-bezier(0.22,1,0.36,1);
  }

  /* FAQ enhanced */
  .sd-faq-item {
    transition: background-color 300ms ease;
  }
  .sd-faq-item:hover {
    background-color: rgba(255,255,255,0.015);
  }
  .sd-faq-item .sd-faq-icon {
    transition: transform 400ms cubic-bezier(0.22,1,0.36,1), color 300ms ease;
  }
  .sd-faq-item.active .sd-faq-icon {
    transform: rotate(180deg);
    color: <?= $accentHex ?>;
  }
  .sd-faq-item .sd-faq-q {
    transition: color 300ms ease;
  }
  .sd-faq-item:hover .sd-faq-q,
  .sd-faq-item.active .sd-faq-q {
    color: <?= $accentHex ?>;
  }
  .sd-faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 400ms cubic-bezier(0.4,0,0.2,1);
  }
  .sd-faq-item.active .sd-faq-answer {
    max-height: 500px;
  }

  /* Stagger */
  .sd-stagger-1 { transition-delay: 80ms; }
  .sd-stagger-2 { transition-delay: 160ms; }
  .sd-stagger-3 { transition-delay: 240ms; }
  .sd-stagger-4 { transition-delay: 320ms; }
  .sd-stagger-5 { transition-delay: 400ms; }
  .sd-stagger-6 { transition-delay: 480ms; }

  /* Section glow divider */
  .sd-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent 5%, <?= $accentHex ?>25 30%, rgba(212,175,55,0.15) 70%, transparent 95%);
  }
</style>

<!-- 1. Cinematic Hero -->
<section class="sd-hero relative flex items-end overflow-hidden px-4 md:px-8 py-24 md:py-32">
  <div class="sd-hero-grid"></div>
  <div class="circuit-bg absolute inset-0 opacity-15"></div>

  <!-- Background orbs -->
  <div class="absolute -top-40 right-[10%] w-[500px] h-[500px] blur-[150px] rounded-full z-0 animate-float-orb" style="background: <?= $accentHex ?>0c;"></div>
  <div class="absolute -bottom-40 left-[5%] w-[400px] h-[400px] bg-sand-gold/5 blur-[120px] rounded-full z-0" style="animation: float-orb 12s ease-in-out infinite reverse;"></div>

  <!-- Huge background icon -->
  <div class="sd-hero-icon hidden lg:block" aria-hidden="true">
    <span class="material-symbols-outlined" style="font-size: inherit; font-variation-settings: 'FILL' 1;"><?= e($service['icon']) ?></span>
  </div>

  <div class="relative z-10 max-w-screen-xl mx-auto w-full">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-on-surface-variant mb-10 reveal" aria-label="مسار التنقل">
      <a href="index.php" class="hover:text-white transition-colors" style="color: <?= $accentHex ?>;">الرئيسية</a>
      <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_left</span>
      <a href="services.php" class="hover:text-white transition-colors" style="color: <?= $accentHex ?>;">خدماتنا</a>
      <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_left</span>
      <span class="text-white/80"><?= e($service['title']) ?></span>
    </nav>

    <!-- Badge -->
    <div class="reveal reveal-delay-1 inline-flex items-center gap-2.5 px-5 py-2 rounded-full text-sm font-bold backdrop-blur-sm mb-8"
         style="background: <?= $accentHex ?>10; border: 1px solid <?= $accentHex ?>20; color: <?= $accentHex ?>;">
      <span class="material-symbols-outlined text-base" aria-hidden="true"><?= e($service['icon']) ?></span>
      <span>خدمة متخصصة</span>
    </div>

    <!-- Headline -->
    <h1 class="reveal reveal-delay-2 text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-headline font-black text-white leading-[1.1] mb-6 max-w-4xl">
      <span class="text-transparent bg-clip-text" style="background: linear-gradient(135deg, <?= $accentHex ?> 0%, <?= $accentHex2 ?> 50%, <?= $accentHex ?> 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        <?= e($service['title']) ?>
      </span>
    </h1>

    <!-- Subtitle -->
    <?php if (!empty($service['description'])): ?>
    <p class="reveal reveal-delay-3 text-lg md:text-xl text-on-surface-variant leading-relaxed max-w-2xl mb-10">
      <?= e($service['description']) ?>
    </p>
    <?php endif; ?>

    <!-- Featured Image -->
    <?php if (!empty($service['featured_image'])): ?>
    <div class="mt-12 reveal">
      <img src="<?= e($service['featured_image']) ?>" alt="<?= e($service['title']) ?>" class="w-full rounded-[2rem] shadow-2xl" />
    </div>
    <?php endif; ?>

    <!-- Quick actions -->
    <div class="reveal reveal-delay-4 flex flex-wrap gap-4">
      <a href="contact.php" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl font-bold text-base transition-all active:scale-[0.97]"
         style="background: linear-gradient(135deg, <?= $accentHex ?>, <?= $accentHex2 ?>); color: #0b1229; box-shadow: 0 0 30px <?= $accentHex ?>30;"
         onmouseover="this.style.boxShadow='0 0 50px <?= $accentHex ?>50'"
         onmouseout="this.style.boxShadow='0 0 30px <?= $accentHex ?>30'">
        <span>اطلب هذه الخدمة</span>
        <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_back</span>
      </a>
      <?php if (!empty($faq)): ?>
      <a href="#service-faq" class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-white px-8 py-4 rounded-2xl font-bold text-base hover:bg-white/10 transition-all">
        <span>الأسئلة الشائعة</span>
        <span class="material-symbols-outlined text-lg" aria-hidden="true">help</span>
      </a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Divider -->
<div class="sd-divider mx-4 md:mx-8"></div>

<!-- 2. Service Overview + Stats -->
<?php if (!empty($service['overview_content']) || !empty($stats)): ?>
<section class="py-24 md:py-32 px-4 md:px-8 relative">
  <div class="absolute -top-40 left-[30%] w-[400px] h-[400px] blur-[150px] rounded-full pointer-events-none" style="background: <?= $accentHex ?>05;"></div>

  <div class="max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-start relative z-10">

    <!-- Right: Overview text -->
    <?php if (!empty($service['overview_content'])): ?>
    <div class="space-y-8">
      <div class="reveal">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4"
             style="background: <?= $accentHex ?>08; border: 1px solid <?= $accentHex ?>12; color: <?= $accentHex ?>;">
          <span class="material-symbols-outlined text-sm" aria-hidden="true">description</span>
          نظرة شاملة
        </div>
        <h2 class="text-3xl sm:text-4xl font-headline font-bold text-white mb-4">نظرة شاملة على الخدمة</h2>
        <div class="w-20 h-1 rounded-full" style="background: linear-gradient(90deg, <?= $accentHex ?>, transparent);"></div>
      </div>
      <div class="reveal text-on-surface-variant leading-relaxed text-lg space-y-4">
        <?= sanitizeHtml($service['overview_content']) ?>
      </div>

      <?php if (!empty($subservices)): ?>
      <div class="reveal glass-panel rounded-2xl p-6 border border-white/5">
        <h3 class="text-sm font-bold text-on-surface-variant uppercase tracking-widest mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-sm" aria-hidden="true" style="color: <?= $accentHex ?>;">checklist</span>
          الخدمات الفرعية
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <?php foreach ($subservices as $sub): ?>
          <div class="flex items-center gap-2.5 text-sm text-white/80">
            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background: <?= $accentHex ?>;"></span>
            <?= e($sub) ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Left: Stats Card -->
    <?php if (!empty($stats)): ?>
    <div class="reveal">
      <div class="glass-panel rounded-[2rem] p-8 md:p-10 space-y-8 border border-white/5">
        <div class="flex items-center gap-3">
          <div class="w-2 h-2 rounded-full animate-pulse" style="background: <?= $accentHex ?>;"></div>
          <h3 class="text-lg font-bold text-white">أرقامنا تتحدث</h3>
        </div>
        <div class="grid grid-cols-2 gap-5">
          <?php foreach ($stats as $i => $stat):
            $isEven = ($i % 2 === 0);
            $statColor = $isEven ? $accentHex : ($isGold ? '#00f2ff' : '#D4AF37');
          ?>
            <div class="sd-stat-ring bg-surface-container rounded-2xl p-6 text-center space-y-2.5 border border-white/5">
              <div class="text-4xl font-black" style="color: <?= $statColor ?>;">
                <?= e($stat['value']) ?>
              </div>
              <div class="text-sm text-on-surface-variant leading-relaxed font-medium"><?= e($stat['label']) ?></div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Trust badge -->
        <div class="flex items-center gap-3 pt-4 border-t border-white/5">
          <span class="material-symbols-outlined text-lg" aria-hidden="true" style="color: <?= $accentHex ?>; font-variation-settings: 'FILL' 1;">workspace_premium</span>
          <span class="text-xs text-on-surface-variant">أرقام محدثة من سجل مشاريعنا المنجزة</span>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div>
</section>
<?php endif; ?>

<!-- 3. Target Businesses -->
<?php if (!empty($targetBusinesses)): ?>
<section class="py-24 md:py-32 px-4 md:px-8 relative" style="background: linear-gradient(180deg, rgba(20,26,50,0.5) 0%, rgba(11,18,41,0) 100%);">
  <div class="max-w-screen-xl mx-auto">
    <div class="text-center mb-16 space-y-5">
      <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-white/5 text-on-surface-variant text-xs font-bold uppercase tracking-widest">
        <span class="material-symbols-outlined text-sm" aria-hidden="true" style="color: <?= $accentHex ?>;">apartment</span>
        القطاعات المستهدفة
      </div>
      <h2 class="reveal text-3xl sm:text-4xl md:text-5xl font-headline font-bold text-white">من يستفيد من هذه الخدمة؟</h2>
      <p class="reveal text-on-surface-variant text-lg max-w-2xl mx-auto">نخدم طيفاً واسعاً من القطاعات بحلول مصممة خصيصاً لكل احتياج</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($targetBusinesses as $i => $biz):
        $isCyan = ($i % 2 === 0);
        $bColor = $isCyan ? $accentHex : ($isGold ? '#00f2ff' : '#D4AF37');
      ?>
      <div class="reveal sd-stagger-<?= min($i + 1, 6) ?> sd-target-card glass-panel rounded-[2rem] p-8 text-center space-y-5 border border-white/5 group">
        <div class="sd-target-icon w-16 h-16 rounded-2xl flex items-center justify-center mx-auto" style="background: <?= $bColor ?>10; border: 1px solid <?= $bColor ?>15;">
          <span class="material-symbols-outlined text-3xl" aria-hidden="true" style="color: <?= $bColor ?>;"><?= e($biz['icon']) ?></span>
        </div>
        <h3 class="text-white font-bold text-lg"><?= e($biz['title']) ?></h3>
        <p class="text-on-surface-variant text-sm leading-relaxed"><?= e($biz['description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Divider -->
<div class="sd-divider mx-4 md:mx-8"></div>

<!-- 4. Benefits -->
<?php if (!empty($benefits)): ?>
<section class="py-24 md:py-32 px-4 md:px-8 relative">
  <div class="absolute -top-40 right-[20%] w-[500px] h-[500px] blur-[150px] rounded-full pointer-events-none" style="background: <?= $accentHex ?>04;"></div>

  <div class="max-w-screen-xl mx-auto relative z-10">
    <div class="text-center mb-16 space-y-5">
      <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest"
           style="background: <?= $accentHex ?>08; border: 1px solid <?= $accentHex ?>12; color: <?= $accentHex ?>;">
        <span class="material-symbols-outlined text-sm" aria-hidden="true">star</span>
        المزايا
      </div>
      <h2 class="reveal text-3xl sm:text-4xl md:text-5xl font-headline font-bold text-white">المزايا التي نقدمها</h2>
      <p class="reveal text-on-surface-variant text-lg max-w-2xl mx-auto">نضمن لك حلاً رقمياً يتفوق على المنافسين بكل المقاييس</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($benefits as $i => $benefit):
        $isCyan = ($i % 2 === 0);
        $bfColor = $isCyan ? $accentHex : ($isGold ? '#00f2ff' : '#D4AF37');
      ?>
      <div class="reveal sd-stagger-<?= min($i + 1, 6) ?> sd-benefit-card bg-surface-container rounded-[2rem] p-7 space-y-4 relative overflow-hidden border border-white/5 hover:border-white/10">
        <!-- Numbered index -->
        <div class="sd-benefit-idx" style="background: <?= $bfColor ?>12; color: <?= $bfColor ?>; border-color: <?= $bfColor ?>20;">
          <?= toArabicNumerals($i + 1) ?>
        </div>

        <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: <?= $bfColor ?>10;">
          <span class="material-symbols-outlined text-2xl" aria-hidden="true" style="color: <?= $bfColor ?>; font-variation-settings: 'FILL' 1;"><?= e($benefit['icon']) ?></span>
        </div>
        <h3 class="text-white font-bold text-xl"><?= e($benefit['title']) ?></h3>
        <p class="text-on-surface-variant leading-relaxed text-sm"><?= e($benefit['description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 5. Tech Stack -->
<?php if (!empty($techStack)): ?>
<section class="py-24 md:py-32 px-4 md:px-8 relative overflow-hidden" style="background: linear-gradient(180deg, rgba(20,26,50,0.5) 0%, rgba(11,18,41,0) 100%);">
  <div class="max-w-screen-xl mx-auto">
    <div class="text-center mb-16 space-y-5">
      <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-white/5 text-on-surface-variant text-xs font-bold uppercase tracking-widest">
        <span class="material-symbols-outlined text-sm" aria-hidden="true" style="color: <?= $accentHex ?>;">code</span>
        البنية التقنية
      </div>
      <h2 class="reveal text-3xl sm:text-4xl md:text-5xl font-headline font-bold text-white">التقنيات التي نستخدمها</h2>
      <p class="reveal text-on-surface-variant text-lg max-w-2xl mx-auto">نختار أفضل التقنيات العالمية لبناء حلول رقمية متينة وقابلة للتطوير</p>
    </div>

    <!-- Infinite scrolling tech stack -->
    <div class="reveal relative overflow-hidden" dir="ltr">
      <!-- Fade masks -->
      <div class="absolute left-0 top-0 bottom-0 w-24 z-10 pointer-events-none" style="background: linear-gradient(90deg, #0b1229 0%, transparent 100%);"></div>
      <div class="absolute right-0 top-0 bottom-0 w-24 z-10 pointer-events-none" style="background: linear-gradient(-90deg, #0b1229 0%, transparent 100%);"></div>

      <div class="sd-tech-track py-4">
        <?php for ($rep = 0; $rep < 2; $rep++): ?>
          <?php foreach ($techStack as $ti => $tech):
            $tColor = ($ti % 2 === 0) ? $accentHex : ($isGold ? '#00f2ff' : '#D4AF37');
          ?>
          <span class="flex-shrink-0 px-7 py-4 rounded-2xl border text-sm font-bold transition-all duration-300 hover:scale-105 cursor-default select-none"
                style="background: rgba(34,41,65,0.8); border-color: <?= $tColor ?>15; color: <?= $tColor ?>;"
                onmouseover="this.style.borderColor='<?= $tColor ?>40'; this.style.boxShadow='0 0 20px <?= $tColor ?>15'"
                onmouseout="this.style.borderColor='<?= $tColor ?>15'; this.style.boxShadow='none'">
            <?= e($tech) ?>
          </span>
          <?php endforeach; ?>
        <?php endfor; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Divider -->
<div class="sd-divider mx-4 md:mx-8"></div>

<!-- 6. Workflow Timeline -->
<?php if (!empty($workflow)): ?>
<section class="py-24 md:py-32 px-4 md:px-8 relative">
  <div class="absolute -top-40 left-[40%] w-[500px] h-[500px] blur-[150px] rounded-full pointer-events-none" style="background: <?= $accentHex ?>04;"></div>

  <div class="max-w-screen-xl mx-auto relative z-10">
    <div class="text-center mb-16 md:mb-20 space-y-5">
      <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest"
           style="background: <?= $accentHex ?>08; border: 1px solid <?= $accentHex ?>12; color: <?= $accentHex ?>;">
        <span class="material-symbols-outlined text-sm" aria-hidden="true">timeline</span>
        منهجية العمل
      </div>
      <h2 class="reveal text-3xl sm:text-4xl md:text-5xl font-headline font-bold text-white">مراحل العمل</h2>
      <p class="reveal text-on-surface-variant text-lg max-w-2xl mx-auto">منهجية عمل واضحة ومنظمة لضمان تسليم مشروعك في الوقت المحدد وبالجودة المطلوبة</p>
    </div>

    <div class="max-w-3xl mx-auto">
      <div class="relative">
        <!-- Timeline line -->
        <div class="sd-timeline-line" aria-hidden="true"></div>

        <div class="space-y-0">
          <?php foreach ($workflow as $i => $step):
            $isLast = ($i === count($workflow) - 1);
          ?>
          <div class="sd-timeline-step flex items-start gap-8 <?= $isLast ? '' : 'pb-14' ?> reveal sd-stagger-<?= min($i + 1, 6) ?>">
            <!-- Numbered dot -->
            <div class="sd-timeline-dot">
              <?= toArabicNumerals($i + 1) ?>
            </div>
            <!-- Card -->
            <div class="sd-timeline-card glass-panel rounded-[1.5rem] p-8 flex-1 border border-white/5">
              <h3 class="text-xl font-bold text-white mb-3"><?= e($step['title']) ?></h3>
              <p class="text-on-surface-variant leading-relaxed"><?= e($step['description']) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 7. FAQ -->
<?php if (!empty($faq)): ?>
<section id="service-faq" class="py-24 md:py-32 px-4 md:px-8 relative" style="background: linear-gradient(180deg, rgba(20,26,50,0.5) 0%, rgba(11,18,41,0) 100%);">
  <div class="max-w-3xl mx-auto">
    <div class="text-center mb-16 md:mb-20 space-y-5">
      <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-white/5 text-on-surface-variant text-xs font-bold uppercase tracking-widest">
        <span class="material-symbols-outlined text-sm" aria-hidden="true" style="color: <?= $accentHex ?>;">quiz</span>
        الأسئلة الشائعة
      </div>
      <h2 class="reveal text-3xl sm:text-4xl md:text-5xl font-headline font-bold text-white">إجابات على أسئلتكم</h2>
      <p class="reveal text-on-surface-variant text-lg">إجابات مباشرة على أكثر الأسئلة التي يطرحها عملاؤنا</p>
    </div>

    <div class="reveal glass-panel rounded-[2rem] overflow-hidden border border-white/5">
      <?php foreach ($faq as $i => $item):
        $isLast = ($i === count($faq) - 1);
        $faqId  = 'faq-item-' . $i;
        $btnId  = 'faq-btn-' . $i;
        $ansId  = 'faq-answer-' . $i;
      ?>
      <div id="<?= $faqId ?>" class="sd-faq-item faq-item <?= $isLast ? '' : 'border-b border-white/5' ?>">
        <button
          id="<?= $btnId ?>"
          class="faq-btn w-full flex justify-between items-center py-6 px-8 text-right cursor-pointer group"
          aria-expanded="false"
          aria-controls="<?= $ansId ?>"
        >
          <span class="sd-faq-q text-lg font-bold text-white transition-colors">
            <?= e($item['question']) ?>
          </span>
          <span class="material-symbols-outlined sd-faq-icon faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="<?= $ansId ?>" class="sd-faq-answer faq-answer" role="region" aria-labelledby="<?= $btnId ?>">
          <p class="pb-6 px-8 text-on-surface-variant leading-relaxed"><?= e($item['answer']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 8. Full Content (raw HTML) -->
<?php if (!empty($service['full_content'])): ?>
<div class="sd-divider mx-4 md:mx-8"></div>
<section class="py-20 px-4 md:px-8">
  <div class="max-w-screen-xl mx-auto blog-content glass-panel rounded-[2rem] p-8 md:p-12 border border-white/5">
    <?= sanitizeHtml($service['full_content']) ?>
  </div>
</section>
<?php endif; ?>

<!-- Related Services (from DB) -->
<?php if (!empty($relatedServices)): ?>
<div class="sd-divider mx-4 md:mx-8"></div>
<section class="py-24 md:py-32 px-4 md:px-8 relative">
  <div class="absolute -top-40 right-[20%] w-[400px] h-[400px] bg-primary-container/3 blur-[150px] rounded-full pointer-events-none"></div>

  <div class="max-w-screen-xl mx-auto relative z-10">
    <div class="text-center mb-16 space-y-5">
      <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-white/5 text-on-surface-variant text-xs font-bold uppercase tracking-widest">
        <span class="material-symbols-outlined text-sm" aria-hidden="true" style="color: <?= $accentHex ?>;">apps</span>
        خدمات أخرى
      </div>
      <h2 class="reveal text-3xl sm:text-4xl md:text-5xl font-headline font-bold text-white">استكشف خدماتنا الأخرى</h2>
      <p class="reveal text-on-surface-variant text-lg max-w-2xl mx-auto">منظومة متكاملة من الحلول التقنية لتلبية جميع احتياجاتك الرقمية</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
      <?php foreach ($relatedServices as $ri => $rel):
        $relIsGold = ($rel['color_scheme'] === 'gold');
        $relAccent = $relIsGold ? '#D4AF37' : '#00f2ff';
        $relAccent2 = $relIsGold ? '#e8c84a' : '#4cd6ff';
      ?>
      <a href="service-detail.php?slug=<?= e($rel['slug']) ?>"
         class="reveal group glass-panel card-hover rounded-[2rem] p-8 border border-white/5 transition-all duration-500 relative overflow-hidden"
         style="transition-delay: <?= ($ri * 100) ?>ms; --accent-color: <?= $relAccent ?>;">

        <!-- Background icon -->
        <div class="absolute -bottom-4 -left-4 text-[8rem] leading-none select-none pointer-events-none opacity-[0.03] group-hover:opacity-[0.07] transition-opacity duration-700" aria-hidden="true">
          <span class="material-symbols-outlined" style="font-size: inherit; font-variation-settings: 'FILL' 1;"><?= e($rel['icon']) ?></span>
        </div>

        <div class="relative z-10 space-y-5">
          <!-- Icon -->
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-400 group-hover:scale-105"
               style="background: <?= $relAccent ?>10; border: 1px solid <?= $relAccent ?>15;">
            <span class="material-symbols-outlined text-3xl" aria-hidden="true" style="color: <?= $relAccent ?>; font-variation-settings: 'FILL' 0, 'wght' 300;"><?= e($rel['icon']) ?></span>
          </div>

          <!-- Title -->
          <h3 class="text-xl font-bold text-white group-hover:text-white/90 transition-colors"><?= e($rel['title']) ?></h3>

          <!-- Description -->
          <p class="text-on-surface-variant text-sm leading-relaxed line-clamp-3"><?= e(truncateText($rel['description'], 120)) ?></p>

          <!-- Link arrow -->
          <span class="inline-flex items-center gap-2 text-sm font-bold group-hover:gap-3 transition-all" style="color: <?= $relAccent ?>;">
            اكتشف الخدمة
            <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
          </span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="px-4 md:px-8 py-16 mb-16 relative">
  <div class="max-w-5xl mx-auto">
    <div class="relative rounded-[3rem] overflow-hidden border border-white/5 shadow-2xl">
      <!-- Background -->
      <div class="absolute inset-0 bg-gradient-to-br from-surface-container-high via-surface-container to-surface-dim"></div>
      <div class="absolute inset-0 circuit-bg opacity-15"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 blur-[100px] rounded-full animate-float-orb" style="background: <?= $accentHex ?>08;"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-sand-gold/5 blur-[100px] rounded-full" style="animation: float-orb 12s ease-in-out infinite reverse;"></div>

      <!-- Content -->
      <div class="relative z-10 p-12 md:p-20 text-center">
        <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-8"
             style="background: <?= $accentHex ?>10; border: 1px solid <?= $accentHex ?>15; color: <?= $accentHex ?>;">
          <span class="material-symbols-outlined text-sm" aria-hidden="true">rocket_launch</span>
          ابدأ الآن
        </div>
        <h2 class="reveal text-3xl sm:text-4xl md:text-5xl font-headline font-bold mb-6 leading-tight text-white">
          ابدأ رحلة التميز الرقمي اليوم
        </h2>
        <p class="reveal text-on-surface-variant text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
          فريقنا التقني مستعد لتحويل احتياجاتك إلى حلول رقمية متكاملة. تواصل معنا الآن لنبدأ رحلتك نحو التحول الرقمي.
        </p>
        <div class="reveal flex flex-col sm:flex-row justify-center gap-5">
          <a href="contact.php" class="inline-flex items-center justify-center gap-2 px-10 py-4.5 rounded-2xl font-bold text-lg transition-all active:scale-[0.97]"
             style="background: linear-gradient(135deg, <?= $accentHex ?>, <?= $accentHex2 ?>); color: #0b1229; box-shadow: 0 0 40px <?= $accentHex ?>30;"
             onmouseover="this.style.boxShadow='0 0 60px <?= $accentHex ?>50'"
             onmouseout="this.style.boxShadow='0 0 40px <?= $accentHex ?>30'">
            <span class="material-symbols-outlined" aria-hidden="true">chat</span>
            تواصل معنا
          </a>
          <a href="contact.php" class="inline-flex items-center justify-center gap-2 bg-white/5 border border-white/10 text-white px-10 py-4.5 rounded-2xl font-bold text-lg hover:bg-white/10 hover:border-white/20 transition-all">
            <span class="material-symbols-outlined" aria-hidden="true">description</span>
            اطلب عرض سعر
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
