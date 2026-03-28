<?php
$pageTitle = 'ركال | حلول برمجية وطنية ذكية';
$activePage = 'index';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section id="hero" class="relative min-h-[70vh] md:min-h-[90vh] flex items-center overflow-hidden px-4 md:px-8 hero-mesh">
  <div class="absolute inset-0 circuit-bg opacity-30"></div>
  <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-container/10 blur-[120px] rounded-full z-0 animate-float-orb"></div>
  <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-sand-gold/10 blur-[120px] rounded-full z-0"></div>
  <div class="max-w-screen-2xl mx-auto w-full grid md:grid-cols-2 gap-12 items-center relative z-10">
    <div class="text-right space-y-8">
      <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-sand-gold/30 text-sand-gold text-sm font-bold badge-pulse reveal reveal-delay-1">
        <span class="w-2 h-2 rounded-full bg-sand-gold animate-pulse"></span>
        نصنع المستقبل الرقمي لرؤية المملكة
      </div>
      <h1 class="text-3xl sm:text-5xl md:text-7xl font-headline font-bold leading-tight text-white reveal reveal-delay-2">
        نبتكر حلولاً <span class="text-transparent bg-clip-text tech-gradient">تقنية ذكية</span> تدفع طموحك نحو ٢٠٣٠
      </h1>
      <p class="text-lg md:text-xl text-on-surface-variant max-w-2xl leading-relaxed reveal reveal-delay-3">
        نطوّر المواقع، الأنظمة، التطبيقات، وحلول الذكاء الاصطناعي بأيدي وطنية وخبرات عالمية تضمن لشركتك التميز في قلب العاصمة الرياض.
      </p>
      <div class="flex flex-wrap gap-4 pt-4 reveal reveal-delay-4">
        <a href="contact.php" class="cta-btn tech-gradient text-on-primary-fixed px-8 py-4 rounded-xl font-bold text-lg flex items-center gap-2 shadow-[0_10px_30px_rgba(0,242,255,0.2)]">
          ابدأ مشروعك
          <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
        </a>
        <a href="#services" class="px-8 py-4 rounded-xl font-bold text-lg border border-outline-variant hover:bg-white/5 transition-all text-white">
          تصفح خدماتنا
        </a>
      </div>
    </div>
    <div class="relative group hidden md:block">
      <div class="absolute inset-0 bg-sand-gold/5 rounded-full blur-3xl animate-pulse z-0"></div>
      <div class="relative glass-panel p-4 rounded-[2.5rem] border-white/5 shadow-2xl overflow-hidden">
        <div class="hero-visual w-full h-[300px] md:h-[550px] rounded-[2rem] opacity-80 group-hover:opacity-100 transition-all duration-700 relative overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-primary-container/20 via-surface-container/80 to-sand-gold/10"></div>
          <div class="absolute inset-0 circuit-bg opacity-80"></div>
          <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] md:w-[400px] md:h-[400px]">
            <div class="absolute inset-0 rounded-full border border-primary-container/20 animate-[spin_30s_linear_infinite]"></div>
            <div class="absolute inset-8 rounded-full border border-sand-gold/15 animate-[spin_20s_linear_infinite_reverse]"></div>
            <div class="absolute inset-16 rounded-full border border-primary-container/10 animate-[spin_25s_linear_infinite]"></div>
            <div class="absolute inset-0 flex items-center justify-center">
              <span class="material-symbols-outlined text-[120px] md:text-[160px] text-primary-container/30" style="font-variation-settings: 'FILL' 1, 'wght' 200;">rocket_launch</span>
            </div>
          </div>
          <div class="absolute bottom-8 right-8 flex gap-3">
            <div class="w-2 h-2 rounded-full bg-primary-container animate-pulse"></div>
            <div class="w-2 h-2 rounded-full bg-sand-gold animate-pulse" style="animation-delay:0.3s"></div>
            <div class="w-2 h-2 rounded-full bg-primary-container animate-pulse" style="animation-delay:0.6s"></div>
          </div>
        </div>
        <div class="absolute top-12 right-12 glass-panel p-6 rounded-2xl border-sand-gold/30 shadow-2xl animate-slow-bounce z-20">
          <span class="material-symbols-outlined text-sand-gold text-4xl" aria-hidden="true">auto_awesome</span>
        </div>
        <div class="absolute bottom-12 left-12 glass-panel p-6 rounded-2xl border-primary-container/30 shadow-2xl z-20">
          <div class="flex gap-4 items-center">
            <div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center">
              <span class="material-symbols-outlined text-primary-container" aria-hidden="true">data_exploration</span>
            </div>
            <div>
              <div class="text-white font-bold">نمو رقمي وطني</div>
              <div class="text-xs text-on-surface-variant">تحليلات دقيقة للسوق المحلي</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stats Bar -->
