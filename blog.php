<?php
require_once 'includes/functions.php';

$db = getDB();

// Search & filter params
$category = $_GET['category'] ?? '';
$search = $_GET['q'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 6;
$offset = ($page - 1) * $perPage;

// Featured article
$stmt = $db->query('SELECT * FROM blogs WHERE is_featured = 1 AND is_active = 1 ORDER BY published_at DESC LIMIT 1');
$featured = $stmt->fetch();

// Build card query with optional filters
$where = 'WHERE is_active = 1 AND is_featured = 0';
$params = [];

if ($category) {
    $where .= ' AND category = :category';
    $params['category'] = $category;
}
if ($search) {
    $where .= ' AND (title LIKE :q OR excerpt LIKE :q)';
    $params['q'] = '%' . $search . '%';
}

// Count total for pagination
$countStmt = $db->prepare("SELECT COUNT(*) FROM blogs $where");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();

// Fetch page of cards — bind LIMIT/OFFSET as integers
$sql = "SELECT * FROM blogs $where ORDER BY published_at DESC LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
foreach ($params as $key => $val) { $stmt->bindValue($key, $val); }
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

$hasMore = $total > ($offset + $perPage);

// Category icon mapping
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

// Category color class helper (returns Tailwind color token)
function categoryColor(string $cat): string {
    $gold = ['أمن المعلومات', 'رؤية ٢٠٣٠'];
    return in_array($cat, $gold) ? 'sand-gold' : 'primary-container';
}

$pageTitle = 'ركال | المدونة - رؤى تقنية ومقالات متخصصة';
$activePage = 'blog';
require_once 'includes/header.php';
?>

  <!-- ===== 1. Hero Section ===== -->
  <section id="blog-hero" class="py-24 circuit-bg relative overflow-hidden">
    <!-- Background glow orbs -->
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute top-0 right-1/4 w-96 h-96 bg-primary-container/5 rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 left-1/4 w-72 h-72 bg-sand-gold/5 rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-4xl mx-auto px-4 md:px-8 text-center relative z-10">
      <!-- Badge -->
      <div class="inline-flex items-center gap-2 glass-panel rounded-full px-5 py-2.5 mb-8 reveal">
        <span class="material-symbols-outlined text-primary-container text-xl" aria-hidden="true">auto_stories</span>
        <span class="text-sm font-bold text-primary-container tracking-widest uppercase">المدونة التقنية</span>
      </div>
      <!-- Headline -->
      <h1 class="text-2xl sm:text-4xl md:text-6xl font-black leading-tight mb-6 text-white reveal">
        رؤى تقنية من قلب
        <span class="tech-gradient bg-clip-text text-transparent inline-block">المملكة</span>
      </h1>
      <!-- Subtitle -->
      <p class="text-lg md:text-xl text-on-surface-variant leading-relaxed max-w-2xl mx-auto reveal">
        نشارككم أعمق الرؤى وأحدث المقالات التقنية في مجال التحول الرقمي، والذكاء الاصطناعي، وأمن المعلومات — من منظور سعودي متخصص يواكب طموحات رؤية ٢٠٣٠.
      </p>
    </div>
  </section>

  <!-- ===== 2. Search + Categories Bar ===== -->
  <section class="py-8 border-b border-white/5 bg-surface/60 backdrop-blur-xl sticky top-20 z-40">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
      <div class="flex flex-col md:flex-row-reverse items-center gap-6">

        <!-- Search form (right in RTL) -->
        <form action="blog.php" method="GET" class="relative w-full md:w-80 flex-shrink-0">
          <?php if ($category): ?>
            <input type="hidden" name="category" value="<?= e($category) ?>">
          <?php endif; ?>
          <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl pointer-events-none" aria-hidden="true">search</span>
          <input
            type="search"
            name="q"
            value="<?= e($search) ?>"
            placeholder="ابحث في المقالات..."
            class="glass-panel w-full rounded-2xl py-3 pr-12 pl-5 text-sm text-white placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary-container/50 transition-all"
          />
        </form>

        <!-- Category Pills -->
        <div class="flex gap-3 overflow-x-auto pb-1 flex-1 w-full scrollbar-none">
          <a href="blog.php<?= $search ? '?q=' . urlencode($search) : '' ?>"
             class="flex-shrink-0 px-5 py-2.5 rounded-full text-sm font-bold transition-all <?= $category === '' ? 'bg-primary-container text-on-primary-fixed' : 'glass-panel text-on-surface-variant hover:text-white hover:border-primary-container/30' ?>">
            الكل
          </a>
          <?php
          $categories = [
              'التحول الرقمي',
              'الذكاء الاصطناعي',
              'أمن المعلومات',
              'تطوير البرمجيات',
              'رؤية ٢٠٣٠',
          ];
          foreach ($categories as $cat):
              $isActive = ($category === $cat);
              $href = 'blog.php?category=' . urlencode($cat) . ($search ? '&q=' . urlencode($search) : '');
          ?>
          <a href="<?= e($href) ?>"
             class="flex-shrink-0 px-5 py-2.5 rounded-full text-sm font-<?= $isActive ? 'bold' : 'medium' ?> transition-all <?= $isActive ? 'bg-primary-container text-on-primary-fixed' : 'glass-panel text-on-surface-variant hover:text-white hover:border-primary-container/30' ?>">
            <?= e($cat) ?>
          </a>
          <?php endforeach; ?>
        </div>

      </div>
    </div>
  </section>

  <!-- ===== 3. Featured Article ===== -->
  <?php if ($featured): ?>
  <section class="py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
      <div class="glass-panel rounded-[2rem] overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
          <!-- Image Area (right in RTL) -->
          <div class="relative aspect-video bg-surface-container-high flex items-center justify-center overflow-hidden md:rounded-r-[2rem] md:rounded-l-none rounded-t-[2rem] md:rounded-t-none">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-container/10 via-transparent to-sand-gold/10"></div>
            <div class="absolute inset-0 circuit-bg opacity-60"></div>
            <div class="relative z-10 flex flex-col items-center gap-4 text-white/20">
              <span class="material-symbols-outlined text-7xl" aria-hidden="true" style="font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 48;">article</span>
              <span class="text-sm font-medium tracking-widest uppercase">المقال المميز</span>
            </div>
            <!-- Featured badge -->
            <div class="absolute top-4 right-4 flex items-center gap-1.5 bg-sand-gold/90 text-on-primary-fixed px-3 py-1.5 rounded-full text-xs font-bold">
              <span class="material-symbols-outlined text-sm" aria-hidden="true" style="font-variation-settings: 'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 20;">star</span>
              مميز
            </div>
          </div>
          <!-- Content Area -->
          <div class="p-8 md:p-10 flex flex-col justify-center gap-6">
            <!-- Category + Date -->
            <?php
            $fColor = categoryColor($featured['category']);
            $fIcon  = categoryIcon($featured['category']);
            ?>
            <div class="flex items-center gap-3 flex-wrap">
              <span class="inline-flex items-center gap-1.5 bg-<?= e($fColor) ?>/10 border border-<?= e($fColor) ?>/20 text-<?= e($fColor) ?> text-xs font-bold px-3 py-1.5 rounded-full">
                <span class="material-symbols-outlined text-sm" aria-hidden="true"><?= e($fIcon) ?></span>
                <?= e($featured['category']) ?>
              </span>
              <span class="text-on-surface-variant text-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm" aria-hidden="true">calendar_today</span>
                <?= e(formatArabicDate($featured['published_at'])) ?>
              </span>
            </div>
            <!-- Title -->
            <h2 class="text-2xl md:text-3xl font-black text-white leading-tight">
              <?= e($featured['title']) ?>
            </h2>
            <!-- Excerpt -->
            <p class="text-on-surface-variant leading-relaxed text-sm md:text-base">
              <?= e($featured['excerpt']) ?>
            </p>
            <!-- Author Row -->
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-primary-container/20 border border-primary-container/30 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-primary-container text-lg" aria-hidden="true">person</span>
              </div>
              <div>
                <div class="text-sm font-bold text-white">فريق ركال التقني</div>
                <div class="text-xs text-on-surface-variant"><?= e(toArabicNumerals($featured['read_time'])) ?> دقائق للقراءة</div>
              </div>
            </div>
            <!-- Read More -->
            <div>
              <a href="blog-detail.php?slug=<?= urlencode($featured['slug']) ?>" class="inline-flex items-center gap-2 tech-gradient text-on-primary-fixed px-6 py-3 rounded-xl font-bold hover:shadow-[0_0_20px_rgba(0,242,255,0.3)] transition-all duration-300 group">
                اقرأ المقال
                <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform" aria-hidden="true">arrow_back</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- Section Divider -->
  <div class="section-divider max-w-7xl mx-auto my-4 px-4 md:px-8"></div>

  <!-- ===== 4. Article Grid ===== -->
  <section class="py-16 section-glow">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
      <!-- Section Header -->
      <div class="flex items-center justify-between mb-12">
        <h2 class="text-2xl font-black text-white">أحدث المقالات</h2>
        <span class="text-sm text-on-surface-variant"><?= e(toArabicNumerals($total)) ?> مقال</span>
      </div>

      <?php if (empty($posts)): ?>
      <div class="text-center py-24 text-on-surface-variant">
        <span class="material-symbols-outlined text-6xl mb-4 block opacity-30">search_off</span>
        <p class="text-lg">لا توجد مقالات مطابقة للبحث.</p>
      </div>
      <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($posts as $post):
          $color = categoryColor($post['category']);
          $icon  = categoryIcon($post['category']);
          // Determine hex color value for inline text color
          $colorHex = ($color === 'sand-gold') ? '#D4AF37' : '#00f2ff';
        ?>
        <article class="card-hover reveal bg-surface-container rounded-[2rem] overflow-hidden border border-white/5 hover:border-primary-container/20 transition-all group flex flex-col">
          <!-- Image area -->
          <div class="relative aspect-video bg-surface-container-high flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-container/8 via-transparent to-transparent"></div>
            <div class="absolute inset-0 circuit-bg opacity-40"></div>
            <span class="material-symbols-outlined text-5xl text-white/10 relative z-10">article</span>
          </div>
          <!-- Content -->
          <div class="p-6 flex flex-col gap-4 flex-1">
            <!-- Category badge -->
            <span class="self-start inline-flex items-center gap-1 bg-<?= e($color) ?>/10 border border-<?= e($color) ?>/20 text-<?= e($color) ?> text-xs font-bold px-3 py-1 rounded-full">
              <span class="material-symbols-outlined text-sm"><?= e($icon) ?></span>
              <?= e($post['category']) ?>
            </span>
            <!-- Title -->
            <h3 class="text-lg font-bold text-white leading-snug line-clamp-2"><?= e($post['title']) ?></h3>
            <!-- Excerpt -->
            <p class="text-on-surface-variant text-sm leading-relaxed line-clamp-2 flex-1"><?= e($post['excerpt']) ?></p>
            <!-- Footer -->
            <div class="flex items-center justify-between pt-4 border-t border-white/5">
              <span class="text-xs text-on-surface-variant flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">calendar_today</span>
                <?= e(formatArabicDate($post['published_at'])) ?>
              </span>
              <a href="blog-detail.php?slug=<?= urlencode($post['slug']) ?>" class="inline-flex items-center gap-1 text-sm font-bold hover:gap-2 transition-all" style="color: <?= $colorHex ?>;">
                اقرأ المزيد
                <span class="material-symbols-outlined text-sm">arrow_back</span>
              </a>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- ===== 5. Load More / Pagination ===== -->
      <?php if ($hasMore):
        $nextPage = $page + 1;
        $nextParams = ['page' => $nextPage];
        if ($category) $nextParams['category'] = $category;
        if ($search)   $nextParams['q']        = $search;
        $nextUrl = 'blog.php?' . http_build_query($nextParams);
      ?>
      <div class="flex justify-center mt-16">
        <a href="<?= e($nextUrl) ?>" class="inline-flex items-center gap-2 bg-surface-container border border-white/10 hover:border-primary-container/30 text-white hover:text-primary-container px-8 py-4 rounded-2xl font-bold transition-all duration-300 group">
          <span class="material-symbols-outlined group-hover:translate-y-0.5 transition-transform">expand_more</span>
          المزيد من المقالات
        </a>
      </div>
      <?php endif; ?>

    </div>
  </section>

  <!-- ===== 6. CTA Block ===== -->
  <section class="py-32 px-4 md:px-6 section-glow">
    <div class="max-w-4xl mx-auto">
      <div class="relative overflow-hidden rounded-[2.5rem] circuit-bg p-6 md:p-12 lg:p-16 text-center border border-primary-container/20">
        <!-- Glow overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-container/10 via-transparent to-sand-gold/5 pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-36 h-36 md:w-72 md:h-72 bg-primary-container/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 md:w-64 md:h-64 bg-sand-gold/8 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="relative z-10">
          <div class="w-16 h-16 rounded-2xl bg-primary-container/15 flex items-center justify-center mx-auto mb-8">
            <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">edit_note</span>
          </div>

          <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white mb-6 leading-tight">
            هل تريد الاستفسار عن
            <span class="block mt-1 text-primary-container">خدماتنا التقنية؟</span>
          </h2>

          <p class="text-white/60 text-lg leading-relaxed mb-10 max-w-xl mx-auto">
            فريقنا جاهز للإجابة على استفساراتك وتقديم الحلول البرمجية المناسبة لاحتياجاتك، تواصل معنا الآن.
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="contact.php" class="tech-gradient text-on-primary-fixed px-8 py-4 rounded-xl font-bold text-lg hover:shadow-[0_0_25px_rgba(0,242,255,0.35)] transition-all duration-300 inline-flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-xl" aria-hidden="true">chat</span>
              تواصل مع فريقنا
            </a>
            <a href="contact.php" class="bg-white/5 border border-white/10 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/10 hover:border-white/20 transition-all duration-300 inline-flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-xl" aria-hidden="true">description</span>
              اطلب عرض سعر
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php require_once 'includes/footer.php'; ?>
