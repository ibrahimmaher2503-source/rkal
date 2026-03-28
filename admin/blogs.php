<?php
require_once 'includes/auth.php';
require_once '../includes/functions.php';

$db = getDB();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    validateCsrf();
    // Get image path before deleting
    $stmt = $db->prepare('SELECT featured_image FROM blogs WHERE id = :id');
    $stmt->execute(['id' => $_POST['delete_id']]);
    $blog = $stmt->fetch();
    if ($blog && $blog['featured_image']) {
        deleteUpload('../' . $blog['featured_image']);
    }
    $stmt = $db->prepare('DELETE FROM blogs WHERE id = :id');
    $stmt->execute(['id' => $_POST['delete_id']]);
    header('Location: blogs.php?deleted=1');
    exit;
}

$stmt = $db->query('SELECT * FROM blogs ORDER BY published_at DESC');
$blogs = $stmt->fetchAll();

$adminPageTitle  = 'إدارة المدونة';
$adminActivePage = 'blogs';
require_once 'includes/admin-header.php';
?>

<!-- ===== Page Header ===== -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
  <div>
    <h1 class="text-2xl md:text-3xl font-bold text-on-surface font-headline">إدارة المدونة</h1>
    <p class="mt-1 text-sm text-on-surface-variant">إجمالي المقالات: <span class="text-[#00dbe7] font-medium"><?= count($blogs) ?></span></p>
  </div>
  <a href="blog-form.php"
     class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all self-start sm:self-auto"
     style="background: rgba(0,219,231,0.12); border: 1px solid rgba(0,219,231,0.3); color: #00dbe7;"
     onmouseover="this.style.background='rgba(0,219,231,0.22)'"
     onmouseout="this.style.background='rgba(0,219,231,0.12)'">
    <span class="material-symbols-outlined text-base" aria-hidden="true">add</span>
    إضافة مقال جديد
  </a>
</div>

<?php if (isset($_GET['deleted'])): ?>
  <div class="mb-6 flex items-center gap-3 rounded-xl border px-5 py-4"
       style="background: rgba(255,180,171,0.08); border-color: rgba(255,180,171,0.3);"
       role="alert">
    <span class="material-symbols-outlined flex-shrink-0 text-error" aria-hidden="true">check_circle</span>
    <p class="text-sm font-medium text-error">تم حذف المقال بنجاح.</p>
  </div>
<?php endif; ?>

<?php if (isset($_GET['saved'])): ?>
  <div class="mb-6 flex items-center gap-3 rounded-xl border px-5 py-4"
       style="background: rgba(0,219,231,0.08); border-color: rgba(0,219,231,0.3);"
       role="alert">
    <span class="material-symbols-outlined flex-shrink-0 text-[#00dbe7]" aria-hidden="true">check_circle</span>
    <p class="text-sm font-medium text-[#00dbe7]">تم حفظ المقال بنجاح.</p>
  </div>
<?php endif; ?>

