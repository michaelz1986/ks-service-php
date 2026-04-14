<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/mail.php';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$s    = get_settings();
$sent = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $msg   = trim($_POST['message'] ?? '');
    if ($name && $email && $msg) {
        $inq = [
            'id'           => new_id(),
            'type'         => 'Kontakt',
            'customerName' => $name,
            'customerEmail'=> $email,
            'customerPhone'=> $phone,
            'message'      => $msg,
            'status'       => 'Neu',
            'notes'        => [],
            'createdAt'    => now_iso(),
        ];
        save_inquiry($inq);
        $mailBody = "Neue Kontaktanfrage\n\nName: {$name}\nE-Mail: {$email}\nTelefon: {$phone}\n\nNachricht:\n{$msg}";
        send_notification("Neue Kontaktanfrage von {$name}", $mailBody);
        $sent = true;
    } else {
        $error = 'Bitte alle Pflichtfelder ausfüllen.';
    }
}

$phoneRaw = preg_replace('/\s+/', '', $s['phone']);
head_open('Kontakt — KS Service');
?>
<body class="bg-white text-[#1a1a1a]">
<?php render_header('KONTAKT'); ?>

<!-- HERO -->
<section class="relative h-[480px] overflow-hidden md:h-[560px]">
  <img src="/assets/images/design/April-1.webp" class="absolute inset-0 w-full h-full object-cover object-center" alt="Kontakt">
  <div class="absolute inset-0 bg-gradient-to-r from-black/88 via-black/60 to-black/20"></div>
  <div class="relative z-10 flex h-full flex-col justify-center px-10 md:px-20">
    <div class="max-w-lg">
      <div class="mb-7"><img src="/assets/images/cropped-ks-service-logo-orange.webp" alt="KS Service" class="w-[160px] md:w-[200px] h-auto"></div>
      <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.3em] text-[#e94e1b]">Wir sind für Sie da</p>
      <h1 class="text-4xl font-black uppercase leading-tight tracking-tight text-white md:text-6xl">KONTAKT<br><span class="text-white/80">AUFNEHMEN</span></h1>
      <p class="mt-4 max-w-md text-sm leading-relaxed text-white/60"><?= htmlspecialchars($s['openingHours']) ?> · <?= htmlspecialchars($s['emergencyService']) ?></p>
      <a href="tel:<?= $phoneRaw ?>" class="mt-8 inline-block border-2 border-[#e94e1b] px-9 py-3.5 text-sm font-bold uppercase tracking-[0.18em] text-white transition hover:bg-[#e94e1b]">JETZT ANRUFEN</a>
    </div>
  </div>
  <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-[#e94e1b]"></div>
</section>

<!-- KONTAKT CONTENT -->
<section class="py-20">
  <div class="mx-auto max-w-7xl px-4 md:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

      <!-- Info -->
      <div>
        <div class="mb-8">
          <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.2em] text-[#e94e1b]">SO ERREICHEN SIE UNS</p>
          <h2 class="text-3xl font-black uppercase tracking-tight">KONTAKT</h2>
          <div class="mt-3 h-1 w-14 bg-[#e94e1b]"></div>
        </div>
        <div class="space-y-4">
          <div class="flex gap-4 items-start">
            <div class="mt-1 w-8 h-8 bg-[#e94e1b] flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <div>
              <p class="text-xs font-bold uppercase tracking-widest text-[#888] mb-1">TELEFON</p>
              <a href="tel:<?= $phoneRaw ?>" class="text-lg font-bold hover:text-[#e94e1b] transition-colors"><?= htmlspecialchars($s['phone']) ?></a>
            </div>
          </div>
          <div class="flex gap-4 items-start">
            <div class="mt-1 w-8 h-8 bg-[#e94e1b] flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
              <p class="text-xs font-bold uppercase tracking-widest text-[#888] mb-1">E-MAIL</p>
              <a href="mailto:<?= htmlspecialchars($s['email']) ?>" class="text-lg font-bold hover:text-[#e94e1b] transition-colors"><?= htmlspecialchars($s['email']) ?></a>
            </div>
          </div>
          <div class="flex gap-4 items-start">
            <div class="mt-1 w-8 h-8 bg-[#e94e1b] flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
              <p class="text-xs font-bold uppercase tracking-widest text-[#888] mb-1">ÖFFNUNGSZEITEN</p>
              <p class="text-sm font-semibold"><?= htmlspecialchars($s['openingHours']) ?></p>
              <p class="text-sm text-[#666]"><?= htmlspecialchars($s['emergencyService']) ?></p>
            </div>
          </div>
          <div class="flex gap-4 items-start">
            <div class="mt-1 w-8 h-8 bg-[#e94e1b] flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
              <p class="text-xs font-bold uppercase tracking-widest text-[#888] mb-1">ADRESSE</p>
              <p class="text-sm font-semibold"><?= nl2br(htmlspecialchars($s['address'])) ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Form -->
      <div>
        <div class="mb-8">
          <h2 class="text-3xl font-black uppercase tracking-tight">NACHRICHT SENDEN</h2>
          <div class="mt-3 h-1 w-14 bg-[#e94e1b]"></div>
        </div>
        <?php if ($sent): ?>
        <div class="rounded bg-green-50 border border-green-200 px-6 py-5 text-sm text-green-700 font-semibold text-center">
          <p class="text-lg mb-1">Danke für Ihre Nachricht!</p>
          <p>Wir melden uns so schnell wie möglich.</p>
        </div>
        <?php else: ?>
        <?php if ($error): ?>
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 px-4 py-3"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Name *</label>
              <input type="text" name="name" required class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors">
            </div>
            <div>
              <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">E-Mail *</label>
              <input type="email" name="email" required class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors">
            </div>
          </div>
          <div>
            <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Telefon</label>
            <input type="tel" name="phone" class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors">
          </div>
          <div>
            <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Nachricht *</label>
            <textarea name="message" rows="5" required class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors resize-none"></textarea>
          </div>
          <button type="submit" class="w-full bg-[#e94e1b] hover:bg-orange-700 transition-colors py-4 text-sm font-bold uppercase tracking-widest text-white">
            NACHRICHT SENDEN
          </button>
        </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php render_footer(); ?>
