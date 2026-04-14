<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/data.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/dashboard-layout.php';
require_login();

$saved = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['section'] ?? 'general';

    if ($section === 'general') {
        $updates = [
            'companyName'       => trim($_POST['companyName'] ?? ''),
            'email'             => trim($_POST['email'] ?? ''),
            'phone'             => trim($_POST['phone'] ?? ''),
            'address'           => trim($_POST['address'] ?? ''),
            'uid'               => trim($_POST['uid'] ?? ''),
            'openingHours'      => trim($_POST['openingHours'] ?? ''),
            'emergencyService'  => trim($_POST['emergencyService'] ?? ''),
            'websiteTitle'      => trim($_POST['websiteTitle'] ?? ''),
            'websiteDescription'=> trim($_POST['websiteDescription'] ?? ''),
        ];
        $current = get_settings();
        write_json('settings.json', array_merge($current, $updates));
        $saved = 'general';
    }

    if ($section === 'email') {
        $current = get_settings();
        $updates = [
            'smtpHost'     => trim($_POST['smtpHost'] ?? ''),
            'smtpPort'     => trim($_POST['smtpPort'] ?? '587'),
            'smtpUser'     => trim($_POST['smtpUser'] ?? ''),
            'smtpFrom'     => trim($_POST['smtpFrom'] ?? ''),
            'smtpFromName' => trim($_POST['smtpFromName'] ?? ''),
            'smtpTo'       => trim($_POST['smtpTo'] ?? ''),
            'smtpEnabled'  => isset($_POST['smtpEnabled']),
        ];
        // Only update password if provided
        if (!empty($_POST['smtpPass'])) {
            $updates['smtpPass'] = $_POST['smtpPass'];
        }
        write_json('settings.json', array_merge($current, $updates));
        $saved = 'email';
    }

    if ($section === 'password') {
        $current = get_settings();
        $pw  = $_POST['newPassword'] ?? '';
        $pw2 = $_POST['newPassword2'] ?? '';
        if (strlen($pw) < 6) {
            $error = 'Passwort muss mindestens 6 Zeichen haben.';
        } elseif ($pw !== $pw2) {
            $error = 'Passwörter stimmen nicht überein.';
        } else {
            $current['adminPasswordHash'] = password_hash($pw, PASSWORD_DEFAULT);
            write_json('settings.json', $current);
            $saved = 'password';
        }
    }
}

$s = get_settings();

ob_start();
?>
<div class="space-y-6 max-w-3xl">
  <h1 class="text-2xl font-black text-white uppercase tracking-tight">Einstellungen</h1>

  <?php if ($saved && !$error): ?>
  <div class="rounded-xl bg-green-900/30 border border-green-500/40 px-4 py-3 text-sm text-green-400 font-semibold">
    Einstellungen gespeichert.
  </div>
  <?php endif; ?>
  <?php if ($error): ?>
  <div class="rounded-xl bg-red-900/30 border border-red-500/40 px-4 py-3 text-sm text-red-400"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- General settings -->
  <div class="rounded-2xl bg-[#161922] border border-white/5 p-6">
    <h2 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-5">FIRMEN-DATEN</h2>
    <form method="post" class="space-y-4">
      <input type="hidden" name="section" value="general">
      <?php
      $fields = [
        ['companyName','Firmenname','text'],
        ['email','E-Mail','email'],
        ['phone','Telefon','text'],
        ['uid','UID / Steuernummer','text'],
        ['openingHours','Öffnungszeiten','text'],
        ['emergencyService','Notdienst','text'],
        ['websiteTitle','Website-Titel','text'],
      ];
      foreach ($fields as [$key,$label,$type]):
      ?>
      <div>
        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50"><?= $label ?></label>
        <input type="<?= $type ?>" name="<?= $key ?>" value="<?= htmlspecialchars($s[$key] ?? '') ?>"
               class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
      </div>
      <?php endforeach; ?>
      <div>
        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Adresse</label>
        <textarea name="address" rows="2"
                  class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors resize-none"><?= htmlspecialchars($s['address'] ?? '') ?></textarea>
      </div>
      <div>
        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Website-Beschreibung</label>
        <textarea name="websiteDescription" rows="2"
                  class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors resize-none"><?= htmlspecialchars($s['websiteDescription'] ?? '') ?></textarea>
      </div>
      <button type="submit" class="bg-[#e94e1b] hover:bg-orange-700 transition-colors px-6 py-2.5 rounded-xl text-sm font-bold text-white uppercase tracking-widest">Speichern</button>
    </form>
  </div>

  <!-- Email / SMTP -->
  <div class="rounded-2xl bg-[#161922] border border-white/5 p-6">
    <h2 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-2">E-MAIL EINSTELLUNGEN (SMTP)</h2>
    <p class="text-xs text-white/30 mb-5">Wenn aktiviert, werden neue Anfragen automatisch per E-Mail gesendet.</p>
    <form method="post" class="space-y-4">
      <input type="hidden" name="section" value="email">

      <div class="flex items-center gap-3">
        <label class="relative inline-flex items-center cursor-pointer">
          <input type="checkbox" name="smtpEnabled" class="sr-only peer" <?= !empty($s['smtpEnabled'])?'checked':'' ?>>
          <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#e94e1b]"></div>
        </label>
        <span class="text-sm text-white/60">E-Mail-Benachrichtigungen aktivieren</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">SMTP Host</label>
          <input type="text" name="smtpHost" value="<?= htmlspecialchars($s['smtpHost'] ?? '') ?>" placeholder="z.B. smtp.gmail.com"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">SMTP Port</label>
          <input type="text" name="smtpPort" value="<?= htmlspecialchars($s['smtpPort'] ?? '587') ?>"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Benutzername</label>
          <input type="text" name="smtpUser" value="<?= htmlspecialchars($s['smtpUser'] ?? '') ?>"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Passwort (leer lassen = unverändert)</label>
          <input type="password" name="smtpPass" placeholder="••••••••"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Absender E-Mail</label>
          <input type="email" name="smtpFrom" value="<?= htmlspecialchars($s['smtpFrom'] ?? '') ?>"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Absender Name</label>
          <input type="text" name="smtpFromName" value="<?= htmlspecialchars($s['smtpFromName'] ?? 'KS Service') ?>"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div class="col-span-2">
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Benachrichtigungen senden an</label>
          <input type="email" name="smtpTo" value="<?= htmlspecialchars($s['smtpTo'] ?? '') ?>" placeholder="<?= htmlspecialchars($s['email'] ?? '') ?>"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
      </div>
      <button type="submit" class="bg-[#e94e1b] hover:bg-orange-700 transition-colors px-6 py-2.5 rounded-xl text-sm font-bold text-white uppercase tracking-widest">Speichern</button>
    </form>
  </div>

  <!-- Password -->
  <div class="rounded-2xl bg-[#161922] border border-white/5 p-6">
    <h2 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-5">PASSWORT ÄNDERN</h2>
    <form method="post" class="space-y-4">
      <input type="hidden" name="section" value="password">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Neues Passwort</label>
          <input type="password" name="newPassword" minlength="6" required
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Passwort bestätigen</label>
          <input type="password" name="newPassword2" minlength="6" required
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
      </div>
      <button type="submit" class="bg-white/10 hover:bg-white/20 transition-colors px-6 py-2.5 rounded-xl text-sm font-bold text-white uppercase tracking-widest">Passwort ändern</button>
    </form>
  </div>
</div>
<?php
$content = ob_get_clean();
dashboard_head('Einstellungen');
dashboard_layout('Einstellungen', $content);
