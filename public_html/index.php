<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$settings = get_settings();
$staplers = array_filter(get_staplers(), fn($s) => $s['status'] === 'Verfügbar');
$staplers = array_slice(array_values($staplers), 0, 4);

head_open();
?>
<body class="bg-white text-[#1a1a1a]">
<?php render_header('HOME'); ?>

<!-- HERO -->
<section class="relative min-h-screen w-full overflow-hidden">
  <img src="/assets/images/design/banner-desktop-final-2.webp" class="hidden md:block absolute inset-0 w-full h-full object-cover object-center" alt="KS Service Hero">
  <img src="/assets/images/design/banner-mobil-final-eins.webp" class="block md:hidden absolute inset-0 w-full h-full object-cover object-center" alt="KS Service Hero">
  <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/15"></div>
  <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent"></div>
  <div class="relative z-10 flex h-full min-h-screen items-center px-8 md:px-16 lg:px-24">
    <div class="max-w-[520px]">
      <div class="mb-7">
        <img src="/assets/images/cropped-ks-service-logo-orange.webp" alt="KS Service" class="w-[220px] md:w-[300px] lg:w-[360px] h-auto">
      </div>
      <div class="mb-9 flex flex-col gap-1">
        <p class="text-[13px] font-bold uppercase tracking-[0.15em] text-white md:text-[15px]">SERVICE – ÜBERPRÜFUNG – REPARATUR</p>
        <p class="text-[13px] font-bold uppercase tracking-[0.15em] text-white md:text-[15px]">AN- &amp; VERKAUF VON STAPLERN</p>
        <p class="text-[13px] font-bold uppercase tracking-[0.15em] text-white md:text-[15px]">STAPLERVERMIETUNG</p>
      </div>
      <a href="/shop.php" class="inline-block border-2 border-[#e94e1b] px-10 py-4 text-sm font-bold uppercase tracking-[0.18em] text-white transition hover:bg-[#e94e1b] md:px-14">
        ZU DEN STAPLERN
      </a>
    </div>
  </div>
  <div class="absolute bottom-0 left-0 right-0 h-[3px] z-10 bg-[#e94e1b]"></div>
</section>

