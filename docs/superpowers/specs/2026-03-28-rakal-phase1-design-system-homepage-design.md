# Phase 1: Design System + Homepage — Rakal (ركال)

## Overview

Rebuild the Rakal website homepage and establish a shared design system for a premium Arabic-first corporate website targeting Saudi businesses, enterprises, and government sectors. This is Phase 1 of a 4-phase project.

### Phases (for context)
| Phase | Scope |
|-------|-------|
| **1 (this spec)** | Design system + Homepage |
| 2 | About, Services, Contact pages |
| 3 | Single Service template, Solutions/Industries page |
| 4 | Blog listing, bilingual readiness, final QA |

### Project Constraints
- Static HTML + Tailwind CSS CDN (no build tools)
- No JavaScript framework — vanilla JS only
- RTL-first Arabic layout
- Dual accent palette: cyan (#00f2ff) primary + sand-gold (#D4AF37) secondary
- No portfolio/projects section (company has none yet)
- No fake testimonials or fabricated client names
- Deploy-anywhere static files

---

## File Structure

```
G:/rkal/
├── index.html          (homepage — full rebuild)
├── about.html          (Phase 2)
├── services.html       (Phase 2)
├── contact.html        (Phase 2)
├── css/
│   └── styles.css      (NEW — shared custom styles)
├── js/
│   └── main.js         (NEW — mobile drawer, FAQ accordion, interactions)
└── assets/             (NEW — for local images/icons later)
```

---

## Design System

### Tailwind Config (inline, identical on every page)

**Colors** — full Material Design 3 token set preserved from existing files:

| Token | Value | Usage |
|-------|-------|-------|
| surface | #0b1229 | Primary background |
| surface-container-low | #141a32 | Section alternate bg |
| surface-container | #181e36 | Card backgrounds |
| surface-container-high | #222941 | Elevated cards |
| surface-container-highest | #2d344c | Inputs, elevated elements |
| surface-container-lowest | #060d24 | Deepest bg |
| primary-container | #00f2ff | Primary accent (cyan) |
| tertiary-fixed-dim | #4cd6ff | Secondary cyan |
| sand-gold | #D4AF37 | Secondary accent (gold) |
| on-surface | #dce1ff | Primary text (white-blue) |
| on-surface-variant | #b9cacb | Secondary text (gray) |
| on-primary-fixed | #002022 | Text on cyan backgrounds |
| on-primary | #00363a | Text on primary |
| outline | #849495 | Borders |
| outline-variant | #3a494b | Subtle borders |
| error | #ffb4ab | Error state |

All other existing tokens (inverse-*, on-error-*, secondary-*, tertiary-*) preserved as-is.

**Typography:**
| Role | Font Stack |
|------|-----------|
| headline | IBM Plex Sans Arabic, sans-serif |
| body | IBM Plex Sans Arabic, sans-serif |
| label | IBM Plex Sans Arabic, sans-serif |

English fallback: Space Grotesk (headlines), Manrope (body) — loaded via Google Fonts.

**Border Radius:** DEFAULT: 0.125rem, lg: 0.25rem, xl: 0.5rem, full: 0.75rem

### Shared CSS (`css/styles.css`)

| Class | Definition |
|-------|-----------|
| `.glass-panel` | `background: rgba(24,30,54,0.6); backdrop-filter: blur(20px); border: 1px solid rgba(0,242,255,0.1)` |
| `.circuit-bg` | Radial dot-grid pattern: `radial-gradient(circle at 2px 2px, rgba(0,242,255,0.05) 1px, transparent 0)`, 40px spacing |
| `.sadu-pattern` | Saudi Sadu diamond SVG pattern, sand-gold at 0.03 opacity, 40px tile |
| `.tech-gradient` | `linear-gradient(135deg, #00f2ff 0%, #4cd6ff 100%)` |
| `.gold-accent` | Sand-gold border/glow utility |
| `.glow-shadow` | `box-shadow: 0 0 30px rgba(0,242,255,0.3)` |
| `.section-divider` | Gradient line separator between sections |

### Shared JS (`js/main.js`)

| Feature | Behavior |
|---------|----------|
| Mobile drawer | Slide-in from right, dark overlay, close on overlay click + ESC, body scroll lock, 300ms ease transition |
| FAQ accordion | One-open-at-a-time, smooth max-height transition (300ms), expand_more icon rotates 180deg |
| Navbar scroll | Background opacity increases on scroll (transparent → solid) |
| Smooth scroll | Anchor links scroll smoothly to target sections |

---

## Homepage Sections

### 1. Navbar (shared component)

**Desktop (>= md):**
- Fixed top, full-width, `bg-surface/80 backdrop-blur-xl`
- Bottom border: `border-white/5` with cyan glow shadow
- Content: max-w-screen-2xl, centered, flex-row-reverse (RTL)
- Left (trailing): Logo "رکال" bold white + "Vision 2030" gold badge
- Center: nav links — الرئيسية, من نحن, خدماتنا, الحلول, المدونة, تواصل معنا
  - Default: `text-white/70`, hover: `text-white`
  - Active page: `text-[#00f2ff]` with `border-b-2 border-[#00f2ff]`
- Right (leading): CTA button "اطلب عرض سعر" — tech-gradient bg, rounded-xl

**Mobile (< md):**
- Logo centered, hamburger icon on left (trailing in RTL)
- CTA hidden (moves into drawer)

**Slide-in Drawer:**
- Slides from right edge, width ~80% / max 320px
- `bg-surface-container` (solid, not glass)
- Dark overlay on rest of page, click-to-close
- Close on ESC key, body scroll locked
- Logo at top, nav links stacked vertically (48px+ tap targets)
- CTA button at bottom
- Social icons row at very bottom
- CSS transition: `transform: translateX()`, 300ms ease

### 2. Hero Section

**Layout:** Full-width, `min-h-[90vh]`, flex centered, 2-column grid (RTL: text right, image left). Single column on mobile.

**Background:** `circuit-bg` pattern at low opacity + two decorative blur orbs (cyan top-right, gold bottom-left).

**Right column (text):**
- Badge pill: gold dot + "نصنع المستقبل الرقمي لرؤية المملكة" — `bg-surface-container-high`, `border-sand-gold/30`
- Headline: `text-5xl md:text-7xl`, bold, white with "تقنية ذكية" in `tech-gradient` clip text
- Subheadline: `text-lg md:text-xl`, `text-on-surface-variant`
- Two CTAs:
  - "ابدأ مشروعك" — tech-gradient bg, dark text, arrow icon, glow hover
  - "تصفح خدماتنا" — outlined, border-outline-variant, hover bg-white/5

**Left column (image):**
- Existing Riyadh skyline image in `glass-panel` frame, `rounded-[2.5rem]`
- Two floating glass badges (hidden on mobile):
  - Top-right: gold sparkle icon, slow bounce
  - Bottom-left: cyan data icon + "نمو رقمي وطني"

### 3. Stats Bar

**Layout:** `py-24`, `bg-surface-container-low`, 4-column grid (2x2 on mobile).

**Background accent:** Gold triangle SVG at `opacity-5` in corner.

**4 stat cards:** `bg-surface-container-highest/40`, `border-white/5`, `rounded-2xl`, `p-8`, hover: `border-sand-gold/20`.

| Icon | Number | Label | Icon color |
|------|--------|-------|-----------|
| rocket_launch | +٢٥٠ | مشاريع في أنحاء المملكة | gold |
| domain | +١٥ | قطاعاً حيوياً | cyan |
| verified | ٪٩٩ | نسبة رضا الشركاء | gold |
| schedule | +١٠ | سنوات خبرة في السوق | cyan |

### 4. Services Overview

**Layout:** `py-32`, `bg-surface`, centered header, asymmetric bento grid `grid-cols-1 md:grid-cols-4`.

**Cards:** `glass-panel`, `rounded-[2rem]`. Large cards: `col-span-2`, `p-10`. Standard cards: `p-8`. Hover border alternates cyan/gold.

| Service | Size | Icon | Icon color |
|---------|------|------|-----------|
| برمجة المواقع والمنصات الحكومية | large | web | cyan |
| تطبيقات الجوال | standard | smartphone | gold |
| الذكاء الاصطناعي | standard | precision_manufacturing | cyan |
| المتاجر الإلكترونية | standard | shopping_cart | gold |
| أنظمة إدارة الموارد ERP | large | settings_suggest | cyan |
| التسويق الرقمي | standard | search_insights | gold |

Large cards include a faint oversized background icon at `opacity-10` and an "استكشف المزيد" link.

### 5. Industries / Sectors

**Layout:** `py-32`, `bg-surface-container-low/50`, responsive grid `grid-cols-2 sm:grid-cols-3 lg:grid-cols-5`.

**Cards:** `bg-surface-container`, `rounded-2xl`, `p-6`, text-center. `border-white/5`, hover: `border-primary-container/30` + `translate-y-[-4px]`. Icon in `w-14 h-14 rounded-xl` container, alternating cyan/gold.

| Sector | Icon |
|--------|------|
| الشركات | domain |
| الجهات الحكومية | account_balance |
| التجارة الإلكترونية | shopping_cart |
| العيادات والمراكز الطبية | local_hospital |
| التعليم | school |
| العقارات | apartment |
| المطاعم والكافيهات | restaurant |
| الخدمات اللوجستية | local_shipping |
| المؤسسات | corporate_fare |

### 6. Process / Workflow

**Layout:** `py-32`, `bg-surface`, centered header.

**Desktop:** Horizontal timeline, `grid-cols-6`. Number circles connected by a gradient line (cyan → gold). Steps 1-3 cyan themed, steps 4-6 gold themed.

**Each step:** number circle (`w-12 h-12 rounded-full`), icon below, title (bold white `text-sm`), description (`text-xs`, `text-on-surface-variant`).

| # | Title | Icon |
|---|-------|------|
| 1 | دراسة الاحتياج | search |
| 2 | التحليل والتخطيط | analytics |
| 3 | تصميم تجربة المستخدم | design_services |
| 4 | التطوير والتنفيذ | code |
| 5 | الاختبار وضمان الجودة | verified_user |
| 6 | الإطلاق والدعم المستمر | rocket_launch |

**Mobile:** Vertical timeline, right-aligned line (RTL) connecting number circles, title + description beside each.

### 7. Saudi Market Value

**Layout:** `py-32`, `bg-surface-container-low/50` with `sadu-pattern` overlay. 2-column grid (RTL: text right, image left). Single column on mobile.

**Right column:** heading with gold underline, 5 value items stacked vertically. Each item: icon in rounded-xl container (alternating cyan/gold), bold title, short description, `border-b border-white/5` divider. Hover: row shifts slightly.

| Icon | Title | Description |
|------|-------|-------------|
| rocket_launch (cyan) | تسريع التحول الرقمي | نختصر مراحل التحول بحلول جاهزة ومُخصصة |
| settings (gold) | تحسين كفاءة التشغيل | أتمتة العمليات وتقليل التكاليف التشغيلية |
| star (cyan) | رفع جودة تجربة العميل | واجهات وتطبيقات تُبهر عملاءك وتزيد ولاءهم |
| trending_up (gold) | بناء أنظمة قابلة للنمو | بنية تحتية مرنة تنمو مع توسع أعمالك |
| domain (cyan) | دعم الهوية الرقمية للمنشأة | حضور رقمي احترافي يعكس مكانتك في السوق |

**Left column:** Existing Riyadh office image, `rounded-[3rem]`, `shadow-2xl`, `border-4 border-white/5`. Decorative offset border behind in `border-sand-gold/20`.

### 8. Trust / Why Trust Us

**Layout:** `py-32`, `bg-surface`, centered header, `grid-cols-1 md:grid-cols-3`, `gap-8`.

**Cards:** `bg-surface-container`, `rounded-[2rem]`, `p-8`, `border-white/5`, hover: `border-primary-container/20`. Icon in `w-14 h-14 rounded-2xl` container. Row 1 cyan, row 2 gold.

| Icon | Title | Description |
|------|-------|-------------|
| handshake (cyan) | شفافية كاملة في كل مرحلة | نشارك العميل في كل قرار تقني ونوفر تقارير تقدم دورية واضحة |
| lock (cyan) | سرية تامة لبياناتك ومشروعك | نلتزم باتفاقيات عدم إفشاء صارمة وأعلى معايير حماية المعلومات |
| forum (cyan) | تواصل مستمر مع فريق العمل | مدير مشروع مخصص وقنوات تواصل مفتوحة طوال فترة التنفيذ |
| bolt (gold) | التزام بالمواعيد المتفق عليها | نعمل بجداول زمنية واقعية ونلتزم بتسليم كل مرحلة في موعدها |
| verified_user (gold) | ضمان جودة شامل قبل التسليم | اختبارات دقيقة متعددة المراحل تضمن خلو المنتج من الأخطاء |
| support_agent (gold) | دعم فني بعد الإطلاق | فريق دعم متخصص لضمان استمرارية عمل منتجك بكفاءة تامة |

### 9. FAQ

**Layout:** `py-32`, `bg-surface-container-low`, centered header, `max-w-3xl mx-auto`.

**Accordion:** one-open-at-a-time, smooth max-height transition (300ms). Each item separated by `border-b border-white/5`.

**Question row:** `py-6`, flex between text and `expand_more` icon. Text: `text-lg` bold white, hover: `text-primary-container`. Icon rotates 180deg when open.

**Answer panel:** hidden by default, `pb-6`, `text-on-surface-variant`, `text-base`, `leading-relaxed`.

| Question | Answer summary |
|----------|---------------|
| هل تقدمون حلولاً مخصصة حسب النشاط؟ | كل مشروع يبدأ بدراسة تفصيلية لطبيعة النشاط والجمهور المستهدف |
| هل يمكن تطوير نظام داخلي خاص بالشركة؟ | نصمم أنظمة داخلية متكاملة من الصفر حسب متطلبات شركتك |
| هل توفرون دعمًا بعد الإطلاق؟ | باقات دعم فني وصيانة دورية تضمن استمرارية عمل المنتج |
| هل يمكن ربط النظام مع خدمات أخرى؟ | أنظمة مفتوحة قابلة للتكامل مع بوابات الدفع وشركات الشحن والأنظمة الحكومية |
| هل تقدمون تطبيقات iOS وAndroid؟ | تطبيقات أصلية ومتعددة المنصات بأحدث التقنيات |
| كيف تبدأون تنفيذ المشروع؟ | جلسة استشارية مجانية لفهم الاحتياجات ثم عرض تفصيلي |

### 10. CTA Block

**Layout:** `max-w-7xl mx-auto px-8 mb-32`.

**Card:** `rounded-[3rem]`, `bg-gradient-to-br from-surface-container-high to-surface-dim`, `border-white/5`, `shadow-2xl`. `circuit-bg` overlay. Two blur orbs (cyan + gold).

**Content:** centered, `max-w-3xl`.
- Heading: "جاهز لبناء حل رقمي احترافي يخدم أعمالك في المملكة؟" — `text-4xl md:text-5xl`, bold
- Subtitle: `text-on-surface-variant`, `text-lg`
- Two buttons:
  - "تحدث معنا الآن" — tech-gradient, glow hover
  - "اطلب عرض سعر" — `bg-white/5`, `border-white/10`

### 11. Footer (shared component)

**Layout:** `rounded-t-[3rem]`, `bg-[#0b1229]`, `border-t border-white/5`, `sadu-pattern` at low opacity. 4-column grid (RTL), max-w-screen-2xl.

**Columns:**
1. Brand: "رکال" logo text, company description, 3 social icon buttons (hover: `bg-primary-container`)
2. Quick links: الرئيسية, من نحن, خدماتنا, الحلول, تواصل معنا (hover: `text-[#00f2ff]`)
3. Services: التحول الرقمي, الأمن السيبراني, الحوسبة السحابية, ذكاء الأعمال, تطوير البرمجيات
4. Newsletter: heading, description, email input + subscribe button (`bg-primary-container`)

**Bottom bar:** `border-t border-white/5`, copyright text, legal links (الخصوصية, الشروط, الكوكيز).

**Mobile:** Single column stack.

---

## Responsive Breakpoints

| Breakpoint | Behavior |
|-----------|----------|
| < 640px (mobile) | Single column, drawer nav, stacked buttons, hidden floating badges |
| 640-768px (sm) | 2-col grids for stats/sectors, still drawer nav |
| 768-1024px (md) | Desktop nav visible, multi-column grids activate |
| 1024px+ (lg) | Full 2-col hero, 5-col sectors, 6-col timeline |

## Interaction Summary

| Interaction | Implementation |
|-------------|---------------|
| Mobile drawer | JS: toggle class, CSS transform + transition |
| FAQ accordion | JS: click handler, CSS max-height transition |
| Navbar scroll | JS: scroll listener, CSS opacity transition |
| Hover effects | CSS only: border-color, translate, scale, glow |
| Floating badges | CSS only: animate-bounce with slow duration |

## Out of Scope (Phase 1)

- About, Services, Contact page rebuilds (Phase 2)
- Single Service template, Solutions page (Phase 3)
- Blog page, bilingual support (Phase 4)
- Form submission backend
- Analytics integration
- Real contact information (placeholders retained)
- Image optimization / local assets
