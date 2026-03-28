<?php
require_once 'includes/auth.php';
require_once '../includes/functions.php';

$db = getDB();
$id      = $_GET['id'] ?? null;
$service = null;
$errors  = [];

if ($id) {
    $stmt = $db->prepare('SELECT * FROM services WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $service = $stmt->fetch();
    if (!$service) {
        header('Location: services.php');
        exit;
    }
}

// ─── Handle POST ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrf();

    // Basic fields
    $title         = trim($_POST['title'] ?? '');
    $slug          = trim($_POST['slug'] ?? '') ?: generateSlug($title);
    $icon          = trim($_POST['icon'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $colorScheme   = $_POST['color_scheme'] ?? 'cyan';
    $gridColSpan   = (int) ($_POST['grid_col_span'] ?? 1);
    $sortOrder     = (int) ($_POST['sort_order'] ?? 0);
    $isActive      = isset($_POST['is_active']) ? 1 : 0;

    // TinyMCE rich content fields
    $overviewContent = $_POST['overview_content'] ?? '';
    $fullContent     = $_POST['full_content'] ?? '';

    // ── Repeater: subservices (simple string array) ──
    $subservicesRaw = $_POST['subservices'] ?? [];
    $subservices    = json_encode(array_values(array_filter(array_map('trim', $subservicesRaw))));

    // ── Repeater: stats [{label, value}] ──
    $statLabels = $_POST['stat_label'] ?? [];
    $statValues = $_POST['stat_value'] ?? [];
    $stats = [];
    foreach ($statLabels as $i => $label) {
        $label = trim($label);
        $value = trim($statValues[$i] ?? '');
        if ($label !== '' || $value !== '') {
            $stats[] = ['label' => $label, 'value' => $value];
        }
    }
    $statsJson = json_encode($stats);

    // ── Repeater: target_businesses [{title, description, icon}] ──
    $tbTitles = $_POST['tb_title'] ?? [];
    $tbDescs  = $_POST['tb_description'] ?? [];
    $tbIcons  = $_POST['tb_icon'] ?? [];
    $targetBusinesses = [];
    foreach ($tbTitles as $i => $title_) {
        $t = trim($title_);
        $d = trim($tbDescs[$i] ?? '');
        $ic = trim($tbIcons[$i] ?? '');
        if ($t !== '' || $d !== '' || $ic !== '') {
            $targetBusinesses[] = ['title' => $t, 'description' => $d, 'icon' => $ic];
        }
    }
    $targetBusinessesJson = json_encode($targetBusinesses);

    // ── Repeater: benefits [{title, description, icon}] ──
    $benefitTitles = $_POST['benefit_title'] ?? [];
    $benefitDescs  = $_POST['benefit_description'] ?? [];
    $benefitIcons  = $_POST['benefit_icon'] ?? [];
    $benefits = [];
    foreach ($benefitTitles as $i => $title_) {
        $t = trim($title_);
        $d = trim($benefitDescs[$i] ?? '');
        $ic = trim($benefitIcons[$i] ?? '');
        if ($t !== '' || $d !== '' || $ic !== '') {
            $benefits[] = ['title' => $t, 'description' => $d, 'icon' => $ic];
        }
    }
    $benefitsJson = json_encode($benefits);

    // ── Repeater: workflow [{title, description}] ──
    $workflowTitles = $_POST['workflow_title'] ?? [];
    $workflowDescs  = $_POST['workflow_description'] ?? [];
    $workflow = [];
    foreach ($workflowTitles as $i => $title_) {
        $t = trim($title_);
        $d = trim($workflowDescs[$i] ?? '');
        if ($t !== '' || $d !== '') {
            $workflow[] = ['title' => $t, 'description' => $d];
        }
    }
    $workflowJson = json_encode($workflow);

    // ── Repeater: tech_stack (simple string array) ──
    $techStackRaw = $_POST['tech_stack'] ?? [];
    $techStack    = json_encode(array_values(array_filter(array_map('trim', $techStackRaw))));

    // ── Repeater: faq [{question, answer}] ──
    $faqQuestions = $_POST['faq_question'] ?? [];
    $faqAnswers   = $_POST['faq_answer'] ?? [];
    $faq = [];
    foreach ($faqQuestions as $i => $question) {
        $q = trim($question);
        $a = trim($faqAnswers[$i] ?? '');
        if ($q !== '' || $a !== '') {
            $faq[] = ['question' => $q, 'answer' => $a];
        }
    }
    $faqJson = json_encode($faq);

    // Validate
    if (!$title) $errors[] = 'العنوان مطلوب';

    // Handle featured image upload
    $featuredImage = $service['featured_image'] ?? null;
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['featured_image'];
        $uploadError = validateImageUpload($file);

        if ($uploadError) {
            $errors[] = $uploadError;
        } else {
            $uploadDir = '../uploads/services/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = secureFilename($file['name']);
            move_uploaded_file($file['tmp_name'], $uploadDir . $filename);

            // Delete old image if replacing
            if ($featuredImage) {
                deleteUpload('../' . $featuredImage);
            }
            $featuredImage = 'uploads/services/' . $filename;
        }
    }

    if (empty($errors)) {
        $params = [
            ':title'               => $title,
            ':slug'                => $slug,
            ':icon'                => $icon,
            ':description'         => $description,
            ':color_scheme'        => $colorScheme,
            ':grid_col_span'       => $gridColSpan,
            ':sort_order'          => $sortOrder,
            ':is_active'           => $isActive,
            ':featured_image'      => $featuredImage,
            ':overview_content'    => $overviewContent,
            ':full_content'        => $fullContent,
            ':subservices'         => $subservices,
            ':stats'               => $statsJson,
            ':target_businesses'   => $targetBusinessesJson,
            ':benefits'            => $benefitsJson,
            ':workflow'            => $workflowJson,
            ':tech_stack'          => $techStack,
            ':faq'                 => $faqJson,
        ];

        if ($id && $service) {
            $params[':id'] = $id;
            $stmt = $db->prepare(
                'UPDATE services SET
                    title             = :title,
                    slug              = :slug,
                    icon              = :icon,
                    description       = :description,
                    color_scheme      = :color_scheme,
                    grid_col_span     = :grid_col_span,
                    sort_order        = :sort_order,
                    is_active         = :is_active,
                    featured_image    = :featured_image,
                    overview_content  = :overview_content,
                    full_content      = :full_content,
                    subservices       = :subservices,
                    stats             = :stats,
                    target_businesses = :target_businesses,
                    benefits          = :benefits,
                    workflow          = :workflow,
                    tech_stack        = :tech_stack,
                    faq               = :faq
                 WHERE id = :id'
            );
            $stmt->execute($params);
        } else {
            $stmt = $db->prepare(
                'INSERT INTO services
                    (title, slug, icon, description, color_scheme, grid_col_span, sort_order, is_active, featured_image,
                     overview_content, full_content, subservices, stats, target_businesses, benefits, workflow, tech_stack, faq)
                 VALUES
                    (:title, :slug, :icon, :description, :color_scheme, :grid_col_span, :sort_order, :is_active, :featured_image,
                     :overview_content, :full_content, :subservices, :stats, :target_businesses, :benefits, :workflow, :tech_stack, :faq)'
            );
            $stmt->execute($params);
        }

        header('Location: services.php?saved=1');
        exit;
    }
}

// ─── Pre-decode JSON repeater data for editing ──────────────────────────────
$existingSubservices      = json_decode($service['subservices'] ?? '[]', true) ?? [];
$existingStats            = json_decode($service['stats'] ?? '[]', true) ?? [];
$existingTargetBusinesses = json_decode($service['target_businesses'] ?? '[]', true) ?? [];
$existingBenefits         = json_decode($service['benefits'] ?? '[]', true) ?? [];
$existingWorkflow         = json_decode($service['workflow'] ?? '[]', true) ?? [];
$existingTechStack        = json_decode($service['tech_stack'] ?? '[]', true) ?? [];
$existingFaq              = json_decode($service['faq'] ?? '[]', true) ?? [];

$adminPageTitle  = $id ? 'تعديل خدمة' : 'إضافة خدمة جديدة';
$adminActivePage = 'services';
require_once 'includes/admin-header.php';
?>

<!-- ===== Page Header ===== -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
  <div>
    <h1 class="text-2xl md:text-3xl font-bold text-on-surface font-headline">
      <?= $id ? 'تعديل خدمة' : 'إضافة خدمة جديدة' ?>
    </h1>
    <p class="mt-1 text-sm text-on-surface-variant">
      <?php if ($id && $service): ?>
        تعديل: <span class="text-[#00dbe7]"><?= e($service['title']) ?></span>
      <?php else: ?>
        أضف خدمة جديدة لعرضها في الموقع
      <?php endif; ?>
    </p>
  </div>
  <a href="services.php"
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
<form method="POST" action="service-form.php<?= $id ? '?id=' . (int) $id : '' ?>"
      enctype="multipart/form-data"
      class="space-y-6">
  <?= csrfField() ?>

  <!-- ── Section 1: Basic Information ── -->
  <div class="glass-panel rounded-xl p-6 space-y-5">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">info</span>
      المعلومات الأساسية
    </h2>

    <!-- Title -->
    <div>
      <label for="title" class="block text-sm font-medium text-on-surface mb-1.5">
        العنوان <span class="text-error" aria-label="مطلوب">*</span>
      </label>
      <input type="text" id="title" name="title"
             value="<?= e($_POST['title'] ?? $service['title'] ?? '') ?>"
             placeholder="عنوان الخدمة"
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
             value="<?= e($_POST['slug'] ?? $service['slug'] ?? '') ?>"
             placeholder="يُولَّد تلقائياً من العنوان"
             dir="ltr"
             class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
             style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
      <p class="mt-1 text-xs text-on-surface-variant">يُستخدم في رابط الخدمة — يُولَّد تلقائياً إذا تُرك فارغاً.</p>
    </div>

    <!-- Icon -->
    <div>
      <label for="icon" class="block text-sm font-medium text-on-surface mb-1.5">الأيقونة</label>
      <input type="text" id="icon" name="icon"
             value="<?= e($_POST['icon'] ?? $service['icon'] ?? '') ?>"
             placeholder="web"
             dir="ltr"
             class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
             style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
      <p class="mt-1 text-xs text-on-surface-variant">اسم الأيقونة من Material Symbols (مثال: web, smartphone, security)</p>
    </div>

    <!-- Description -->
    <div>
      <label for="description" class="block text-sm font-medium text-on-surface mb-1.5">الوصف المختصر</label>
      <textarea id="description" name="description" rows="3"
                placeholder="وصف موجز يظهر في بطاقة الخدمة…"
                class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40 resize-y"
                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);"><?= e($_POST['description'] ?? $service['description'] ?? '') ?></textarea>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
      <!-- Color Scheme -->
      <div class="sm:col-span-1">
        <p class="text-sm font-medium text-on-surface mb-2">نظام الألوان</p>
        <?php $selectedColor = $_POST['color_scheme'] ?? $service['color_scheme'] ?? 'cyan'; ?>
        <div class="flex items-center gap-5">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="color_scheme" value="cyan"
                   <?= $selectedColor === 'cyan' ? 'checked' : '' ?>
                   class="accent-[#00dbe7]">
            <span class="text-sm text-[#00dbe7] font-medium">أزرق مائي</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="color_scheme" value="gold"
                   <?= $selectedColor === 'gold' ? 'checked' : '' ?>
                   class="accent-[#D4AF37]">
            <span class="text-sm font-medium" style="color: #D4AF37;">ذهبي</span>
          </label>
        </div>
      </div>

      <!-- Grid Col Span -->
      <div>
        <label for="grid_col_span" class="block text-sm font-medium text-on-surface mb-1.5">اتساع البطاقة</label>
        <?php $selectedSpan = (int) ($_POST['grid_col_span'] ?? $service['grid_col_span'] ?? 1); ?>
        <select id="grid_col_span" name="grid_col_span"
                class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                style="background: rgba(20,26,50,0.9); border: 1px solid rgba(0,242,255,0.12);">
          <option value="1" <?= $selectedSpan === 1 ? 'selected' : '' ?>>1 عمود</option>
          <option value="2" <?= $selectedSpan === 2 ? 'selected' : '' ?>>2 عمودين</option>
        </select>
      </div>

      <!-- Sort Order -->
      <div>
        <label for="sort_order" class="block text-sm font-medium text-on-surface mb-1.5">الترتيب</label>
        <input type="number" id="sort_order" name="sort_order" min="0"
               value="<?= (int) ($_POST['sort_order'] ?? $service['sort_order'] ?? 0) ?>"
               class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
               style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
      </div>
    </div>

    <!-- Is Active -->
    <div>
      <label class="flex items-center gap-3 cursor-pointer select-none">
        <input type="checkbox" id="is_active" name="is_active" value="1"
               <?php
                 $activeChecked = isset($_POST['is_active'])
                   ? ($_POST['is_active'] ? 'checked' : '')
                   : ((!$id || ($service['is_active'] ?? 1)) ? 'checked' : '');
                 echo $activeChecked;
               ?>
               class="w-4 h-4 rounded accent-[#00dbe7]">
        <div>
          <span class="text-sm font-medium text-on-surface">نشط</span>
          <p class="text-xs text-on-surface-variant mt-0.5">تظهر الخدمة في موقع الويب</p>
        </div>
      </label>
    </div>
  </div>

  <!-- ── Section 2: Rich Content (TinyMCE) ── -->
  <div class="glass-panel rounded-xl p-6 space-y-5">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">edit_document</span>
      المحتوى التفصيلي
    </h2>

    <!-- Overview Content -->
    <div>
      <label for="overview_content" class="block text-sm font-medium text-on-surface mb-1.5">نظرة عامة</label>
      <textarea id="overview_content" name="overview_content"><?= e($_POST['overview_content'] ?? $service['overview_content'] ?? '') ?></textarea>
    </div>

    <!-- Full Content -->
    <div>
      <label for="full_content" class="block text-sm font-medium text-on-surface mb-1.5">المحتوى الكامل</label>
      <textarea id="full_content" name="full_content"><?= e($_POST['full_content'] ?? $service['full_content'] ?? '') ?></textarea>
    </div>
  </div>

  <!-- ── Featured Image ── -->
  <div class="glass-panel rounded-xl p-6 space-y-5">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">image</span>
      الصورة المميزة
    </h2>

    <!-- Current image preview (edit mode) -->
    <?php $currentImage = $service['featured_image'] ?? null; ?>
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

  <!-- ── Section 3: Subservices ── -->
  <div class="glass-panel rounded-xl p-6 space-y-4">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">list</span>
      الخدمات الفرعية
    </h2>
    <p class="text-xs text-on-surface-variant">قائمة الخدمات الفرعية المنضوية تحت هذه الخدمة الرئيسية.</p>

    <div id="repeater-subservices" class="space-y-1">
      <!-- Existing rows -->
      <?php foreach ($existingSubservices as $sub): ?>
        <div class="repeater-row flex gap-3 items-center mb-3">
          <input type="text" name="subservices[]"
                 value="<?= e($sub) ?>"
                 placeholder="اسم الخدمة الفرعية"
                 class="flex-1 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <button type="button" class="remove-row inline-flex items-center gap-1 text-xs px-3 py-2 rounded-xl transition-all font-medium flex-shrink-0"
                  style="background: rgba(255,180,171,0.08); border: 1px solid rgba(255,180,171,0.2); color: #ffb4ab;"
                  onmouseover="this.style.background='rgba(255,180,171,0.2)'"
                  onmouseout="this.style.background='rgba(255,180,171,0.08)'"
                  title="إزالة">
            <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">close</span>
          </button>
        </div>
      <?php endforeach; ?>
      <!-- Add button -->
      <button type="button" class="add-row inline-flex items-center gap-2 text-xs px-4 py-2 rounded-xl transition-all font-medium mt-2"
              style="background: rgba(0,219,231,0.08); border: 1px solid rgba(0,219,231,0.2); color: #00dbe7;"
              onmouseover="this.style.background='rgba(0,219,231,0.16)'"
              onmouseout="this.style.background='rgba(0,219,231,0.08)'">
        <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">add</span>
        إضافة خدمة فرعية
      </button>
    </div>
  </div>

  <!-- ── Section 4: Stats ── -->
  <div class="glass-panel rounded-xl p-6 space-y-4">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">bar_chart</span>
      الإحصائيات والأرقام
    </h2>
    <p class="text-xs text-on-surface-variant">أرقام وإحصائيات تُبرز نتائج الخدمة (مثال: 95% رضا العملاء).</p>

    <div id="repeater-stats" class="space-y-1">
      <?php foreach ($existingStats as $stat): ?>
        <div class="repeater-row flex gap-3 items-center mb-3">
          <input type="text" name="stat_value[]"
                 value="<?= e($stat['value'] ?? '') ?>"
                 placeholder="القيمة (مثال: 95%)"
                 dir="ltr"
                 class="w-32 flex-shrink-0 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <input type="text" name="stat_label[]"
                 value="<?= e($stat['label'] ?? '') ?>"
                 placeholder="الوصف (مثال: رضا العملاء)"
                 class="flex-1 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <button type="button" class="remove-row inline-flex items-center gap-1 text-xs px-3 py-2 rounded-xl transition-all font-medium flex-shrink-0"
                  style="background: rgba(255,180,171,0.08); border: 1px solid rgba(255,180,171,0.2); color: #ffb4ab;"
                  onmouseover="this.style.background='rgba(255,180,171,0.2)'"
                  onmouseout="this.style.background='rgba(255,180,171,0.08)'"
                  title="إزالة">
            <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">close</span>
          </button>
        </div>
      <?php endforeach; ?>
      <button type="button" class="add-row inline-flex items-center gap-2 text-xs px-4 py-2 rounded-xl transition-all font-medium mt-2"
              style="background: rgba(0,219,231,0.08); border: 1px solid rgba(0,219,231,0.2); color: #00dbe7;"
              onmouseover="this.style.background='rgba(0,219,231,0.16)'"
              onmouseout="this.style.background='rgba(0,219,231,0.08)'">
        <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">add</span>
        إضافة إحصائية
      </button>
    </div>
  </div>

  <!-- ── Section 5: Target Businesses ── -->
  <div class="glass-panel rounded-xl p-6 space-y-4">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">business</span>
      الجهات المستفيدة
    </h2>
    <p class="text-xs text-on-surface-variant">أنواع الشركات أو الجهات التي تستفيد من هذه الخدمة.</p>

    <div id="repeater-target-businesses" class="space-y-1">
      <?php foreach ($existingTargetBusinesses as $tb): ?>
        <div class="repeater-row flex gap-3 items-start mb-3">
          <input type="text" name="tb_icon[]"
                 value="<?= e($tb['icon'] ?? '') ?>"
                 placeholder="الأيقونة"
                 dir="ltr"
                 class="w-28 flex-shrink-0 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <input type="text" name="tb_title[]"
                 value="<?= e($tb['title'] ?? '') ?>"
                 placeholder="العنوان"
                 class="w-40 flex-shrink-0 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <input type="text" name="tb_description[]"
                 value="<?= e($tb['description'] ?? '') ?>"
                 placeholder="الوصف"
                 class="flex-1 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <button type="button" class="remove-row inline-flex items-center gap-1 text-xs px-3 py-2 rounded-xl transition-all font-medium flex-shrink-0 mt-0.5"
                  style="background: rgba(255,180,171,0.08); border: 1px solid rgba(255,180,171,0.2); color: #ffb4ab;"
                  onmouseover="this.style.background='rgba(255,180,171,0.2)'"
                  onmouseout="this.style.background='rgba(255,180,171,0.08)'"
                  title="إزالة">
            <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">close</span>
          </button>
        </div>
      <?php endforeach; ?>
      <button type="button" class="add-row inline-flex items-center gap-2 text-xs px-4 py-2 rounded-xl transition-all font-medium mt-2"
              style="background: rgba(0,219,231,0.08); border: 1px solid rgba(0,219,231,0.2); color: #00dbe7;"
              onmouseover="this.style.background='rgba(0,219,231,0.16)'"
              onmouseout="this.style.background='rgba(0,219,231,0.08)'">
        <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">add</span>
        إضافة جهة مستفيدة
      </button>
    </div>
  </div>

  <!-- ── Section 6: Benefits ── -->
  <div class="glass-panel rounded-xl p-6 space-y-4">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">verified</span>
      المزايا والفوائد
    </h2>
    <p class="text-xs text-on-surface-variant">المزايا الرئيسية التي تقدمها هذه الخدمة.</p>

    <div id="repeater-benefits" class="space-y-1">
      <?php foreach ($existingBenefits as $benefit): ?>
        <div class="repeater-row flex gap-3 items-start mb-3">
          <input type="text" name="benefit_icon[]"
                 value="<?= e($benefit['icon'] ?? '') ?>"
                 placeholder="الأيقونة"
                 dir="ltr"
                 class="w-28 flex-shrink-0 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <input type="text" name="benefit_title[]"
                 value="<?= e($benefit['title'] ?? '') ?>"
                 placeholder="العنوان"
                 class="w-40 flex-shrink-0 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <input type="text" name="benefit_description[]"
                 value="<?= e($benefit['description'] ?? '') ?>"
                 placeholder="الوصف"
                 class="flex-1 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <button type="button" class="remove-row inline-flex items-center gap-1 text-xs px-3 py-2 rounded-xl transition-all font-medium flex-shrink-0 mt-0.5"
                  style="background: rgba(255,180,171,0.08); border: 1px solid rgba(255,180,171,0.2); color: #ffb4ab;"
                  onmouseover="this.style.background='rgba(255,180,171,0.2)'"
                  onmouseout="this.style.background='rgba(255,180,171,0.08)'"
                  title="إزالة">
            <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">close</span>
          </button>
        </div>
      <?php endforeach; ?>
      <button type="button" class="add-row inline-flex items-center gap-2 text-xs px-4 py-2 rounded-xl transition-all font-medium mt-2"
              style="background: rgba(0,219,231,0.08); border: 1px solid rgba(0,219,231,0.2); color: #00dbe7;"
              onmouseover="this.style.background='rgba(0,219,231,0.16)'"
              onmouseout="this.style.background='rgba(0,219,231,0.08)'">
        <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">add</span>
        إضافة ميزة
      </button>
    </div>
  </div>

  <!-- ── Section 7: Workflow ── -->
  <div class="glass-panel rounded-xl p-6 space-y-4">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">account_tree</span>
      مراحل سير العمل
    </h2>
    <p class="text-xs text-on-surface-variant">خطوات تنفيذ الخدمة من البداية حتى الإنجاز.</p>

    <div id="repeater-workflow" class="space-y-1">
      <?php foreach ($existingWorkflow as $step): ?>
        <div class="repeater-row flex gap-3 items-start mb-3">
          <input type="text" name="workflow_title[]"
                 value="<?= e($step['title'] ?? '') ?>"
                 placeholder="عنوان المرحلة"
                 class="w-48 flex-shrink-0 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <textarea name="workflow_description[]"
                    rows="2"
                    placeholder="الوصف"
                    class="flex-1 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40 resize-y"
                    style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);"><?= e($step['description'] ?? '') ?></textarea>
          <button type="button" class="remove-row inline-flex items-center gap-1 text-xs px-3 py-2 rounded-xl transition-all font-medium flex-shrink-0 mt-0.5"
                  style="background: rgba(255,180,171,0.08); border: 1px solid rgba(255,180,171,0.2); color: #ffb4ab;"
                  onmouseover="this.style.background='rgba(255,180,171,0.2)'"
                  onmouseout="this.style.background='rgba(255,180,171,0.08)'"
                  title="إزالة">
            <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">close</span>
          </button>
        </div>
      <?php endforeach; ?>
      <button type="button" class="add-row inline-flex items-center gap-2 text-xs px-4 py-2 rounded-xl transition-all font-medium mt-2"
              style="background: rgba(0,219,231,0.08); border: 1px solid rgba(0,219,231,0.2); color: #00dbe7;"
              onmouseover="this.style.background='rgba(0,219,231,0.16)'"
              onmouseout="this.style.background='rgba(0,219,231,0.08)'">
        <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">add</span>
        إضافة مرحلة
      </button>
    </div>
  </div>

  <!-- ── Section 8: Tech Stack ── -->
  <div class="glass-panel rounded-xl p-6 space-y-4">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">code</span>
      التقنيات المستخدمة
    </h2>
    <p class="text-xs text-on-surface-variant">التقنيات والأدوات المستخدمة في تقديم هذه الخدمة.</p>

    <div id="repeater-tech-stack" class="space-y-1">
      <?php foreach ($existingTechStack as $tech): ?>
        <div class="repeater-row flex gap-3 items-center mb-3">
          <input type="text" name="tech_stack[]"
                 value="<?= e($tech) ?>"
                 placeholder="اسم التقنية (مثال: React, Laravel)"
                 dir="ltr"
                 class="flex-1 rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                 style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
          <button type="button" class="remove-row inline-flex items-center gap-1 text-xs px-3 py-2 rounded-xl transition-all font-medium flex-shrink-0"
                  style="background: rgba(255,180,171,0.08); border: 1px solid rgba(255,180,171,0.2); color: #ffb4ab;"
                  onmouseover="this.style.background='rgba(255,180,171,0.2)'"
                  onmouseout="this.style.background='rgba(255,180,171,0.08)'"
                  title="إزالة">
            <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">close</span>
          </button>
        </div>
      <?php endforeach; ?>
      <button type="button" class="add-row inline-flex items-center gap-2 text-xs px-4 py-2 rounded-xl transition-all font-medium mt-2"
              style="background: rgba(0,219,231,0.08); border: 1px solid rgba(0,219,231,0.2); color: #00dbe7;"
              onmouseover="this.style.background='rgba(0,219,231,0.16)'"
              onmouseout="this.style.background='rgba(0,219,231,0.08)'">
        <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">add</span>
        إضافة تقنية
      </button>
    </div>
  </div>

  <!-- ── Section 9: FAQ ── -->
  <div class="glass-panel rounded-xl p-6 space-y-4">
    <h2 class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-[#00dbe7]" aria-hidden="true">help</span>
      الأسئلة الشائعة
    </h2>
    <p class="text-xs text-on-surface-variant">إجابات على الأسئلة الأكثر شيوعاً حول هذه الخدمة.</p>

    <div id="repeater-faq" class="space-y-1">
      <?php foreach ($existingFaq as $item): ?>
        <div class="repeater-row flex gap-3 items-start mb-3">
          <div class="flex-1 space-y-2">
            <input type="text" name="faq_question[]"
                   value="<?= e($item['question'] ?? '') ?>"
                   placeholder="السؤال"
                   class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40"
                   style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);">
            <textarea name="faq_answer[]"
                      rows="2"
                      placeholder="الإجابة"
                      class="w-full rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40 resize-y"
                      style="background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);"><?= e($item['answer'] ?? '') ?></textarea>
          </div>
          <button type="button" class="remove-row inline-flex items-center gap-1 text-xs px-3 py-2 rounded-xl transition-all font-medium flex-shrink-0 mt-0.5"
                  style="background: rgba(255,180,171,0.08); border: 1px solid rgba(255,180,171,0.2); color: #ffb4ab;"
                  onmouseover="this.style.background='rgba(255,180,171,0.2)'"
                  onmouseout="this.style.background='rgba(255,180,171,0.08)'"
                  title="إزالة">
            <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">close</span>
          </button>
        </div>
      <?php endforeach; ?>
      <button type="button" class="add-row inline-flex items-center gap-2 text-xs px-4 py-2 rounded-xl transition-all font-medium mt-2"
              style="background: rgba(0,219,231,0.08); border: 1px solid rgba(0,219,231,0.2); color: #00dbe7;"
              onmouseover="this.style.background='rgba(0,219,231,0.16)'"
              onmouseout="this.style.background='rgba(0,219,231,0.08)'">
        <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">add</span>
        إضافة سؤال
      </button>
    </div>
  </div>

  <!-- ── Submit ── -->
  <div class="flex items-center justify-end gap-4 pt-2">
    <a href="services.php"
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
      <?= $id ? 'حفظ التعديلات' : 'حفظ الخدمة' ?>
    </button>
  </div>