<section id="stats" class="py-24 px-4 md:px-8 bg-surface-container-low relative overflow-hidden section-glow">
  <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none">
    <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
      <path class="text-sand-gold" d="M0 100 L100 0 L100 100 Z" fill="currentColor"></path>
    </svg>
  </div>
  <div class="max-w-screen-2xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative z-10">
    <div class="text-center p-8 rounded-2xl bg-surface-container-highest/40 border border-white/5 hover:border-sand-gold/20 transition-all card-hover reveal reveal-delay-1">
      <span class="material-symbols-outlined text-4xl text-sand-gold mb-4 block" aria-hidden="true">rocket_launch</span>
      <div class="text-4xl font-headline font-bold text-white mb-2" data-count="250" data-prefix="+" data-arabic="true">+٢٥٠</div>
      <div class="text-on-surface-variant">مشاريع في أنحاء المملكة</div>
    </div>
    <div class="text-center p-8 rounded-2xl bg-surface-container-highest/40 border border-white/5 hover:border-sand-gold/20 transition-all card-hover reveal reveal-delay-2">
      <span class="material-symbols-outlined text-4xl text-primary-container mb-4 block" aria-hidden="true">domain</span>
      <div class="text-4xl font-headline font-bold text-white mb-2" data-count="15" data-prefix="+" data-arabic="true">+١٥</div>
      <div class="text-on-surface-variant">قطاعاً حيوياً</div>
    </div>
    <div class="text-center p-8 rounded-2xl bg-surface-container-highest/40 border border-white/5 hover:border-sand-gold/20 transition-all card-hover reveal reveal-delay-3">
      <span class="material-symbols-outlined text-4xl text-sand-gold mb-4 block" aria-hidden="true">verified</span>
      <div class="text-4xl font-headline font-bold text-white mb-2" data-count="99" data-suffix="٪" data-arabic="true">٪٩٩</div>
      <div class="text-on-surface-variant">نسبة رضا الشركاء</div>
    </div>
    <div class="text-center p-8 rounded-2xl bg-surface-container-highest/40 border border-white/5 hover:border-sand-gold/20 transition-all card-hover reveal reveal-delay-4">
      <span class="material-symbols-outlined text-4xl text-primary-container mb-4 block" aria-hidden="true">schedule</span>
      <div class="text-4xl font-headline font-bold text-white mb-2" data-count="10" data-prefix="+" data-arabic="true">+١٠</div>
      <div class="text-on-surface-variant">سنوات خبرة في السوق</div>
    </div>
  </div>
</section>

<!-- Services Overview -->
<section id="services" class="py-32 px-4 md:px-8">
  <div class="max-w-screen-2xl mx-auto">
    <div class="text-center mb-20 space-y-4 reveal">
      <h2 class="text-2xl sm:text-4xl md:text-5xl font-headline font-bold text-white">خدماتنا الرقمية المتكاملة</h2>
      <p class="text-on-surface-variant max-w-2xl mx-auto">نقدم حزمة من الخدمات التقنية التي تضمن تحولاً رقمياً آمناً وفق أعلى المعايير الوطنية.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="md:col-span-2 glass-panel card-hover reveal p-10 rounded-[2rem] hover:border-primary-container/40 transition-all duration-500 group overflow-hidden relative">
        <div class="relative z-10">
          <span class="material-symbols-outlined text-5xl text-primary-container mb-6 block" aria-hidden="true">web</span>
          <h3 class="text-2xl font-bold text-white mb-4">برمجة المواقع والمنصات الحكومية</h3>
          <p class="text-on-surface-variant mb-8 leading-relaxed">بناء منصات إلكترونية متقدمة متوافقة مع متطلبات هيئة الحكومة الرقمية وتجربة مستخدم متميزة.</p>
          <a href="services.php" class="text-primary-container flex items-center gap-2 group-hover:gap-4 transition-all font-bold">
            استكشف المزيد
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
          </a>
        </div>
        <div class="absolute -bottom-10 -left-10 opacity-10 group-hover:opacity-20 transition-opacity">
          <span class="material-symbols-outlined text-[12rem]" aria-hidden="true">code</span>
        </div>
      </div>
      <div class="glass-panel card-hover reveal p-8 rounded-[2rem] hover:border-sand-gold/40 transition-all duration-500 text-right">
        <span class="material-symbols-outlined text-4xl text-sand-gold mb-6 block" aria-hidden="true">smartphone</span>
        <h3 class="text-xl font-bold text-white mb-3">تطبيقات الجوال</h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">تطبيقات ذكية تعزز تواصلك مع جمهورك في المملكة بسلاسة وأمان.</p>
      </div>
      <div class="glass-panel card-hover reveal p-8 rounded-[2rem] hover:border-primary-container/40 transition-all duration-500 text-right">
        <span class="material-symbols-outlined text-4xl text-primary-container mb-6 block" aria-hidden="true">precision_manufacturing</span>
        <h3 class="text-xl font-bold text-white mb-3">الذكاء الاصطناعي</h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">دمج تقنيات الـ AI لرفع كفاءة المنشآت الوطنية وتحليل البيانات الضخمة.</p>
      </div>
      <div class="glass-panel card-hover reveal p-8 rounded-[2rem] hover:border-sand-gold/40 transition-all duration-500 text-right">
        <span class="material-symbols-outlined text-4xl text-sand-gold mb-6 block" aria-hidden="true">shopping_cart</span>
        <h3 class="text-xl font-bold text-white mb-3">المتاجر الإلكترونية</h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">حلول تجارة إلكترونية متكاملة مع بوابات دفع محلية وشركات شحن وطنية.</p>
      </div>
      <div class="md:col-span-2 glass-panel card-hover reveal p-10 rounded-[2rem] hover:border-primary-container/40 transition-all duration-500 group relative overflow-hidden">
        <div class="relative z-10">
          <span class="material-symbols-outlined text-5xl text-primary-container mb-6 block" aria-hidden="true">settings_suggest</span>
          <h3 class="text-2xl font-bold text-white mb-4">أنظمة إدارة الموارد (ERP)</h3>
          <p class="text-on-surface-variant mb-8 leading-relaxed">أنظمة مخصصة تتماشى مع القوانين والأنظمة المالية السعودية بدقة متناهية.</p>
          <a href="services.php" class="text-primary-container flex items-center gap-2 group-hover:gap-4 transition-all font-bold">
            استكشف المزيد
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
          </a>
        </div>
        <div class="absolute -bottom-10 -left-10 opacity-10 group-hover:opacity-20 transition-opacity">
          <span class="material-symbols-outlined text-[12rem]" aria-hidden="true">database</span>
        </div>
      </div>
      <div class="glass-panel card-hover reveal p-8 rounded-[2rem] hover:border-sand-gold/40 transition-all duration-500 text-right">
        <span class="material-symbols-outlined text-4xl text-sand-gold mb-6 block" aria-hidden="true">search_insights</span>
        <h3 class="text-xl font-bold text-white mb-3">التسويق الرقمي</h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">إدارة حملاتك الرقمية بذكاء لاستهداف الجمهور المحلي في كافة مناطق المملكة.</p>
      </div>
    </div>
  </div>
