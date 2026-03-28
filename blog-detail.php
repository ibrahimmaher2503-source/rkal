<?php
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) { render404(); }

$db = getDB();
$stmt = $db->prepare('SELECT * FROM blogs WHERE slug = :slug AND is_active = 1');
$stmt->execute(['slug' => $slug]);
$post = $stmt->fetch();

if (!$post) { render404(); }

// Fetch related posts (same category, excluding current, limit 3)
$relStmt = $db->prepare(
    'SELECT id, title, slug, excerpt, category, category_color, featured_image, read_time, published_at
     FROM blogs
     WHERE is_active = 1 AND id != :id AND category = :cat
     ORDER BY published_at DESC
     LIMIT 3'
);
$relStmt->execute(['id' => $post['id'], 'cat' => $post['category']]);
$relatedPosts = $relStmt->fetchAll();

// If fewer than 3 related, fill with recent posts from other categories
if (count($relatedPosts) < 3) {
    $existingIds = array_merge([$post['id']], array_column($relatedPosts, 'id'));
    $placeholders = implode(',', array_fill(0, count($existingIds), '?'));
    $fillStmt = $db->prepare(
        "SELECT id, title, slug, excerpt, category, category_color, featured_image, read_time, published_at
         FROM blogs
         WHERE is_active = 1 AND id NOT IN ($placeholders)
         ORDER BY published_at DESC
         LIMIT " . (3 - count($relatedPosts))
    );
    $fillStmt->execute($existingIds);
    $relatedPosts = array_merge($relatedPosts, $fillStmt->fetchAll());
}

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

  <!-- Reading Progress Bar -->
  <div class="reading-progress" aria-hidden="true">
    <div id="reading-bar" class="reading-progress-bar"></div>
  </div>

  <!-- ===== Hero / Breadcrumb ===== -->
  <section class="relative py-12 md:py-20 px-4 md:px-8 overflow-hidden hero-mesh">
    <div class="absolute inset-0 circuit-bg opacity-30 pointer-events-none"></div>
    <div class="absolute -top-40 -right-40 w-48 h-48 md:w-96 md:h-96 bg-primary-container/6 blur-[120px] rounded-full z-0 animate-float-orb"></div>
    <div class="absolute -bottom-20 -left-20 w-36 h-36 md:w-72 md:h-72 bg-sand-gold/5 blur-[100px] rounded-full z-0"></div>

    <div class="relative z-10 max-w-4xl mx-auto">

      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-sm text-on-surface-variant mb-10 reveal" aria-label="مسار التنقل">
        <a href="/" class="text-primary-container hover:text-white transition-colors">الرئيسية</a>
        <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_left</span>
        <a href="/blog" class="text-primary-container hover:text-white transition-colors">المدونة</a>
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
      <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[3.5rem] font-black text-white leading-[1.15] mb-10 reveal">
        <?= e($post['title']) ?>
      </h1>

      <!-- Featured image -->
      <?php if (!empty($post['featured_image'])): ?>
        <img
          src="<?= e($post['featured_image']) ?>"
          alt="<?= e($post['title']) ?>"
          class="w-full rounded-[2rem] mb-10 reveal border border-white/5 shadow-[0_8px_40px_rgba(0,0,0,0.4)]"
        />
      <?php else: ?>
        <div class="relative w-full aspect-video bg-surface-container-high rounded-[2rem] mb-10 flex items-center justify-center overflow-hidden reveal border border-white/5 shadow-[0_8px_40px_rgba(0,0,0,0.3)]">
          <div class="absolute inset-0 bg-gradient-to-br from-primary-container/10 via-transparent to-sand-gold/10"></div>
          <div class="absolute inset-0 circuit-bg opacity-40"></div>
          <div class="relative z-10 flex flex-col items-center gap-3 text-white/10">
            <span class="material-symbols-outlined text-8xl md:text-9xl" aria-hidden="true" style="font-variation-settings: 'FILL' 1, 'wght' 200, 'GRAD' 0, 'opsz' 48;">article</span>
          </div>
        </div>
      <?php endif; ?>

      <!-- Author row + share buttons -->
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 reveal">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-primary-container/15 border border-primary-container/20 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-primary-container text-xl" aria-hidden="true">person</span>
          </div>
          <div>
            <div class="text-sm font-bold text-white"><?= e($post['author']) ?></div>
            <div class="text-xs text-on-surface-variant"><?= e(toArabicNumerals($post['read_time'])) ?> دقائق للقراءة</div>
          </div>
        </div>
        <!-- Share buttons -->
        <div class="flex items-center gap-2">
          <span class="text-xs text-on-surface-variant/50 ml-2">مشاركة</span>
          <button onclick="navigator.clipboard.writeText(window.location.href)" class="share-btn w-9 h-9 rounded-xl bg-surface-container-high/80 flex items-center justify-center text-white/40 hover:text-primary-container hover:bg-primary-container/10" aria-label="نسخ الرابط">
            <span class="material-symbols-outlined text-lg" aria-hidden="true">link</span>
          </button>
          <a href="https://twitter.com/intent/tweet?url=<?= urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" rel="noopener" class="share-btn w-9 h-9 rounded-xl bg-surface-container-high/80 flex items-center justify-center text-white/40 hover:text-primary-container hover:bg-primary-container/10" aria-label="مشاركة على تويتر">
            <span class="material-symbols-outlined text-lg" aria-hidden="true">share</span>
          </a>
        </div>
      </div>

    </div>
  </section>

  <!-- Section divider -->
  <div class="section-divider max-w-4xl mx-auto mb-2 px-4 md:px-8"></div>

  <!-- ===== Article Body ===== -->
  <section id="article-body" class="py-12 md:py-16 px-4 md:px-8">
    <div class="max-w-4xl mx-auto">
      <div class="blog-content glass-panel rounded-[2rem] p-6 sm:p-8 md:p-12 lg:p-16">
        <?= sanitizeHtml($post['content']) ?>
      </div>
    </div>
  </section>

  <!-- ===== Related Posts (from DB) ===== -->
  <?php if (!empty($relatedPosts)): ?>
  <section class="py-20 px-4 md:px-8 relative" style="background: linear-gradient(180deg, rgba(20,26,50,0.4) 0%, rgba(11,18,41,0) 100%);">
    <div class="absolute -top-40 left-[30%] w-[400px] h-[400px] bg-primary-container/3 blur-[150px] rounded-full pointer-events-none"></div>

    <div class="max-w-screen-xl mx-auto relative z-10">
      <div class="text-center mb-14 space-y-4">
        <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-white/5 text-on-surface-variant text-xs font-bold uppercase tracking-widest">
          <span class="material-symbols-outlined text-sm text-[#00f2ff]" aria-hidden="true">auto_stories</span>
          مقالات ذات صلة
        </div>
        <h2 class="reveal text-3xl sm:text-4xl font-headline font-bold text-white">قد يعجبك أيضاً</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
        <?php foreach ($relatedPosts as $ri => $rel):
          $relColor = in_array($rel['category'], ['أمن المعلومات', 'رؤية ٢٠٣٠']) ? 'sand-gold' : 'primary-container';
          $relHex   = ($relColor === 'sand-gold') ? '#D4AF37' : '#00f2ff';
          $relIcon  = categoryIcon($rel['category']);
        ?>
        <a href="/blog/<?= e($rel['slug']) ?>"
           class="reveal blog-card group glass-panel card-hover rounded-[2rem] overflow-hidden border border-white/5 hover:border-<?= $relColor ?>/20 transition-all duration-500"
           style="transition-delay: <?= ($ri * 80) ?>ms;">

          <!-- Image / Placeholder -->
          <div class="blog-card-img relative aspect-[16/10] bg-surface-container-high">
            <?php if (!empty($rel['featured_image'])): ?>
              <img src="<?= e($rel['featured_image']) ?>" alt="<?= e($rel['title']) ?>"
                   class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" />
            <?php else: ?>
              <div class="w-full h-full flex items-center justify-center relative">
                <div class="absolute inset-0 bg-gradient-to-br from-<?= $relColor ?>/10 via-transparent to-sand-gold/5"></div>
                <div class="absolute inset-0 circuit-bg opacity-30"></div>
                <span class="blog-card-icon material-symbols-outlined text-6xl text-white/8 relative z-10" aria-hidden="true"
                      style="font-variation-settings: 'FILL' 1;"><?= e($relIcon) ?></span>
              </div>
            <?php endif; ?>
            <!-- Category badge overlay -->
            <div class="absolute top-4 right-4 z-10">
              <span class="inline-flex items-center gap-1 bg-surface/80 backdrop-blur-md text-xs font-bold px-2.5 py-1 rounded-lg border border-white/10"
                    style="color: <?= $relHex ?>;">
                <span class="material-symbols-outlined text-xs" aria-hidden="true"><?= e($relIcon) ?></span>
                <?= e($rel['category']) ?>
              </span>
            </div>
          </div>

          <!-- Content -->
          <div class="p-6 space-y-3">
            <div class="flex items-center gap-3 text-xs text-on-surface-variant">
              <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-xs" aria-hidden="true">calendar_today</span>
                <?= e(formatArabicDate($rel['published_at'])) ?>
              </span>
              <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-xs" aria-hidden="true">schedule</span>
                <?= e(toArabicNumerals($rel['read_time'])) ?> د
              </span>
            </div>
            <h3 class="text-lg font-bold text-white group-hover:text-<?= $relColor ?> transition-colors leading-snug line-clamp-2">
              <?= e($rel['title']) ?>
            </h3>
            <?php if (!empty($rel['excerpt'])): ?>
            <p class="text-sm text-on-surface-variant leading-relaxed line-clamp-2"><?= e(truncateText($rel['excerpt'], 100)) ?></p>
            <?php endif; ?>
            <span class="inline-flex items-center gap-1.5 text-xs font-bold group-hover:gap-2.5 transition-all" style="color: <?= $relHex ?>;">
              اقرأ المزيد
              <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_back</span>
            </span>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ===== Back Link + Navigation ===== -->
  <section class="pb-24 px-4 md:px-8">
    <div class="max-w-4xl mx-auto flex items-center justify-between">
      <a href="/blog" class="inline-flex items-center gap-2 text-sm font-bold transition-all hover:gap-3 group" style="color: <?= $colorHex ?>;">
        <span class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform" aria-hidden="true">arrow_forward</span>
        العودة إلى المدونة
      </a>
      <!-- Scroll to top -->
      <button onclick="window.scrollTo({top:0,behavior:'smooth'})" class="inline-flex items-center gap-2 text-sm text-on-surface-variant/50 hover:text-white transition-colors">
        <span class="material-symbols-outlined text-base" aria-hidden="true">vertical_align_top</span>
        أعلى الصفحة
      </button>
    </div>
  </section>

  <!-- Reading progress script -->
  <script>
  (function() {
    const bar = document.getElementById('reading-bar');
    const article = document.getElementById('article-body');
    if (!bar || !article) return;
    window.addEventListener('scroll', function() {
      const rect = article.getBoundingClientRect();
      const start = article.offsetTop - window.innerHeight;
      const end = article.offsetTop + article.offsetHeight;
      const scrolled = window.scrollY;
      const progress = Math.min(1, Math.max(0, (scrolled - start) / (end - start)));
      bar.style.width = (progress * 100) + '%';
    }, { passive: true });
  })();
  </script>

<?php require_once 'includes/footer.php'; ?>
