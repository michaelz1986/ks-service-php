<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: /dashboard/');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pw = $_POST['password'] ?? '';
    if (do_login($pw)) {
        header('Location: /dashboard/');
        exit;
    }
    $error = 'Falsches Passwort.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Login — KS Service</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{colors:{brand:'#e94e1b',dark:'#1a1a1a'}}}}</script>
<style>body{background:#0f1117;font-family:'Helvetica Neue',Arial,sans-serif}</style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">
  <div class="w-full max-w-sm">
    <div class="mb-8 text-center">
      <img src="/assets/images/cropped-ks-service-logo-orange.webp" alt="KS Service" class="h-12 w-auto mx-auto mb-6">
      <h1 class="text-2xl font-bold text-white tracking-tight">Dashboard Login</h1>
    </div>
    <?php if ($error): ?>
    <div class="mb-4 rounded-lg bg-red-900/30 border border-red-500/40 px-4 py-3 text-sm text-red-400"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" class="bg-[#161922] rounded-2xl p-6 border border-white/5 shadow-xl">
      <label class="block mb-1.5 text-sm font-medium text-white/60">Passwort</label>
      <input type="password" name="password" autofocus required
             class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-3 text-sm text-white outline-none focus:border-brand transition-colors mb-5">
      <button type="submit"
              class="w-full rounded-xl bg-brand hover:bg-orange-700 transition-colors px-4 py-3 text-sm font-bold text-white uppercase tracking-widest">
        Einloggen
      </button>
    </form>
    <p class="mt-4 text-center text-xs text-white/30">Standard-Passwort: <code class="text-white/50">ks2024</code></p>
  </div>
</body>
</html>
