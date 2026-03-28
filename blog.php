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

// Arabic numeral for article index
function toArabicIndex(int $n): string {
    $map = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    $str = str_pad((string)$n, 2, '0', STR_PAD_LEFT);
    return implode('', array_map(fn($d) => $map[(int)$d], str_split($str)));
}

$pageTitle = 'ركال | المدونة - رؤى تقنية ومقالات متخصصة';
$activePage = 'blog';
require_once 'includes/header.php';
?>

<style>
  /* ── Blog Page Enhancements ── */

  /* Hero animated grid */
  .blog-hero-grid {
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(0,242,255,0.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(0,242,255,0.025) 1px, transparent 1px);
    background-size: 80px 80px;
    mask-image: radial-gradient(ellipse 60% 70% at 50% 40%, black 10%, transparent 60%);
    -webkit-mask-image: radial-gradient(ellipse 60% 70% at 50% 40%, black 10%, transparent 60%);
    animation: blog-grid-drift 25s linear infinite;
  }
  @keyframes blog-grid-drift {
    0% { background-position: 0 0; }
    100% { background-position: 80px 80px; }
  }

  /* Hero large decorative number */
  .hero-deco-number {
    position: absolute;
    left: 5%;
    top: 50%;
    transform: translateY(-50%);
    font-size: clamp(8rem, 20vw, 22rem);
    font-weight: 900;
    line-height: 1;
    color: transparent;
    -webkit-text-stroke: 1px rgba(0,242,255,0.06);
    pointer-events: none;
    user-select: none;
    letter-spacing: -0.04em;
  }

  /* Category pill enhanced */
  .cat-pill {
    position: relative;
    padding: 0.6rem 1.25rem;
    border-radius: 9999px;
    font-size: 0.8125rem;
    font-weight: 600;
    transition: all 350ms cubic-bezier(0.22, 1, 0.36, 1);
    white-space: nowrap;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
  }
  .cat-pill-inactive {
    background: rgba(24, 30, 54, 0.55);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.06);
    color: rgba(185, 202, 203, 0.7);
  }
  .cat-pill-inactive:hover {
    color: #fff;
    border-color: rgba(0,242,255,0.15);
    background: rgba(0,242,255,0.06);
    transform: translateY(-1px);
  }
  .cat-pill-active {
    background: linear-gradient(135deg, #00f2ff 0%, #00bcd4 100%);
    color: #002022;
    font-weight: 700;
    box-shadow: 0 4px 20px rgba(0,242,255,0.25), inset 0 1px 0 rgba(255,255,255,0.2);
  }
  .cat-pill-active .cat-pill-icon {
    color: #002022;
  }
  .cat-pill-icon {
    font-size: 1rem;
    transition: transform 300ms ease;
  }
  .cat-pill:hover .cat-pill-icon {
    transform: scale(1.15);
  }

  /* Featured card cinematic */
  .featured-cinematic {
    position: relative;
    border-radius: 2rem;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.06);
    transition: all 600ms cubic-bezier(0.22, 1, 0.36, 1);
    background: rgba(24, 30, 54, 0.55);
  }
  .featured-cinematic:hover {
    border-color: rgba(212,175,55,0.2);
    box-shadow:
      0 30px 80px rgba(0,0,0,0.4),
      0 0 60px rgba(212,175,55,0.04);
    transform: translateY(-4px);
  }
  .featured-cinematic .featured-img {
    transition: transform 800ms cubic-bezier(0.22, 1, 0.36, 1);
  }
  .featured-cinematic:hover .featured-img {
    transform: scale(1.04);
  }

  /* Featured overlay gradient */
  .featured-overlay {
    background: linear-gradient(
      0deg,
      rgba(11,18,41,0.95) 0%,
      rgba(11,18,41,0.7) 35%,
      rgba(11,18,41,0.3) 60%,
      transparent 100%
    );
  }

  /* Article card enhanced */
  .blog-card-v2 {
    position: relative;
    border-radius: 1.75rem;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.05);
    background: rgba(24, 30, 54, 0.4);
    transition: all 500ms cubic-bezier(0.22, 1, 0.36, 1);
    display: flex;
    flex-direction: column;
  }
  .blog-card-v2:hover {
    transform: translateY(-6px);
    border-color: rgba(0,242,255,0.12);
    box-shadow:
      0 24px 60px rgba(0,0,0,0.3),
      0 0 40px rgba(0,242,255,0.04);
  }
  .blog-card-v2.gold-accent:hover {
    border-color: rgba(212,175,55,0.15);
    box-shadow:
      0 24px 60px rgba(0,0,0,0.3),
      0 0 40px rgba(212,175,55,0.04);
  }

  /* Card image zoom */
  .blog-card-v2 .card-img-wrap {
    overflow: hidden;
    position: relative;
  }
  .blog-card-v2 .card-img-inner {
    transition: transform 600ms cubic-bezier(0.22, 1, 0.36, 1);
  }
  .blog-card-v2:hover .card-img-inner {
    transform: scale(1.06);
  }

  /* Card article index watermark */
  .card-index {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 800;
    z-index: 10;
    backdrop-filter: blur(12px);
    transition: all 400ms ease;
  }

  /* Card reading time bar */
  .read-bar {
    height: 2px;
    border-radius: 1px;
    transition: width 800ms cubic-bezier(0.22, 1, 0.36, 1);
  }

  /* Horizontal card (first card in grid) */
  .blog-card-horizontal {
    border-radius: 2rem;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.05);
    background: rgba(24, 30, 54, 0.4);
    transition: all 500ms cubic-bezier(0.22, 1, 0.36, 1);
  }
  .blog-card-horizontal:hover {
    transform: translateY(-4px);
    border-color: rgba(0,242,255,0.12);
    box-shadow:
      0 24px 60px rgba(0,0,0,0.3),
      0 0 40px rgba(0,242,255,0.04);
  }
  .blog-card-horizontal .hz-img {
    transition: transform 700ms cubic-bezier(0.22, 1, 0.36, 1);
  }
  .blog-card-horizontal:hover .hz-img {
    transform: scale(1.04);
  }

  /* Animated placeholder gradient */
  @keyframes placeholder-shift {
    0% { background-position: 200% 50%; }
    100% { background-position: -200% 50%; }
  }
  .placeholder-animated {
    background: linear-gradient(
      120deg,
      rgba(0,242,255,0.04) 0%,
      rgba(0,242,255,0.08) 25%,
      rgba(212,175,55,0.04) 50%,
      rgba(0,242,255,0.08) 75%,
      rgba(0,242,255,0.04) 100%
    );
    background-size: 400% 100%;
    animation: placeholder-shift 8s ease-in-out infinite;
  }

  /* Load more button */
  .load-more-btn {
    position: relative;
    overflow: hidden;
    transition: all 400ms cubic-bezier(0.22, 1, 0.36, 1);
  }
  .load-more-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: linear-gradient(135deg, rgba(0,242,255,0.1) 0%, transparent 50%, rgba(212,175,55,0.05) 100%);
    opacity: 0;
    transition: opacity 400ms ease;
  }
  .load-more-btn:hover::before {
    opacity: 1;
  }
  .load-more-btn:hover {
    transform: translateY(-2px);
    border-color: rgba(0,242,255,0.2);
    box-shadow: 0 8px 30px rgba(0,0,0,0.3), 0 0 20px rgba(0,242,255,0.06);
  }

  /* Floating diamond decorations */
  @keyframes diamond-float {
    0%, 100% { transform: rotate(45deg) translateY(0); opacity: 0.4; }
    50% { transform: rotate(45deg) translateY(-12px); opacity: 0.8; }
  }
  .deco-diamond {
    width: 8px;
    height: 8px;
    border: 1px solid rgba(212,175,55,0.3);
    transform: rotate(45deg);
    animation: diamond-float 4s ease-in-out infinite;
    pointer-events: none;
  }

  /* Search input glow on focus */
  .search-input-v2:focus {
    box-shadow: 0 0 0 2px rgba(0,242,255,0.15), 0 4px 20px rgba(0,242,255,0.05);
    border-color: rgba(0,242,255,0.2);
  }