</section>

<!-- Industries / Sectors -->
<section id="industries" class="py-32 px-4 md:px-8 bg-surface-container-low/50">
  <div class="max-w-screen-2xl mx-auto">
    <div class="text-center mb-20 space-y-4 reveal">
      <h2 class="text-2xl sm:text-4xl md:text-5xl font-headline font-bold text-white">القطاعات التي نخدمها</h2>
      <p class="text-on-surface-variant max-w-2xl mx-auto">حلول رقمية مصممة لتلائم طبيعة كل قطاع في المملكة</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-primary-container/30 hover:translate-y-[-4px] transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-xl bg-primary-container/10 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">domain</span></div>
        <span class="text-sm font-bold text-white">الشركات</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-sand-gold/30 hover:translate-y-[-4px] transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-xl bg-sand-gold/10 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">account_balance</span></div>
        <span class="text-sm font-bold text-white">الجهات الحكومية</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-primary-container/30 hover:translate-y-[-4px] transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-xl bg-primary-container/10 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">shopping_cart</span></div>
        <span class="text-sm font-bold text-white">التجارة الإلكترونية</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-sand-gold/30 hover:translate-y-[-4px] transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-xl bg-sand-gold/10 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">local_hospital</span></div>
        <span class="text-sm font-bold text-white">العيادات والمراكز الطبية</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-primary-container/30 hover:translate-y-[-4px] transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-xl bg-primary-container/10 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">school</span></div>
        <span class="text-sm font-bold text-white">التعليم</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-sand-gold/30 hover:translate-y-[-4px] transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-xl bg-sand-gold/10 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">apartment</span></div>
        <span class="text-sm font-bold text-white">العقارات</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-primary-container/30 hover:translate-y-[-4px] transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-xl bg-primary-container/10 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">restaurant</span></div>
        <span class="text-sm font-bold text-white">المطاعم والكافيهات</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-sand-gold/30 hover:translate-y-[-4px] transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-xl bg-sand-gold/10 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">local_shipping</span></div>
        <span class="text-sm font-bold text-white">الخدمات اللوجستية</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-primary-container/30 hover:translate-y-[-4px] transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-xl bg-primary-container/10 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">corporate_fare</span></div>
        <span class="text-sm font-bold text-white">المؤسسات</span>
      </div>
      <div class="bg-surface-container rounded-2xl p-6 text-center border border-white/5 hover:border-sand-gold/30 hover:translate-y-[-4px] transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-xl bg-sand-gold/10 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">support_agent</span></div>
        <span class="text-sm font-bold text-white">الاستشارات</span>
      </div>
    </div>
  </div>
</section>

