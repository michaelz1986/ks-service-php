<?php
function render_header(string $active = ''): void {
    $s = get_settings();
    $email = htmlspecialchars($s['email']);
    $phone = htmlspecialchars($s['phone']);
    $hours = htmlspecialchars($s['openingHours']);
    $phoneRaw = preg_replace('/\s+/', '', $s['phone']);
    $nav = [
        ['/', 'HOME'],
        ['/service.php', 'SERVICE'],
        ['/shop.php', 'SHOP'],
        ['/ankauf.php', 'ANKAUF'],
        ['/kontakt.php', 'KONTAKT'],
        ['https://www.kssales.at/', 'PHOTOVOLTAIK-SHOP', true],
    ];
    echo <<<HTML
<header class="sticky top-0 z-50 shadow-sm" x-data="{open:false,q:''}">

  <!-- Info bar -->
  <div class="hidden border-b border-gray-200 bg-white sm:block">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2 text-[11px] text-gray-600 md:px-6">
      <div class="flex items-center gap-6">
        <a href="mailto:{$email}" class="hover:text-brand transition-colors">E-MAIL: {$email}</a>
        <a href="tel:{$phoneRaw}" class="hover:text-brand transition-colors">TEL: {$phone}</a>
      </div>
      <span>ÖFFNUNGSZEITEN {$hours}</span>
    </div>
  </div>

  <!-- Logo + Search -->
  <div class="border-b border-gray-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center gap-6 px-4 py-3 md:px-6">
      <a href="/" class="shrink-0">
        <img src="/assets/images/cropped-ks-service-logo-orange.webp" alt="KS Service" class="h-12 w-auto object-contain md:h-14">
      </a>
      <form action="/shop.php" method="get" class="mx-auto hidden max-w-lg flex-1 lg:flex">
        <div class="flex w-full border border-gray-300 focus-within:border-brand transition-colors">
          <input type="search" name="q" placeholder="Produkte suchen ..." class="flex-1 px-4 py-2.5 text-sm text-gray-700 outline-none bg-white">
          <button type="submit" class="bg-brand px-4 text-white hover:bg-orange-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </button>
        </div>
      </form>
      <button @click="open=!open" class="ml-auto rounded p-2 text-gray-700 hover:bg-gray-100 lg:hidden" aria-label="Menü">
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
  </div>

  <!-- Desktop Nav -->
  <nav class="hidden bg-dark lg:block">
    <div class="mx-auto flex max-w-7xl items-center px-4 md:px-6">
HTML;
    foreach ($nav as $item) {
        [$href, $label] = $item;
        $ext = !empty($item[2]);
        $isActive = ($active === $label);
        $cls = 'relative inline-flex items-center px-6 py-4 text-xs font-bold uppercase tracking-widest transition-colors whitespace-nowrap ' .
               ($isActive ? 'text-brand' : 'text-white hover:text-brand');
        if ($ext) {
            echo "<a href=\"{$href}\" target=\"_blank\" rel=\"noopener noreferrer\" class=\"{$cls}\">{$label}</a>";
        } else {
            echo "<a href=\"{$href}\" class=\"{$cls}\">{$label}";
            if ($isActive) echo '<span class="absolute inset-x-6 bottom-0 h-[2px] bg-brand"></span>';
            echo "</a>";
        }
    }
    echo <<<HTML
    </div>
  </nav>

  <!-- Mobile menu -->
  <div x-show="open" x-cloak x-transition class="overflow-hidden border-t border-gray-200 bg-white lg:hidden">
    <form action="/shop.php" method="get" class="flex border-b border-gray-200">
      <input type="search" name="q" placeholder="Produkte suchen ..." class="flex-1 px-4 py-3 text-sm text-gray-700 outline-none">
      <button type="submit" class="bg-brand px-4 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </button>
    </form>
    <div class="bg-dark">
HTML;
    foreach ($nav as $item) {
        [$href, $label] = $item;
        $ext = !empty($item[2]);
        $isActive = ($active === $label);
        $cls = 'block border-b border-white/10 px-4 py-3.5 text-sm font-bold uppercase tracking-widest last:border-0 ' .
               ($isActive ? 'text-brand' : 'text-white');
        if ($ext) {
            echo "<a href=\"{$href}\" target=\"_blank\" rel=\"noopener noreferrer\" @click=\"open=false\" class=\"{$cls}\">{$label}</a>";
        } else {
            echo "<a href=\"{$href}\" @click=\"open=false\" class=\"{$cls}\">{$label}</a>";
        }
    }
    echo <<<HTML
    </div>
    <div class="border-t border-gray-100 px-4 py-3 text-xs text-gray-500">
      <a href="mailto:{$email}" class="block py-1">{$email}</a>
      <a href="tel:{$phoneRaw}" class="block py-1">{$phone}</a>
    </div>
  </div>

</header>
HTML;
}
