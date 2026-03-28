<?php
$pageTitle = 'ركال | تواصل معنا';
$activePage = 'contact';
require_once 'includes/header.php';
?>

  <!-- 1. Hero Section -->
  <section id="contact-hero" class="relative py-24 circuit-bg overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-surface/60 via-transparent to-surface/80 pointer-events-none"></div>
    <div class="relative z-10 max-w-screen-2xl mx-auto px-4 md:px-8 text-center">
      <div class="reveal inline-flex items-center gap-2 bg-primary-container/10 border border-primary-container/20 rounded-full px-5 py-2 text-sm text-primary-container font-medium mb-8">
        <span class="material-symbols-outlined text-base" aria-hidden="true">contact_support</span>
        <span>تواصل مع فريق ركال</span>
      </div>
      <h1 class="reveal text-2xl sm:text-4xl md:text-6xl font-black leading-tight mb-6">
        لنصنع
        <span class="tech-gradient bg-clip-text text-transparent"> مستقبل أعمالك</span>
        الرقمي معاً
      </h1>
      <p class="reveal text-lg md:text-xl text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
        فريقنا من الخبراء السعوديين جاهز لدعم رحلتك نحو التحول الرقمي، بما يتوافق مع متطلبات رؤية المملكة ٢٠٣٠ وأعلى معايير الجودة التقنية.
      </p>
    </div>
  </section>

  <!-- 2. Contact Grid -->
  <section class="py-16 max-w-screen-2xl mx-auto px-4 md:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

      <!-- Left Column: Contact Form (lg:col-span-7) -->
      <div class="lg:col-span-7">
        <div class="reveal-scale glass-panel p-8 md:p-12 rounded-[2rem]">
          <div class="flex items-center gap-3 mb-8">
            <span class="material-symbols-outlined text-3xl text-primary-container" aria-hidden="true">mail</span>
            <h2 class="text-2xl md:text-3xl font-bold text-on-surface">أرسل استفسارك</h2>
          </div>

          <form onsubmit="return false;" class="flex flex-col gap-6" novalidate>
            <!-- Row 1: Full Name + Work Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col gap-2">
                <label for="full-name" class="text-sm font-medium text-on-surface-variant">الاسم الكامل</label>
                <input
                  id="full-name"
                  type="text"
                  name="full_name"
                  placeholder="أدخل اسمك الكامل"
                  class="bg-surface-container-highest border-none rounded-xl p-4 text-on-surface focus:ring-2 focus:ring-primary-container/20 outline-none placeholder:text-on-surface-variant/40 transition-all"
                />
              </div>
              <div class="flex flex-col gap-2">
                <label for="work-email" class="text-sm font-medium text-on-surface-variant">البريد الإلكتروني للعمل</label>
                <input
                  id="work-email"
                  type="email"
                  name="work_email"
                  dir="ltr"
                  placeholder="name@company.com"
                  class="bg-surface-container-highest border-none rounded-xl p-4 text-on-surface focus:ring-2 focus:ring-primary-container/20 outline-none placeholder:text-on-surface-variant/40 transition-all"
                />
              </div>
            </div>

            <!-- Row 2: Phone + Service Type -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex flex-col gap-2">
                <label for="phone" class="text-sm font-medium text-on-surface-variant">رقم الجوال</label>
                <input
                  id="phone"
                  type="tel"
                  name="phone"
                  dir="ltr"
                  placeholder="+966"
                  class="bg-surface-container-highest border-none rounded-xl p-4 text-on-surface focus:ring-2 focus:ring-primary-container/20 outline-none placeholder:text-on-surface-variant/40 transition-all"
                />
              </div>
              <div class="flex flex-col gap-2">
                <label for="service-type" class="text-sm font-medium text-on-surface-variant">نوع الخدمة</label>
                <select
                  id="service-type"
                  name="service_type"
                  class="bg-surface-container-highest border-none rounded-xl p-4 text-on-surface focus:ring-2 focus:ring-primary-container/20 outline-none transition-all appearance-none"
                >
                  <option value="" disabled selected>اختر نوع الخدمة</option>
                  <option value="digital-transformation">حلول التحول الرقمي</option>
                  <option value="custom-software">تطوير البرمجيات المخصصة</option>
                  <option value="cybersecurity">الأمن السيبراني</option>
                  <option value="consulting">الاستشارات التقنية</option>
                </select>
              </div>
            </div>

            <!-- Full Width: Project Details -->
            <div class="flex flex-col gap-2">
              <label for="project-details" class="text-sm font-medium text-on-surface-variant">تفاصيل المشروع</label>
              <textarea
                id="project-details"
                name="project_details"
                rows="4"
                placeholder="صف مشروعك واحتياجاتك التقنية بإيجاز..."
                class="bg-surface-container-highest border-none rounded-xl p-4 text-on-surface focus:ring-2 focus:ring-primary-container/20 outline-none placeholder:text-on-surface-variant/40 transition-all resize-none"
              ></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center md:justify-start">
              <button
                type="submit"
                class="tech-gradient text-on-primary-fixed font-bold px-10 py-4 rounded-xl flex items-center gap-3 w-full md:w-auto justify-center hover:shadow-[0_0_20px_rgba(0,242,255,0.35)] transition-all duration-300"
              >
                <span class="material-symbols-outlined" aria-hidden="true">send</span>
                إرسال الاستفسار
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Right Column: Info Sidebar (lg:col-span-5) -->
      <div class="lg:col-span-5 flex flex-col gap-6">

        <!-- Location Card -->
        <div class="card-hover glass-panel p-6 rounded-xl flex gap-4 items-start">
          <div class="w-12 h-12 rounded-xl bg-primary-container/10 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-2xl text-primary-container" aria-hidden="true">location_on</span>
          </div>
          <div>
            <h3 class="font-bold text-on-surface text-lg mb-1">المقر الرئيسي</h3>
            <p class="text-on-surface-variant text-sm leading-relaxed">طريق الملك فهد، حي المروج، الرياض</p>
          </div>
        </div>

        <!-- Two Small Cards: Phone + Email -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="card-hover glass-panel p-5 rounded-xl flex flex-col gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-container/10 flex items-center justify-center">
              <span class="material-symbols-outlined text-xl text-primary-container" aria-hidden="true">call</span>
            </div>
            <div>
              <p class="text-xs font-medium text-on-surface-variant mb-1">اتصل بنا</p>
              <p class="text-sm font-bold text-on-surface" dir="ltr">+966 11 000 0000</p>
            </div>
          </div>
          <div class="card-hover glass-panel p-5 rounded-xl flex flex-col gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-container/10 flex items-center justify-center">
              <span class="material-symbols-outlined text-xl text-primary-container" aria-hidden="true">mail</span>
            </div>
            <div>
              <p class="text-xs font-medium text-on-surface-variant mb-1">البريد الإلكتروني</p>
              <p class="text-sm font-bold text-on-surface" dir="ltr">info@rakal.sa</p>
            </div>
          </div>
        </div>

        <!-- Map Placeholder -->
        <div class="relative h-64 rounded-xl overflow-hidden bg-surface-container-high">
          <!-- Background gradient simulating a dark map -->
          <div class="absolute inset-0 bg-gradient-to-br from-surface-dim via-surface-container to-surface-container-high"></div>
          <!-- Grid lines pattern simulating a map grid -->
          <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(rgba(0,242,255,0.4) 1px, transparent 1px), linear-gradient(90deg, rgba(0,242,255,0.4) 1px, transparent 1px); background-size: 40px 40px;"></div>
          <!-- Dark overlay -->
          <div class="absolute inset-0 bg-surface-dim/40"></div>
          <!-- Pulsing dot + label -->
          <div class="absolute inset-0 flex flex-col items-center justify-center gap-3">
            <div class="relative flex items-center justify-center">
              <span class="absolute w-8 h-8 rounded-full bg-green-500/30 animate-ping"></span>
              <span class="relative w-4 h-4 rounded-full bg-green-400 shadow-[0_0_12px_rgba(74,222,128,0.8)]"></span>
            </div>
            <p class="text-white/80 text-sm font-medium">نحن هنا في الرياض</p>
          </div>
          <!-- Open Maps button overlay -->
          <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
            <a
              href="https://maps.google.com/?q=King+Fahd+Road,+Al+Muruj,+Riyadh"
              target="_blank"
              rel="noopener noreferrer"
              class="flex items-center gap-2 bg-surface-container/90 backdrop-blur-sm border border-white/10 text-on-surface text-xs font-bold px-4 py-2 rounded-xl hover:bg-primary-container/20 hover:border-primary-container/40 transition-all duration-300"
            >
              <span class="material-symbols-outlined text-sm text-primary-container" aria-hidden="true">open_in_new</span>
              فتح الخرائط
            </a>
          </div>
        </div>

        <!-- Trust Sidebar Card -->
        <div class="card-hover bg-surface-container-low p-8 rounded-xl border-r-4 border-primary-container">
          <h3 class="font-bold text-on-surface text-lg mb-6">لماذا ركال؟</h3>
          <ul class="flex flex-col gap-5">
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-xl text-primary-container flex-shrink-0 mt-0.5" aria-hidden="true">verified</span>
              <span class="text-sm text-on-surface-variant leading-relaxed">فريق عمل سعودي خبير بالمتطلبات المحلية</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-xl text-primary-container flex-shrink-0 mt-0.5" aria-hidden="true">verified</span>
              <span class="text-sm text-on-surface-variant leading-relaxed">الالتزام بمعايير الأمن السيبراني الوطنية</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-xl text-primary-container flex-shrink-0 mt-0.5" aria-hidden="true">verified</span>
              <span class="text-sm text-on-surface-variant leading-relaxed">دعم فني واستشارات تقنية على مدار الساعة</span>
            </li>
          </ul>
        </div>

      </div>
    </div>
  </section>

  <!-- 3. CTA Block -->
  <section class="py-16 max-w-screen-2xl mx-auto px-4 md:px-8">
    <div class="glass-panel p-12 md:p-16 rounded-[2rem] text-center">
      <h2 class="text-2xl md:text-4xl font-black text-on-surface mb-4">
        هل تفضل التحدث مباشرة مع خبير؟
      </h2>
      <p class="text-on-surface-variant text-lg max-w-xl mx-auto mb-8 leading-relaxed">
        احجز جلسة استشارية مجانية مع أحد متخصصينا ونساعدك في رسم خارطة طريق رقمية واضحة لأعمالك.
      </p>
      <button class="tech-gradient text-on-primary-fixed font-bold px-10 py-4 rounded-xl inline-flex items-center gap-3 hover:shadow-[0_0_20px_rgba(0,242,255,0.35)] transition-all duration-300">
        <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
        احجز موعداً مع خبير
      </button>
    </div>
  </section>

<?php require_once 'includes/footer.php'; ?>
