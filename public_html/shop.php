<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$q        = trim($_GET['q'] ?? '');
$category = $_GET['cat'] ?? '';
$drive    = $_GET['drive'] ?? '';

$all = get_staplers();
$filtered = array_filter($all, function($s) use ($q, $category, $drive) {
    if ($s['status'] === 'Entwurf') return false;
    if ($q && stripos($s['title'] . ' ' . $s['brand'] . ' ' . ($s['model'] ?? '') . ' ' . $s['description'], $q) === false) return false;
    if ($category && $s['category'] !== $category) return false;
    if ($drive && $s['driveType'] !== $drive) return false;
    return true;
});
$filtered = array_values($filtered);

$categories = ['Elektrostapler','Dieselstapler','Hubwagen','Andere'];
$drives     = ['Elektro','Diesel','Gas','Gas/Treibgas'];

head_open('Stapler Shop — KS Service');
?>
<body class="bg-white text-[#1a1a1a]">
<?php render_header('SHOP'); ?>

<!-- HERO -->
<section class="relative h-[320px] overflow-hidden md:h-[400px]">
  <img src="/assets/images/design/hero-lamborghini.jpg" class="absolute inset-0 w-full h-full object-cover object-center" alt="Stapler Shop">
  <div class="absolute inset-0 bg-gradient-to-r from-black/88 via-black/60 to-black/20"></div>
  <div class="relative z-10 flex h-full flex-col justify-center px-10 md:px-20">
    <div class="max-w-lg">
      <div class="mb-7"><img src="/assets/images/cropped-ks-service-logo-orange.webp" alt="KS Service" class="w-[160px] md:w-[200px] h-auto"></div>
      <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.3em] text-[#e94e1b]">Geräte &amp; Ersatzteile</p>
      <h1 class="text-4xl font-black uppercase leading-tight tracking-tight text-white md:text-6xl">STAPLER<br><span class="text-white/80">SHOP</span></h1>
    </div>
  </div>
  <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-[#e94e1b]"></div>
</section>

<!-- FILTER + RESULTS -->
<section class="py-12 bg-[#f5f5f5]" id="shop-list">
  <div class="mx-auto max-w-7xl px-4 md:px-6">

    <!-- Filter bar -->
    <form method="get" class="mb-8 flex flex-wrap gap-3 items-end">
      <div class="flex-1 min-w-[200px]">
        <label class="block mb-1 text-[11px] font-bold uppercase tracking-wider text-[#666]">Suche</label>
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Marke, Modell ..."
               class="w-full border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#e94e1b] transition-colors bg-white">
      </div>
      <div>
        <label class="block mb-1 text-[11px] font-bold uppercase tracking-wider text-[#666]">Kategorie</label>
        <select name="cat" class="border border-gray-300 px-3 py-2.5 text-sm bg-white outline-none focus:border-[#e94e1b]">
          <option value="">Alle</option>
          <?php foreach ($categories as $c): ?>
          <option value="<?= $c ?>" <?= $category===$c?'selected':'' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block mb-1 text-[11px] font-bold uppercase tracking-wider text-[#666]">Antrieb</label>
        <select name="drive" class="border border-gray-300 px-3 py-2.5 text-sm bg-white outline-none focus:border-[#e94e1b]">
          <option value="">Alle</option>
          <?php foreach ($drives as $d): ?>
          <option value="<?= $d ?>" <?= $drive===$d?'selected':'' ?>><?= $d ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="bg-[#e94e1b] hover:bg-orange-700 transition-colors px-6 py-2.5 text-sm font-bold uppercase tracking-widest text-white">FILTERN</button>
      <?php if ($q || $category || $drive): ?>
      <a href="/shop.php" class="border border-gray-300 px-6 py-2.5 text-sm font-bold uppercase tracking-widest text-[#666] hover:bg-gray-100 transition-colors">ZURÜCKSETZEN</a>
      <?php endif; ?>
    </form>

    <p class="mb-6 text-sm text-[#888]"><?= count($filtered) ?> Gerät<?= count($filtered)!==1?'e':'' ?> gefunden</p>

    <?php if (empty($filtered)): ?>
      <div class="py-20 text-center">
        <p class="text-lg font-bold text-[#666]">Keine Geräte gefunden.</p>
        <a href="/shop.php" class="mt-4 inline-block text-sm text-[#e94e1b] hover:underline">Alle anzeigen</a>
      </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <?php foreach ($filtered as $s):
        $badge = match($s['status']) {
          'Verfügbar' => 'bg-green-100 text-green-700',
          'Reserviert' => 'bg-blue-100 text-blue-700',
          'Verkauft'  => 'bg-gray-100 text-gray-500',
          default     => 'bg-gray-100 text-gray-500',
        };
      ?>
      <a href="/stapler.php?id=<?= urlencode($s['id']) ?>" class="group block border border-[#e0e0e0] hover:border-[#e94e1b] transition-colors bg-white">
        <div class="relative aspect-square overflow-hidden bg-[#f5f5f5]">
          <img src="<?= htmlspecialchars($s['images'][0] ?? '/assets/images/logo.png') ?>"
               alt="<?= htmlspecialchars($s['title']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
          <span class="absolute top-3 left-3 <?= $badge ?> px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide rounded"><?= $s['status'] ?></span>
        </div>
        <div class="p-4">
          <p class="text-[10px] font-bold uppercase tracking-widest text-[#888] mb-1"><?= htmlspecialchars($s['brand']) ?></p>
          <h3 class="mb-1 text-sm font-bold uppercase tracking-wide line-clamp-2"><?= htmlspecialchars($s['title']) ?></h3>
          <p class="text-xl font-black text-[#e94e1b]">€ <?= number_format($s['price'], 0, ',', '.') ?></p>
          <p class="text-[11px] text-[#888]">exkl. MwSt.</p>
          <div class="mt-3 bg-[#1a1a1a] py-2 text-center text-[11px] font-bold uppercase tracking-widest text-white transition group-hover:bg-[#e94e1b]">ZUM GERÄT</div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php render_footer(); ?>
