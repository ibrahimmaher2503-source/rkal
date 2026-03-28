<?php
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) { http_response_code(404); require_once '404.php'; exit; }

$db = getDB();
$stmt = $db->prepare('SELECT * FROM blogs WHERE slug = :slug AND is_active = 1');
$stmt->execute(['slug' => $slug]);
$post = $stmt->fetch();

if (!$post) { http_response_code(404); require_once '404.php'; exit; }

// Category color helper
function categoryColor(string $cat): string {
    $gold = ['أمن المعلومات', 'رؤية ٢٠٣٠'];
    return in_array($cat, $gold) ? 'sand-gold' : 'primary-container';
}

// Category icon helper
function categoryIcon(string $cat): string {
    $map = [
        'التحول الرقمي'     => 'transform',
        'الذكاء الاصطناعي'  => 'psychology',
        'أمن المعلومات'      => 'shield',
        'تطوير البرمجيات'   => 'code',
        'رؤية ٢٠٣٠'         => 'flag',
    ];
    return $map[$cat] ?? 'article';
}

$pageTitle = 'ركال | ' . e($post['title']);
$activePage = 'blog';
require_once 'includes/header.php';

$color     = categoryColor($post['category']);
$icon      = categoryIcon($post['category']);
$colorHex  = ($color === 'sand-gold') ? '#D4AF37' : '#00f2ff';
?>

  <!-- ===== Hero / Breadcrumb ===== -->
  <section class="py-16 px-4 md:px-8 relative overflow-hidden">
    <div class="circuit-bg absolute inset-0 opacity-40"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-container/8 blur-[120px] rounded-full z-0"></div>
    <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-sand-gold/5 blur-[100px] rounded-full z-0"></div>

    <div class="relative z-10 max-w-4xl mx-auto">

      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-sm text-on-surface-variant mb-8 reveal" aria-label="مسار التنقل">
        <a href="index.php" class="text-primary-container hover:text-white transition-colors">الرئيسية</a>
        <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_left</span>
        <a href="blog.php" class="text-primary-container hover:text-white transition-colors">المدونة</a>
        <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_left</span>
        <span class="truncate max-w-[200px] md:max-w-xs"><?= e($post['title']) ?></span>
      </nav>

      <!-- Category badge + meta -->
      <div class="flex flex-wrap items-center gap-3 mb-6 reveal">
        <span class="inline-flex items-center gap-1.5 bg-<?= e($color) ?>/10 border border-<?= e($color) ?>/20 text-<?= e($color) ?> text-xs font-bold px-3 py-1.5 rounded-full">
          <span class="material-symbols-outlined text-sm" aria-hidden="true"><?= e($icon) ?></span>
          <?= e($post['category']) ?>
        </span>
        <span class="text-on-surface-variant text-sm flex items-center gap-1.5">
          <span class="material-symbols-outlined text-sm" aria-hidden="true">calendar_today</span>
          <?= e(formatArabicDate($post['published_at'])) ?>
        </span>
        <span class="text-on-surface-variant text-sm flex items-center gap-1.5">
          <span class="material-symbols-outlined text-sm" aria-hidden="true">schedule</span>
          <?= e(toArabicNumerals($post['read_time'])) ?> دقائق للقراءة
        </span>
      </div>

      <!-- Title -->
      <h1 class="text-4xl md:text-5xl font-black text-white leading-tight mb-8 reveal">
        <?= e($post['title']) ?>
      </h1>

      <!-- Featured image -->
      <?php if (!empty($post['featured_image'])): ?>
        <img
          src="<?= e($post['featured_image']) ?>"
          alt="<?= e($post['title']) ?>"
          class="w-full rounded-[2rem] mb-8 reveal"
        />
      <?php else: ?>
        <div class="relative w-full aspect-video bg-surface-container-high rounded-[2rem] mb-8 flex items-center justify-center overflow-hidden reveal">
          <div class="absolute inset-0 bg-gradient-to-br from-primary-container/10 via-transparent to-sand-gold/10"></div>
          <div class="absolute inset-0 circuit-bg opacity-60"></div>
          <div class="relative z-10 flex flex-col items-center gap-3 text-white/15">
            <span class="material-symbols-outlined text-8xl" aria-hidden="true" style="font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 48;">article</span>
          </div>
        </div>
      <?php endif; ?>

      <!-- Author row -->
      <div class="flex items-center gap-4 mb-10 reveal">
        <div class="w-12 h-12 rounded-full bg-primary-container/20 border border-primary-container/30 flex items-center justify-center flex-shrink-0">
          <span class="material-symbols-outlined text-primary-container text-xl" aria-hidden="true">person</span>
        </div>
        <div>
          <div class="text-sm font-bold text-white"><?= e($post['author']) ?></div>
          <div class="text-xs text-on-surface-variant"><?= e(toArabicNumerals($post['read_time'])) ?> دقائق للقراءة</div>
        </div>
      </div>

    </div>
  </section>

  <!-- Section divider -->
  <div class="section-divider max-w-4xl mx-auto mb-8 px-4 md:px-8"></div>

  <!-- ===== Article Body ===== -->
  <section class="pb-20 px-4 md:px-8">
    <div class="max-w-4xl mx-auto">
      <div class="blog-content glass-panel rounded-[2rem] p-8 md:p-12">
        <?= $post['content'] ?>
      </div>
    </div>
  </section>

  <!-- ===== Back Link ===== -->
  <section class="pb-24 px-4 md:px-8">
    <div class="max-w-4xl mx-auto">
      <a href="blog.php" class="inline-flex items-center gap-2 text-sm font-bold transition-all hover:gap-3" style="color: <?= $colorHex ?>;">
        <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
        العودة إلى المدونة
      </a>
    </div>
  </section>

<?php require_once 'includes/footer.php'; ?>