<!-- Process / Workflow -->
<section id="process" class="relative py-20 md:py-40 px-4 md:px-8 overflow-hidden">
  <!-- Atmospheric background -->
  <div class="absolute inset-0 bg-gradient-to-b from-surface via-surface-container-low/30 to-surface pointer-events-none"></div>
  <div class="absolute inset-0 circuit-bg opacity-20 pointer-events-none"></div>
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] md:w-[800px] md:h-[800px] bg-primary-container/3 rounded-full blur-[200px] pointer-events-none"></div>
  <div class="absolute top-20 right-20 w-64 h-64 bg-sand-gold/4 rounded-full blur-[120px] pointer-events-none animate-float-orb"></div>

  <div class="max-w-screen-xl mx-auto relative z-10">
    <!-- Header -->
    <div class="text-center mb-28 reveal">
      <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-primary-container/8 border border-primary-container/15 text-primary-container text-sm font-bold mb-8 backdrop-blur-sm">
        <span class="material-symbols-outlined text-base" aria-hidden="true">route</span>
        المنهجية
      </div>
      <h2 class="text-4xl md:text-6xl font-headline font-bold text-white mb-6 leading-tight">
        كيف ننفذ <span class="text-transparent bg-clip-text tech-gradient">مشروعك؟</span>
      </h2>
      <p class="text-on-surface-variant text-lg max-w-xl mx-auto leading-relaxed">ست مراحل مدروسة تحوّل فكرتك إلى منتج رقمي متكامل</p>
    </div>

    <!-- ===== Desktop: Staggered Bento Grid ===== -->
    <div class="hidden lg:grid grid-cols-3 gap-6 max-w-5xl mx-auto">

      <!-- Step 1 -->
      <div class="group relative reveal reveal-delay-1">
        <div class="absolute -inset-[1px] rounded-[2rem] bg-gradient-to-br from-primary-container/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
        <div class="relative glass-panel rounded-[2rem] p-8 h-full border-white/5 group-hover:border-primary-container/20 transition-all duration-500">
          <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 rounded-2xl bg-primary-container/10 border border-primary-container/20 flex items-center justify-center group-hover:bg-primary-container/15 group-hover:scale-110 transition-all duration-500">
              <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">search</span>
            </div>
            <span class="text-5xl font-black text-white/[0.04] group-hover:text-primary-container/10 transition-colors duration-500 select-none">٠١</span>
          </div>
          <h4 class="text-lg font-bold text-white mb-2 group-hover:text-primary-fixed transition-colors">دراسة الاحتياج</h4>
          <p class="text-sm text-on-surface-variant leading-relaxed">نبدأ بفهم عميق لمتطلبات المشروع وأهدافه التجارية والتقنية ضمن سياق السوق السعودي.</p>
          <!-- Connector arrow -->
          <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-surface-container border border-primary-container/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20 hidden lg:flex">
            <span class="material-symbols-outlined text-primary-container text-sm" aria-hidden="true">arrow_back</span>
          </div>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="group relative reveal reveal-delay-2">
        <div class="absolute -inset-[1px] rounded-[2rem] bg-gradient-to-br from-primary-container/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
        <div class="relative glass-panel rounded-[2rem] p-8 h-full border-white/5 group-hover:border-primary-container/20 transition-all duration-500">
          <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 rounded-2xl bg-primary-container/10 border border-primary-container/20 flex items-center justify-center group-hover:bg-primary-container/15 group-hover:scale-110 transition-all duration-500">
              <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">analytics</span>
            </div>
            <span class="text-5xl font-black text-white/[0.04] group-hover:text-primary-container/10 transition-colors duration-500 select-none">٠٢</span>
          </div>
          <h4 class="text-lg font-bold text-white mb-2 group-hover:text-primary-fixed transition-colors">التحليل والتخطيط</h4>
          <p class="text-sm text-on-surface-variant leading-relaxed">نضع خارطة الطريق التقنية وبنية النظام المقترحة مع تحديد الأولويات والمراحل.</p>
          <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-surface-container border border-primary-container/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20 hidden lg:flex">
            <span class="material-symbols-outlined text-primary-container text-sm" aria-hidden="true">arrow_back</span>
          </div>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="group relative reveal reveal-delay-3">
        <div class="absolute -inset-[1px] rounded-[2rem] bg-gradient-to-br from-primary-container/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
        <div class="relative glass-panel rounded-[2rem] p-8 h-full border-white/5 group-hover:border-primary-container/20 transition-all duration-500">
          <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 rounded-2xl bg-primary-container/10 border border-primary-container/20 flex items-center justify-center group-hover:bg-primary-container/15 group-hover:scale-110 transition-all duration-500">
              <span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">design_services</span>
            </div>
            <span class="text-5xl font-black text-white/[0.04] group-hover:text-primary-container/10 transition-colors duration-500 select-none">٠٣</span>
          </div>
          <h4 class="text-lg font-bold text-white mb-2 group-hover:text-primary-fixed transition-colors">تصميم تجربة المستخدم</h4>
          <p class="text-sm text-on-surface-variant leading-relaxed">نصمم واجهات جذابة بصرياً وبديهية في الاستخدام مع مراعاة الهوية البصرية لمنشأتك.</p>
        </div>
      </div>

      <!-- Animated connecting line between rows -->
      <div class="col-span-3 flex items-center justify-center py-4 reveal">
        <div class="w-full max-w-3xl h-[1px] bg-gradient-to-l from-primary-container/30 via-sand-gold/20 to-sand-gold/30 relative">
          <div class="absolute right-0 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-primary-container/50 shadow-[0_0_10px_rgba(0,242,255,0.4)]"></div>
          <div class="absolute left-0 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-sand-gold/50 shadow-[0_0_10px_rgba(212,175,55,0.4)]"></div>
          <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <div class="w-10 h-10 rounded-xl bg-surface-container border border-white/10 flex items-center justify-center shadow-lg">
              <span class="material-symbols-outlined text-sand-gold text-lg" aria-hidden="true">keyboard_double_arrow_down</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="group relative reveal reveal-delay-1">
        <div class="absolute -inset-[1px] rounded-[2rem] bg-gradient-to-br from-sand-gold/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
        <div class="relative glass-panel rounded-[2rem] p-8 h-full border-white/5 group-hover:border-sand-gold/20 transition-all duration-500">
          <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 rounded-2xl bg-sand-gold/10 border border-sand-gold/20 flex items-center justify-center group-hover:bg-sand-gold/15 group-hover:scale-110 transition-all duration-500">
              <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">code</span>
            </div>
            <span class="text-5xl font-black text-white/[0.04] group-hover:text-sand-gold/10 transition-colors duration-500 select-none">٠٤</span>
          </div>
          <h4 class="text-lg font-bold text-white mb-2 group-hover:text-sand-gold transition-colors">التطوير والتنفيذ</h4>
          <p class="text-sm text-on-surface-variant leading-relaxed">نبني المنتج بأحدث التقنيات العالمية مع مراجعات دورية وتقارير تقدم مستمرة.</p>
          <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-surface-container border border-sand-gold/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20 hidden lg:flex">
            <span class="material-symbols-outlined text-sand-gold text-sm" aria-hidden="true">arrow_back</span>
          </div>
        </div>
      </div>

      <!-- Step 5 -->
      <div class="group relative reveal reveal-delay-2">
        <div class="absolute -inset-[1px] rounded-[2rem] bg-gradient-to-br from-sand-gold/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
        <div class="relative glass-panel rounded-[2rem] p-8 h-full border-white/5 group-hover:border-sand-gold/20 transition-all duration-500">
          <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 rounded-2xl bg-sand-gold/10 border border-sand-gold/20 flex items-center justify-center group-hover:bg-sand-gold/15 group-hover:scale-110 transition-all duration-500">
              <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">verified_user</span>
            </div>
            <span class="text-5xl font-black text-white/[0.04] group-hover:text-sand-gold/10 transition-colors duration-500 select-none">٠٥</span>
          </div>
          <h4 class="text-lg font-bold text-white mb-2 group-hover:text-sand-gold transition-colors">الاختبار وضمان الجودة</h4>
          <p class="text-sm text-on-surface-variant leading-relaxed">فحص شامل متعدد المراحل يضمن خلو المنتج من الأخطاء وجاهزيته للإطلاق.</p>
          <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-surface-container border border-sand-gold/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20 hidden lg:flex">
            <span class="material-symbols-outlined text-sand-gold text-sm" aria-hidden="true">arrow_back</span>
          </div>
        </div>
      </div>

      <!-- Step 6 — Highlighted final step -->
      <div class="group relative reveal reveal-delay-3">
        <div class="absolute -inset-[1px] rounded-[2rem] bg-gradient-to-br from-sand-gold/30 via-primary-container/10 to-transparent opacity-60 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
        <div class="relative rounded-[2rem] p-8 h-full border border-sand-gold/15 group-hover:border-sand-gold/30 transition-all duration-500 bg-gradient-to-br from-sand-gold/[0.04] via-surface-container/80 to-surface-container/60 backdrop-blur-xl shadow-[0_8px_32px_rgba(212,175,55,0.06)]">
          <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 rounded-2xl bg-sand-gold/15 border border-sand-gold/25 flex items-center justify-center group-hover:scale-110 transition-all duration-500 shadow-[0_0_20px_rgba(212,175,55,0.1)]">
              <span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">rocket_launch</span>
            </div>
            <span class="text-5xl font-black text-sand-gold/[0.08] group-hover:text-sand-gold/15 transition-colors duration-500 select-none">٠٦</span>
          </div>
          <h4 class="text-lg font-bold text-white mb-2 group-hover:text-sand-gold transition-colors">الإطلاق والدعم المستمر</h4>
          <p class="text-sm text-on-surface-variant leading-relaxed">نطلق المنتج بسلاسة مع فريق دعم متخصص يضمن استمرارية الأداء بعد التسليم.</p>
          <!-- Launch glow -->
          <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-sand-gold/5 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        </div>
      </div>

    </div>

    <!-- ===== Mobile: Vertical Timeline Cards ===== -->
    <div class="lg:hidden max-w-md mx-auto relative">
      <!-- Vertical line -->
      <div class="absolute right-6 top-0 bottom-0 w-[2px] bg-gradient-to-b from-primary-container/30 via-primary-container/15 to-sand-gold/30 pointer-events-none" aria-hidden="true"></div>

      <div class="space-y-6">
        <!-- Mobile Step 1 -->
        <div class="relative flex gap-5 items-start reveal reveal-delay-1">
          <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-primary-container/10 border border-primary-container/25 flex items-center justify-center z-10 shadow-[0_0_15px_rgba(0,242,255,0.08)]">
            <span class="material-symbols-outlined text-primary-container text-xl" aria-hidden="true">search</span>
          </div>
          <div class="glass-panel rounded-2xl p-5 flex-1 border-white/5">
            <div class="flex items-center justify-between mb-2">
              <h4 class="font-bold text-white">دراسة الاحتياج</h4>
              <span class="text-xs text-primary-container/40 font-black">٠١</span>
            </div>
            <p class="text-sm text-on-surface-variant leading-relaxed">فهم عميق لمتطلبات المشروع وأهدافه التجارية والتقنية.</p>
          </div>
        </div>

        <!-- Mobile Step 2 -->
        <div class="relative flex gap-5 items-start reveal reveal-delay-2">
          <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-primary-container/10 border border-primary-container/25 flex items-center justify-center z-10 shadow-[0_0_15px_rgba(0,242,255,0.08)]">
            <span class="material-symbols-outlined text-primary-container text-xl" aria-hidden="true">analytics</span>
          </div>
          <div class="glass-panel rounded-2xl p-5 flex-1 border-white/5">
            <div class="flex items-center justify-between mb-2">
              <h4 class="font-bold text-white">التحليل والتخطيط</h4>
              <span class="text-xs text-primary-container/40 font-black">٠٢</span>
            </div>
            <p class="text-sm text-on-surface-variant leading-relaxed">وضع خارطة الطريق التقنية وبنية النظام.</p>
          </div>
        </div>

        <!-- Mobile Step 3 -->
        <div class="relative flex gap-5 items-start reveal reveal-delay-3">
          <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-primary-container/10 border border-primary-container/25 flex items-center justify-center z-10 shadow-[0_0_15px_rgba(0,242,255,0.08)]">
            <span class="material-symbols-outlined text-primary-container text-xl" aria-hidden="true">design_services</span>
          </div>
          <div class="glass-panel rounded-2xl p-5 flex-1 border-white/5">
            <div class="flex items-center justify-between mb-2">
              <h4 class="font-bold text-white">تصميم تجربة المستخدم</h4>
              <span class="text-xs text-primary-container/40 font-black">٠٣</span>
            </div>
            <p class="text-sm text-on-surface-variant leading-relaxed">واجهات جذابة وبديهية تعكس هوية منشأتك.</p>
          </div>
        </div>

        <!-- Mobile Step 4 -->
        <div class="relative flex gap-5 items-start reveal reveal-delay-4">
          <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-sand-gold/10 border border-sand-gold/25 flex items-center justify-center z-10 shadow-[0_0_15px_rgba(212,175,55,0.08)]">
            <span class="material-symbols-outlined text-sand-gold text-xl" aria-hidden="true">code</span>
          </div>
          <div class="glass-panel rounded-2xl p-5 flex-1 border-white/5">
            <div class="flex items-center justify-between mb-2">
              <h4 class="font-bold text-white">التطوير والتنفيذ</h4>
              <span class="text-xs text-sand-gold/40 font-black">٠٤</span>
            </div>
            <p class="text-sm text-on-surface-variant leading-relaxed">بناء المنتج بأحدث التقنيات مع مراجعات دورية.</p>
          </div>
        </div>

        <!-- Mobile Step 5 -->
        <div class="relative flex gap-5 items-start reveal reveal-delay-5">
          <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-sand-gold/10 border border-sand-gold/25 flex items-center justify-center z-10 shadow-[0_0_15px_rgba(212,175,55,0.08)]">
            <span class="material-symbols-outlined text-sand-gold text-xl" aria-hidden="true">verified_user</span>
          </div>
          <div class="glass-panel rounded-2xl p-5 flex-1 border-white/5">
            <div class="flex items-center justify-between mb-2">
              <h4 class="font-bold text-white">الاختبار وضمان الجودة</h4>
              <span class="text-xs text-sand-gold/40 font-black">٠٥</span>
            </div>
            <p class="text-sm text-on-surface-variant leading-relaxed">فحص شامل يضمن خلو المنتج من الأخطاء.</p>
          </div>
        </div>

        <!-- Mobile Step 6 -->
        <div class="relative flex gap-5 items-start reveal reveal-delay-6">
          <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-sand-gold/15 border border-sand-gold/30 flex items-center justify-center z-10 shadow-[0_0_20px_rgba(212,175,55,0.12)]">
            <span class="material-symbols-outlined text-sand-gold text-xl" aria-hidden="true">rocket_launch</span>
          </div>
          <div class="rounded-2xl p-5 flex-1 border border-sand-gold/15 bg-gradient-to-br from-sand-gold/[0.04] via-surface-container/80 to-surface-container/60 backdrop-blur-xl">
            <div class="flex items-center justify-between mb-2">
              <h4 class="font-bold text-white">الإطلاق والدعم المستمر</h4>
              <span class="text-xs text-sand-gold/40 font-black">٠٦</span>
            </div>
            <p class="text-sm text-on-surface-variant leading-relaxed">إطلاق سلس مع فريق دعم يضمن استمرارية الأداء.</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- Saudi Market Value -->
