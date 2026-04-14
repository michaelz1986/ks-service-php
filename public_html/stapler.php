<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/mail.php';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$id = $_GET['id'] ?? '';
$s  = $id ? get_stapler($id) : null;
if (!$s) {
    header('Location: /shop.php');
    exit;
}

$sent  = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $msg   = trim($_POST['message'] ?? '');
    if ($name && $email && $msg) {
        $inq = [
            'id'           => new_id(),
            'type'         => 'Produktanfrage',
            'customerName' => $name,
            'customerEmail'=> $email,
            'customerPhone'=> $phone,
            'message'      => $msg,
            'staplerId'    => $s['id'],
            'staplerTitle' => $s['title'],
            'status'       => 'Neu',
            'notes'        => [],
            'createdAt'    => now_iso(),
        ];
        save_inquiry($inq);
        $mailBody = "Neue Produktanfrage\n\nGerät: {$s['title']}\nName: {$name}\nE-Mail: {$email}\nTelefon: {$phone}\n\nNachricht:\n{$msg}";
        send_notification("Neue Anfrage: {$s['title']}", $mailBody);
        $sent = true;
    } else {
        $error = 'Bitte alle Pflichtfelder ausfüllen.';
    }
}

$specs = $s['specs'] ?? [];
head_open(htmlspecialchars($s['title']) . ' — KS Service');
?>
<body class="bg-white text-[#1a1a1a]">
<?php render_header('SHOP'); ?>

<div class="mx-auto max-w-7xl px-4 py-10 md:px-6 md:py-14">

  <!-- Breadcrumb -->
  <nav class="mb-6 text-xs text-[#888]">
    <a href="/" class="hover:text-[#e94e1b]">Home</a> /
    <a href="/shop.php" class="hover:text-[#e94e1b]">Shop</a> /
    <span><?= htmlspecialchars($s['title']) ?></span>
  </nav>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

    <!-- Images -->
    <div x-data="{active:0}" class="space-y-3">
      <div class="aspect-square overflow-hidden bg-[#f5f5f5] border border-[#e0e0e0]">
        <?php foreach ($s['images'] as $i => $img): ?>
        <img src="<?= htmlspecialchars($img) ?>" alt="" x-show="active===<?= $i ?>"
             class="w-full h-full object-contain">
        <?php endforeach; ?>
        <?php if (empty($s['images'])): ?>
        <img src="/assets/images/logo.png" alt="" class="w-full h-full object-contain p-8">
        <?php endif; ?>
      </div>
      <?php if (count($s['images']) > 1): ?>
      <div class="flex gap-2 flex-wrap">
        <?php foreach ($s['images'] as $i => $img): ?>
        <button @click="active=<?= $i ?>" class="w-16 h-16 border-2 overflow-hidden transition-colors"
                :class="active===<?= $i ?> ? 'border-[#e94e1b]' : 'border-[#e0e0e0] hover:border-[#e94e1b]'">
          <img src="<?= htmlspecialchars($img) ?>" class="w-full h-full object-cover" alt="">
        </button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Info -->
    <div>
      <?php
      $badge = match($s['status']) {
        'Verfügbar' => 'bg-green-100 text-green-700',
        'Reserviert' => 'bg-blue-100 text-blue-700',
        'Verkauft'  => 'bg-gray-100 text-gray-500',
        default     => 'bg-gray-100 text-gray-500',
      };
      ?>
      <span class="<?= $badge ?> px-3 py-1 text-xs font-bold uppercase tracking-widest rounded mb-4 inline-block"><?= $s['status'] ?></span>
      <p class="text-[11px] font-bold uppercase tracking-widest text-[#888] mb-1"><?= htmlspecialchars($s['brand']) ?></p>
      <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tight mb-4"><?= htmlspecialchars($s['title']) ?></h1>
      <div class="mb-6">
        <span class="text-4xl font-black text-[#e94e1b]">€ <?= number_format($s['price'], 0, ',', '.') ?></span>
        <span class="ml-2 text-sm text-[#888]">exkl. MwSt.</span>
        <?php if (!empty($s['priceOld'])): ?>
        <span class="ml-3 text-lg line-through text-[#aaa]">€ <?= number_format($s['priceOld'], 0, ',', '.') ?></span>
        <?php endif; ?>
      </div>
      <p class="text-sm leading-relaxed text-[#555] mb-8"><?= nl2br(htmlspecialchars($s['description'])) ?></p>

      <!-- Specs -->
      <?php if (array_filter($specs)): ?>
      <div class="border border-[#e0e0e0] mb-8">
        <div class="bg-[#1a1a1a] px-4 py-2">
          <span class="text-xs font-bold uppercase tracking-widest text-white">Technische Daten</span>
        </div>
        <div class="divide-y divide-[#e0e0e0]">
          <?php
          $labels = [
            'liftCapacity'    => 'Tragfähigkeit',
            'liftHeight'      => 'Hubhöhe (mm)',
            'buildHeight'     => 'Bauhöhe (mm)',
            'yearBuilt'       => 'Baujahr',
            'operatingHours'  => 'Betriebsstunden',
            'weight'          => 'Eigengewicht (kg)',
            'batteryInfo'     => 'Batterie',
            'tiresFront'      => 'Bereifung vorne',
            'tiresRear'       => 'Bereifung hinten',
            'specialEquipment'=> 'Sonderausstattung',
          ];
          foreach ($labels as $key => $label):
            $val = $specs[$key] ?? null;
            if (!$val) continue;
          ?>
          <div class="flex px-4 py-2.5 text-sm">
            <span class="w-44 font-semibold text-[#666]"><?= $label ?></span>
            <span><?= htmlspecialchars((string)$val) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Inquiry form -->
      <?php if ($sent): ?>
      <div class="rounded bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 font-semibold">
        Ihre Anfrage wurde gesendet! Wir melden uns so schnell wie möglich.
      </div>
      <?php else: ?>
      <div class="border border-[#e0e0e0] p-6">
        <h3 class="mb-4 text-sm font-black uppercase tracking-widest">ANFRAGE STELLEN</h3>
        <?php if ($error): ?>
        <div class="mb-3 text-sm text-red-600"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" class="space-y-3">
          <input type="hidden" name="stapler_id" value="<?= htmlspecialchars($s['id']) ?>">
          <input type="text" name="name" placeholder="Ihr Name *" required
                 class="w-full border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-[#e94e1b] transition-colors">
          <input type="email" name="email" placeholder="E-Mail *" required
                 class="w-full border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-[#e94e1b] transition-colors">
          <input type="tel" name="phone" placeholder="Telefon"
                 class="w-full border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-[#e94e1b] transition-colors">
          <textarea name="message" rows="3" placeholder="Ihre Nachricht *" required
                    class="w-full border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-[#e94e1b] transition-colors resize-none"></textarea>
          <button type="submit"
                  class="w-full bg-[#e94e1b] hover:bg-orange-700 transition-colors py-3 text-sm font-bold uppercase tracking-widest text-white">
            ANFRAGE SENDEN
          </button>
        </form>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php render_footer(); ?>
