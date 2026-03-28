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

$pageTitle  = 'ركال | ' . e($service['title']);
$activePage = 'services';
require_once 'includes/header.php';
?>

<!-- 1. Hero Section -->
<section id="service-hero" class="py-24 px-4 md:px-8 relative overflow-hidden">
  <div class="circuit-bg absolute inset-0 opacity-60"></div>
  <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-container/10 blur-[120px] rounded-full z-0 animate-float-orb"></div>
  <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-sand-gold/8 blur-[100px] rounded-full z-0"></div>
  <div class="relative z-10 max-w-screen-xl mx-auto">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-on-surface-variant mb-10 reveal" aria-label="مسار التنقل">
      <a href="index.php" class="text-primary-container hover:text-white transition-colors">الرئيسية</a>
      <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_left</span>
      <a href="services.php" class="text-primary-container hover:text-white transition-colors">خدماتنا</a>
      <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_left</span>
      <span><?= e($service['title']) ?></span>
    </nav>

    <!-- Badge -->
    <div class="inline-flex items-center gap-2 bg-primary-container/10 border border-primary-container/20 text-primary-container px-4 py-2 rounded-xl text-sm font-medium mb-8 reveal">
      <span class="material-symbols-outlined text-base" aria-hidden="true"><?= e($service['icon']) ?></span>
      <span>خدمة متخصصة</span>
    </div>

    <!-- Headline -->
    <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-headline font-black text-white leading-tight mb-6 max-w-4xl reveal">
      <span class="text-transparent bg-clip-text" style="background: linear-gradient(135deg, #00f2ff 0%, #4cd6ff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?= e($service['title']) ?></span>
    </h1>

    <!-- Subtitle -->
    <?php if (!empty($service['description'])): ?>
    <p class="text-lg md:text-xl text-on-surface-variant leading-relaxed max-w-2xl reveal">
      <?= e($service['description']) ?>
    </p>
    <?php endif; ?>

  </div>
</section>

