<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/data.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/dashboard-layout.php';
require_login();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = $_POST['id'] ?? '';
    if ($id) {
        if ($action === 'delete') {
            delete_inquiry($id);
        } elseif ($action === 'status') {
            $inq = null;
            foreach (get_inquiries() as $q) { if ($q['id'] === $id) { $inq = $q; break; } }
            if ($inq) {
                $inq['status'] = $_POST['status'] ?? $inq['status'];
                save_inquiry($inq);
            }
        } elseif ($action === 'note') {
            $note = trim($_POST['note'] ?? '');
            if ($note) {
                $inq = null;
                foreach (get_inquiries() as $q) { if ($q['id'] === $id) { $inq = $q; break; } }
                if ($inq) {
                    $inq['notes'][] = date('d.m.Y H:i') . ': ' . $note;
                    save_inquiry($inq);
                }
            }
        }
    }
    header('Location: /dashboard/anfragen.php' . ($_GET['filter'] ? '?filter=' . urlencode($_GET['filter']) : ''));
    exit;
}

$filter   = $_GET['filter'] ?? 'Alle';
$inquiries = get_inquiries();
if ($filter !== 'Alle') {
    $inquiries = array_filter($inquiries, fn($q) =>
        $q['status'] === $filter || ($filter === 'Ankauf' && $q['type'] === 'Ankauf'));
}
$tabs = ['Alle', 'Neu', 'Bearbeitet', 'Abgeschlossen', 'Ankauf'];

ob_start();
?>
<div class="space-y-6">
  <h1 class="text-2xl font-black text-white uppercase tracking-tight">Anfragen</h1>

  <!-- Tabs -->
  <div class="flex flex-wrap gap-2">
    <?php foreach ($tabs as $tab):
      $active = $filter === $tab;
    ?>
    <a href="?filter=<?= urlencode($tab) ?>"
       class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-colors <?= $active ? 'bg-[#e94e1b] text-white' : 'bg-[#161922] border border-white/10 text-white/50 hover:text-white' ?>">
      <?= $tab ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- List -->
  <?php if (empty($inquiries)): ?>
  <div class="rounded-2xl bg-[#161922] border border-white/5 py-16 text-center text-white/30">
    <p class="text-sm">Keine Anfragen gefunden.</p>
  </div>
  <?php else: ?>
  <div class="space-y-3" x-data="{open:null}">
    <?php foreach ($inquiries as $q):
      $badgeColor = match($q['status']) {
        'Neu'        => 'text-orange-400 bg-orange-400/10 border-orange-400/30',
        'Bearbeitet' => 'text-blue-400 bg-blue-400/10 border-blue-400/30',
        default      => 'text-green-400 bg-green-400/10 border-green-400/30',
      };
      $qid = htmlspecialchars($q['id']);
    ?>
    <div class="rounded-2xl bg-[#161922] border border-white/5 overflow-hidden">
      <!-- Header row -->
      <div class="flex items-center gap-3 px-5 py-4 cursor-pointer hover:bg-white/[0.02] transition-colors"
           @click="open = open === '<?= $qid ?>' ? null : '<?= $qid ?>'">
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center gap-2 mb-1">
            <span class="font-bold text-white/90 text-sm"><?= htmlspecialchars($q['customerName']) ?></span>
            <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded border <?= $badgeColor ?>"><?= $q['status'] ?></span>
            <span class="text-[10px] font-bold uppercase tracking-widest text-white/30"><?= $q['type'] ?></span>
          </div>
          <div class="flex flex-wrap gap-3 text-xs text-white/40">
            <span><?= date('d.m.Y H:i', strtotime($q['createdAt'])) ?></span>
            <span><?= htmlspecialchars($q['customerEmail']) ?></span>
            <?php if (!empty($q['customerPhone'])): ?><span><?= htmlspecialchars($q['customerPhone']) ?></span><?php endif; ?>
            <?php if (!empty($q['staplerTitle'])): ?><span class="text-[#e94e1b]"><?= htmlspecialchars($q['staplerTitle']) ?></span><?php endif; ?>
          </div>
        </div>
        <svg class="w-4 h-4 text-white/30 transition-transform flex-shrink-0" :class="open==='<?= $qid ?>'?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </div>

      <!-- Expanded -->
      <div x-show="open==='<?= $qid ?>'" x-cloak class="px-5 pb-5 border-t border-white/5">
        <div class="pt-4 space-y-4">
          <!-- Message -->
          <div>
            <p class="text-xs font-bold uppercase tracking-widest text-white/30 mb-2">NACHRICHT</p>
            <p class="text-sm text-white/70 whitespace-pre-wrap"><?= htmlspecialchars($q['message'] ?? '') ?></p>
          </div>

          <?php if (!empty($q['ankaufData']) && array_filter($q['ankaufData'])): ?>
          <!-- Ankauf data -->
          <div>
            <p class="text-xs font-bold uppercase tracking-widest text-white/30 mb-2">ANKAUF-DETAILS</p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
              <?php foreach (['staplerType'=>'Typ','brand'=>'Marke','yearBuilt'=>'Baujahr','operatingHours'=>'Stunden','condition'=>'Zustand'] as $k=>$l): ?>
              <?php if (!empty($q['ankaufData'][$k])): ?>
              <div class="bg-white/5 rounded-lg px-3 py-2">
                <span class="text-white/30"><?= $l ?>:</span>
                <span class="ml-1 text-white/80"><?= htmlspecialchars($q['ankaufData'][$k]) ?></span>
              </div>
              <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Notes -->
          <?php if (!empty($q['notes'])): ?>
          <div>
            <p class="text-xs font-bold uppercase tracking-widest text-white/30 mb-2">NOTIZEN</p>
            <div class="space-y-1">
              <?php foreach ($q['notes'] as $note): ?>
              <p class="text-xs text-white/50 bg-white/5 rounded-lg px-3 py-2"><?= htmlspecialchars($note) ?></p>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Actions -->
          <div class="flex flex-wrap gap-3 pt-2">
            <!-- Status -->
            <form method="post" class="flex gap-2">
              <input type="hidden" name="action" value="status">
              <input type="hidden" name="id" value="<?= $qid ?>">
              <select name="status" class="rounded-lg bg-[#0f1117] border border-white/10 px-3 py-2 text-xs text-white outline-none">
                <?php foreach (['Neu','Bearbeitet','Abgeschlossen'] as $st): ?>
                <option value="<?= $st ?>" <?= $q['status']===$st?'selected':'' ?>><?= $st ?></option>
                <?php endforeach; ?>
              </select>
              <button type="submit" class="rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 px-3 py-2 text-xs text-white/70 hover:text-white transition-colors">Status setzen</button>
            </form>

            <!-- Note -->
            <form method="post" class="flex gap-2 flex-1 min-w-[200px]">
              <input type="hidden" name="action" value="note">
              <input type="hidden" name="id" value="<?= $qid ?>">
              <input type="text" name="note" placeholder="Notiz hinzufügen..."
                     class="flex-1 rounded-lg bg-[#0f1117] border border-white/10 px-3 py-2 text-xs text-white outline-none focus:border-[#e94e1b]">
              <button type="submit" class="rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 px-3 py-2 text-xs text-white/70 hover:text-white transition-colors">+</button>
            </form>

            <!-- Delete -->
            <form method="post" onsubmit="return confirm('Anfrage wirklich löschen?')">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $qid ?>">
              <button type="submit" class="rounded-lg bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 px-3 py-2 text-xs text-red-400 transition-colors">Löschen</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
dashboard_head('Anfragen');
dashboard_layout('Anfragen', $content);
