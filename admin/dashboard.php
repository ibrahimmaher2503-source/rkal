<?php
require_once 'includes/auth.php';
require_once '../includes/functions.php';

$db = getDB();
$blogCount    = $db->query('SELECT COUNT(*) FROM blogs')->fetchColumn();
$serviceCount = $db->query('SELECT COUNT(*) FROM services')->fetchColumn();

$adminPageTitle  = 'الرئيسية';
$adminActivePage = 'dashboard';
require_once 'includes/admin-header.php';
?>

<!-- ===== Password Change Warning Banner ===== -->
<?php if (!empty($_SESSION['must_change_password'])): ?>
  <div class="mb-6 flex items-start gap-3 rounded-xl border px-5 py-4"
       style="background: rgba(212, 175, 55, 0.08); border-color: rgba(212, 175, 55, 0.3);"
       role="alert">
    <span class="material-symbols-outlined flex-shrink-0 mt-0.5" style="color: #D4AF37;" aria-hidden="true">warning</span>
    <div class="flex-1 min-w-0">
      <p class="text-sm font-medium" style="color: #D4AF37;">يرجى تغيير كلمة المرور الافتراضية</p>
      <p class="text-xs text-on-surface-variant mt-0.5">لحماية حسابك، قم بتغيير كلمة المرور قبل المتابعة.</p>
    </div>
    <a href="login.php?change_password=1"
       class="flex-shrink-0 text-xs font-bold px-3 py-1.5 rounded-xl transition-all"
       style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; border: 1px solid rgba(212, 175, 55, 0.3);"
       onmouseover="this.style.background='rgba(212,175,55,0.25)'"
       onmouseout="this.style.background='rgba(212,175,55,0.15)'">
      تغيير الآن
    </a>
  </div>
<?php endif; ?>

<!-- ===== Welcome Heading ===== -->
<div class="mb-8">
  <h1 class="text-2xl md:text-3xl font-bold text-on-surface font-headline">
    مرحبًا، <span class="text-[#00dbe7]"><?= e($_SESSION['admin_username']) ?></span>
  </h1>
  <p class="mt-1 text-sm text-on-surface-variant">
    <?= date('l، j F Y') ?> — لوحة التحكم
  </p>
</div>

<!-- ===== Stats Grid ===== -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-10">

  <!-- Blog Count Card -->
  <div class="glass-panel rounded-xl p-6 flex items-center gap-5 transition-all hover:scale-[1.01]">
    <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0"
         style="background: rgba(0, 219, 231, 0.1); border: 1px solid rgba(0, 219, 231, 0.2);">
      <span class="material-symbols-outlined text-3xl text-[#00dbe7]" aria-hidden="true">article</span>
    </div>
    <div>
      <p class="text-4xl font-bold text-[#00dbe7] font-headline leading-none"><?= (int) $blogCount ?></p>
      <p class="mt-1 text-sm text-on-surface-variant font-medium">مقالات المدونة</p>
    </div>
    <div class="mr-auto">
      <a href="blogs.php"
         class="text-xs text-[#00dbe7]/70 hover:text-[#00dbe7] transition-colors flex items-center gap-1">
        <span>إدارة</span>
        <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_left</span>
      </a>
    </div>
  </div>

  <!-- Service Count Card -->
  <div class="glass-panel rounded-xl p-6 flex items-center gap-5 transition-all hover:scale-[1.01]">
    <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0"
         style="background: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.2);">
      <span class="material-symbols-outlined text-3xl" style="color: #D4AF37;" aria-hidden="true">design_services</span>
    </div>
    <div>
      <p class="text-4xl font-bold font-headline leading-none" style="color: #D4AF37;"><?= (int) $serviceCount ?></p>
      <p class="mt-1 text-sm text-on-surface-variant font-medium">الخدمات</p>
    </div>
    <div class="mr-auto">
      <a href="services.php"
         class="text-xs transition-colors flex items-center gap-1"
         style="color: rgba(212,175,55,0.7);"
         onmouseover="this.style.color='#D4AF37'"
         onmouseout="this.style.color='rgba(212,175,55,0.7)'">
        <span>إدارة</span>
        <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_left</span>
      </a>
    </div>
  </div>

</div>

<!-- ===== Quick Actions ===== -->
<div class="glass-panel rounded-xl p-6">
  <h2 class="text-base font-semibold text-on-surface mb-4 flex items-center gap-2">
    <span class="material-symbols-outlined text-lg text-[#00dbe7]" aria-hidden="true">bolt</span>
    إجراءات سريعة
  </h2>
  <div class="flex flex-wrap gap-3">
    <a href="blogs.php?action=new"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all"
       style="background: rgba(0,219,231,0.1); border: 1px solid rgba(0,219,231,0.2); color: #00dbe7;"
       onmouseover="this.style.background='rgba(0,219,231,0.18)'"
       onmouseout="this.style.background='rgba(0,219,231,0.1)'">
      <span class="material-symbols-outlined text-base" aria-hidden="true">add</span>
      مقال جديد
    </a>
    <a href="services.php?action=new"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all"
       style="background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.2); color: #D4AF37;"
       onmouseover="this.style.background='rgba(212,175,55,0.18)'"
       onmouseout="this.style.background='rgba(212,175,55,0.1)'">
      <span class="material-symbols-outlined text-base" aria-hidden="true">add</span>
      خدمة جديدة
    </a>
  </div>
</div>

</main>
</body>
</html>
