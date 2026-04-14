<?php
function render_footer(): void {
    $s = get_settings();
    $year = date('Y');
    $name = htmlspecialchars($s['companyName']);
    $email = htmlspecialchars($s['email']);
    $phone = htmlspecialchars($s['phone']);
    $phoneRaw = preg_replace('/\s+/', '', $s['phone']);
    $address = nl2br(htmlspecialchars($s['address']));
    $hours = htmlspecialchars($s['openingHours']);
    echo <<<HTML
<footer class="bg-dark text-white">
  <div class="border-t-4 border-brand"></div>
  <div class="mx-auto max-w-7xl px-4 py-12 md:px-6 lg:py-16">
    <div class="grid gap-8 md:grid-cols-3">
      <div>
        <img src="/assets/images/cropped-ks-service-logo-orange.webp" alt="KS Service" class="h-12 w-auto mb-4">
        <p class="text-sm text-white/60 leading-relaxed">Ihr Partner für Stapler, Flurförderzeuge und Photovoltaik in Kärnten.</p>
      </div>
      <div>
        <h3 class="text-xs font-bold uppercase tracking-widest text-brand mb-4">KONTAKT</h3>
        <div class="space-y-2 text-sm text-white/70">
          <div>{$address}</div>
          <a href="tel:{$phoneRaw}" class="block hover:text-brand transition-colors">{$phone}</a>
          <a href="mailto:{$email}" class="block hover:text-brand transition-colors">{$email}</a>
        </div>
      </div>
      <div>
        <h3 class="text-xs font-bold uppercase tracking-widest text-brand mb-4">ÖFFNUNGSZEITEN</h3>
        <p class="text-sm text-white/70">{$hours}</p>
        <div class="mt-6 flex gap-3">
          <img src="/assets/images/meisterbetrieb-badge.png" alt="Meisterbetrieb" class="h-14 w-auto object-contain opacity-80">
        </div>
      </div>
    </div>
    <div class="mt-10 border-t border-white/10 pt-6 text-center text-xs text-white/40">
      &copy; {$year} {$name} &mdash; Alle Rechte vorbehalten.
    </div>
  </div>
</footer>
</body></html>
HTML;
}
