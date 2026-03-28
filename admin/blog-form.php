<?php
require_once 'includes/auth.php';
require_once '../includes/functions.php';

$db = getDB();
$id   = $_GET['id'] ?? null;
$blog = null;
$errors = [];

if ($id) {
    $stmt = $db->prepare('SELECT * FROM blogs WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $blog = $stmt->fetch();
    if (!$blog) {
        header('Location: blogs.php');
        exit;
    }
}

// ─── Handle POST ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrf();

    $title         = trim($_POST['title'] ?? '');
    $slug          = trim($_POST['slug'] ?? '') ?: generateSlug($title);
    $author        = trim($_POST['author'] ?? '') ?: 'فريق رقال التقني';
    $category      = trim($_POST['category'] ?? '');
    $categoryColor = $_POST['category_color'] ?? 'cyan';
    $excerpt       = trim($_POST['excerpt'] ?? '');
    $content       = $_POST['content'] ?? '';
    $readTime      = (int) ($_POST['read_time'] ?? 5);
    $publishedAt   = $_POST['published_at'] ?? date('Y-m-d\TH:i');
    $isFeatured    = isset($_POST['is_featured']) ? 1 : 0;
    $isActive      = isset($_POST['is_active']) ? 1 : 0;

    // Validate required
    if (!$title)    $errors[] = 'العنوان مطلوب';
    if (!$category) $errors[] = 'التصنيف مطلوب';

    // Handle featured image upload
    $featuredImage = $blog['featured_image'] ?? null;
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $file         = $_FILES['featured_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize      = 2 * 1024 * 1024; // 2 MB

        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = 'نوع الملف غير مدعوم (JPEG, PNG, WebP فقط)';
        } elseif ($file['size'] > $maxSize) {
            $errors[] = 'حجم الملف يتجاوز 2 ميجابايت';
        } else {
            $uploadDir = '../uploads/blog/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], $uploadDir . $filename);

            // Delete old image if replacing
            if ($featuredImage) {
                deleteUpload('../' . $featuredImage);
            }
            $featuredImage = 'uploads/blog/' . $filename;
        }
    }

    if (empty($errors)) {
        // Un-feature others if this one is featured
        if ($isFeatured) {
            $db->exec('UPDATE blogs SET is_featured = 0 WHERE is_featured = 1');
        }

        $params = [
            ':title'          => $title,
            ':slug'           => $slug,
            ':author'         => $author,
            ':category'       => $category,
            ':category_color' => $categoryColor,
            ':excerpt'        => $excerpt,
            ':content'        => $content,
            ':featured_image' => $featuredImage,
            ':read_time'      => $readTime,
            ':is_featured'    => $isFeatured,
            ':is_active'      => $isActive,
            ':published_at'   => $publishedAt,
        ];

        if ($id && $blog) {
            $params[':id'] = $id;
            $stmt = $db->prepare(
                'UPDATE blogs SET
                    title          = :title,
                    slug           = :slug,
                    author         = :author,
                    category       = :category,
                    category_color = :category_color,
                    excerpt        = :excerpt,
                    content        = :content,
                    featured_image = :featured_image,
                    read_time      = :read_time,
                    is_featured    = :is_featured,
                    is_active      = :is_active,
                    published_at   = :published_at
                 WHERE id = :id'
            );
            $stmt->execute($params);
        } else {
            $stmt = $db->prepare(
                'INSERT INTO blogs
                    (title, slug, author, category, category_color, excerpt, content, featured_image, read_time, is_featured, is_active, published_at)
                 VALUES
                    (:title, :slug, :author, :category, :category_color, :excerpt, :content, :featured_image, :read_time, :is_featured, :is_active, :published_at)'
            );
            $stmt->execute($params);
        }

        header('Location: blogs.php?saved=1');
        exit;
    }
}

$adminPageTitle  = $id ? 'تعديل مقال' : 'إضافة مقال جديد';
$adminActivePage = 'blogs';
require_once 'includes/admin-header.php';
?>

