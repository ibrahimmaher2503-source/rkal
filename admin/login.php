<?php
session_start();
require_once '../includes/functions.php';

$error = '';
$mode = 'login'; // or 'change_password'

// Already logged in but must change password
if (isset($_SESSION['admin_id']) && !empty($_SESSION['must_change_password'])) {
    $mode = 'change_password';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrf();

    if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
        // Handle password change
        $oldPassword     = $_POST['old_password']     ?? '';
        $newPassword     = $_POST['new_password']     ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirmPassword) {
            $error = 'كلمات المرور غير متطابقة';
        } elseif (strlen($newPassword) < 8) {
            $error = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل';
        } else {
            $db   = getDB();
            $stmt = $db->prepare('SELECT * FROM admins WHERE id = :id');
            $stmt->execute(['id' => $_SESSION['admin_id']]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($oldPassword, $admin['password_hash'])) {
                $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt    = $db->prepare('UPDATE admins SET password_hash = :hash, must_change_password = 0 WHERE id = :id');
                $stmt->execute(['hash' => $newHash, 'id' => $admin['id']]);
                $_SESSION['must_change_password'] = false;
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'كلمة المرور القديمة غير صحيحة';
            }
        }
        $mode = 'change_password';
    } else {
        // Handle login
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $db   = getDB();
        $stmt = $db->prepare('SELECT * FROM admins WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']             = $admin['id'];
            $_SESSION['admin_username']        = $admin['username'];
            $_SESSION['must_change_password'] = (bool) $admin['must_change_password'];
            $_SESSION['last_activity']         = time();

            if ($admin['must_change_password']) {
                header('Location: login.php?change_password=1');
            } else {
                header('Location: dashboard.php');
            }
            exit;
        } else {
            $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
        }
    }
}

// Show timeout message
if (isset($_GET['timeout'])) {
    $error = 'انتهت صلاحية الجلسة. يرجى تسجيل الدخول مرة أخرى';
}

