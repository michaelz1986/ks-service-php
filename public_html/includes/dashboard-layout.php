<?php
function dashboard_head(string $title): void {
    echo <<<HTML
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>{$title} — KS Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{colors:{brand:'#e94e1b',dark:'#1a1a1a'}}}}</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
  [x-cloak]{display:none!important}
  body{background:#0f1117;color:#e2e8f0;font-family:'Helvetica Neue',Arial,sans-serif}
  .sidebar-link{display:flex;align-items:center;gap:10px;padding:10px 16px;border-radius:10px;font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.6);transition:all .15s}
  .sidebar-link:hover,.sidebar-link.active{background:rgba(233,78,27,.15);color:#e94e1b}
</style>
</head>
<body>
HTML;
}

function dashboard_layout(string $active, string $content): void {
    $nav = [
        ['/dashboard/', 'Übersicht', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['/dashboard/stapler/', 'Stapler', 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
        ['/dashboard/anfragen.php', 'Anfragen', 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
        ['/dashboard/einstellungen.php', 'Einstellungen', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0'],
        ['/dashboard/backup.php', 'Backup', 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10'],
    ];
    $links = '';
    foreach ($nav as [$href, $label, $icon]) {
        $cls = ($active === $label) ? 'sidebar-link active' : 'sidebar-link';
        $links .= "<a href=\"{$href}\" class=\"{$cls}\"><svg class=\"w-4 h-4 flex-shrink-0\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"{$icon}\"/></svg>{$label}</a>";
    }
    echo <<<HTML
<div class="flex min-h-screen" x-data="{sideOpen:false}">

  <!-- Sidebar -->
  <aside class="fixed inset-y-0 left-0 z-40 w-56 flex-shrink-0 flex flex-col bg-[#161922] border-r border-white/5 transform transition-transform duration-200 lg:translate-x-0"
         :class="sideOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    <div class="flex items-center gap-3 px-4 py-5 border-b border-white/5">
      <img src="/assets/images/cropped-ks-service-logo-orange.webp" alt="KS" class="h-8 w-auto">
      <span class="text-xs font-bold uppercase tracking-widest text-white/50">Dashboard</span>
    </div>
    <nav class="flex-1 overflow-y-auto p-3 space-y-1">
      {$links}
    </nav>
    <div class="p-3 border-t border-white/5">
      <a href="/logout.php" class="sidebar-link text-red-400 hover:text-red-300 hover:bg-red-900/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Abmelden
      </a>
    </div>
  </aside>

  <!-- Overlay mobile -->
  <div x-show="sideOpen" x-cloak @click="sideOpen=false" class="fixed inset-0 z-30 bg-black/50 lg:hidden"></div>

  <!-- Main -->
  <div class="flex-1 lg:ml-56 flex flex-col min-h-screen">
    <!-- Topbar -->
    <header class="sticky top-0 z-20 flex items-center gap-4 px-4 py-3 bg-[#161922] border-b border-white/5">
      <button @click="sideOpen=true" class="lg:hidden text-white/60 hover:text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <span class="text-sm font-semibold text-white/80">{$active}</span>
      <div class="ml-auto flex items-center gap-3">
        <a href="/" target="_blank" class="text-xs text-white/40 hover:text-white/70 transition-colors">↗ Website</a>
      </div>
    </header>
    <!-- Content -->
    <main class="flex-1 p-4 md:p-8">
      {$content}
    </main>
  </div>
</div>
HTML;
}
