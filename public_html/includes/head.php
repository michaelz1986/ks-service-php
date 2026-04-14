<?php
// Usage: head_open($title, $description)
function head_open(string $title = '', string $desc = ''): void {
    $s = get_settings();
    $t = $title ?: $s['websiteTitle'];
    $d = $desc  ?: $s['websiteDescription'];
    echo <<<HTML
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$t}</title>
<meta name="description" content="{$d}">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: { brand: '#e94e1b', dark: '#1a1a1a' }
    }
  }
}
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
  [x-cloak]{display:none!important}
  html{scroll-behavior:smooth}
  body{font-family:'Helvetica Neue',Arial,sans-serif}
</style>
</head>
HTML;
}
