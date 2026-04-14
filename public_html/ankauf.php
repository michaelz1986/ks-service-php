<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/mail.php';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$sent  = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $msg   = trim($_POST['message'] ?? '');
    $type  = trim($_POST['staplerType'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $year  = trim($_POST['yearBuilt'] ?? '');
    $hours = trim($_POST['operatingHours'] ?? '');
    $cond  = trim($_POST['condition'] ?? '');
    if ($name && $email) {
        $inq = [
            'id'           => new_id(),
            'type'         => 'Ankauf',
            'customerName' => $name,
            'customerEmail'=> $email,
            'customerPhone'=> $phone,
            'message'      => $msg,
            'status'       => 'Neu',
            'notes'        => [],
            'createdAt'    => now_iso(),
            'ankaufData'   => [
                'staplerType'    => $type,
                'brand'          => $brand,
                'yearBuilt'      => $year,
                'operatingHours' => $hours,
                'condition'      => $cond,
            ],
        ];
        save_inquiry($inq);
        $mailBody = "Neue Ankauf-Anfrage\n\nName: {$name}\nE-Mail: {$email}\nTelefon: {$phone}\nTyp: {$type}\nMarke: {$brand}\nBaujahr: {$year}\nStunden: {$hours}\nZustand: {$cond}\n\nNachricht:\n{$msg}";
        send_notification("Neue Ankauf-Anfrage von {$name}", $mailBody);
        $sent = true;
    } else {
        $error = 'Bitte Name und E-Mail ausfüllen.';
    }
}

head_open('Stapler Ankauf — KS Service');
?>
<body class="bg-white text-[#1a1a1a]">
<?php render_header('ANKAUF'); ?>

<!-- HERO -->
<section class="relative h-[480px] overflow-hidden md:h-[560px]">
  <img src="/assets/images/design/banner-stapler-ks.jpg" class="absolute inset-0 w-full h-full object-cover object-center" alt="Stapler Ankauf">
  <div class="absolute inset-0 bg-gradient-to-r from-black/88 via-black/60 to-black/20"></div>
  <div class="relative z-10 flex h-full flex-col justify-center px-10 md:px-20">
    <div class="max-w-lg">
      <div class="mb-7"><img src="/assets/images/cropped-ks-service-logo-orange.webp" alt="KS Service" class="w-[160px] md:w-[200px] h-auto"></div>
      <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.3em] text-[#e94e1b]">An- &amp; Verkauf</p>
      <h1 class="text-4xl font-black uppercase leading-tight tracking-tight text-white md:text-6xl">STAPLER<br><span class="text-white/80">ANKAUF</span></h1>
      <p class="mt-4 max-w-md text-sm leading-relaxed text-white/60">Wir kaufen Stapler aller Marken und Modelle — auch defekte Geräte. Angebot binnen 12 Stunden.</p>
      <a href="#ankauf-form" class="mt-8 inline-block border-2 border-[#e94e1b] px-9 py-3.5 text-sm font-bold uppercase tracking-[0.18em] text-white transition hover:bg-[#e94e1b]">JETZT ANFRAGEN</a>
    </div>
  </div>
  <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-[#e94e1b]"></div>
</section>

<!-- WIR KAUFEN -->
<section class="py-20 bg-[#f5f5f5]">
  <div class="mx-auto max-w-7xl px-4 md:px-6">
    <div class="mb-12">
      <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.2em] text-[#e94e1b]">WAS WIR KAUFEN</p>
      <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight">ALLE STAPLER-TYPEN</h2>
      <div class="mt-3 h-1 w-14 bg-[#e94e1b]"></div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
      <?php foreach (['Elektrostapler','Dieselstapler','Gasstapler','Schubmaststapler','Kommissionierer','Lagertechnik'] as $item): ?>
      <div class="flex items-center gap-3 bg-white p-4 border border-[#e0e0e0]">
        <div class="w-2 h-2 bg-[#e94e1b] flex-shrink-0 rounded-full"></div>
        <span class="text-sm font-semibold"><?= $item ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ANKAUF FORM -->
<section class="py-20 bg-white" id="ankauf-form">
  <div class="mx-auto max-w-2xl px-4 md:px-6">
    <div class="mb-10">
      <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.2em] text-[#e94e1b]">KOSTENLOS</p>
      <h2 class="text-3xl font-black uppercase tracking-tight">ANKAUF-ANFRAGE</h2>
      <div class="mt-3 h-1 w-14 bg-[#e94e1b]"></div>
    </div>

    <?php if ($sent): ?>
    <div class="rounded bg-green-50 border border-green-200 px-6 py-5 text-sm text-green-700 font-semibold text-center">
      <p class="text-lg mb-1">Danke für Ihre Anfrage!</p>
      <p>Wir melden uns innerhalb von 12 Stunden mit einem Angebot.</p>
    </div>
    <?php else: ?>
    <?php if ($error): ?>
    <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 px-4 py-3 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" class="space-y-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Ihr Name *</label>
          <input type="text" name="name" required class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">E-Mail *</label>
          <input type="email" name="email" required class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Telefon</label>
          <input type="tel" name="phone" class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Stapler-Typ</label>
          <select name="staplerType" class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] bg-white">
            <option value="">Bitte wählen</option>
            <?php foreach (['Elektrostapler','Dieselstapler','Gasstapler','Schubmaststapler','Kommissionierer','Sonstiges'] as $opt): ?>
            <option><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Marke</label>
          <input type="text" name="brand" class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Baujahr</label>
          <input type="text" name="yearBuilt" class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Betriebsstunden</label>
          <input type="text" name="operatingHours" class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors">
        </div>
        <div>
          <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Zustand</label>
          <select name="condition" class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] bg-white">
            <option value="">Bitte wählen</option>
            <?php foreach (['Sehr gut','Gut','Gebraucht','Defekt'] as $opt): ?>
            <option><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div>
        <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-[#666]">Nachricht</label>
        <textarea name="message" rows="4" class="w-full border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#e94e1b] transition-colors resize-none"></textarea>
      </div>
      <button type="submit" class="w-full bg-[#e94e1b] hover:bg-orange-700 transition-colors py-4 text-sm font-bold uppercase tracking-widest text-white">
        ANFRAGE ABSENDEN
      </button>
    </form>
    <?php endif; ?>
  </div>
</section>

<?php render_footer(); ?>
