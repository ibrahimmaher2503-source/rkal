<?php
http_response_code(404);
$pageTitle = 'ركال | الصفحة غير موجودة';
$activePage = '';
require_once __DIR__ . '/includes/header.php';
?>

  <section class="min-h-[60vh] flex items-center justify-center circuit-bg">
    <div class="text-center px-8">
      <div class="text-8xl font-black text-primary-container mb-4">٤٠٤</div>
      <h1 class="text-3xl font-bold text-white mb-4">الصفحة غير موجودة</h1>
      <p class="text-on-surface-variant mb-8">عذرًا، الصفحة التي تبحث عنها غير متوفرة.</p>
      <a href="index.php" class="inline-flex items-center gap-2 tech-gradient text-on-primary-fixed px-8 py-3 rounded-xl font-bold">
        <span class="material-symbols-outlined">home</span>
        العودة للرئيسية
      </a>
    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