<!-- ===== Page Header ===== -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
  <div>
    <h1 class="text-2xl md:text-3xl font-bold text-on-surface font-headline">
      <?= $id ? 'تعديل مقال' : 'إضافة مقال جديد' ?>
    </h1>
    <p class="mt-1 text-sm text-on-surface-variant">
      <?php if ($id && $blog): ?>
        تعديل: <span class="text-[#00dbe7]"><?= e($blog['title']) ?></span>
      <?php else: ?>
        أضف مقالاً جديداً إلى المدونة
      <?php endif; ?>
    </p>
  </div>
  <a href="blogs.php"
     class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all self-start sm:self-auto text-on-surface-variant hover:text-on-surface"
     style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);"
     onmouseover="this.style.background='rgba(255,255,255,0.09)'"
     onmouseout="this.style.background='rgba(255,255,255,0.05)'">
    <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
    العودة إلى القائمة
  </a>
</div>

<!-- ===== Error List ===== -->
<?php if (!empty($errors)): ?>
  <div class="mb-6 rounded-xl border px-5 py-4"
       style="background: rgba(255,180,171,0.08); border-color: rgba(255,180,171,0.3);"
       role="alert">
    <div class="flex items-center gap-2 mb-2">
      <span class="material-symbols-outlined text-error flex-shrink-0" aria-hidden="true">error</span>
      <p class="text-sm font-semibold text-error">يوجد أخطاء في النموذج:</p>
    </div>
    <ul class="list-disc list-inside space-y-1">
      <?php foreach ($errors as $err): ?>
        <li class="text-sm text-error/90"><?= e($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<!-- ===== Form ===== -->
<form method="POST" action="blog-form.php<?= $id ? '?id=' . (int) $id : '' ?>"
      enctype="multipart/form-data"
      class="space-y-6">
  <?= csrfField() ?>

  <!-- Row 1: Title + Slug -->
  <div class="glass-panel rounded-xl p-6 space-y-5">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">title</span>
      المعلومات الأساسية
    </h2>

    <!-- Title -->
    <div>
      <label for="title" class="block text-sm font-medium text-on-surface mb-1.5">
        العنوان <span class="text-error" aria-label="مطلوب">*</span>
      </label>
      <input type="text" id="title" name="title"
             value="<?= e($_POST['title'] ?? $blog['title'] ?? '') ?>"
             placeholder="عنوان المقال"
             class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
             style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);"
             required>
    </div>

    <!-- Slug -->
    <div>
      <label for="slug" class="block text-sm font-medium text-on-surface mb-1.5">
        الرابط المختصر (Slug)
      </label>
      <input type="text" id="slug" name="slug"
             value="<?= e($_POST['slug'] ?? $blog['slug'] ?? '') ?>"
             placeholder="يُولَّد تلقائياً من العنوان"
             dir="ltr"
             class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
             style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
      <p class="mt-1 text-xs text-on-surface-variant">يُستخدم في رابط المقال — يُولَّد تلقائياً إذا تُرك فارغاً.</p>
    </div>

    <!-- Author -->
    <div>
      <label for="author" class="block text-sm font-medium text-on-surface mb-1.5">الكاتب</label>
      <input type="text" id="author" name="author"
             value="<?= e($_POST['author'] ?? $blog['author'] ?? 'فريق رقال التقني') ?>"
             placeholder="فريق رقال التقني"
             class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
             style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
    </div>
  </div>

  <!-- Row 2: Category + Color + Read Time + Published At -->
  <div class="glass-panel rounded-xl p-6 space-y-5">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">category</span>
      التصنيف والنشر
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
      <!-- Category -->
      <div>
        <label for="category" class="block text-sm font-medium text-on-surface mb-1.5">
          التصنيف <span class="text-error" aria-label="مطلوب">*</span>
        </label>
        <?php
          $selectedCat = $_POST['category'] ?? $blog['category'] ?? '';
          $categories  = ['التحول الرقمي', 'الذكاء الاصطناعي', 'أمن المعلومات', 'تطوير البرمجيات', 'رؤية ٢٠٣٠'];
        ?>
        <select id="category" name="category"
                class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                style="background: rgba(20,26,50,0.9); border: 1px solid rgba(0,242,255,0.12);"
                required>
          <option value="">— اختر التصنيف —</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= e($cat) ?>" <?= $selectedCat === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Read Time -->
      <div>
        <label for="read_time" class="block text-sm font-medium text-on-surface mb-1.5">وقت القراءة (دقيقة)</label>
        <input type="number" id="read_time" name="read_time" min="1" max="60"
               value="<?= (int) ($_POST['read_time'] ?? $blog['read_time'] ?? 5) ?>"
               class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
               style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
      </div>
    </div>

    <!-- Category Color -->
    <div>
      <p class="text-sm font-medium text-on-surface mb-2">لون التصنيف</p>
      <?php $selectedColor = $_POST['category_color'] ?? $blog['category_color'] ?? 'cyan'; ?>
      <div class="flex items-center gap-6">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="category_color" value="cyan"
                 <?= $selectedColor === 'cyan' ? 'checked' : '' ?>
                 class="accent-[#00dbe7]">
          <span class="text-sm text-[#00dbe7] font-medium">أزرق مائي (Cyan)</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="category_color" value="gold"
                 <?= $selectedColor === 'gold' ? 'checked' : '' ?>
                 class="accent-[#D4AF37]">
          <span class="text-sm font-medium" style="color: #D4AF37;">ذهبي (Gold)</span>
        </label>
      </div>
    </div>

    <!-- Published At -->
    <div>
      <label for="published_at" class="block text-sm font-medium text-on-surface mb-1.5">تاريخ النشر</label>
      <?php
        $rawPubAt = $_POST['published_at'] ?? $blog['published_at'] ?? date('Y-m-d\TH:i');
        // Normalise to datetime-local format (YYYY-MM-DDTHH:MM)
        $pubAtForInput = strlen($rawPubAt) >= 16 ? substr(str_replace(' ', 'T', $rawPubAt), 0, 16) : date('Y-m-d\TH:i');
      ?>
      <input type="datetime-local" id="published_at" name="published_at"
             value="<?= e($pubAtForInput) ?>"
             class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
             style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12); color-scheme: dark;">
    </div>
  </div>

  <!-- Row 3: Excerpt -->
  <div class="glass-panel rounded-xl p-6 space-y-5">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">short_text</span>
      الملخص
    </h2>
    <div>
      <label for="excerpt" class="block text-sm font-medium text-on-surface mb-1.5">مقتطف المقال</label>
      <textarea id="excerpt" name="excerpt" rows="3"
                placeholder="وصف قصير يظهر في قائمة المقالات…"
                class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40 resize-y"
                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);"><?= e($_POST['excerpt'] ?? $blog['excerpt'] ?? '') ?></textarea>
    </div>
  </div>

  <!-- Row 4: Content (TinyMCE) -->
  <div class="glass-panel rounded-xl p-6 space-y-5">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">edit_document</span>
      محتوى المقال
    </h2>
    <div>
      <label for="content" class="block text-sm font-medium text-on-surface mb-1.5">المحتوى</label>
      <textarea id="content" name="content"><?= e($_POST['content'] ?? $blog['content'] ?? '') ?></textarea>
    </div>
  </div>

  <!-- Row 5: Featured Image -->
  <div class="glass-panel rounded-xl p-6 space-y-5">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">image</span>
      الصورة المميزة
    </h2>

    <!-- Current image preview (edit mode) -->
    <?php $currentImage = $blog['featured_image'] ?? null; ?>
    <?php if ($currentImage): ?>
      <div class="mb-3">
        <p class="text-xs text-on-surface-variant mb-2">الصورة الحالية:</p>
        <img src="../<?= e($currentImage) ?>" alt="الصورة المميزة الحالية"
             class="h-32 rounded-xl object-cover border"
             style="border-color: rgba(0,242,255,0.15);">
      </div>
    <?php endif; ?>

    <div>
      <label for="featured_image" class="block text-sm font-medium text-on-surface mb-1.5">
        <?= $currentImage ? 'استبدال الصورة' : 'رفع صورة' ?>
      </label>
      <input type="file" id="featured_image" name="featured_image"
             accept="image/jpeg,image/png,image/webp"
             class="w-full text-sm text-on-surface-variant file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:cursor-pointer transition-all"
             style="background: rgba(255,255,255,0.04); border: 1px solid rgba(0,242,255,0.12); border-radius: 0.5rem; padding: 0.5rem;"
             onchange="previewImage(this)">
      <p class="mt-1 text-xs text-on-surface-variant">JPEG, PNG أو WebP — بحد أقصى 2 ميجابايت</p>
    </div>

    <!-- New image preview -->
    <img id="image-preview" src="" alt="معاينة الصورة"
         class="hidden h-32 rounded-xl object-cover border mt-2"
         style="border-color: rgba(0,242,255,0.15);">
  </div>

  <!-- Row 6: Settings (is_featured, is_active) -->
  <div class="glass-panel rounded-xl p-6">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2 mb-5">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">settings</span>
      الإعدادات
    </h2>
    <div class="flex flex-col sm:flex-row gap-6">
      <!-- Is Active -->
      <label class="flex items-center gap-3 cursor-pointer select-none">
        <input type="checkbox" id="is_active" name="is_active" value="1"
               <?php
                 $activeChecked = isset($_POST['is_active'])
                   ? ($_POST['is_active'] ? 'checked' : '')
                   : ((!$id || ($blog['is_active'] ?? 1)) ? 'checked' : '');
                 echo $activeChecked;
               ?>
               class="w-4 h-4 rounded accent-[#00dbe7]">
        <div>
          <span class="text-sm font-medium text-on-surface">نشط</span>
          <p class="text-xs text-on-surface-variant mt-0.5">يظهر المقال في موقع الويب</p>
        </div>
      </label>
      <!-- Is Featured -->
      <label class="flex items-center gap-3 cursor-pointer select-none">
        <input type="checkbox" id="is_featured" name="is_featured" value="1"
               <?php
                 $featuredChecked = isset($_POST['is_featured'])
                   ? ($_POST['is_featured'] ? 'checked' : '')
                   : (($blog['is_featured'] ?? 0) ? 'checked' : '');
                 echo $featuredChecked;
               ?>
               class="w-4 h-4 rounded accent-[#D4AF37]">
        <div>
          <span class="text-sm font-medium" style="color: #D4AF37;">مقال مميز</span>
          <p class="text-xs text-on-surface-variant mt-0.5">يظهر بارزاً في أعلى صفحة المدونة — سيُلغى تمييز المقال المميز الحالي</p>
        </div>
      </label>
    </div>
  </div>

  <!-- Submit -->
  <div class="flex items-center justify-end gap-4 pt-2">
    <a href="blogs.php"
       class="px-5 py-2.5 rounded-xl text-sm font-medium text-on-surface-variant transition-all"
       style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);"
       onmouseover="this.style.background='rgba(255,255,255,0.09)'"
       onmouseout="this.style.background='rgba(255,255,255,0.05)'">
      إلغاء
    </a>
    <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all"
            style="background: rgba(0,219,231,0.15); border: 1px solid rgba(0,219,231,0.35); color: #00dbe7;"
            onmouseover="this.style.background='rgba(0,219,231,0.25)'"
            onmouseout="this.style.background='rgba(0,219,231,0.15)'">
      <span class="material-symbols-outlined text-base" aria-hidden="true">save</span>
      <?= $id ? 'حفظ التعديلات' : 'نشر المقال' ?>
    </button>
  </div>