<!-- LEISTUNGEN -->
<section class="bg-[#f5f5f5] py-20">
  <div class="mx-auto max-w-7xl px-4 md:px-6">
    <div class="mb-12">
      <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.2em] text-[#e94e1b]">WAS WIR TUN</p>
      <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight">UNSERE LEISTUNGEN</h2>
      <div class="mt-3 h-1 w-14 bg-[#e94e1b]"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php
      $cards = [
        ['SERVICE', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0', 'Volle Unterstützung rund um Ihren Stapler in Kärnten. Wartung, Inspektion und Ersatzteile.'],
        ['REPARATUR', 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'Ein defekter Stapler kann den Arbeitsfluss maßgeblich stören. Schnelle Hilfe garantiert.'],
        ['ÜBERPRÜFUNG', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'Wir überprüfen jede Art von Stapler für Sie. UVV-Prüfung nach aktuellen Vorgaben.'],
        ['KAUF UND MIETE', 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'Full-Service rund um Ihren Stapler in Kärnten. Kauf, Verkauf und Vermietung.'],
      ];
      foreach ($cards as [$title, $icon, $text]):
      ?>
      <div class="bg-white p-8 text-center hover:shadow-md transition-shadow">
        <h3 class="mb-4 text-base font-black uppercase tracking-widest text-[#e94e1b]"><?= $title ?></h3>
        <div class="mb-5 flex justify-center">
          <div class="flex h-14 w-14 items-center justify-center rounded-full border-2 border-[#e94e1b]">
            <svg class="w-6 h-6 text-[#e94e1b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/></svg>
          </div>
        </div>
        <p class="text-sm leading-relaxed text-[#666]"><?= $text ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TOP GERÄTE -->
<section class="bg-white py-20">
  <div class="mx-auto max-w-7xl px-4 md:px-6">
    <div class="mb-12">
      <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.2em] text-[#e94e1b]">VERFÜGBAR</p>
      <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight">TOP GERÄTE AUF LAGER</h2>
      <div class="mt-3 h-1 w-14 bg-[#e94e1b]"></div>
    </div>
    <?php if (empty($staplers)): ?>
      <p class="text-sm text-[#888]">Aktuell keine Geräte verfügbar.</p>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($staplers as $s): ?>
      <a href="/stapler.php?id=<?= urlencode($s['id']) ?>" class="group block border border-[#e0e0e0] hover:border-[#e94e1b] transition-colors">
        <div class="relative aspect-square overflow-hidden bg-[#f5f5f5]">
          <img src="<?= htmlspecialchars($s['images'][0] ?? '/assets/images/logo.png') ?>" alt="<?= htmlspecialchars($s['title']) ?>"
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        </div>
        <div class="p-4">
          <h3 class="mb-1 text-sm font-bold uppercase tracking-wide line-clamp-2"><?= htmlspecialchars($s['title']) ?></h3>
          <p class="text-xl font-black text-[#e94e1b]">€ <?= number_format($s['price'], 0, ',', '.') ?></p>
          <p class="text-[11px] text-[#888]">exkl. MwSt.</p>
          <div class="mt-3 bg-[#1a1a1a] py-2 text-center text-[11px] font-bold uppercase tracking-widest text-white transition group-hover:bg-[#e94e1b]">ZUM GERÄT</div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="mt-10 text-center">
      <a href="/shop.php" class="inline-block border-2 border-[#1a1a1a] px-10 py-3.5 text-sm font-bold uppercase tracking-widest transition hover:bg-[#1a1a1a] hover:text-white">ALLE GERÄTE ANSEHEN</a>
    </div>
  </div>
</section>

<!-- CTA BANNER -->
<section class="relative overflow-hidden h-[320px] md:h-[400px]">
  <img src="/assets/images/design/banner-stapler-frau.jpg" class="absolute inset-0 w-full h-full object-cover" alt="">
  <div class="absolute inset-0 bg-black/65"></div>
  <div class="relative z-10 mx-auto flex max-w-7xl items-center justify-end px-8 h-full md:px-16">
    <div class="max-w-md text-right">
      <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.2em] text-[#e94e1b]">AN- &amp; VERKAUF</p>
      <h2 class="mb-4 text-3xl font-black uppercase leading-tight text-white md:text-4xl">GERÄT KAUFEN<br>ODER VERKAUFEN?</h2>
      <p class="mb-6 text-sm leading-relaxed text-white/70">Binnen 12 Stunden erhalten Sie Ihr persönliches Angebot.</p>
      <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
        <a href="/shop.php" class="border-2 border-white px-7 py-3 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-white hover:text-[#1a1a1a]">KAUFEN</a>
        <a href="/ankauf.php" class="border-2 border-[#e94e1b] bg-[#e94e1b] px-7 py-3 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-orange-700">VERKAUFEN</a>
      </div>
    </div>
  </div>
</section>

<!-- PV SECTION -->
<section class="bg-[#f5f5f5] py-20">
  <div class="mx-auto max-w-7xl px-4 md:px-6">
    <div class="mb-12">
      <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.2em] text-[#e94e1b]">ENERGIE</p>
      <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight">PHOTOVOLTAIK SHOP</h2>
      <div class="mt-3 h-1 w-14 bg-[#e94e1b]"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
      <?php foreach ([['PV-MODULE','pv-module.webp'],['PV-SPEICHER','pv-speicher.webp'],['WECHSELRICHTER','wechselrichter.webp']] as [$t,$img]): ?>
      <a href="https://www.kssales.at/" target="_blank" rel="noopener noreferrer" class="group relative overflow-hidden aspect-[4/3] block">
        <img src="/assets/images/design/<?= $img ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="<?= $t ?>">
        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/55 transition-colors"></div>
        <div class="absolute inset-0 flex items-end p-6">
          <span class="text-sm font-black uppercase tracking-widest text-white"><?= $t ?></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <div class="mt-10 text-center">
      <a href="https://www.kssales.at/" target="_blank" rel="noopener noreferrer"
         class="inline-block border-2 border-[#e94e1b] px-10 py-3.5 text-sm font-bold uppercase tracking-widest text-[#e94e1b] transition hover:bg-[#e94e1b] hover:text-white">
        ZUM PV-SHOP
      </a>
    </div>
  </div>
</section>

<?php render_footer(); ?>