<section id="value" class="py-32 px-4 md:px-8 bg-surface-container-low/50 relative">
  <div class="absolute inset-0 sadu-pattern opacity-10 pointer-events-none"></div>
  <div class="max-w-screen-2xl mx-auto flex flex-col lg:flex-row gap-16 items-center relative z-10">
    <div class="w-full lg:w-1/2 relative order-2 lg:order-1 reveal-left">
      <div class="absolute -inset-4 border border-sand-gold/20 rounded-[3rem] -z-10 translate-x-4 translate-y-4"></div>
      <div class="rounded-[3rem] shadow-2xl border-4 border-white/5 w-full h-[350px] md:h-[600px] relative overflow-hidden bg-surface-container">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-container/15 via-transparent to-sand-gold/10"></div>
        <div class="absolute inset-0 circuit-bg opacity-60"></div>
        <div class="absolute inset-0 flex items-center justify-center">
          <div class="text-center space-y-6">
            <div class="w-24 h-24 mx-auto rounded-2xl bg-primary-container/10 border border-primary-container/20 flex items-center justify-center">
              <span class="material-symbols-outlined text-5xl text-primary-container" style="font-variation-settings: 'FILL' 1, 'wght' 300;">apartment</span>
            </div>
            <div>
              <div class="text-white/40 text-sm font-bold tracking-widest uppercase">Riyadh Tech Hub</div>
              <div class="text-white/20 text-xs mt-1">بيئة عمل تقنية حديثة</div>
            </div>
          </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-surface-container to-transparent"></div>
      </div>
    </div>
    <div class="w-full lg:w-1/2 space-y-10 text-right order-1 lg:order-2 reveal-right">
      <div class="space-y-4">
        <h2 class="text-4xl font-headline font-bold text-white">القيمة التي نضيفها لأعمالك في المملكة</h2>
        <div class="h-1 w-24 bg-sand-gold rounded-full"></div>
      </div>
      <div class="space-y-0">
        <div class="flex items-start gap-4 py-6 border-b border-white/5 hover:translate-x-[-4px] transition-transform">
          <div class="w-12 h-12 rounded-xl bg-primary-container/10 flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-primary-container" aria-hidden="true">rocket_launch</span></div>
          <div><h4 class="text-xl font-bold text-white mb-1">تسريع التحول الرقمي</h4><p class="text-sm text-on-surface-variant">نختصر مراحل التحول بحلول جاهزة ومُخصصة لطبيعة عملك</p></div>
        </div>
        <div class="flex items-start gap-4 py-6 border-b border-white/5 hover:translate-x-[-4px] transition-transform">
          <div class="w-12 h-12 rounded-xl bg-sand-gold/10 flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-sand-gold" aria-hidden="true">settings</span></div>
          <div><h4 class="text-xl font-bold text-white mb-1">تحسين كفاءة التشغيل</h4><p class="text-sm text-on-surface-variant">أتمتة العمليات وتقليل التكاليف التشغيلية بحلول ذكية</p></div>
        </div>
        <div class="flex items-start gap-4 py-6 border-b border-white/5 hover:translate-x-[-4px] transition-transform">
          <div class="w-12 h-12 rounded-xl bg-primary-container/10 flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-primary-container" aria-hidden="true">star</span></div>
          <div><h4 class="text-xl font-bold text-white mb-1">رفع جودة تجربة العميل</h4><p class="text-sm text-on-surface-variant">واجهات وتطبيقات تُبهر عملاءك وتزيد ولاءهم لعلامتك</p></div>
        </div>
        <div class="flex items-start gap-4 py-6 border-b border-white/5 hover:translate-x-[-4px] transition-transform">
          <div class="w-12 h-12 rounded-xl bg-sand-gold/10 flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-sand-gold" aria-hidden="true">trending_up</span></div>
          <div><h4 class="text-xl font-bold text-white mb-1">بناء أنظمة قابلة للنمو</h4><p class="text-sm text-on-surface-variant">بنية تحتية مرنة تنمو مع توسع أعمالك دون إعادة بناء</p></div>
        </div>
        <div class="flex items-start gap-4 py-6 hover:translate-x-[-4px] transition-transform">
          <div class="w-12 h-12 rounded-xl bg-primary-container/10 flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-primary-container" aria-hidden="true">domain</span></div>
          <div><h4 class="text-xl font-bold text-white mb-1">دعم الهوية الرقمية للمنشأة</h4><p class="text-sm text-on-surface-variant">حضور رقمي احترافي يعكس مكانتك في السوق السعودي</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Trust / Why Trust Us -->
