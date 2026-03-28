<?php
$pageTitle = 'ركال | حلولنا - حلول رقمية لكل قطاع';
$activePage = 'solutions';
require_once 'includes/header.php';
?>

  <!-- 1. Hero Banner -->
  <section id="solutions-hero" class="relative min-h-[50vh] flex items-center justify-center overflow-hidden hero-mesh">
    <!-- Sadu pattern overlay -->
    <div class="absolute inset-0 sadu-pattern opacity-40"></div>
    <!-- Glow accents -->
    <div class="absolute top-0 right-1/4 w-48 h-48 md:w-96 md:h-96 bg-primary-container/10 rounded-full blur-[120px] pointer-events-none floating-orb"></div>
    <div class="absolute bottom-0 left-1/4 w-40 h-40 md:w-80 md:h-80 bg-sand-gold/8 rounded-full blur-[100px] pointer-events-none floating-orb"></div>

    <div class="relative z-10 text-center px-4 md:px-6 max-w-4xl mx-auto py-24">
      <!-- Badge -->
      <div class="inline-flex items-center gap-2 bg-primary-container/10 border border-primary-container/20 text-primary-container px-5 py-2 rounded-full text-sm font-bold mb-8 backdrop-blur-sm reveal">
        <span class="material-symbols-outlined text-base" aria-hidden="true">grid_view</span>
        حلول القطاعات
      </div>

      <!-- Headline -->
      <h1 class="text-2xl sm:text-4xl md:text-6xl font-black text-white leading-tight mb-6 reveal">
        حلول رقمية مصممة لطبيعة
        <span class="tech-gradient bg-clip-text text-transparent block mt-2">قطاعك</span>
      </h1>

      <!-- Subtitle -->
      <p class="text-lg md:text-xl text-white/60 leading-relaxed max-w-2xl mx-auto reveal">
        نقدم حلولاً برمجية مخصصة لكل قطاع في المملكة العربية السعودية، مصممة لتلبية الاحتياجات الفريدة لكل صناعة ومتوافقة مع متطلبات التحول الرقمي ورؤية ٢٠٣٠.
      </p>
    </div>
  </section>

  <!-- 2. Industry Solutions Grid -->
  <section class="py-32 px-4 md:px-6 max-w-screen-2xl mx-auto section-glow">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

      <!-- Card 1: الشركات والمؤسسات -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all group reveal card-hover">
        <div class="w-16 h-16 rounded-2xl bg-primary-container/15 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">domain</span>
        </div>
        <h3 class="text-xl font-bold text-white mb-3">الشركات والمؤسسات</h3>
        <p class="text-white/60 text-sm leading-relaxed mb-6">
          أنظمة إدارة متكاملة تُحسّن كفاءة العمليات الداخلية وتُمكّن فرق العمل من الأداء بمستوى أعلى مع رؤية شاملة لبيانات المؤسسة.
        </p>
        <ul class="space-y-3 mb-8">
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            أنظمة ERP مخصصة
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            بوابات الموظفين
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            أتمتة سير العمل
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            لوحات التحليلات
          </li>
        </ul>
        <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-2 text-primary-container text-sm font-bold hover:gap-3 transition-all">
          اكتشف المزيد
          <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        </a>
      </div>

      <!-- Card 2: الجهات الحكومية -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all group reveal card-hover">
        <div class="w-16 h-16 rounded-2xl bg-sand-gold/15 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-3xl text-sand-gold" aria-hidden="true">account_balance</span>
        </div>
        <h3 class="text-xl font-bold text-white mb-3">الجهات الحكومية</h3>
        <p class="text-white/60 text-sm leading-relaxed mb-6">
          منصات رقمية تلبي معايير الحكومة الرقمية السعودية وتُسهم في رفع كفاءة الخدمات الحكومية وتحسين تجربة المواطن.
        </p>
        <ul class="space-y-3 mb-8">
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            بوابات الخدمات الإلكترونية
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            أنظمة إدارة الوثائق
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            التكامل مع أبشر/نفاذ
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            لوحات البيانات
          </li>
        </ul>
        <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-2 text-sand-gold text-sm font-bold hover:gap-3 transition-all">
          اكتشف المزيد
          <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        </a>
      </div>

      <!-- Card 3: التجارة الإلكترونية -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all group reveal card-hover">
        <div class="w-16 h-16 rounded-2xl bg-primary-container/15 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">shopping_cart</span>
        </div>
        <h3 class="text-xl font-bold text-white mb-3">التجارة الإلكترونية</h3>
        <p class="text-white/60 text-sm leading-relaxed mb-6">
          متاجر إلكترونية متكاملة مع البنية التحتية المحلية، تجمع بين تجربة المستخدم الاستثنائية والأداء التقني العالي.
        </p>
        <ul class="space-y-3 mb-8">
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            تصميم المتجر
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            بوابات الدفع المحلية
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            إدارة المخزون
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            تكامل شركات الشحن
          </li>
        </ul>
        <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-2 text-primary-container text-sm font-bold hover:gap-3 transition-all">
          اكتشف المزيد
          <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        </a>
      </div>

      <!-- Card 4: العيادات والمراكز الطبية -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all group reveal card-hover">
        <div class="w-16 h-16 rounded-2xl bg-sand-gold/15 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-3xl text-sand-gold" aria-hidden="true">local_hospital</span>
        </div>
        <h3 class="text-xl font-bold text-white mb-3">العيادات والمراكز الطبية</h3>
        <p class="text-white/60 text-sm leading-relaxed mb-6">
          أنظمة صحية ذكية تُحسّن تجربة المريض والإدارة الطبية، مع ضمان أمان البيانات والامتثال للمعايير الصحية السعودية.
        </p>
        <ul class="space-y-3 mb-8">
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            نظام الملف الطبي
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            إدارة المواعيد
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            الفوترة الطبية
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            التقارير الصحية
          </li>
        </ul>
        <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-2 text-sand-gold text-sm font-bold hover:gap-3 transition-all">
          اكتشف المزيد
          <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        </a>
      </div>

      <!-- Card 5: التعليم -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all group reveal card-hover">
        <div class="w-16 h-16 rounded-2xl bg-primary-container/15 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">school</span>
        </div>
        <h3 class="text-xl font-bold text-white mb-3">التعليم</h3>
        <p class="text-white/60 text-sm leading-relaxed mb-6">
          منصات تعليمية تفاعلية تدعم التعلم عن بُعد والحضوري، مصممة لتعزيز التفاعل وقياس التقدم بدقة عالية.
        </p>
        <ul class="space-y-3 mb-8">
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            أنظمة إدارة التعلم
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            المنصات التفاعلية
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            إدارة الطلاب
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            التقييم الرقمي
          </li>
        </ul>
        <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-2 text-primary-container text-sm font-bold hover:gap-3 transition-all">
          اكتشف المزيد
          <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        </a>
      </div>

      <!-- Card 6: العقارات -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all group reveal card-hover">
        <div class="w-16 h-16 rounded-2xl bg-sand-gold/15 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-3xl text-sand-gold" aria-hidden="true">apartment</span>
        </div>
        <h3 class="text-xl font-bold text-white mb-3">العقارات</h3>
        <p class="text-white/60 text-sm leading-relaxed mb-6">
          حلول رقمية لإدارة العقارات والتسويق العقاري، تُسرّع دورة البيع وتُوفر رؤية تحليلية شاملة للسوق العقاري.
        </p>
        <ul class="space-y-3 mb-8">
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            منصات عرض العقارات
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            إدارة الإيجارات
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            CRM عقاري
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            التسويق الرقمي
          </li>
        </ul>
        <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-2 text-sand-gold text-sm font-bold hover:gap-3 transition-all">
          اكتشف المزيد
          <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        </a>
      </div>

      <!-- Card 7: المطاعم والكافيهات -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all group reveal card-hover">
        <div class="w-16 h-16 rounded-2xl bg-primary-container/15 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">restaurant</span>
        </div>
        <h3 class="text-xl font-bold text-white mb-3">المطاعم والكافيهات</h3>
        <p class="text-white/60 text-sm leading-relaxed mb-6">
          أنظمة طلب وإدارة ذكية تُعزز تجربة العميل وتُخفض التكاليف التشغيلية مع إدارة فعّالة للمطبخ والمخزون.
        </p>
        <ul class="space-y-3 mb-8">
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            أنظمة نقاط البيع
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            الطلب الإلكتروني
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            إدارة المخزون
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            برامج الولاء
          </li>
        </ul>
        <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-2 text-primary-container text-sm font-bold hover:gap-3 transition-all">
          اكتشف المزيد
          <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        </a>
      </div>

      <!-- Card 8: الخدمات اللوجستية -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all group reveal card-hover">
        <div class="w-16 h-16 rounded-2xl bg-sand-gold/15 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-3xl text-sand-gold" aria-hidden="true">local_shipping</span>
        </div>
        <h3 class="text-xl font-bold text-white mb-3">الخدمات اللوجستية</h3>
        <p class="text-white/60 text-sm leading-relaxed mb-6">
          منصات تتبع وإدارة سلاسل الإمداد بكفاءة عالية، مع أدوات تحليلية تُحسّن مسارات التوصيل وتُقلل التكاليف.
        </p>
        <ul class="space-y-3 mb-8">
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            تتبع الشحنات
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            إدارة المستودعات
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            تحسين المسارات
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-sand-gold text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            التقارير التشغيلية
          </li>
        </ul>
        <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-2 text-sand-gold text-sm font-bold hover:gap-3 transition-all">
          اكتشف المزيد
          <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        </a>
      </div>

      <!-- Card 9: الاستشارات -->
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all group reveal card-hover">
        <div class="w-16 h-16 rounded-2xl bg-primary-container/15 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">support_agent</span>
        </div>
        <h3 class="text-xl font-bold text-white mb-3">الاستشارات</h3>
        <p class="text-white/60 text-sm leading-relaxed mb-6">
          أدوات رقمية تُمكّن شركات الاستشارات من تقديم خدمات أفضل وإدارة أعمالها بكفاءة أعلى مع رؤية واضحة لكل مشروع.
        </p>
        <ul class="space-y-3 mb-8">
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            إدارة المشاريع
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            بوابات العملاء
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            التقارير المتقدمة
          </li>
          <li class="flex items-center gap-3 text-sm text-white/70">
            <span class="material-symbols-outlined text-primary-container text-base flex-shrink-0" aria-hidden="true">check_circle</span>
            أنظمة الفوترة
          </li>
        </ul>
        <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-2 text-primary-container text-sm font-bold hover:gap-3 transition-all">
          اكتشف المزيد
          <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        </a>
      </div>

    </div>
  </section>

  <!-- 3. Value Proposition Section -->
  <section class="py-32 bg-surface-container-low/50 section-glow">
    <div class="max-w-screen-2xl mx-auto px-4 md:px-6">
      <!-- Heading -->
      <div class="text-center mb-16">
        <h2 class="text-2xl sm:text-3xl md:text-5xl font-black text-white">لماذا حلول ركال لقطاعك؟</h2>
      </div>

      <!-- 3 glass-panel cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Card 1: تخصيص كامل -->
        <div class="glass-panel rounded-[2rem] p-10 text-center">
          <div class="w-16 h-16 rounded-2xl bg-primary-container/15 flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">tune</span>
          </div>
          <h3 class="text-xl font-bold text-white mb-4">تخصيص كامل</h3>
          <p class="text-white/60 text-sm leading-relaxed">
            كل حل مصمم خصيصاً لطبيعة قطاعك، لا قوالب جاهزة — بل بناء حقيقي يعكس احتياجاتك الفريدة.
          </p>
        </div>

        <!-- Card 2: أمان وامتثال -->
        <div class="glass-panel rounded-[2rem] p-10 text-center">
          <div class="w-16 h-16 rounded-2xl bg-sand-gold/15 flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-3xl text-sand-gold" aria-hidden="true">security</span>
          </div>
          <h3 class="text-xl font-bold text-white mb-4">أمان وامتثال</h3>
          <p class="text-white/60 text-sm leading-relaxed">
            متوافق مع المعايير السعودية والعالمية، مع بنية أمنية متعددة الطبقات تحمي بياناتك ومعاملاتك.
          </p>
        </div>

        <!-- Card 3: قابلية التوسع -->
        <div class="glass-panel rounded-[2rem] p-10 text-center">
          <div class="w-16 h-16 rounded-2xl bg-primary-container/15 flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">trending_up</span>
          </div>
          <h3 class="text-xl font-bold text-white mb-4">قابلية التوسع</h3>
          <p class="text-white/60 text-sm leading-relaxed">
            ينمو مع نمو أعمالك دون إعادة بناء — معمارية مرنة تستوعب التوسع والتطور مهما كان حجم طموحك.
          </p>
        </div>

      </div>
    </div>
  </section>

  <!-- 4. CTA Block -->
  <section class="py-32 px-4 md:px-6 section-glow">
    <div class="max-w-4xl mx-auto">
      <div class="relative overflow-hidden rounded-[2.5rem] circuit-bg p-6 md:p-12 lg:p-16 text-center border border-primary-container/20">
        <!-- Glow overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-container/10 via-transparent to-sand-gold/5 pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-36 h-36 md:w-72 md:h-72 bg-primary-container/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 md:w-64 md:h-64 bg-sand-gold/8 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="relative z-10">
          <div class="w-16 h-16 rounded-2xl bg-primary-container/15 flex items-center justify-center mx-auto mb-8">
            <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">contact_support</span>
          </div>

          <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white mb-6 leading-tight">
            لم تجد قطاعك؟ تواصل معنا
            <span class="block mt-1 text-primary-container">لحل مخصص</span>
          </h2>

          <p class="text-white/60 text-lg leading-relaxed mb-10 max-w-xl mx-auto">
            فريقنا جاهز لتحليل احتياجاتك وتصميم حل برمجي يُناسب طبيعة عملك بالضبط، مهما كان قطاعك.
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= url('/contact') ?>" class="tech-gradient text-on-primary-fixed px-8 py-4 rounded-xl font-bold text-lg hover:shadow-[0_0_25px_rgba(0,242,255,0.35)] transition-all duration-300 inline-flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-xl" aria-hidden="true">chat</span>
              تواصل مع فريقنا
            </a>
            <a href="<?= url('/contact') ?>" class="bg-white/5 border border-white/10 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/10 hover:border-white/20 transition-all duration-300 inline-flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-xl" aria-hidden="true">description</span>
              اطلب عرض سعر
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php require_once 'includes/footer.php'; ?>
