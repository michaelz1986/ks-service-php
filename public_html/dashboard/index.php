<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/data.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/dashboard-layout.php';
require_login();

$staplers  = get_staplers();
$inquiries = get_inquiries();

$totalStapler    = count($staplers);
$available       = count(array_filter($staplers, fn($s) => $s['status'] === 'Verfügbar'));
$newInquiries    = count(array_filter($inquiries, fn($q) => $q['status'] === 'Neu'));
$totalInquiries  = count($inquiries);

// Chart: last 6 months inquiry counts
$months = [];
for ($i = 5; $i >= 0; $i--) {
    $ts = strtotime("-{$i} months");
    $key = date('Y-m', $ts);
    $label = ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'][date('n', $ts) - 1];
    $count = count(array_filter($inquiries, fn($q) => str_starts_with($q['createdAt'], $key)));
    $months[] = ['label' => $label, 'count' => $count];
}
$maxCount = max(1, max(array_column($months, 'count')));

$recentInquiries = array_slice($inquiries, 0, 5);

ob_start();
?>
<div class="space-y-6">

  <!-- Stats -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <?php
    $stats = [
      ['Stapler gesamt', $totalStapler, '#e94e1b', 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
      ['Verfügbar', $available, '#10b981', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0'],
      ['Neue Anfragen', $newInquiries, '#f59e0b', 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
      ['Anfragen gesamt', $totalInquiries, '#6366f1', 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
    ];
    foreach ($stats as [$label, $value, $color, $icon]):
    ?>
    <div class="rounded-2xl bg-[#161922] border border-white/5 p-5">
      <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-bold uppercase tracking-widest text-white/40"><?= $label ?></span>
        <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background:<?= $color ?>22">
          <svg class="w-4 h-4" style="color:<?= $color ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
          </svg>
        </div>
      </div>
      <p class="text-3xl font-black" style="color:<?= $color ?>"><?= $value ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Chart -->
    <div class="rounded-2xl bg-[#161922] border border-white/5 p-6">
      <h2 class="text-sm font-bold uppercase tracking-widest text-white/60 mb-6">ANFRAGEN LETZTE 6 MONATE</h2>
      <div class="flex items-end gap-3 h-40">
        <?php foreach ($months as $m): $pct = ($m['count'] / $maxCount) * 100; ?>
        <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
          <span class="text-[11px] text-white/40"><?= $m['count'] ?></span>
          <div class="w-full rounded-t transition-all" style="height:<?= max(4, $pct) ?>%;background:linear-gradient(180deg,#ff7043 0%,#e94e1b 100%)"></div>
          <span class="text-[11px] text-white/40"><?= $m['label'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Recent inquiries -->
    <div class="rounded-2xl bg-[#161922] border border-white/5 p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-bold uppercase tracking-widest text-white/60">LETZTE ANFRAGEN</h2>
        <a href="/dashboard/anfragen.php" class="text-xs text-[#e94e1b] hover:underline">Alle ansehen</a>
      </div>
      <?php if (empty($recentInquiries)): ?>
      <p class="text-sm text-white/30">Noch keine Anfragen.</p>
      <?php else: ?>
      <div class="space-y-3">
        <?php foreach ($recentInquiries as $q):
          $badgeColor = match($q['status']) {
            'Neu'        => 'text-orange-400 bg-orange-400/10 border-orange-400/30',
            'Bearbeitet' => 'text-blue-400 bg-blue-400/10 border-blue-400/30',
            default      => 'text-green-400 bg-green-400/10 border-green-400/30',
          };
        ?>
        <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
          <div>
            <p class="text-sm font-semibold text-white/80"><?= htmlspecialchars($q['customerName']) ?></p>
            <p class="text-xs text-white/40"><?= htmlspecialchars($q['type']) ?> · <?= date('d.m.Y', strtotime($q['createdAt'])) ?></p>
          </div>
          <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded border <?= $badgeColor ?>"><?= $q['status'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Quick links -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <a href="/dashboard/stapler/new.php" class="flex items-center gap-3 rounded-xl bg-[#e94e1b] hover:bg-orange-700 transition-colors p-4">
      <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      <span class="text-sm font-bold text-white uppercase tracking-wide">Neuer Stapler</span>
    </a>
    <a href="/dashboard/anfragen.php" class="flex items-center gap-3 rounded-xl bg-[#161922] border border-white/5 hover:border-[#e94e1b]/50 transition-colors p-4">
      <svg class="w-5 h-5 text-white/60 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
      <span class="text-sm font-bold text-white/60 uppercase tracking-wide">Anfragen</span>
    </a>
    <a href="/dashboard/einstellungen.php" class="flex items-center gap-3 rounded-xl bg-[#161922] border border-white/5 hover:border-[#e94e1b]/50 transition-colors p-4">
      <svg class="w-5 h-5 text-white/60 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0"/></svg>
      <span class="text-sm font-bold text-white/60 uppercase tracking-wide">Einstellungen</span>
    </a>
    <a href="/dashboard/backup.php" class="flex items-center gap-3 rounded-xl bg-[#161922] border border-white/5 hover:border-[#e94e1b]/50 transition-colors p-4">
      <svg class="w-5 h-5 text-white/60 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
      <span class="text-sm font-bold text-white/60 uppercase tracking-wide">Backup</span>
    </a>
  </div>
</div>
<?php
$content = ob_get_clean();
dashboard_head('Übersicht');
dashboard_layout('Übersicht', $content);
