<?php
// Shared form for new.php and edit.php
// Variables available: $stapler (array or empty), $error (string)
$isEdit = !empty($stapler['id']);
$s = $stapler ?? [];
$specs = $s['specs'] ?? [];
$v = fn($k,$d='') => htmlspecialchars($s[$k] ?? $d);
$sv = fn($k,$d='') => htmlspecialchars($specs[$k] ?? $d);
?>
<div class="space-y-6 max-w-4xl">
  <div class="flex items-center gap-4">
    <a href="/dashboard/stapler/" class="text-white/40 hover:text-white transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="text-2xl font-black text-white uppercase tracking-tight"><?= $isEdit ? 'Stapler bearbeiten' : 'Neuer Stapler' ?></h1>
  </div>

  <?php if ($error): ?>
  <div class="rounded-xl bg-red-900/30 border border-red-500/40 px-4 py-3 text-sm text-red-400"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="space-y-6">

    <!-- Basics -->
    <div class="rounded-2xl bg-[#161922] border border-white/5 p-6 space-y-4">
      <h2 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-2">GRUNDDATEN</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Titel *</label>
          <input type="text" name="title" value="<?= $v('title') ?>" required
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Marke *</label>
          <input type="text" name="brand" value="<?= $v('brand') ?>" required
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Modell</label>
          <input type="text" name="model" value="<?= $v('model') ?>"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Preis (€) *</label>
          <input type="number" name="price" value="<?= $v('price','0') ?>" min="0"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Alter Preis (€)</label>
          <input type="number" name="priceOld" value="<?= $v('priceOld','') ?>" min="0"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Antrieb</label>
          <select name="driveType" class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b]">
            <?php foreach (['Elektro','Diesel','Gas','Gas/Treibgas'] as $opt): ?>
            <option value="<?= $opt ?>" <?= ($s['driveType']??'')===$opt?'selected':'' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Kategorie</label>
          <select name="category" class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b]">
            <?php foreach (['Elektrostapler','Dieselstapler','Hubwagen','Andere'] as $opt): ?>
            <option value="<?= $opt ?>" <?= ($s['category']??'')===$opt?'selected':'' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Status</label>
          <select name="status" class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b]">
            <?php foreach (['Verfügbar','Reserviert','Verkauft','Entwurf'] as $opt): ?>
            <option value="<?= $opt ?>" <?= ($s['status']??'Entwurf')===$opt?'selected':'' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div>
        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Beschreibung</label>
        <textarea name="description" rows="4"
                  class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors resize-none"><?= $v('description') ?></textarea>
      </div>
      <div>
        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Zustand</label>
        <input type="text" name="condition" value="<?= $v('condition') ?>" placeholder="z.B. Generalüberholt, Sehr gut..."
               class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
      </div>
    </div>

    <!-- Specs -->
    <div class="rounded-2xl bg-[#161922] border border-white/5 p-6 space-y-4">
      <h2 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-2">TECHNISCHE DATEN</h2>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <?php
        $specFields = [
          ['liftCapacity','Tragfähigkeit (kg)'],
          ['liftHeight','Hubhöhe (mm)'],
          ['buildHeight','Bauhöhe (mm)'],
          ['yearBuilt','Baujahr'],
          ['operatingHours','Betriebsstunden'],
          ['weight','Eigengewicht (kg)'],
          ['batteryInfo','Batterie'],
          ['tiresFront','Bereifung vorne'],
          ['tiresRear','Bereifung hinten'],
        ];
        foreach ($specFields as [$key, $label]):
        ?>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50"><?= $label ?></label>
          <input type="text" name="<?= $key ?>" value="<?= $sv($key) ?>"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <?php endforeach; ?>
        <div class="col-span-2 md:col-span-3">
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Sonderausstattung</label>
          <input type="text" name="specialEquipment" value="<?= $sv('specialEquipment') ?>"
                 class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white outline-none focus:border-[#e94e1b] transition-colors">
        </div>
      </div>
    </div>

    <!-- Images -->
    <div class="rounded-2xl bg-[#161922] border border-white/5 p-6 space-y-4">
      <h2 class="text-xs font-bold uppercase tracking-widest text-white/40 mb-2">BILDER</h2>

      <?php if (!empty($s['images'])): ?>
      <div class="flex flex-wrap gap-3 mb-4">
        <?php foreach ($s['images'] as $img): ?>
        <div class="relative group">
          <img src="<?= htmlspecialchars($img) ?>" class="w-20 h-20 object-cover rounded-lg border border-white/10" alt="">
          <label class="absolute inset-0 flex items-center justify-center bg-red-900/70 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg cursor-pointer">
            <input type="checkbox" name="remove_images[]" value="<?= htmlspecialchars($img) ?>" class="hidden">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          </label>
        </div>
        <?php endforeach; ?>
      </div>
      <p class="text-xs text-white/30">Hover über Bild und Checkbox aktivieren zum Entfernen</p>
      <?php endif; ?>

      <div>
        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-white/50">Neue Bilder hochladen</label>
        <input type="file" name="images[]" multiple accept="image/*"
               class="w-full rounded-xl bg-[#0f1117] border border-white/10 px-4 py-2.5 text-sm text-white/60 file:mr-4 file:rounded-lg file:border-0 file:bg-[#e94e1b] file:text-white file:text-xs file:font-bold file:uppercase file:px-3 file:py-1.5 file:cursor-pointer">
      </div>
    </div>

    <!-- Submit -->
    <div class="flex gap-3">
      <button type="submit" class="bg-[#e94e1b] hover:bg-orange-700 transition-colors px-8 py-3 rounded-xl text-sm font-bold text-white uppercase tracking-widest">
        <?= $isEdit ? 'Speichern' : 'Anlegen' ?>
      </button>
      <a href="/dashboard/stapler/" class="rounded-xl border border-white/10 px-8 py-3 text-sm font-bold text-white/60 hover:text-white hover:border-white/30 transition-colors">Abbrechen</a>
    </div>
  </form>
</div>