</form>

<!-- ===== TinyMCE ===== -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#content',
    directionality: 'rtl',
    language: 'ar',
    plugins: 'link image lists table code',
    toolbar: 'undo redo | blocks | bold italic | alignright aligncenter alignleft | bullist numlist | link image | table | code',
    height: 400,
    skin: 'oxide-dark',
    content_css: 'dark',
});
</script>

<!-- ===== Auto-slug + Image preview JS ===== -->
<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function () {
    var slugField = document.getElementById('slug');
    if (!slugField.dataset.manual) {
        slugField.value = arabicToSlug(this.value);
    }
});

// Mark slug as manually edited
document.getElementById('slug').addEventListener('input', function () {
    this.dataset.manual = '1';
});

function arabicToSlug(text) {
    var map = {
        'ا': 'a',  'أ': 'a',  'إ': 'i',  'آ': 'a',
        'ب': 'b',  'ت': 't',  'ث': 'th', 'ج': 'j',
        'ح': 'h',  'خ': 'kh', 'د': 'd',  'ذ': 'dh',
        'ر': 'r',  'ز': 'z',  'س': 's',  'ش': 'sh',
        'ص': 's',  'ض': 'd',  'ط': 't',  'ظ': 'z',
        'ع': 'a',  'غ': 'gh', 'ف': 'f',  'ق': 'q',
        'ك': 'k',  'ل': 'l',  'م': 'm',  'ن': 'n',
        'ه': 'h',  'و': 'w',  'ي': 'y',  'ة': 'h',
        'ى': 'a',  'ء': 'a',  'ؤ': 'w',  'ئ': 'y',
    };
    return text.split('').map(function (c) { return map[c] || c; }).join('')
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^a-z0-9-]/g, '')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
}

// Image preview
function previewImage(input) {
    var preview = document.getElementById('image-preview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '';
        preview.classList.add('hidden');
    }
}
</script>

</main>
</body>
</html>