<section id="trust" class="py-32 px-4 md:px-8">
  <div class="max-w-screen-2xl mx-auto">
    <div class="text-center mb-20 space-y-4 reveal">
      <h2 class="text-2xl sm:text-4xl md:text-5xl font-headline font-bold text-white">لماذا يثق بنا عملاؤنا؟</h2>
      <p class="text-on-surface-variant max-w-2xl mx-auto">التزامات حقيقية نقدمها لكل شريك نعمل معه</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-2xl bg-primary-container/10 flex items-center justify-center mb-6"><span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">handshake</span></div>
        <h3 class="text-lg font-bold text-white mb-3">شفافية كاملة في كل مرحلة</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">نشارك العميل في كل قرار تقني ونوفر تقارير تقدم دورية واضحة تضمن الاطلاع الكامل.</p>
      </div>
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-2xl bg-primary-container/10 flex items-center justify-center mb-6"><span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">lock</span></div>
        <h3 class="text-lg font-bold text-white mb-3">سرية تامة لبياناتك ومشروعك</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">نلتزم باتفاقيات عدم إفشاء صارمة وأعلى معايير حماية المعلومات والبيانات.</p>
      </div>
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-primary-container/20 transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-2xl bg-primary-container/10 flex items-center justify-center mb-6"><span class="material-symbols-outlined text-primary-container text-2xl" aria-hidden="true">forum</span></div>
        <h3 class="text-lg font-bold text-white mb-3">تواصل مستمر مع فريق العمل</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">مدير مشروع مخصص وقنوات تواصل مفتوحة طوال فترة التنفيذ لضمان سلاسة العمل.</p>
      </div>
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-sand-gold/20 transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-2xl bg-sand-gold/10 flex items-center justify-center mb-6"><span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">bolt</span></div>
        <h3 class="text-lg font-bold text-white mb-3">التزام بالمواعيد المتفق عليها</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">نعمل بجداول زمنية واقعية ونلتزم بتسليم كل مرحلة في موعدها المحدد.</p>
      </div>
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-sand-gold/20 transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-2xl bg-sand-gold/10 flex items-center justify-center mb-6"><span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">verified_user</span></div>
        <h3 class="text-lg font-bold text-white mb-3">ضمان جودة شامل قبل التسليم</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">اختبارات دقيقة متعددة المراحل تضمن خلو المنتج من الأخطاء التقنية.</p>
      </div>
      <div class="bg-surface-container rounded-[2rem] p-8 border border-white/5 hover:border-sand-gold/20 transition-all card-hover reveal">
        <div class="w-14 h-14 rounded-2xl bg-sand-gold/10 flex items-center justify-center mb-6"><span class="material-symbols-outlined text-sand-gold text-2xl" aria-hidden="true">support_agent</span></div>
        <h3 class="text-lg font-bold text-white mb-3">دعم فني بعد الإطلاق</h3>
        <p class="text-sm text-on-surface-variant leading-relaxed">فريق دعم متخصص لضمان استمرارية عمل منتجك بكفاءة تامة بعد التسليم.</p>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="py-32 px-4 md:px-8 bg-surface-container-low">
  <div class="max-w-3xl mx-auto">
    <div class="text-center mb-20 space-y-4 reveal">
      <h2 class="text-2xl sm:text-4xl md:text-5xl font-headline font-bold text-white">الأسئلة الشائعة</h2>
      <p class="text-on-surface-variant">إجابات واضحة على أكثر الاستفسارات التي تهم عملاءنا</p>
    </div>
    <div class="space-y-0">
      <div class="faq-item border-b border-white/5">
        <button id="faq-btn-1" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-1">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">هل تقدمون حلولاً مخصصة حسب النشاط؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-1" class="faq-answer" role="region" aria-labelledby="faq-btn-1">
          <p class="pb-6 text-on-surface-variant leading-relaxed">نعم، كل مشروع يبدأ بدراسة تفصيلية لطبيعة النشاط والجمهور المستهدف. نصمم حلولاً مخصصة بالكامل تراعي خصوصية كل قطاع ومتطلباته التقنية والتشغيلية في السوق السعودي.</p>
        </div>
      </div>
      <div class="faq-item border-b border-white/5">
        <button id="faq-btn-2" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-2">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">هل يمكن تطوير نظام داخلي خاص بالشركة؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-2" class="faq-answer" role="region" aria-labelledby="faq-btn-2">
          <p class="pb-6 text-on-surface-variant leading-relaxed">بالتأكيد. نصمم ونطور أنظمة داخلية متكاملة مبنية من الصفر حسب متطلبات شركتك وطبيعة عملياتها، سواء كانت أنظمة إدارة موارد أو أنظمة تشغيلية متخصصة.</p>
        </div>
      </div>
      <div class="faq-item border-b border-white/5">
        <button id="faq-btn-3" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-3">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">هل توفرون دعمًا بعد الإطلاق؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-3" class="faq-answer" role="region" aria-labelledby="faq-btn-3">
          <p class="pb-6 text-on-surface-variant leading-relaxed">نقدم باقات دعم فني وصيانة دورية تضمن استمرارية عمل المنتج بكفاءة عالية. يشمل ذلك مراقبة الأداء، تحديثات الأمان، وإصلاح أي مشكلات فورياً.</p>
        </div>
      </div>
      <div class="faq-item border-b border-white/5">
        <button id="faq-btn-4" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-4">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">هل يمكن ربط النظام مع خدمات أخرى؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-4" class="faq-answer" role="region" aria-labelledby="faq-btn-4">
          <p class="pb-6 text-on-surface-variant leading-relaxed">نبني أنظمة مفتوحة قابلة للتكامل مع بوابات الدفع المحلية، شركات الشحن، الأنظمة الحكومية، وأي خدمات خارجية عبر واجهات برمجة التطبيقات (APIs).</p>
        </div>
      </div>
      <div class="faq-item border-b border-white/5">
        <button id="faq-btn-5" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-5">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">هل تقدمون تطبيقات iOS وAndroid؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-5" class="faq-answer" role="region" aria-labelledby="faq-btn-5">
          <p class="pb-6 text-on-surface-variant leading-relaxed">نطور تطبيقات أصلية ومتعددة المنصات بأحدث التقنيات، مع مراعاة تجربة المستخدم العربي وتوافق كامل مع متطلبات متاجر التطبيقات.</p>
        </div>
      </div>
      <div class="faq-item">
        <button id="faq-btn-6" class="faq-btn w-full flex justify-between items-center py-6 text-right cursor-pointer group" aria-expanded="false" aria-controls="faq-answer-6">
          <span class="text-lg font-bold text-white group-hover:text-primary-container transition-colors">كيف تبدأون تنفيذ المشروع؟</span>
          <span class="material-symbols-outlined faq-icon text-on-surface-variant flex-shrink-0 mr-4" aria-hidden="true">expand_more</span>
        </button>
        <div id="faq-answer-6" class="faq-answer" role="region" aria-labelledby="faq-btn-6">
          <p class="pb-6 text-on-surface-variant leading-relaxed">نبدأ بجلسة استشارية مجانية لفهم احتياجاتك بالتفصيل، ثم نقدم عرضاً تفصيلياً يتضمن الحل المقترح والجدول الزمني والتكلفة، وبعد الموافقة نبدأ التنفيذ فوراً.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Block -->