</form>

<!-- ===== TinyMCE ===== -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#overview_content, #full_content',
    directionality: 'rtl',
    language: 'ar',
    plugins: 'link image lists table code',
    toolbar: 'undo redo | blocks | bold italic | alignright aligncenter alignleft | bullist numlist | link image | code',
    height: 300,
    skin: 'oxide-dark',
    content_css: 'dark',
});
</script>

<!-- ===== Auto-slug JS ===== -->
<script>
document.getElementById('title').addEventListener('input', function () {
    var slugField = document.getElementById('slug');
    if (!slugField.dataset.manual) {
        slugField.value = arabicToSlug(this.value);
    }
});

document.getElementById('slug').addEventListener('input', function () {
    this.dataset.manual = '1';
});

function arabicToSlug(text) {
    var map = {
        'ا': 'a', 'أ': 'a', 'إ': 'i', 'آ': 'a',
        'ب': 'b', 'ت': 't', 'ث': 'th', 'ج': 'j',
        'ح': 'h', 'خ': 'kh', 'د': 'd', 'ذ': 'dh',
        'ر': 'r', 'ز': 'z', 'س': 's', 'ش': 'sh',
        'ص': 's', 'ض': 'd', 'ط': 't', 'ظ': 'z',
        'ع': 'a', 'غ': 'gh', 'ف': 'f', 'ق': 'q',
        'ك': 'k', 'ل': 'l', 'م': 'm', 'ن': 'n',
        'ه': 'h', 'و': 'w', 'ي': 'y', 'ة': 'h',
        'ى': 'a', 'ء': 'a', 'ؤ': 'w', 'ئ': 'y',
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

<!-- ===== Repeater JS ===== -->
<script>
// Input field builder helpers
var inputClass = 'rounded-xl px-4 py-2.5 text-sm text-on-surface placeholder-on-surface-variant/50 transition-all focus:outline-none focus:ring-2 focus:ring-[#00dbe7]/40';
var inputStyle = 'background: rgba(255,255,255,0.05); border: 1px solid rgba(0,242,255,0.12);';
var textareaStyle = inputStyle;
var removeBtnHtml = '<button type="button" class="remove-row inline-flex items-center gap-1 text-xs px-3 py-2 rounded-xl transition-all font-medium flex-shrink-0 mt-0.5"'
    + ' style="background: rgba(255,180,171,0.08); border: 1px solid rgba(255,180,171,0.2); color: #ffb4ab;"'
    + ' onmouseover="this.style.background=\'rgba(255,180,171,0.2)\'"'
    + ' onmouseout="this.style.background=\'rgba(255,180,171,0.08)\'"'
    + ' title="إزالة"><span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">close</span></button>';

function makeInput(name, placeholder, extraClass, dir) {
    return '<input type="text" name="' + name + '" placeholder="' + placeholder + '"'
        + (dir ? ' dir="' + dir + '"' : '')
        + ' class="' + (extraClass || 'flex-1') + ' ' + inputClass + '"'
        + ' style="' + inputStyle + '">';
}

function makeTextarea(name, placeholder, extraClass) {
    return '<textarea name="' + name + '" rows="2" placeholder="' + placeholder + '"'
        + ' class="' + (extraClass || 'flex-1') + ' ' + inputClass + ' resize-y"'
        + ' style="' + textareaStyle + '"></textarea>';
}

function initRepeater(containerId, templateHtml) {
    var container = document.getElementById(containerId);
    var addBtn    = container.querySelector('.add-row');

    addBtn.addEventListener('click', function () {
        var row = document.createElement('div');
        row.className = 'repeater-row flex gap-3 items-start mb-3';
        row.innerHTML = templateHtml;
        container.insertBefore(row, addBtn);
    });

    container.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('.repeater-row').remove();
        }
    });
}

