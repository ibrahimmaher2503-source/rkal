-- ============================================================
-- RAKAL (ركال) — Database Schema & Seed Data
-- Charset: utf8mb4 | Engine: InnoDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS rakal_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rakal_db;

-- ============================================================
-- TABLE: services
-- ============================================================
CREATE TABLE IF NOT EXISTS services (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  title             VARCHAR(255) NOT NULL,
  slug              VARCHAR(255) NOT NULL UNIQUE,
  icon              VARCHAR(100),
  description       TEXT,
  overview_content  LONGTEXT,
  full_content      LONGTEXT,
  color_scheme      ENUM('cyan','gold'),
  subservices       JSON,
  stats             JSON,
  target_businesses JSON,
  benefits          JSON,
  tech_stack        JSON,
  workflow          JSON,
  faq               JSON,
  grid_col_span     TINYINT DEFAULT 1,
  sort_order        INT DEFAULT 0,
  is_active         TINYINT(1) DEFAULT 1,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: blogs
-- ============================================================
CREATE TABLE IF NOT EXISTS blogs (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  title           VARCHAR(255) NOT NULL,
  slug            VARCHAR(255) NOT NULL UNIQUE,
  excerpt         TEXT,
  content         LONGTEXT,
  author          VARCHAR(255) DEFAULT 'فريق رقال التقني',
  category        VARCHAR(100),
  category_color  ENUM('cyan','gold'),
  featured_image  VARCHAR(500),
  read_time       INT,
  is_featured     TINYINT(1) DEFAULT 0,
  is_active       TINYINT(1) DEFAULT 1,
  published_at    DATETIME,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: admins
-- ============================================================
CREATE TABLE IF NOT EXISTS admins (
  id                   INT AUTO_INCREMENT PRIMARY KEY,
  username             VARCHAR(100) NOT NULL UNIQUE,
  password_hash        VARCHAR(255),
  must_change_password TINYINT(1) DEFAULT 1,
  created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED: services
-- ============================================================

-- Service 1: تطوير المواقع والمنصات (full data from service-detail.html)
INSERT INTO services (
  title, slug, icon, description, overview_content, full_content,
  color_scheme, subservices, stats, target_businesses, benefits,
  tech_stack, workflow, faq, grid_col_span, sort_order, is_active
) VALUES (
  'تطوير المواقع والمنصات',
  'tatawwur-almawaqi-walmanssat',
  'web',
  'بناء منصات إلكترونية متوافقة مع معايير الحكومة الرقمية، تجمع بين الأداء العالي والتصميم العصري مع دعم كامل للغة العربية وتجربة المستخدم المحلي.',
  '<p>نقدم خدمة تطوير مواقع ومنصات رقمية متكاملة تشمل كل مراحل المشروع من التحليل والتخطيط إلى التصميم والتطوير والإطلاق والمتابعة. نعتمد على أحدث التقنيات العالمية مع مراعاة المتطلبات المحلية والمعايير الحكومية السعودية للخدمات الرقمية.</p>
<p>سواء كنت تحتاج إلى بوابة حكومية إلكترونية، موقع مؤسسي احترافي، منصة تعليمية تفاعلية، أو نظام متكامل لإدارة المحتوى، فريقنا المتخصص يمتلك الخبرة التقنية والمعرفة القطاعية لتقديم حل يلبي احتياجاتك بدقة.</p>
<p>نحرص على أن تكون كل منصة نطورها سريعة الأداء، آمنة، سهلة الاستخدام، ومتوافقة مع معايير الوصول العالمية لضمان وصول أكبر شريحة من المستخدمين إليها بأفضل تجربة ممكنة.</p>',
  NULL,
  'cyan',
  '["مواقع الشركات", "البوابات الحكومية", "لوحات التحكم", "المنصات التعليمية"]',
  '[{"label": "موقع ومنصة تم تسليمها", "value": "+١٥٠"}, {"label": "توافق مع المعايير", "value": "٪١٠٠"}, {"label": "دعم فني متواصل", "value": "24/7"}, {"label": "تقنية معتمدة", "value": "+٣٠"}]',
  '[{"title": "الجهات الحكومية", "description": "بوابات إلكترونية متوافقة مع المعايير الحكومية", "icon": "account_balance"}, {"title": "الشركات الكبرى", "description": "مواقع مؤسسية تعكس الهوية الرقمية وتعزز الحضور التجاري", "icon": "domain"}, {"title": "المؤسسات التعليمية", "description": "منصات تعليمية تفاعلية تدعم التعلم الرقمي", "icon": "school"}, {"title": "القطاع الصحي", "description": "بوابات مرضى وأنظمة حجز مواعيد ذكية", "icon": "local_hospital"}]',
  '[{"title": "أداء فائق السرعة", "description": "مواقع محسّنة وفق أعلى معايير الأداء تحقق درجات متميزة في Core Web Vitals وتضمن تجربة تصفح سلسة.", "icon": "speed"}, {"title": "تصميم متجاوب", "description": "تجربة مستخدم مثالية ومتسقة على كل الأجهزة من الهاتف المحمول إلى الشاشات الكبيرة بلا استثناء.", "icon": "devices"}, {"title": "أمان متقدم", "description": "حماية شاملة من الثغرات الأمنية مع تشفير البيانات وتطبيق أحدث بروتوكولات الأمان الرقمي.", "icon": "lock"}, {"title": "صديق لمحركات البحث", "description": "بنية تقنية محسّنة للـ SEO من الأساس تضمن ظهور موقعك في صدارة نتائج البحث وزيادة الزيارات العضوية.", "icon": "search"}, {"title": "دعم ثنائي اللغة", "description": "تجربة عربية وإنجليزية متكاملة بسلاسة تامة مع دعم كامل لاتجاه النص RTL/LTR وخطوط احترافية لكلتا اللغتين.", "icon": "translate"}, {"title": "سهولة الوصول", "description": "متوافق مع معايير WCAG للوصول الرقمي لضمان وصول جميع المستخدمين بما فيهم ذوو الاحتياجات الخاصة.", "icon": "accessibility"}]',
  '["React", "Next.js", "Vue.js", "Laravel", "Node.js", "Python", "WordPress", "Tailwind CSS", "PostgreSQL", "MongoDB", "AWS", "Docker"]',
  '[{"title": "تحليل المتطلبات", "description": "نبدأ بفهم أهدافك التجارية وجمهورك المستهدف وملامح المشروع بالكامل. نجري دراسة شاملة للمنافسين والسوق لوضع أساس متين للمشروع."}, {"title": "التصميم والنمذجة", "description": "نصمم واجهات المستخدم بدقة عالية ونبني نماذج تفاعلية للاعتماد قبل البدء في التطوير، مما يوفر الوقت ويضمن توافق الرؤية."}, {"title": "التطوير والبرمجة", "description": "نبني الموقع أو المنصة باستخدام أحدث التقنيات مع تطبيق أفضل الممارسات البرمجية وضمان جودة الكود ومراجعته بشكل مستمر."}, {"title": "الإطلاق والمتابعة", "description": "نشر الموقع على البنية التحتية المناسبة مع مراقبة الأداء والأمان بشكل مستمر وتقديم تقارير دورية عن حالة المنصة."}]',
  '[{"question": "كم يستغرق تطوير موقع إلكتروني؟", "answer": "يعتمد على حجم المشروع ومتطلباته التقنية. الموقع التعريفي البسيط يستغرق عادةً ٤-٦ أسابيع، بينما المنصات المتكاملة تحتاج إلى ٨-١٢ أسبوعاً. نقدم جدولاً زمنياً تفصيلياً في بداية كل مشروع."}, {"question": "هل يمكنني إدارة محتوى الموقع بنفسي؟", "answer": "نعم بالتأكيد. نوفر لوحة تحكم سهلة الاستخدام مصممة خصيصاً لتمكينك من إدارة المحتوى دون الحاجة إلى أي خبرة تقنية. نقدم أيضاً جلسات تدريبية وأدلة استخدام مفصلة."}, {"question": "هل يشمل السعر الاستضافة؟", "answer": "نقدم باقات استضافة اختيارية على خوادم سحابية عالية الأداء، كما يمكننا العمل مع مزود الاستضافة المفضل لديك. نرشدك لاختيار الحل الأنسب وفق حجم مشروعك وميزانيتك."}, {"question": "هل توفرون خدمات الصيانة بعد الإطلاق؟", "answer": "نعم، نقدم باقات صيانة ودعم فني شهرية تشمل تحديثات الأمان، مراقبة الأداء، إصلاح الأخطاء، وتحديثات المحتوى. فريقنا متاح على مدار الساعة للتعامل مع أي طارئ."}]',
  2,
  1,
  1
);

-- Service 2: تطبيقات الجوال
INSERT INTO services (
  title, slug, icon, description, overview_content, full_content,
  color_scheme, subservices, stats, target_businesses, benefits,
  tech_stack, workflow, faq, grid_col_span, sort_order, is_active
) VALUES (
  'تطبيقات الجوال',
  'tatbiqat-aljawwal',
  'smartphone',
  'تطوير تطبيقات iOS و Android بأحدث التقنيات، بأداء سلس وتجربة مستخدم عربية أصيلة تناسب كل فئات الجمهور.',
  '<p>تفاصيل الخدمة قريبًا...</p>',
  NULL,
  'gold',
  '["تطبيقات أصلية", "تطبيقات متعددة المنصات", "تطبيقات المؤسسات"]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  1,
  2,
  1
);

-- Service 3: أنظمة ERP و CRM
INSERT INTO services (
  title, slug, icon, description, overview_content, full_content,
  color_scheme, subservices, stats, target_businesses, benefits,
  tech_stack, workflow, faq, grid_col_span, sort_order, is_active
) VALUES (
  'أنظمة ERP و CRM',
  'anzimat-erp-wa-crm',
  'settings_suggest',
  'أنظمة إدارة موارد متكاملة متوافقة مع ZATCA، تغطي جميع عمليات المؤسسة من المالية إلى الموارد البشرية.',
  '<p>تفاصيل الخدمة قريبًا...</p>',
  NULL,
  'cyan',
  '["الفوترة الإلكترونية", "إدارة الموظفين", "سلاسل الإمداد", "التقارير المالية"]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  1,
  3,
  1
);

-- Service 4: المتاجر الإلكترونية
INSERT INTO services (
  title, slug, icon, description, overview_content, full_content,
  color_scheme, subservices, stats, target_businesses, benefits,
  tech_stack, workflow, faq, grid_col_span, sort_order, is_active
) VALUES (
  'المتاجر الإلكترونية',
  'almatajir-aleliktruniyya',
  'shopping_cart',
  'حلول تجارة إلكترونية متكاملة مع بوابات دفع محلية وتجربة تسوق عربية سلسة تحقق أعلى معدلات التحويل.',
  '<p>تفاصيل الخدمة قريبًا...</p>',
  NULL,
  'gold',
  '["تصميم المتجر", "إدارة المنتجات", "بوابات الدفع", "التكامل مع الشحن"]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  1,
  4,
  1
);

-- Service 5: حلول الذكاء الاصطناعي
INSERT INTO services (
  title, slug, icon, description, overview_content, full_content,
  color_scheme, subservices, stats, target_businesses, benefits,
  tech_stack, workflow, faq, grid_col_span, sort_order, is_active
) VALUES (
  'حلول الذكاء الاصطناعي',
  'hulul-aldhaka-alasinai',
  'precision_manufacturing',
  'دمج تقنيات AI المتقدمة لتحليل البيانات وأتمتة العمليات المؤسسية، مع تخصيص نماذج معالجة اللغة العربية لتحقيق نتائج دقيقة في السياق السعودي.',
  '<p>تفاصيل الخدمة قريبًا...</p>',
  NULL,
  'cyan',
  '["تحليل البيانات الضخمة", "الأتمتة الذكية", "معالجة اللغة العربية", "التنبؤ والتوصيات"]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  2,
  5,
  1
);

-- Service 6: تكامل الأنظمة و APIs
INSERT INTO services (
  title, slug, icon, description, overview_content, full_content,
  color_scheme, subservices, stats, target_businesses, benefits,
  tech_stack, workflow, faq, grid_col_span, sort_order, is_active
) VALUES (
  'تكامل الأنظمة و APIs',
  'takaml-alanzimat-wa-apis',
  'api',
  'ربط الأنظمة المختلفة وبناء واجهات برمجة التطبيقات لضمان تدفق البيانات بسلاسة عبر منظومتك التقنية.',
  '<p>تفاصيل الخدمة قريبًا...</p>',
  NULL,
  'gold',
  '["REST APIs", "تكامل الأنظمة الحكومية", "بوابات الدفع", "خدمات الطرف الثالث"]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  1,
  6,
  1
);

-- Service 7: التسويق الرقمي و SEO
INSERT INTO services (
  title, slug, icon, description, overview_content, full_content,
  color_scheme, subservices, stats, target_businesses, benefits,
  tech_stack, workflow, faq, grid_col_span, sort_order, is_active
) VALUES (
  'التسويق الرقمي و SEO',
  'altaswiq-alraqmi-wa-seo',
  'search_insights',
  'إدارة الحملات الرقمية واستهداف الجمهور المحلي بأساليب مدروسة تعزز الحضور الرقمي وتزيد المبيعات.',
  '<p>تفاصيل الخدمة قريبًا...</p>',
  NULL,
  'cyan',
  '["تحسين محركات البحث", "إدارة الحملات", "التحليلات", "إدارة المحتوى"]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  1,
  7,
  1
);

-- Service 8: الصيانة والدعم التقني
INSERT INTO services (
  title, slug, icon, description, overview_content, full_content,
  color_scheme, subservices, stats, target_businesses, benefits,
  tech_stack, workflow, faq, grid_col_span, sort_order, is_active
) VALUES (
  'الصيانة والدعم التقني',
  'alssiyana-waldaem-alttiqni',
  'support_agent',
  'دعم فني متواصل ومراقبة أداء الأنظمة على مدار الساعة لضمان استمرارية الأعمال وأعلى مستويات الموثوقية.',
  '<p>تفاصيل الخدمة قريبًا...</p>',
  NULL,
  'gold',
  '["مراقبة 24/7", "تحديثات أمنية", "نسخ احتياطي", "تحسين الأداء"]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  '[]',
  1,
  8,
  1
);

-- ============================================================
-- SEED: blogs
-- ============================================================

-- Blog 1: FEATURED
INSERT INTO blogs (
  title, slug, excerpt, content, author, category, category_color,
  featured_image, read_time, is_featured, is_active, published_at
) VALUES (
  'كيف يُسهم التحول الرقمي في تحقيق رؤية ٢٠٣٠',
  'kayfa-yusahim-altahawwul-alraqmi-fi-tahqiq-ruyat-2030',
  'نستعرض في هذا المقال الدور المحوري الذي تؤديه التحولات الرقمية في دعم أهداف المملكة العربية السعودية الطموحة ضمن إطار رؤية ٢٠٣٠، من تمكين القطاع الخاص إلى بناء اقتصاد رقمي متنوع ومستدام.',
  '<p>محتوى المقال قريبًا...</p>',
  'فريق رقال التقني',
  'التحول الرقمي',
  'cyan',
  NULL,
  8,
  1,
  1,
  '2026-03-15 00:00:00'
);

-- Blog 2
INSERT INTO blogs (
  title, slug, excerpt, content, author, category, category_color,
  featured_image, read_time, is_featured, is_active, published_at
) VALUES (
  'أفضل ممارسات التحول الرقمي للشركات السعودية',
  'afdal-mumarasat-altahawwul-alraqmi-lilsharikaat-alsaudiyya',
  'دليل عملي يستعرض أبرز الاستراتيجيات والأدوات التي تعتمدها الشركات السعودية الرائدة في مسيرة تحولها الرقمي الشامل.',
  '<p>محتوى المقال قريبًا...</p>',
  'فريق رقال التقني',
  'التحول الرقمي',
  'cyan',
  NULL,
  5,
  0,
  1,
  '2026-03-10 00:00:00'
);

-- Blog 3
INSERT INTO blogs (
  title, slug, excerpt, content, author, category, category_color,
  featured_image, read_time, is_featured, is_active, published_at
) VALUES (
  'كيف يُغيّر الذكاء الاصطناعي مستقبل الأعمال في المملكة',
  'kayfa-yughayyir-aldhaka-alasinai-mustaqbal-alamal-fi-almamlaka',
  'نظرة معمّقة على تطبيقات الذكاء الاصطناعي في القطاعات الحيوية بالمملكة، وكيف تُعيد رسم خارطة الأعمال والاقتصاد الوطني.',
  '<p>محتوى المقال قريبًا...</p>',
  'فريق رقال التقني',
  'الذكاء الاصطناعي',
  'gold',
  NULL,
  7,
  0,
  1,
  '2026-03-05 00:00:00'
);

-- Blog 4
INSERT INTO blogs (
  title, slug, excerpt, content, author, category, category_color,
  featured_image, read_time, is_featured, is_active, published_at
) VALUES (
  'دليلك الشامل للأمن السيبراني وفق معايير NCA',
  'daliluk-alshamil-lilamn-alsibrani-wafq-mayair-nca',
  'استعراض شامل لمتطلبات الأمن السيبراني الصادرة عن الهيئة الوطنية للأمن السيبراني وكيفية تطبيقها في بيئات العمل السعودية.',
  '<p>محتوى المقال قريبًا...</p>',
  'فريق رقال التقني',
  'أمن المعلومات',
  'cyan',
  NULL,
  6,
  0,
  1,
  '2026-02-28 00:00:00'
);

-- Blog 5
INSERT INTO blogs (
  title, slug, excerpt, content, author, category, category_color,
  featured_image, read_time, is_featured, is_active, published_at
) VALUES (
  'مقارنة بين أطر عمل تطوير تطبيقات الجوال في ٢٠٢٦',
  'muqarana-bayna-utur-amal-tatbiqat-aljawwal-fi-2026',
  'تحليل مفصّل لأبرز أطر عمل تطوير تطبيقات الجوال المتاحة في ٢٠٢٦، مع مقارنة موضوعية تُعينك على اختيار الأنسب لمشروعك.',
  '<p>محتوى المقال قريبًا...</p>',
  'فريق رقال التقني',
  'تطوير البرمجيات',
  'gold',
  NULL,
  8,
  0,
  1,
  '2026-02-20 00:00:00'
);

-- Blog 6
INSERT INTO blogs (
  title, slug, excerpt, content, author, category, category_color,
  featured_image, read_time, is_featured, is_active, published_at
) VALUES (
  'التقنية المالية في المملكة: فرص النمو والتحديات',
  'altiqniyya-almaliyya-fi-almamlaka-furas-alnumuw-waltahadiyyat',
  'رصد شامل للمشهد المتنامي للتقنية المالية في المملكة العربية السعودية، وأبرز الفرص والتحديات في ظل التشريعات والإصلاحات الهيكلية.',
  '<p>محتوى المقال قريبًا...</p>',
  'فريق رقال التقني',
  'رؤية ٢٠٣٠',
  'cyan',
  NULL,
  4,
  0,
  1,
  '2026-02-15 00:00:00'
);

-- Blog 7
INSERT INTO blogs (
  title, slug, excerpt, content, author, category, category_color,
  featured_image, read_time, is_featured, is_active, published_at
) VALUES (
  'بناء ثقافة الابتكار الرقمي داخل المؤسسات السعودية',
  'bina-thaqafat-alibtikaar-alraqmi-dakhil-almuassasat-alsaudiyya',
  'كيف تزرع ثقافة الابتكار الرقمي في بيئة العمل المؤسسية؟ خطوات عملية ونماذج ناجحة من تجارب الشركات السعودية الرائدة.',
  '<p>محتوى المقال قريبًا...</p>',
  'فريق رقال التقني',
  'التحول الرقمي',
  'gold',
  NULL,
  6,
  0,
  1,
  '2026-02-10 00:00:00'
);

-- ============================================================
-- SEED: admins
-- WARNING: Change this password immediately after first login!
-- ============================================================
INSERT INTO admins (username, password_hash, must_change_password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);