<section id="cta" class="px-4 md:px-8 py-16 mb-16">
  <div class="max-w-7xl mx-auto">
    <div class="relative rounded-[3rem] bg-gradient-to-br from-surface-container-high to-surface-dim p-12 md:p-24 overflow-hidden border border-white/5 shadow-2xl reveal-scale">
      <div class="absolute inset-0 circuit-bg opacity-20 pointer-events-none"></div>
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-container/5 blur-[120px] rounded-full"></div>
      <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-sand-gold/5 blur-[120px] rounded-full"></div>
      <div class="relative z-10 text-center max-w-3xl mx-auto">
        <h2 class="text-2xl sm:text-4xl md:text-5xl font-headline font-bold mb-8 leading-tight text-white">جاهز لبناء حل رقمي احترافي يخدم أعمالك في المملكة؟</h2>
        <p class="text-on-surface-variant text-lg mb-12">فريقنا التقني مستعد لتحويل تحدياتك إلى فرص نمو حقيقية. ابدأ اليوم.</p>
        <div class="flex flex-col md:flex-row justify-center gap-6">
          <a href="contact.php" class="tech-gradient text-on-primary-fixed px-12 py-5 rounded-2xl font-bold text-lg hover:shadow-[0_0_50px_rgba(0,242,255,0.4)] transition-all active:scale-95">تحدث معنا الآن</a>
          <a href="contact.php" class="bg-white/5 border border-white/10 text-white px-12 py-5 rounded-2xl font-bold text-lg hover:bg-white/10 transition-all">اطلب عرض سعر</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