<!-- ===== Blog Table ===== -->
<div class="glass-panel rounded-xl overflow-hidden">
  <?php if (empty($blogs)): ?>
    <div class="px-8 py-16 text-center">
      <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-4 block" aria-hidden="true">article</span>
      <p class="text-on-surface-variant">لا توجد مقالات بعد.</p>
      <a href="blog-form.php" class="mt-4 inline-flex items-center gap-1 text-sm text-[#00dbe7] hover:underline">
        <span class="material-symbols-outlined text-base" aria-hidden="true">add</span>
        أضف أول مقال
      </a>
    </div>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr style="border-bottom: 1px solid rgba(0,242,255,0.08); background: rgba(0,242,255,0.03);">
            <th class="text-right px-5 py-3.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">العنوان</th>
            <th class="text-right px-5 py-3.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider hidden md:table-cell">التصنيف</th>
            <th class="text-right px-5 py-3.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider hidden lg:table-cell">تاريخ النشر</th>
            <th class="text-right px-5 py-3.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">الحالة</th>
            <th class="text-right px-5 py-3.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider hidden sm:table-cell">مميز</th>
            <th class="text-right px-5 py-3.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20">
          <?php foreach ($blogs as $row): ?>
            <tr class="transition-colors hover:bg-surface-container/50">
              <!-- Title -->
              <td class="px-5 py-4 font-medium text-on-surface max-w-xs">
                <span class="block truncate" title="<?= e($row['title']) ?>"><?= e($row['title']) ?></span>
                <span class="block text-xs text-on-surface-variant/60 mt-0.5 truncate">/<?= e($row['slug']) ?></span>
              </td>
              <!-- Category -->
              <td class="px-5 py-4 hidden md:table-cell">
                <?php
                  $color = ($row['category_color'] ?? 'cyan') === 'gold'
                    ? 'rgba(212,175,55,0.15); color: #D4AF37; border-color: rgba(212,175,55,0.3)'
                    : 'rgba(0,219,231,0.1); color: #00dbe7; border-color: rgba(0,219,231,0.25)';
                ?>
                <span class="inline-block text-xs px-2.5 py-1 rounded-full border font-medium whitespace-nowrap"
                      style="background: <?= $color ?>">
                  <?= e($row['category']) ?>
                </span>
              </td>
              <!-- Published at -->
              <td class="px-5 py-4 text-on-surface-variant hidden lg:table-cell whitespace-nowrap">
                <?= $row['published_at'] ? formatArabicDate($row['published_at']) : '—' ?>
              </td>
              <!-- Status -->
              <td class="px-5 py-4 whitespace-nowrap">
                <?php if ($row['is_active']): ?>
                  <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full font-medium"
                        style="background: rgba(0,219,231,0.1); color: #00dbe7; border: 1px solid rgba(0,219,231,0.25);">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#00dbe7] inline-block" aria-hidden="true"></span>
                    نشط
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full font-medium"
                        style="background: rgba(255,255,255,0.05); color: #b9cacb; border: 1px solid rgba(185,202,203,0.2);">
                    <span class="w-1.5 h-1.5 rounded-full bg-on-surface-variant inline-block" aria-hidden="true"></span>
                    مسودة
                  </span>
                <?php endif; ?>
              </td>
              <!-- Featured -->
              <td class="px-5 py-4 hidden sm:table-cell">
                <?php if ($row['is_featured']): ?>
                  <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full font-medium"
                        style="background: rgba(212,175,55,0.1); color: #D4AF37; border: 1px solid rgba(212,175,55,0.3);">
                    <span class="material-symbols-outlined text-sm" style="font-size:14px;" aria-hidden="true">star</span>
                    مميز
                  </span>
                <?php else: ?>
                  <span class="text-on-surface-variant/40 text-xs">—</span>
                <?php endif; ?>
              </td>
              <!-- Actions -->
              <td class="px-5 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <!-- Edit -->
                  <a href="blog-form.php?id=<?= (int) $row['id'] ?>"
                     class="inline-flex items-center gap-1 text-xs px-3 py-1.5 rounded-xl transition-all font-medium"
                     style="background: rgba(0,219,231,0.08); border: 1px solid rgba(0,219,231,0.2); color: #00dbe7;"
                     onmouseover="this.style.background='rgba(0,219,231,0.16)'"
                     onmouseout="this.style.background='rgba(0,219,231,0.08)'"
                     title="تعديل">
                    <span class="material-symbols-outlined" style="font-size:14px;" aria-hidden="true">edit</span>
                    تعديل
                  </a>
                  <!-- Delete -->
                  <form method="POST" action="blogs.php" class="inline">
                    <?= csrfField() ?>
                    <input type="hidden" name="delete_id" value="<?= (int) $row['id'] ?>">
                    <button type="submit"
                            class="inline-flex items-center gap-1 text-xs px-3 py-1.5 rounded-xl transition-all font-medium"
                            style="background: rgba(255,180,171,0.08); border: 1px solid rgba(255,180,171,0.2); color: #ffb4ab;"
                            onmouseover="this.style.background='rgba(255,180,171,0.16)'"
                            onmouseout="this.style.background='rgba(255,180,171,0.08)'"
                            onclick="return confirm('هل أنت متأكد من الحذف؟')"
                            title="حذف">
                      <span class="material-symbols-outlined" style="font-size:14px;" aria-hidden="true">delete</span>
                      حذف
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

</main>
</body>
</html>