</style>

  <!-- ===== 1. Hero Section ===== -->
  <section id="blog-hero" class="relative py-16 md:py-24 overflow-hidden hero-mesh">
    <!-- Layered backgrounds -->
    <div class="absolute inset-0 circuit-bg opacity-20 pointer-events-none"></div>
    <div class="blog-hero-grid"></div>
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute top-0 right-1/4 w-48 h-48 md:w-96 md:h-96 bg-primary-container/6 rounded-full blur-3xl animate-float-orb"></div>
      <div class="absolute bottom-0 left-1/4 w-36 h-36 md:w-72 md:h-72 bg-sand-gold/5 rounded-full blur-3xl"></div>
    </div>
    <!-- Decorative lines -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden" aria-hidden="true">
      <div class="absolute -top-20 -left-20 w-[600px] h-[1px] bg-gradient-to-l from-transparent via-primary-container/10 to-transparent rotate-[25deg] origin-top-left"></div>
      <div class="absolute -bottom-10 -right-10 w-[400px] h-[1px] bg-gradient-to-r from-transparent via-sand-gold/10 to-transparent -rotate-[15deg] origin-bottom-right"></div>
    </div>
    <!-- Decorative diamonds -->
    <div class="absolute top-16 left-[15%] deco-diamond hidden md:block" style="animation-delay: 0s;"></div>
    <div class="absolute top-32 right-[12%] deco-diamond hidden md:block" style="animation-delay: 1.5s; border-color: rgba(0,242,255,0.2);"></div>
    <div class="absolute bottom-20 left-[8%] deco-diamond hidden md:block" style="animation-delay: 3s;"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10">
      <!-- Badge -->
      <div class="inline-flex items-center gap-2.5 glass-panel rounded-full px-6 py-3 mb-8 reveal">
        <span class="material-symbols-outlined text-primary-container text-lg" aria-hidden="true">auto_stories</span>
        <span class="text-sm font-bold text-primary-container tracking-widest uppercase">المدونة التقنية</span>
        <span class="w-1.5 h-1.5 rounded-full bg-primary-container/50 animate-pulse"></span>
      </div>
      <!-- Headline -->
      <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-[1.08] mb-6 text-white reveal">
        رؤى تقنية من قلب
        <span class="block mt-2 tech-gradient bg-clip-text text-transparent text-shimmer">المملكة</span>
      </h1>
      <!-- Subtitle -->
      <p class="text-base sm:text-lg md:text-xl text-on-surface-variant/70 leading-relaxed max-w-2xl mx-auto mb-10 reveal">
        نشارككم أحدث المقالات التقنية في مجال التحول الرقمي، والذكاء الاصطناعي، وأمن المعلومات — من منظور سعودي متخصص يواكب طموحات رؤية ٢٠٣٠.
      </p>
      <!-- Stats row -->
      <div class="flex items-center justify-center gap-6 md:gap-10 reveal">
        <div class="flex flex-col items-center">
          <span class="text-2xl md:text-3xl font-black text-white"><?= e(toArabicNumerals($total + ($featured ? 1 : 0))) ?></span>
          <span class="text-xs text-on-surface-variant/50 font-medium mt-1">مقال منشور</span>
        </div>
        <div class="w-[1px] h-10 bg-gradient-to-b from-transparent via-white/10 to-transparent"></div>
        <div class="flex flex-col items-center">
          <span class="text-2xl md:text-3xl font-black text-primary-container">٥</span>
          <span class="text-xs text-on-surface-variant/50 font-medium mt-1">تصنيفات</span>
        </div>
        <div class="w-[1px] h-10 bg-gradient-to-b from-transparent via-white/10 to-transparent"></div>
        <div class="flex flex-col items-center">
          <span class="text-2xl md:text-3xl font-black text-sand-gold">٢٠٣٠</span>
          <span class="text-xs text-on-surface-variant/50 font-medium mt-1">نحو الرؤية</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== 2. Search + Categories Bar ===== -->
  <section class="py-6 border-b border-white/5 bg-surface/70 backdrop-blur-2xl sticky top-20 z-40">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row-reverse items-center gap-5">

        <!-- Search form -->
        <form action="blog.php" method="GET" class="relative w-full md:w-80 flex-shrink-0">
          <?php if ($category): ?>
            <input type="hidden" name="category" value="<?= e($category) ?>">
          <?php endif; ?>
          <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg pointer-events-none" aria-hidden="true">search</span>
          <input
            type="search"
            name="q"
            value="<?= e($search) ?>"
            placeholder="ابحث في المقالات..."
            class="search-input-v2 w-full bg-surface-container/60 border border-white/6 rounded-2xl py-3 pr-12 pl-5 text-sm text-white placeholder:text-on-surface-variant/40 focus:outline-none transition-all duration-300"
          />
        </form>

        <!-- Category Pills -->
        <div class="flex gap-2.5 overflow-x-auto pb-1 flex-1 w-full scrollbar-none">
          <a href="/blog<?= $search ? '?q=' . urlencode($search) : '' ?>"
             class="cat-pill <?= $category === '' ? 'cat-pill-active' : 'cat-pill-inactive' ?>">
            <span class="material-symbols-outlined cat-pill-icon" aria-hidden="true">grid_view</span>
            الكل
          </a>
          <?php
          $categories = [
              'التحول الرقمي'    => 'transform',
              'الذكاء الاصطناعي' => 'psychology',
              'أمن المعلومات'     => 'shield',
              'تطوير البرمجيات'  => 'code',
              'رؤية ٢٠٣٠'        => 'flag',
          ];
          foreach ($categories as $cat => $catIcon):
              $isActive = ($category === $cat);
              $href = '/blog?category=' . urlencode($cat) . ($search ? '&q=' . urlencode($search) : '');
          ?>
          <a href="<?= e($href) ?>"
             class="cat-pill <?= $isActive ? 'cat-pill-active' : 'cat-pill-inactive' ?>">
            <span class="material-symbols-outlined cat-pill-icon" aria-hidden="true"><?= e($catIcon) ?></span>
            <?= e($cat) ?>
          </a>
          <?php endforeach; ?>
        </div>

      </div>
    </div>
  </section>

  <!-- ===== 3. Featured Article ===== -->
  <?php if ($featured): ?>
  <section class="py-10 md:py-16">
    <div class="max-w-7xl mx-auto">
      <!-- Section label -->
      <div class="flex items-center gap-3 mb-8 reveal">
        <div class="w-10 h-10 rounded-xl bg-sand-gold/10 border border-sand-gold/20 flex items-center justify-center">
          <span class="material-symbols-outlined text-sand-gold text-lg" aria-hidden="true" style="font-variation-settings: 'FILL' 1;">star</span>
        </div>
        <div>
          <span class="text-sm font-black text-sand-gold tracking-wider uppercase block">المقال المميز</span>
          <span class="text-[11px] text-on-surface-variant/40">اختيار فريق التحرير</span>
        </div>
        <div class="flex-1 h-[1px] bg-gradient-to-l from-transparent to-sand-gold/10 mr-4"></div>
      </div>

      <a href="/blog/<?= urlencode($featured['slug']) ?>" class="block featured-cinematic group reveal">
        <div class="relative min-h-[320px] md:min-h-[480px]">
          <!-- Image -->
          <div class="absolute inset-0 overflow-hidden rounded-[2rem]">
            <?php if (!empty($featured['featured_image'])): ?>
              <img src="<?= e($featured['featured_image']) ?>" alt="<?= e($featured['title']) ?>" class="featured-img absolute inset-0 w-full h-full object-cover" />
            <?php else: ?>
              <div class="absolute inset-0 placeholder-animated"></div>
              <div class="absolute inset-0 circuit-bg opacity-40"></div>
              <div class="absolute inset-0 flex items-center justify-center">
                <span class="material-symbols-outlined text-[10rem] text-white/[0.03]" aria-hidden="true" style="font-variation-settings: 'FILL' 1, 'wght' 200;">article</span>
              </div>
            <?php endif; ?>
          </div>

          <!-- Gradient overlay -->
          <div class="absolute inset-0 featured-overlay rounded-[2rem]"></div>

          <!-- Featured badge -->
          <div class="absolute top-6 right-6 z-10 flex items-center gap-1.5 bg-sand-gold text-[#1a1400] px-4 py-2 rounded-full text-xs font-black shadow-[0_4px_20px_rgba(212,175,55,0.35)]">
            <span class="material-symbols-outlined text-sm" aria-hidden="true" style="font-variation-settings: 'FILL' 1;">star</span>
            مميز
          </div>

          <!-- Read time -->
          <div class="absolute top-6 left-6 z-10 flex items-center gap-1.5 bg-black/30 backdrop-blur-xl border border-white/10 rounded-full px-3.5 py-2 text-xs text-white/80">
            <span class="material-symbols-outlined text-sm" aria-hidden="true">schedule</span>
            <?= e(toArabicNumerals($featured['read_time'])) ?> دقائق
          </div>

          <!-- Content overlay — bottom -->
          <div class="absolute bottom-0 right-0 left-0 p-6 md:p-10 z-10">
            <?php
            $fColor = categoryColor($featured['category']);
            $fIcon  = categoryIcon($featured['category']);
            $fHex   = ($fColor === 'sand-gold') ? '#D4AF37' : '#00f2ff';
            ?>
            <!-- Category + Date -->
            <div class="flex items-center gap-3 flex-wrap mb-4">
              <span class="inline-flex items-center gap-1.5 bg-black/30 backdrop-blur-md border border-white/10 text-xs font-bold px-3 py-1.5 rounded-full" style="color: <?= $fHex ?>;">
                <span class="material-symbols-outlined text-sm" aria-hidden="true"><?= e($fIcon) ?></span>
                <?= e($featured['category']) ?>
              </span>
              <span class="text-white/50 text-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm" aria-hidden="true">calendar_today</span>
                <?= e(formatArabicDate($featured['published_at'])) ?>
              </span>
            </div>

            <!-- Title -->
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white leading-[1.15] mb-4 max-w-3xl group-hover:text-primary-fixed transition-colors duration-500">
              <?= e($featured['title']) ?>
            </h2>

            <!-- Excerpt -->
            <p class="text-white/50 leading-relaxed text-sm md:text-base max-w-2xl line-clamp-2 mb-6 hidden sm:block">
              <?= e($featured['excerpt']) ?>
            </p>

            <!-- Bottom row: Author + CTA -->
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/10 flex items-center justify-center flex-shrink-0">
                  <span class="material-symbols-outlined text-primary-container text-lg" aria-hidden="true">person</span>
                </div>
                <div>
                  <div class="text-sm font-bold text-white/90">فريق ركال التقني</div>
                  <div class="text-xs text-white/30"><?= e(toArabicNumerals($featured['read_time'])) ?> دقائق للقراءة</div>
                </div>
              </div>
              <div class="hidden sm:flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/10 text-white px-5 py-2.5 rounded-xl font-bold text-sm group-hover:bg-primary-container/20 group-hover:border-primary-container/30 group-hover:text-primary-container transition-all duration-400">
                اقرأ المقال
                <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform" aria-hidden="true">arrow_back</span>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </section>
  <?php endif; ?>

  <!-- ===== 4. Article Grid ===== -->
  <section class="py-12 md:py-16 section-glow">
    <div class="max-w-7xl mx-auto">
      <!-- Section Header -->
      <div class="flex items-center justify-between mb-10 reveal">
        <div class="flex items-center gap-3">
          <div class="w-1.5 h-10 rounded-full bg-gradient-to-b from-primary-container to-primary-container/20"></div>
          <div>
            <h2 class="text-xl sm:text-2xl font-black text-white">أحدث المقالات</h2>
            <p class="text-xs text-on-surface-variant/40 mt-0.5">تصفّح أحدث ما كتبه فريقنا التقني</p>
          </div>
        </div>
        <div class="flex items-center gap-2 bg-surface-container/60 border border-white/5 px-4 py-2 rounded-xl">
          <span class="material-symbols-outlined text-primary-container text-sm" aria-hidden="true">library_books</span>
          <span class="text-sm text-on-surface-variant/70 font-bold"><?= e(toArabicNumerals($total)) ?> مقال</span>
        </div>
      </div>

      <?php if (empty($posts)): ?>
      <!-- Empty State -->
      <div class="text-center py-28">
        <div class="relative w-28 h-28 mx-auto mb-8">
          <div class="absolute inset-0 rounded-3xl bg-surface-container-high/50 rotate-6"></div>
          <div class="absolute inset-0 rounded-3xl bg-surface-container-high/30 -rotate-3"></div>
          <div class="relative w-full h-full rounded-3xl bg-surface-container-high/80 flex items-center justify-center">
            <span class="material-symbols-outlined text-5xl text-white/10" aria-hidden="true">search_off</span>
          </div>
        </div>
        <p class="text-xl font-black text-white/30 mb-2">لا توجد مقالات مطابقة</p>
        <p class="text-sm text-on-surface-variant/40 max-w-sm mx-auto mb-8">جرّب تغيير كلمات البحث أو اختيار تصنيف آخر</p>
        <a href="/blog" class="inline-flex items-center gap-2 text-primary-container text-sm font-bold hover:gap-3 transition-all bg-primary-container/8 border border-primary-container/15 px-6 py-3 rounded-xl">
          <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
          عرض جميع المقالات
        </a>
      </div>
      <?php else: ?>

      <!-- Mixed layout grid -->
      <div class="space-y-8">
        <?php
        $articleNum = $offset;
        foreach ($posts as $i => $post):
          $color    = categoryColor($post['category']);
          $icon     = categoryIcon($post['category']);
          $colorHex = ($color === 'sand-gold') ? '#D4AF37' : '#00f2ff';
          $colorHexBg = ($color === 'sand-gold') ? 'rgba(212,175,55,' : 'rgba(0,242,255,';
          $isGold   = ($color === 'sand-gold');
          $articleNum++;
          $indexStr = toArabicIndex($articleNum);

          // First card: horizontal layout
          if ($i === 0):
        ?>
        <a href="/blog/<?= urlencode($post['slug']) ?>" class="block blog-card-horizontal group reveal">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-0">
            <!-- Image — 5 cols -->
            <div class="relative aspect-video md:aspect-auto md:col-span-5 overflow-hidden bg-surface-container-high">
              <?php if (!empty($post['featured_image'])): ?>
                <img src="<?= e($post['featured_image']) ?>" alt="<?= e($post['title']) ?>" class="hz-img absolute inset-0 w-full h-full object-cover" />
              <?php else: ?>
                <div class="absolute inset-0 placeholder-animated"></div>
                <div class="absolute inset-0 circuit-bg opacity-30"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                  <span class="material-symbols-outlined text-7xl text-white/[0.04]" aria-hidden="true" style="font-variation-settings: 'FILL' 1, 'wght' 200;">article</span>
                </div>
              <?php endif; ?>
              <!-- Article index -->
              <div class="card-index bg-black/30 backdrop-blur-xl border border-white/10 text-white/50">
                <?= $indexStr ?>
              </div>
              <!-- Gradient fade into content area -->
              <div class="absolute inset-y-0 left-0 w-24 bg-gradient-to-l from-transparent to-surface-container/80 hidden md:block"></div>
            </div>
            <!-- Content — 7 cols -->
            <div class="md:col-span-7 p-6 md:p-10 flex flex-col justify-center gap-4">
              <div class="flex items-center gap-3 flex-wrap">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full border" style="color: <?= $colorHex ?>; background: <?= $colorHexBg ?>0.08); border-color: <?= $colorHexBg ?>0.15);">
                  <span class="material-symbols-outlined text-sm" aria-hidden="true"><?= e($icon) ?></span>
                  <?= e($post['category']) ?>
                </span>
                <span class="text-on-surface-variant/50 text-sm flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-sm" aria-hidden="true">calendar_today</span>
                  <?= e(formatArabicDate($post['published_at'])) ?>
                </span>
                <span class="text-on-surface-variant/40 text-sm flex items-center gap-1">
                  <span class="material-symbols-outlined text-xs" aria-hidden="true">schedule</span>
                  <?= e(toArabicNumerals($post['read_time'])) ?> د
                </span>
              </div>
              <h3 class="text-xl md:text-2xl font-black text-white leading-snug group-hover:text-primary-fixed transition-colors duration-400">
                <?= e($post['title']) ?>
              </h3>
              <p class="text-on-surface-variant/60 text-sm leading-relaxed line-clamp-2">
                <?= e($post['excerpt']) ?>
              </p>
              <div class="flex items-center gap-2 font-bold text-sm mt-2 group-hover:gap-3 transition-all duration-300" style="color: <?= $colorHex ?>;">
                اقرأ المقال
                <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
              </div>
              <!-- Reading time bar -->
              <div class="w-full bg-white/[0.03] rounded-full mt-2">
                <div class="read-bar" style="width: <?= min(100, (int)$post['read_time'] * 12) ?>%; background: linear-gradient(90deg, <?= $colorHex ?>, transparent);"></div>
              </div>
            </div>
          </div>
        </a>

        <?php
          // Start the standard grid after the first horizontal card
          if (count($posts) > 1): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php endif;

          else: // Standard card for index > 0
        ?>
        <article class="blog-card-v2 <?= $isGold ? 'gold-accent' : '' ?> reveal group">
          <a href="/blog/<?= urlencode($post['slug']) ?>" class="flex flex-col h-full">
            <!-- Image area -->
            <div class="card-img-wrap relative aspect-[16/10]">
              <div class="card-img-inner absolute inset-0">
                <?php if (!empty($post['featured_image'])): ?>
                  <img src="<?= e($post['featured_image']) ?>" alt="<?= e($post['title']) ?>" class="w-full h-full object-cover" />
                <?php else: ?>
                  <div class="w-full h-full placeholder-animated relative">
                    <div class="absolute inset-0 circuit-bg opacity-25"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                      <span class="material-symbols-outlined text-6xl text-white/[0.04]" aria-hidden="true" style="font-variation-settings: 'FILL' 1, 'wght' 200;">article</span>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
              <!-- Article index -->
              <div class="card-index bg-black/30 backdrop-blur-xl border border-white/10 text-white/50">
                <?= $indexStr ?>
              </div>
              <!-- Category badge -->
              <div class="absolute bottom-3 right-3 z-10">
                <span class="inline-flex items-center gap-1.5 bg-surface-container/90 backdrop-blur-md text-xs font-bold px-3 py-1.5 rounded-full shadow-lg border" style="color: <?= $colorHex ?>; border-color: <?= $colorHexBg ?>0.15);">
                  <span class="material-symbols-outlined text-sm" aria-hidden="true"><?= e($icon) ?></span>
                  <?= e($post['category']) ?>
                </span>
              </div>
            </div>
            <!-- Content -->
            <div class="p-5 md:p-6 flex flex-col gap-3 flex-1">
              <h3 class="text-lg font-bold text-white leading-snug line-clamp-2 group-hover:text-primary-fixed transition-colors duration-300">
                <?= e($post['title']) ?>
              </h3>
              <p class="text-on-surface-variant/60 text-sm leading-relaxed line-clamp-2 flex-1">
                <?= e($post['excerpt']) ?>
              </p>
              <!-- Footer -->
              <div class="flex items-center justify-between pt-3 mt-auto">
                <span class="text-xs text-on-surface-variant/40 flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-sm" aria-hidden="true">calendar_today</span>
                  <?= e(formatArabicDate($post['published_at'])) ?>
                </span>
                <span class="text-xs text-on-surface-variant/30 flex items-center gap-1">
                  <span class="material-symbols-outlined text-xs" aria-hidden="true">schedule</span>
                  <?= e(toArabicNumerals($post['read_time'])) ?> د
                </span>
              </div>
              <!-- Reading time bar -->
              <div class="w-full bg-white/[0.03] rounded-full">
                <div class="read-bar" style="width: <?= min(100, (int)$post['read_time'] * 12) ?>%; background: linear-gradient(90deg, <?= $colorHex ?>, transparent);"></div>
              </div>
            </div>
          </a>
        </article>
        <?php
          endif;
        endforeach;

        // Close the grid div if we had more than 1 post
        if (count($posts) > 1): ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- ===== 5. Load More / Pagination ===== -->
      <?php if ($hasMore):
        $nextPage = $page + 1;
        $nextParams = ['page' => $nextPage];
        if ($category) $nextParams['category'] = $category;
        if ($search)   $nextParams['q']        = $search;
        $nextUrl = '/blog?' . http_build_query($nextParams);
      ?>
      <div class="flex justify-center mt-14">
        <a href="<?= e($nextUrl) ?>" class="load-more-btn inline-flex items-center gap-3 bg-surface-container/60 border border-white/8 text-white px-8 py-4 rounded-2xl font-bold transition-all duration-400 group">
          <span class="material-symbols-outlined text-lg group-hover:translate-y-0.5 transition-transform duration-300" aria-hidden="true">expand_more</span>
          المزيد من المقالات
          <span class="text-xs text-on-surface-variant/40 bg-surface-container-high/50 px-2.5 py-1 rounded-lg">صفحة <?= e(toArabicNumerals($nextPage)) ?></span>
        </a>
      </div>
      <?php endif; ?>

    </div>
  </section>

  <!-- ===== 6. CTA Block ===== -->
  <section class="py-24 md:py-32 section-glow">
    <div class="max-w-4xl mx-auto">
      <div class="relative overflow-hidden rounded-[2.5rem] circuit-bg p-8 md:p-14 lg:p-16 text-center border border-primary-container/15 reveal">
        <!-- Glow overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-container/8 via-transparent to-sand-gold/4 pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-36 h-36 md:w-72 md:h-72 bg-primary-container/8 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 md:w-64 md:h-64 bg-sand-gold/6 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="relative z-10">
          <div class="w-16 h-16 rounded-2xl bg-primary-container/12 border border-primary-container/15 flex items-center justify-center mx-auto mb-8">
            <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">edit_note</span>
          </div>

          <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white mb-5 leading-tight">
            هل تريد الاستفسار عن
            <span class="block mt-1 text-primary-container">خدماتنا التقنية؟</span>
          </h2>

          <p class="text-white/50 text-lg leading-relaxed mb-10 max-w-xl mx-auto">
            فريقنا جاهز للإجابة على استفساراتك وتقديم الحلول البرمجية المناسبة لاحتياجاتك.
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/contact" class="cta-btn tech-gradient text-on-primary-fixed px-8 py-4 rounded-xl font-bold text-lg inline-flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-xl" aria-hidden="true">chat</span>
              تواصل مع فريقنا
            </a>
            <a href="/contact" class="bg-white/5 border border-white/10 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/10 hover:border-white/15 transition-all duration-300 inline-flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-xl" aria-hidden="true">description</span>
              اطلب عرض سعر
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php require_once 'includes/footer.php'; ?>
