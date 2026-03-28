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
  <section id="blog-hero" class="relative py-20 md:py-28 overflow-hidden hero-mesh">
    <!-- Layered background -->
    <div class="absolute inset-0 circuit-bg opacity-30 pointer-events-none"></div>
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute top-0 right-1/4 w-48 h-48 md:w-96 md:h-96 bg-primary-container/6 rounded-full blur-3xl animate-float-orb"></div>
      <div class="absolute bottom-0 left-1/4 w-36 h-36 md:w-72 md:h-72 bg-sand-gold/5 rounded-full blur-3xl"></div>
    </div>
    <!-- Diagonal decorative line -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden" aria-hidden="true">
      <div class="absolute -top-20 -left-20 w-[600px] h-[1px] bg-gradient-to-l from-transparent via-primary-container/10 to-transparent rotate-[25deg] origin-top-left"></div>
      <div class="absolute -bottom-10 -right-10 w-[400px] h-[1px] bg-gradient-to-r from-transparent via-sand-gold/10 to-transparent -rotate-[15deg] origin-bottom-right"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 md:px-8 text-center relative z-10">
      <!-- Badge -->
      <div class="inline-flex items-center gap-2.5 glass-panel rounded-full px-6 py-3 mb-10 reveal">
        <span class="material-symbols-outlined text-primary-container text-xl" aria-hidden="true">auto_stories</span>
        <span class="text-sm font-bold text-primary-container tracking-widest uppercase">المدونة التقنية</span>
        <span class="w-1.5 h-1.5 rounded-full bg-primary-container/50 animate-pulse"></span>
      </div>
      <!-- Headline -->
      <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-[1.1] mb-8 text-white reveal">
        رؤى تقنية من قلب
        <span class="block mt-2 tech-gradient bg-clip-text text-transparent">المملكة</span>
      </h1>
      <!-- Subtitle -->
      <p class="text-base sm:text-lg md:text-xl text-on-surface-variant/80 leading-relaxed max-w-2xl mx-auto reveal">
        نشارككم أعمق الرؤى وأحدث المقالات التقنية في مجال التحول الرقمي، والذكاء الاصطناعي، وأمن المعلومات — من منظور سعودي متخصص يواكب طموحات رؤية ٢٠٣٠.
      </p>
      <!-- Scroll hint -->
      <div class="mt-12 reveal">
        <div class="inline-flex flex-col items-center gap-2 text-white/20">
          <span class="text-[10px] tracking-[0.3em] uppercase font-bold">استكشف</span>
          <span class="material-symbols-outlined text-lg animate-bounce">expand_more</span>
        </div>
      </div>
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
  <section class="py-10 md:py-14">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
      <!-- Section label -->
      <div class="flex items-center gap-3 mb-6">
        <span class="material-symbols-outlined text-sand-gold text-xl" aria-hidden="true" style="font-variation-settings: 'FILL' 1;">star</span>
        <span class="text-sm font-black text-sand-gold tracking-widest uppercase">المقال المميز</span>
        <div class="flex-1 h-[1px] bg-gradient-to-l from-transparent to-sand-gold/15"></div>
      </div>

      <a href="blog-detail.php?slug=<?= urlencode($featured['slug']) ?>" class="block featured-card glass-panel rounded-[2rem] overflow-hidden group">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-0">
          <!-- Image Area — takes 3 cols on desktop -->
          <div class="relative aspect-video md:aspect-auto md:col-span-3 bg-surface-container-high flex items-center justify-center overflow-hidden">
            <?php if (!empty($featured['featured_image'])): ?>
              <img src="<?= e($featured['featured_image']) ?>" alt="<?= e($featured['title']) ?>" class="absolute inset-0 w-full h-full object-cover" />
            <?php else: ?>
              <div class="absolute inset-0 bg-gradient-to-br from-primary-container/10 via-transparent to-sand-gold/10 group-hover:from-primary-container/15 transition-all duration-700"></div>
              <div class="absolute inset-0 circuit-bg opacity-40 group-hover:opacity-60 transition-opacity duration-700"></div>
              <div class="relative z-10 flex flex-col items-center gap-4 text-white/15 group-hover:text-white/25 transition-colors duration-500">
                <span class="material-symbols-outlined text-8xl md:text-9xl transition-transform duration-700 group-hover:scale-110" aria-hidden="true" style="font-variation-settings: 'FILL' 1, 'wght' 200, 'GRAD' 0, 'opsz' 48;">article</span>
              </div>
            <?php endif; ?>
            <!-- Featured badge -->
            <div class="absolute top-5 right-5 flex items-center gap-1.5 bg-sand-gold text-on-primary-fixed px-4 py-2 rounded-full text-xs font-black shadow-[0_4px_15px_rgba(212,175,55,0.3)]">
              <span class="material-symbols-outlined text-sm" aria-hidden="true" style="font-variation-settings: 'FILL' 1;">star</span>
              مميز
            </div>
            <!-- Read time overlay -->
            <div class="absolute bottom-5 left-5 flex items-center gap-1.5 glass-panel rounded-full px-3 py-1.5 text-xs text-white/70 backdrop-blur-md">
              <span class="material-symbols-outlined text-sm" aria-hidden="true">schedule</span>
              <?= e(toArabicNumerals($featured['read_time'])) ?> دقائق
            </div>
          </div>

          <!-- Content Area — takes 2 cols -->
          <div class="md:col-span-2 p-6 md:p-10 flex flex-col justify-center gap-5">
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

            <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-white leading-tight group-hover:text-primary-fixed transition-colors duration-300">
              <?= e($featured['title']) ?>
            </h2>

            <p class="text-on-surface-variant leading-relaxed text-sm md:text-base line-clamp-3">
              <?= e($featured['excerpt']) ?>
            </p>

            <!-- Author -->
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-primary-container/15 border border-primary-container/20 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-primary-container text-lg" aria-hidden="true">person</span>
              </div>
              <div>
                <div class="text-sm font-bold text-white">فريق ركال التقني</div>
                <div class="text-xs text-on-surface-variant"><?= e(toArabicNumerals($featured['read_time'])) ?> دقائق للقراءة</div>
              </div>
            </div>

            <!-- CTA -->
            <div class="flex items-center gap-2 text-primary-container font-bold text-sm group-hover:gap-3 transition-all duration-300 mt-2">
              اقرأ المقال الكامل
              <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_back</span>
            </div>
          </div>
        </div>
      </a>
    </div>
  </section>
  <?php endif; ?>

  <!-- Section Divider -->
  <div class="section-divider max-w-7xl mx-auto my-4 px-4 md:px-8"></div>

  <!-- ===== 4. Article Grid ===== -->
  <section class="py-16 section-glow">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
      <!-- Section Header -->
      <div class="flex items-center justify-between mb-12 reveal">
        <div class="flex items-center gap-3">
          <div class="w-1 h-8 rounded-full bg-gradient-to-b from-primary-container to-primary-container/30"></div>
          <h2 class="text-xl sm:text-2xl font-black text-white">أحدث المقالات</h2>
        </div>
        <span class="text-sm text-on-surface-variant/60 bg-surface-container-high/50 px-4 py-1.5 rounded-full"><?= e(toArabicNumerals($total)) ?> مقال</span>
      </div>

      <?php if (empty($posts)): ?>
      <div class="text-center py-32 text-on-surface-variant">
        <div class="w-24 h-24 rounded-3xl bg-surface-container-high/50 flex items-center justify-center mx-auto mb-6">
          <span class="material-symbols-outlined text-5xl opacity-20" aria-hidden="true">search_off</span>
        </div>
        <p class="text-xl font-bold text-white/40 mb-2">لا توجد مقالات مطابقة</p>
        <p class="text-sm text-on-surface-variant/50 max-w-sm mx-auto">جرّب تغيير كلمات البحث أو اختيار تصنيف آخر</p>
        <a href="blog.php" class="inline-flex items-center gap-2 mt-8 text-primary-container text-sm font-bold hover:gap-3 transition-all">
          <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
          عرض جميع المقالات
        </a>
      </div>
      <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($posts as $post):
          $color = categoryColor($post['category']);
          $icon  = categoryIcon($post['category']);
          // Determine hex color value for inline text color
          $colorHex = ($color === 'sand-gold') ? '#D4AF37' : '#00f2ff';
        ?>
        <article class="blog-card card-hover reveal bg-surface-container rounded-[2rem] overflow-hidden border border-white/5 hover:border-<?= e($color) ?>/20 transition-all duration-500 group flex flex-col">
          <!-- Image area -->
          <div class="blog-card-img relative aspect-[16/10] bg-surface-container-high flex items-center justify-center">
            <?php if (!empty($post['featured_image'])): ?>
              <img src="<?= e($post['featured_image']) ?>" alt="<?= e($post['title']) ?>" class="absolute inset-0 w-full h-full object-cover" />
            <?php else: ?>
              <div class="absolute inset-0 bg-gradient-to-br from-<?= e($color) ?>/8 via-transparent to-transparent group-hover:from-<?= e($color) ?>/12 transition-all duration-500"></div>
              <div class="absolute inset-0 circuit-bg opacity-30 group-hover:opacity-50 transition-opacity duration-500"></div>
              <span class="blog-card-icon material-symbols-outlined text-6xl text-white/8 relative z-10" aria-hidden="true">article</span>
            <?php endif; ?>
            <!-- Category badge overlapping image bottom -->
            <div class="absolute bottom-4 right-4 z-10">
              <span class="inline-flex items-center gap-1.5 bg-surface-container/90 backdrop-blur-md border border-<?= e($color) ?>/20 text-<?= e($color) ?> text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                <span class="material-symbols-outlined text-sm" aria-hidden="true"><?= e($icon) ?></span>
                <?= e($post['category']) ?>
              </span>
            </div>
            <!-- Read time -->
            <div class="absolute bottom-4 left-4 z-10 text-[11px] text-white/40 flex items-center gap-1">
              <span class="material-symbols-outlined text-xs" aria-hidden="true">schedule</span>
              <?= e(toArabicNumerals($post['read_time'])) ?> د
            </div>
          </div>
          <!-- Content -->
          <div class="p-5 md:p-6 flex flex-col gap-3 flex-1">
            <!-- Title -->
            <h3 class="text-lg font-bold text-white leading-snug line-clamp-2 group-hover:text-<?= e($color) ?> transition-colors duration-300"><?= e($post['title']) ?></h3>
            <!-- Excerpt -->
            <p class="text-on-surface-variant text-sm leading-relaxed line-clamp-2 flex-1"><?= e($post['excerpt']) ?></p>
            <!-- Footer -->
            <div class="flex items-center justify-between pt-3 border-t border-white/5 mt-auto">
              <span class="text-xs text-on-surface-variant/60 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm" aria-hidden="true">calendar_today</span>
                <?= e(formatArabicDate($post['published_at'])) ?>
              </span>
              <a href="blog-detail.php?slug=<?= urlencode($post['slug']) ?>" class="inline-flex items-center gap-1 text-sm font-bold group-hover:gap-2 transition-all duration-300" style="color: <?= $colorHex ?>;">
                اقرأ
                <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_back</span>
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