// Switch to change_password mode if URL param is set
if (isset($_GET['change_password'])) {
    $mode = 'change_password';
}
?>
<!DOCTYPE html>
<html class="dark" dir="rtl" lang="ar">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>ركال | لوحة التحكم</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary-fixed": "#74f5ff",
            "surface-bright": "#323851",
            "inverse-on-surface": "#292f48",
            "on-primary-fixed-variant": "#004f54",
            "tertiary-fixed-dim": "#4cd6ff",
            "inverse-surface": "#dce1ff",
            "primary-container": "#00f2ff",
            "surface-dim": "#0b1229",
            "surface-container-high": "#222941",
            "on-error": "#690005",
            "primary-fixed-dim": "#00dbe7",
            "surface-variant": "#2d344c",
            "error": "#ffb4ab",
            "on-tertiary": "#003543",
            "on-tertiary-fixed-variant": "#004e60",
            "secondary-fixed-dim": "#bcc5ed",
            "surface-container-lowest": "#060d24",
            "surface-container-highest": "#2d344c",
            "on-surface-variant": "#b9cacb",
            "on-secondary-fixed-variant": "#3d4567",
            "on-primary": "#00363a",
            "surface-container": "#181e36",
            "secondary-container": "#3d4567",
            "outline": "#849495",
            "inverse-primary": "#00696f",
            "tertiary-fixed": "#b7eaff",
            "on-primary-fixed": "#002022",
            "secondary-fixed": "#dce1ff",
            "on-tertiary-fixed": "#001f28",
            "on-error-container": "#ffdad6",
            "on-surface": "#dce1ff",
            "on-secondary": "#262f4f",
            "surface": "#0b1229",
            "secondary": "#bcc5ed",
            "primary": "#e1fdff",
            "on-secondary-container": "#abb3db",
            "surface-tint": "#00dbe7",
            "surface-container-low": "#141a32",
            "tertiary": "#eef9ff",
            "on-background": "#dce1ff",
            "error-container": "#93000a",
            "outline-variant": "#3a494b",
            "tertiary-container": "#a0e5ff",
            "background": "#0b1229",
            "on-primary-container": "#006a71",
            "on-secondary-fixed": "#111a39",
            "on-tertiary-container": "#006881",
            "sand-gold": "#D4AF37"
          },
          fontFamily: {
            "headline": ["IBM Plex Sans Arabic", "sans-serif"],
            "body":     ["IBM Plex Sans Arabic", "sans-serif"],
            "label":    ["IBM Plex Sans Arabic", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.125rem",
            "lg": "0.25rem",
            "xl": "0.5rem"
          },
        },
      },
    }
  </script>
  <style>
    /* Animated grid background */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(0,219,231,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,219,231,0.04) 1px, transparent 1px);
      background-size: 48px 48px;
      pointer-events: none;
      z-index: 0;
    }

    /* Radial glow behind the card */
    .login-glow {
      background: radial-gradient(ellipse 70% 55% at 50% 50%,
        rgba(0,219,231,0.12) 0%,
        rgba(0,219,231,0.04) 45%,
        transparent 70%
      );
    }

    /* Glass card */
    .glass-card {
      background: rgba(24, 30, 54, 0.85);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid rgba(0,219,231,0.15);
      box-shadow:
        0 0 0 1px rgba(0,219,231,0.06),
        0 32px 64px rgba(0,0,0,0.5),
        0 0 80px rgba(0,219,231,0.06) inset;
    }

    /* Gradient submit button */
    .tech-gradient {
      background: linear-gradient(135deg, #00dbe7 0%, #006a71 100%);
    }
    .tech-gradient:hover {
      background: linear-gradient(135deg, #00f2ff 0%, #007a82 100%);
    }

    /* Input focus ring */
    .admin-input:focus {
      outline: none;
      border-color: #00dbe7;
      box-shadow: 0 0 0 3px rgba(0,219,231,0.15);
    }

    /* Logo glow text */
    .logo-glow {
      text-shadow: 0 0 32px rgba(0,219,231,0.5), 0 0 64px rgba(0,219,231,0.2);
    }

    /* Divider accent */
    .accent-divider {
      background: linear-gradient(90deg, transparent, #00dbe7, transparent);
      height: 1px;
    }
  </style>
</head>
<body class="bg-surface text-on-surface font-body min-h-screen flex items-center justify-center relative overflow-hidden">

  <!-- Background glow layer -->
  <div class="login-glow fixed inset-0 pointer-events-none z-0"></div>

  <!-- Card wrapper -->
  <div class="relative z-10 w-full max-w-md mx-auto px-4 py-10">
    <div class="glass-card rounded-xl p-8 sm:p-10">

      <!-- Logo & branding -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-surface-container-high border border-primary-container/20 mb-4">
          <span class="text-3xl font-bold text-[#00dbe7] logo-glow font-headline" aria-hidden="true">ر</span>
        </div>
        <h1 class="text-3xl font-bold text-[#00dbe7] logo-glow font-headline tracking-wide">رکال</h1>
        <p class="mt-1 text-sm text-on-surface-variant tracking-widest uppercase">
          <?= $mode === 'change_password' ? 'تغيير كلمة المرور' : 'لوحة التحكم' ?>
        </p>
        <div class="accent-divider mt-4 mx-auto w-24"></div>
      </div>

      <!-- Error message -->
      <?php if ($error): ?>
        <div class="mb-6 flex items-start gap-3 rounded-xl bg-error-container/30 border border-error/30 px-4 py-3" role="alert">
          <span class="material-symbols-outlined text-error text-lg mt-0.5 flex-shrink-0">error</span>
          <p class="text-sm text-error leading-relaxed"><?= e($error) ?></p>
        </div>
      <?php endif; ?>

      <?php if ($mode === 'change_password'): ?>
        <!-- ========== PASSWORD CHANGE FORM ========== -->
        <form method="POST" action="login.php" novalidate>
          <?= csrfField() ?>
          <input type="hidden" name="action" value="change_password"/>

          <div class="space-y-5">
            <!-- Old password -->
            <div>
              <label for="old_password" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                كلمة المرور الحالية
              </label>
              <div class="relative">
                <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-on-surface-variant text-lg pointer-events-none" aria-hidden="true">lock</span>
                <input
                  type="password"
                  id="old_password"
                  name="old_password"
                  required
                  autocomplete="current-password"
                  class="admin-input w-full bg-surface-container-high border border-outline-variant rounded-xl pr-10 pl-4 py-3 text-on-surface placeholder:text-on-surface-variant/50 transition-colors"
                  placeholder="••••••••"
                />
              </div>
            </div>

            <!-- New password -->
            <div>
              <label for="new_password" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                كلمة المرور الجديدة
              </label>
              <div class="relative">
                <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-on-surface-variant text-lg pointer-events-none" aria-hidden="true">lock_reset</span>
                <input
                  type="password"
                  id="new_password"
                  name="new_password"
                  required
                  minlength="8"
                  autocomplete="new-password"
                  class="admin-input w-full bg-surface-container-high border border-outline-variant rounded-xl pr-10 pl-4 py-3 text-on-surface placeholder:text-on-surface-variant/50 transition-colors"
                  placeholder="8 أحرف على الأقل"
                />
              </div>
            </div>

            <!-- Confirm new password -->
            <div>
              <label for="confirm_password" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                تأكيد كلمة المرور
              </label>
              <div class="relative">
                <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-on-surface-variant text-lg pointer-events-none" aria-hidden="true">lock_open</span>
                <input
                  type="password"
                  id="confirm_password"
                  name="confirm_password"
                  required
                  minlength="8"
                  autocomplete="new-password"
                  class="admin-input w-full bg-surface-container-high border border-outline-variant rounded-xl pr-10 pl-4 py-3 text-on-surface placeholder:text-on-surface-variant/50 transition-colors"
                  placeholder="••••••••"
                />
              </div>
            </div>

            <!-- Submit -->
            <button
              type="submit"
              class="tech-gradient w-full text-on-primary-fixed font-bold py-3.5 rounded-xl transition-all duration-200 shadow-lg shadow-primary-container/20 hover:shadow-primary-container/30 active:scale-[0.98] mt-2"
            >
              تغيير كلمة المرور
            </button>
          </div>
        </form>

      <?php else: ?>
        <!-- ========== LOGIN FORM ========== -->
        <form method="POST" action="login.php" novalidate>
          <?= csrfField() ?>

          <div class="space-y-5">
            <!-- Username -->
            <div>
              <label for="username" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                اسم المستخدم
              </label>
              <div class="relative">
                <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-on-surface-variant text-lg pointer-events-none" aria-hidden="true">person</span>
                <input
                  type="text"
                  id="username"
                  name="username"
                  required
                  autocomplete="username"
                  autofocus
                  class="admin-input w-full bg-surface-container-high border border-outline-variant rounded-xl pr-10 pl-4 py-3 text-on-surface placeholder:text-on-surface-variant/50 transition-colors"
                  placeholder="admin"
                  value="<?= e($_POST['username'] ?? '') ?>"
                />
              </div>
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-on-surface-variant mb-1.5">
                كلمة المرور
              </label>
              <div class="relative">
                <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-3 text-on-surface-variant text-lg pointer-events-none" aria-hidden="true">lock</span>
                <input
                  type="password"
                  id="password"
                  name="password"
                  required
                  autocomplete="current-password"
                  class="admin-input w-full bg-surface-container-high border border-outline-variant rounded-xl pr-10 pl-4 py-3 text-on-surface placeholder:text-on-surface-variant/50 transition-colors"
                  placeholder="••••••••"
                />
              </div>
            </div>

            <!-- Submit -->
            <button
              type="submit"
              class="tech-gradient w-full text-on-primary-fixed font-bold py-3.5 rounded-xl transition-all duration-200 shadow-lg shadow-primary-container/20 hover:shadow-primary-container/30 active:scale-[0.98] mt-2"
            >
              تسجيل الدخول
            </button>
          </div>
        </form>
      <?php endif; ?>

      <!-- Footer note -->
      <p class="mt-8 text-center text-xs text-on-surface-variant/50">
        &copy; <?= date('Y') ?> ركال &mdash; جميع الحقوق محفوظة
      </p>

    </div><!-- /glass-card -->
  </div><!-- /card wrapper -->

</body>
</html>
