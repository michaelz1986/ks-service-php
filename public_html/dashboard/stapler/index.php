<?php
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/data.php';
require_once dirname(dirname(__DIR__)) . '/includes/auth.php';
require_once dirname(dirname(__DIR__)) . '/includes/dashboard-layout.php';
require_login();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = $_POST['id'] ?? '';
    if ($id) delete_stapler($id);
    header('Location: /dashboard/stapler/');
    exit;
}

$staplers = get_staplers();
$search   = trim($_GET['q'] ?? '');
if ($search) {
    $staplers = array_filter($staplers, fn($s) =>
        stripos($s['title'] . ' ' . $s['brand'], $search) !== false);
}

ob_start();
?>
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-black text-white uppercase tracking-tight">Stapler</h1>
    <a href="/dashboard/stapler/new.php" class="flex items-center gap-2 bg-[#e94e1b] hover:bg-orange-700 transition-colors px-4 py-2.5 rounded-xl text-sm font-bold text-white uppercase tracking-widest">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Neu
    </a>
  </div>

  <!-- Search -->
  <form method="get" class="flex gap-3">
    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Suche..."
           class="flex-1 rounded-xl bg-[#161922] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
    <button type="submit" class="bg-[#e94e1b] hover:bg-orange-700 px-4 rounded-xl text-sm font-bold text-white uppercase transition-colors">Suchen</button>
    <?php if ($search): ?><a href="/dashboard/stapler/" class="rounded-xl border border-white/10 px-4 py-2.5 text-sm text-white/60 hover:text-white hover:border-white/30 transition-colors">Reset</a><?php endif; ?>
  </form>

  <!-- Table -->
  <div class="rounded-2xl bg-[#161922] border border-white/5 overflow-hidden">
    <?php if (empty($staplers)): ?>
    <div class="py-16 text-center text-white/30">
      <p class="text-sm">Noch keine Stapler eingetragen.</p>
      <a href="/dashboard/stapler/new.php" class="mt-3 inline-block text-[#e94e1b] hover:underline text-sm">Ersten Stapler anlegen</a>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-white/5 text-left">
            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-widest text-white/40">Bild</th>
            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-widest text-white/40">Titel</th>
            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-widest text-white/40 hidden md:table-cell">Marke</th>
            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-widest text-white/40">Preis</th>
            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-widest text-white/40">Status</th>
            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-widest text-white/40">Aktionen</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          <?php foreach ($staplers as $s):
            $badge = match($s['status']) {
              'Verfügbar' => 'text-green-400 bg-green-400/10 border border-green-400/30',
              'Reserviert'=> 'text-blue-400 bg-blue-400/10 border border-blue-400/30',
              'Verkauft'  => 'text-gray-400 bg-gray-400/10 border border-gray-400/30',
              default     => 'text-yellow-400 bg-yellow-400/10 border border-yellow-400/30',
            };
          ?>
          <tr class="hover:bg-white/[0.02] transition-colors">
            <td class="px-4 py-3">
              <div class="w-12 h-12 rounded overflow-hidden bg-white/5 flex-shrink-0">
                <img src="<?= htmlspecialchars($s['images'][0] ?? '/assets/images/logo.png') ?>" class="w-full h-full object-cover" alt="">
              </div>
            </td>
            <td class="px-4 py-3">
              <span class="font-semibold text-white/90"><?= htmlspecialchars($s['title']) ?></span>
            </td>
            <td class="px-4 py-3 text-white/50 hidden md:table-cell"><?= htmlspecialchars($s['brand']) ?></td>
            <td class="px-4 py-3 font-bold text-[#e94e1b]">€ <?= number_format($s['price'], 0, ',', '.') ?></td>
            <td class="px-4 py-3">
              <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded <?= $badge ?>"><?= $s['status'] ?></span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="/dashboard/stapler/edit.php?id=<?= urlencode($s['id']) ?>"
                   class="rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 px-3 py-1.5 text-xs text-white/70 hover:text-white transition-colors">Bearbeiten</a>
                <form method="post" onsubmit="return confirm('Wirklich löschen?')">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= htmlspecialchars($s['id']) ?>">
                  <button type="submit" class="rounded-lg bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 px-3 py-1.5 text-xs text-red-400 transition-colors">Löschen</button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php
$content = ob_get_clean();
dashboard_head('Stapler');
dashboard_layout('Stapler', $content);