// ── Init each repeater ──

// 1. Subservices
initRepeater('repeater-subservices',
    makeInput('subservices[]', 'اسم الخدمة الفرعية', 'flex-1', null) + removeBtnHtml
);

// 2. Stats
initRepeater('repeater-stats',
    '<input type="text" name="stat_value[]" placeholder="القيمة (مثال: 95%)" dir="ltr"'
    + ' class="w-32 flex-shrink-0 ' + inputClass + '" style="' + inputStyle + '">'
    + makeInput('stat_label[]', 'الوصف (مثال: رضا العملاء)', 'flex-1', null)
    + removeBtnHtml
);

// 3. Target Businesses
initRepeater('repeater-target-businesses',
    '<input type="text" name="tb_icon[]" placeholder="الأيقونة" dir="ltr"'
    + ' class="w-28 flex-shrink-0 ' + inputClass + '" style="' + inputStyle + '">'
    + '<input type="text" name="tb_title[]" placeholder="العنوان"'
    + ' class="w-40 flex-shrink-0 ' + inputClass + '" style="' + inputStyle + '">'
    + makeInput('tb_description[]', 'الوصف', 'flex-1', null)
    + removeBtnHtml
);

// 4. Benefits
initRepeater('repeater-benefits',
    '<input type="text" name="benefit_icon[]" placeholder="الأيقونة" dir="ltr"'
    + ' class="w-28 flex-shrink-0 ' + inputClass + '" style="' + inputStyle + '">'
    + '<input type="text" name="benefit_title[]" placeholder="العنوان"'
    + ' class="w-40 flex-shrink-0 ' + inputClass + '" style="' + inputStyle + '">'
    + makeInput('benefit_description[]', 'الوصف', 'flex-1', null)
    + removeBtnHtml
);

// 5. Workflow
initRepeater('repeater-workflow',
    '<input type="text" name="workflow_title[]" placeholder="عنوان المرحلة"'
    + ' class="w-48 flex-shrink-0 ' + inputClass + '" style="' + inputStyle + '">'
    + makeTextarea('workflow_description[]', 'الوصف', 'flex-1')
    + removeBtnHtml
);

// 6. Tech Stack
initRepeater('repeater-tech-stack',
    makeInput('tech_stack[]', 'اسم التقنية (مثال: React, Laravel)', 'flex-1', 'ltr') + removeBtnHtml
);

// 7. FAQ
initRepeater('repeater-faq',
    '<div class="flex-1 space-y-2">'
    + makeInput('faq_question[]', 'السؤال', 'w-full', null)
    + makeTextarea('faq_answer[]', 'الإجابة', 'w-full')
    + '</div>'
    + removeBtnHtml
);
</script>

</main>
</body>
</html>
