<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/data.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/dashboard-layout.php';
require_login();

$message = '';
$msgType = '';

// Export
if (isset($_GET['export'])) {
    $which = $_GET['export'];
    if ($which === 'all') {
        $data = [
            'exported_at' => now_iso(),
            'stapler'     => get_staplers(),
            'anfragen'    => get_inquiries(),
            'settings'    => get_settings(),
        ];
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="ks-service-backup-' . date('Y-m-d') . '.json"');
        header('Content-Length: ' . strlen($json));
        echo $json;
        exit;
    }
    if ($which === 'stapler') {
        $json = json_encode(['exported_at'=>now_iso(),'stapler'=>get_staplers()], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="stapler-' . date('Y-m-d') . '.json"');
        echo $json; exit;
    }
    if ($which === 'anfragen') {
        $json = json_encode(['exported_at'=>now_iso(),'anfragen'=>get_inquiries()], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="anfragen-' . date('Y-m-d') . '.json"');
        echo $json; exit;
    }
}

// Import
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['backup_file']['tmp_name'])) {
    $json = file_get_contents($_FILES['backup_file']['tmp_name']);
    $data = json_decode($json, true);
    if (!$data) {
        $message = 'Ungültige JSON-Datei.';
        $msgType = 'error';
    } else {
        $imported = [];
        if (!empty($data['stapler'])) {
            write_json('stapler.json', $data['stapler']);
            $imported[] = count($data['stapler']) . ' Stapler';
        }
        if (!empty($data['anfragen'])) {
            write_json('anfragen.json', $data['anfragen']);
            $imported[] = count($data['anfragen']) . ' Anfragen';
        }
        if (!empty($data['settings'])) {
            // Don't overwrite password hash from backup for security
            $current = get_settings();
            $data['settings']['adminPasswordHash'] = $current['adminPasswordHash'];
            write_json('settings.json', $data['settings']);
            $imported[] = 'Einstellungen';
        }
        $message = 'Import erfolgreich: ' . implode(', ', $imported);
        $msgType = 'success';
    }
}

$staplerCount  = count(get_staplers());
$anfragenCount = count(get_inquiries());

ob_start();
?>
<div class="space-y-6 max-w-3xl">
  <h1 class="text-2xl font-black text-white uppercase tracking-tight">Backup</h1>

  <?php if ($message): ?>
  <div class="rounded-xl px-4 py-3 text-sm font-semibold <?= $msgType==='error'?'bg-red-900/30 border border-red-500/40 text-red-400':'bg-green-900/30 border border-green-500/40 text-green-400' ?>">
    <?= htmlspecialchars($message) ?>
  </div>
  <?php endif; ?>

  <!-- Stats -->
  <div class="grid grid-cols-2 gap-4">
    <div class="rounded-2xl bg-[#161922] border border-white/5 p-5">
      <p class="text-xs font-bold uppercase tracking-widest text-white/40 mb-1">Stapler</p>
      <p class="text-3xl font-black text-[#e94e1b]"><?= $staplerCount ?></p>
    </div>
    <div class="rounded-2xl bg-[#161922] border border-white/5 p-5">
      <p class="text-xs font-bold uppercase tracking-widest text-white/40 mb-1">Anfragen</p>
      <p class="text-3xl font-black text-[#e94e1b]"><?= $anfragenCount ?></p>
    </div>
  </div>

  <!-- Export -->
  <div class="rounded-2xl bg-[#161922] border border-white/5 p-6">
    <h2 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-5">EXPORT</h2>
    <div class="space-y-3">
      <a href="?export=all" class="flex items-center justify-between rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 px-5 py-4 transition-colors group">
        <div>
          <p class="text-sm font-bold text-white">Vollständiges Backup</p>
          <p class="text-xs text-white/40">Stapler + Anfragen + Einstellungen</p>
        </div>
        <svg class="w-5 h-5 text-[#e94e1b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
      </a>
      <a href="?export=stapler" class="flex items-center justify-between rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 px-5 py-4 transition-colors">
        <div>
          <p class="text-sm font-bold text-white">Nur Stapler</p>
          <p class="text-xs text-white/40"><?= $staplerCount ?> Einträge</p>
        </div>
        <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
      </a>
      <a href="?export=anfragen" class="flex items-center justify-between rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 px-5 py-4 transition-colors">
        <div>
          <p class="text-sm font-bold text-white">Nur Anfragen</p>
          <p class="text-xs text-white/40"><?= $anfragenCount ?> Einträge</p>
        </div>
        <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
      </a>
    </div>
  </div>

  <!-- Import -->
  <div class="rounded-2xl bg-[#161922] border border-white/5 p-6">
    <h2 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-2">IMPORT</h2>
    <p class="text-xs text-white/30 mb-5">Lädt Daten aus einer zuvor exportierten Backup-Datei. Vorhandene Daten werden überschrieben. Das Passwort wird nicht importiert.</p>
    <form method="post" enctype="multipart/form-data" class="space-y-4" onsubmit="return confirm('Achtung: Vorhandene Daten werden überschrieben! Fortfahren?')">
      <div>
        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Backup-Datei (.json)</label>
        <input type="file" name="backup_file" accept=".json" required
               class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white/60 file:mr-4 file:rounded-lg file:border-0 file:bg-white/10 file:text-white file:text-xs file:font-bold file:uppercase file:px-3 file:py-1.5 file:cursor-pointer">
      </div>
      <button type="submit" class="bg-white/10 hover:bg-white/20 transition-colors px-6 py-2.5 rounded-xl text-sm font-bold text-white uppercase tracking-widest">
        Import starten
      </button>
    </form>
  </div>
</div>
<?php
$content = ob_get_clean();
dashboard_head('Backup');
dashboard_layout('Backup', $content);