<!-- 2. Service Overview -->
<?php if (!empty($service['overview_content'])): ?>
<section class="py-32 px-4 md:px-8">
  <div class="max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

    <!-- Right Column: Overview Text -->
    <div class="space-y-8">
      <div>
        <h2 class="text-2xl sm:text-4xl font-headline font-bold text-white mb-4 reveal">نظرة شاملة على الخدمة</h2>
        <div class="w-16 h-1 bg-sand-gold rounded-full"></div>
      </div>
      <div class="text-on-surface-variant leading-relaxed text-lg space-y-4 reveal">
        <?= $service['overview_content'] ?>
      </div>
    </div>

    <!-- Left Column: Stats Card -->
    <?php if (!empty($stats)): ?>
    <div class="glass-panel rounded-[2rem] p-10 space-y-8 border border-white/5 reveal">
      <h3 class="text-xl font-bold text-white mb-6">أرقامنا تتحدث</h3>
      <div class="grid grid-cols-2 gap-6">
        <?php foreach ($stats as $i => $stat): ?>
          <?php $isEven = ($i % 2 === 0); ?>
          <div class="bg-surface-container rounded-[1.5rem] p-6 text-center space-y-2">
            <div class="text-4xl font-black <?= $isEven ? 'text-primary-container' : 'text-sand-gold' ?>">
              <?= e($stat['value']) ?>
            </div>
            <div class="text-sm text-on-surface-variant leading-relaxed"><?= e($stat['label']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</section>
<?php endif; ?>

<!-- 3. Target Businesses -->
<?php if (!empty($targetBusinesses)): ?>
<section class="py-32 px-4 md:px-8 bg-surface-container-low">
  <div class="max-w-screen-xl mx-auto">
    <div class="text-center mb-16 space-y-4 reveal">
      <h2 class="text-4xl md:text-5xl font-headline font-bold text-white">من يستفيد من هذه الخدمة؟</h2>
      <p class="text-on-surface-variant text-lg max-w-2xl mx-auto">نخدم طيفاً واسعاً من القطاعات بحلول مصممة خصيصاً لكل احتياج</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($targetBusinesses as $i => $biz):
        $isCyan = ($i % 2 === 0);
        $colorText   = $isCyan ? 'text-[#00f2ff]'    : 'text-sand-gold';
        $colorBg     = $isCyan ? 'bg-primary-container/10' : 'bg-sand-gold/10';
        $colorBgHov  = $isCyan ? 'group-hover:bg-primary-container/20' : 'group-hover:bg-sand-gold/20';
        $hoverBorder = $isCyan ? 'hover:border-primary-container/30' : 'hover:border-sand-gold/30';
      ?>
      <div class="glass-panel card-hover reveal rounded-[2rem] p-8 text-center space-y-4 border border-white/5 <?= $hoverBorder ?> transition-all duration-300 group">
        <div class="w-16 h-16 rounded-2xl <?= $colorBg ?> <?= $colorBgHov ?> flex items-center justify-center mx-auto transition-all">
          <span class="material-symbols-outlined text-3xl <?= $colorText ?>" aria-hidden="true"><?= e($biz['icon']) ?></span>
        </div>
        <h3 class="text-white font-bold text-lg"><?= e($biz['title']) ?></h3>
        <p class="text-on-surface-variant text-sm leading-relaxed"><?= e($biz['description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 4. Benefits -->
<?php if (!empty($benefits)): ?>
<section class="py-32 px-4 md:px-8">
  <div class="max-w-screen-xl mx-auto">
    <div class="text-center mb-16 space-y-4 reveal">
      <h2 class="text-4xl md:text-5xl font-headline font-bold text-white">المزايا التي نقدمها</h2>
      <p class="text-on-surface-variant text-lg max-w-2xl mx-auto">نضمن لك حلاً رقمياً يتفوق على المنافسين بكل المقاييس</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($benefits as $i => $benefit):
        $isCyan    = ($i % 2 === 0);
        $colorText = $isCyan ? 'text-primary-container' : 'text-sand-gold';
        $colorBg   = $isCyan ? 'bg-primary-container/10' : 'bg-sand-gold/10';
      ?>
      <div class="bg-surface-container rounded-[2rem] p-6 space-y-4 hover:bg-surface-container-high transition-all duration-300 card-hover reveal">
        <div class="w-12 h-12 rounded-xl <?= $colorBg ?> flex items-center justify-center">
          <span class="material-symbols-outlined text-2xl <?= $colorText ?>" aria-hidden="true"><?= e($benefit['icon']) ?></span>
        </div>
        <h3 class="text-white font-bold text-xl"><?= e($benefit['title']) ?></h3>
        <p class="text-on-surface-variant leading-relaxed"><?= e($benefit['description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 5. Tech Stack -->
<?php if (!empty($techStack)): ?>
<section class="py-32 px-4 md:px-8 bg-surface-container-low/50">
  <div class="max-w-screen-xl mx-auto">
    <div class="text-center mb-16 space-y-4 reveal">
      <h2 class="text-4xl md:text-5xl font-headline font-bold text-white">التقنيات التي نستخدمها</h2>
      <p class="text-on-surface-variant text-lg max-w-2xl mx-auto">نختار أفضل التقنيات العالمية لبناء حلول رقمية متينة وقابلة للتطوير</p>
    </div>
    <div class="flex flex-wrap gap-4 justify-center">
      <?php foreach ($techStack as $tech): ?>
      <span class="bg-surface-container-high px-6 py-3 rounded-xl border border-white/5 text-sm text-on-surface font-medium card-hover reveal">
        <?= e($tech) ?>
      </span>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 6. Workflow -->
<?php if (!empty($workflow)): ?>
<section class="py-32 px-4 md:px-8">
  <div class="max-w-screen-xl mx-auto">
    <div class="text-center mb-16 space-y-4 reveal">
      <h2 class="text-4xl md:text-5xl font-headline font-bold text-white">مراحل العمل</h2>
      <p class="text-on-surface-variant text-lg max-w-2xl mx-auto">منهجية عمل واضحة ومنظمة لضمان تسليم مشروعك في الوقت المحدد وبالجودة المطلوبة</p>
    </div>
    <div class="max-w-3xl mx-auto">
      <div class="relative">
        <!-- Vertical line -->
        <div class="absolute right-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-primary-container via-primary-container/50 to-transparent" aria-hidden="true"></div>
        <div class="space-y-0">
          <?php foreach ($workflow as $i => $step):
            $isLast = ($i === count($workflow) - 1);
          ?>
          <div class="flex items-start gap-8 <?= $isLast ? '' : 'pb-12' ?>">
            <div class="relative z-10 flex-shrink-0 w-12 h-12 rounded-full tech-gradient flex items-center justify-center text-on-primary-fixed font-black text-lg shadow-[0_0_20px_rgba(0,242,255,0.4)]">
              <?= toArabicNumerals($i + 1) ?>
            </div>
            <div class="glass-panel card-hover reveal rounded-[1.5rem] p-8 flex-1 border border-white/5">
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
<section id="service-faq" class="py-32 px-4 md:px-8 bg-surface-container-low">
  <div class="max-w-3xl mx-auto">
    <div class="text-center mb-20 space-y-4 reveal">
      <h2 class="text-4xl md:text-5xl font-headline font-bold text-white">الأسئلة الشائعة</h2>
      <p class="text-on-surface-variant">إجابات مباشرة على أكثر الأسئلة التي يطرحها عملاؤنا</p>
    </div>
    <div class="space-y-0">
      <?php foreach ($faq as $i => $item):
        $isLast = ($i === count($faq) - 1);
        $faqId  = 'faq-item-' . $i;
        $btnId  = 'faq-btn-' . $i;
        $ansId  = 'faq-answer-' . $i;
      ?>
      <div id="<?= $faqId ?>" class="faq-item <?= $isLast ? '' : 'border-b border-white/5' ?>">
        <button
          id="<?= $btnId ?>"
          class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group"
          aria-expanded="false"
          aria-controls="<?= $ansId ?>"
        >
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">
            <?= e($item['question']) ?>
          </span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="<?= $ansId ?>" class="faq-answer" role="region" aria-labelledby="<?= $btnId ?>">
          <p class="pb-6 text-on-surface-variant leading-relaxed"><?= e($item['answer']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 8. Full Content (raw HTML) -->
<?php if (!empty($service['full_content'])): ?>
<section class="py-20 px-4 md:px-8">
  <div class="max-w-screen-xl mx-auto prose prose-invert max-w-none text-on-surface-variant leading-relaxed">
    <?= $service['full_content'] ?>
  </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section id="service-cta" class="px-4 md:px-8 py-16 mb-16">
  <div class="max-w-7xl mx-auto">
    <div class="relative rounded-[3rem] bg-gradient-to-br from-surface-container-high to-surface-dim p-12 md:p-24 overflow-hidden border border-white/5 shadow-2xl">
      <div class="absolute inset-0 circuit-bg opacity-20 pointer-events-none"></div>
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-container/5 blur-[120px] rounded-full"></div>
      <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-sand-gold/5 blur-[120px] rounded-full"></div>
      <div class="relative z-10 text-center max-w-3xl mx-auto">
        <h2 class="text-2xl sm:text-4xl md:text-5xl font-headline font-bold mb-8 leading-tight text-white">
          ابدأ رحلة التميز الرقمي اليوم
        </h2>
        <p class="text-on-surface-variant text-lg mb-12">
          فريقنا التقني مستعد لتحويل احتياجاتك إلى حلول رقمية متكاملة. تواصل معنا الآن لنبدأ رحلتك نحو التحول الرقمي.
        </p>
        <div class="flex flex-col md:flex-row justify-center gap-6">
          <a href="contact.php" class="tech-gradient text-on-primary-fixed px-12 py-5 rounded-2xl font-bold text-lg hover:shadow-[0_0_50px_rgba(0,242,255,0.4)] transition-all active:scale-95">تواصل معنا</a>
          <a href="contact.php" class="bg-white/5 border border-white/10 text-white px-12 py-5 rounded-2xl font-bold text-lg hover:bg-white/10 transition-all">اطلب عرض سعر</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
