<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

head_open('Stapler Service — KS Service');
?>
<body class="bg-white text-[#1a1a1a]">
<?php render_header('SERVICE'); ?>

<!-- HERO -->
<section class="relative h-[480px] overflow-hidden md:h-[560px]">
  <img src="/assets/images/design/banner-stapler-ks.jpg" class="absolute inset-0 w-full h-full object-cover object-center" alt="Stapler Service">
  <div class="absolute inset-0 bg-gradient-to-r from-black/88 via-black/60 to-black/20"></div>
  <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent"></div>
  <div class="relative z-10 flex h-full flex-col justify-center px-10 md:px-20">
    <div class="max-w-lg">
      <div class="mb-7"><img src="/assets/images/cropped-ks-service-logo-orange.webp" alt="KS Service" class="w-[160px] md:w-[200px] h-auto"></div>
      <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.3em] text-[#e94e1b]">Professionell &amp; Zuverlässig</p>
      <h1 class="text-4xl font-black uppercase leading-tight tracking-tight text-white md:text-6xl">STAPLER<br><span class="text-white/80">SERVICE</span></h1>
      <p class="mt-4 max-w-md text-sm leading-relaxed text-white/60">Wartung, Reparatur und UVV-Prüfung — schnell, sauber und zu fairen Preisen.</p>
      <a href="/kontakt.php" class="mt-8 inline-block border-2 border-[#e94e1b] px-9 py-3.5 text-sm font-bold uppercase tracking-[0.18em] text-white transition hover:bg-[#e94e1b]">TERMIN ANFRAGEN</a>
    </div>
  </div>
  <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-[#e94e1b]"></div>
</section>

<!-- SERVICE LEISTUNGEN -->
<section class="py-20 bg-[#f5f5f5]">
  <div class="mx-auto max-w-7xl px-4 md:px-6">
    <div class="mb-12">
      <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.2em] text-[#e94e1b]">UNSER ANGEBOT</p>
      <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight">SERVICE LEISTUNGEN</h2>
      <div class="mt-3 h-1 w-14 bg-[#e94e1b]"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      $items = [
        ['Wartung & Inspektion', 'Regelmäßige Wartung verlängert die Lebensdauer Ihres Staplers und verhindert teure Ausfälle.'],
        ['Reparatur', 'Schnelle und fachgerechte Reparatur aller Stapler-Typen — Elektro, Diesel und Gas.'],
        ['UVV-Prüfung', 'Gesetzlich vorgeschriebene Unfallverhütungsvorschriften-Prüfung durch zertifizierte Fachkräfte.'],
        ['Batterieservice', 'Wartung, Pflege und Austausch von Traktionsbatterien für Elektrostapler.'],
        ['Ersatzteile', 'Originale und kompatible Ersatzteile für alle gängigen Marken und Modelle.'],
        ['Notdienst', 'Schnelle Hilfe bei Ausfall — unser Notdienst ist 24/7 für Sie erreichbar.'],
      ];
      foreach ($items as [$title, $text]):
      ?>
      <div class="bg-white p-8 hover:shadow-md transition-shadow">
        <div class="mb-4 h-1 w-10 bg-[#e94e1b]"></div>
        <h3 class="mb-3 text-base font-black uppercase tracking-wide"><?= $title ?></h3>
        <p class="text-sm leading-relaxed text-[#666]"><?= $text ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- SERVICE BILDER -->
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-4 md:px-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <?php foreach (['service-batterie.jpg','service-oelwechsel.jpg','service-reparatur.jpg','service-check.jpg'] as $img): ?>
      <div class="aspect-square overflow-hidden">
        <img src="/assets/images/design/<?= $img ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" alt="Service">
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="bg-[#1a1a1a] py-16">
  <div class="mx-auto max-w-3xl px-4 text-center">
    <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.2em] text-[#e94e1b]">KONTAKT</p>
    <h2 class="mb-6 text-3xl font-black uppercase text-white">TERMIN VEREINBAREN</h2>
    <p class="mb-8 text-sm leading-relaxed text-white/60">Nehmen Sie jetzt Kontakt auf — wir melden uns innerhalb von 24 Stunden bei Ihnen.</p>
    <a href="/kontakt.php" class="inline-block border-2 border-[#e94e1b] bg-[#e94e1b] px-10 py-4 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-orange-700">JETZT ANFRAGEN</a>
  </div>
</section>

<?php render_footer(); ?>
