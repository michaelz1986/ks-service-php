<?php
require_once __DIR__ . '/config.php';

function read_json(string $file): array {
    $path = DATA_DIR . '/' . $file;
    if (!file_exists($path)) return [];
    $content = file_get_contents($path);
    return json_decode($content, true) ?? [];
}

function write_json(string $file, array $data): void {
    $path = DATA_DIR . '/' . $file;
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function get_settings(): array {
    $defaults = [
        'companyName'        => 'KS Service',
        'email'              => 'office@ks-service.at',
        'phone'              => '+43 664 54439',
        'address'            => 'Kärnten, Österreich',
        'uid'                => '',
        'openingHours'       => 'Mo–Sa 8:00–18:00',
        'emergencyService'   => 'Notdienst 24/7',
        'websiteTitle'       => 'KS Service – Stapler & Flurförderzeuge',
        'websiteDescription' => 'Gebrauchte und generalüberholte Elektro-, Diesel- und Hubwagen in Kärnten.',
        'adminPasswordHash'  => DEFAULT_PASSWORD_HASH,
        // SMTP
        'smtpHost'           => '',
        'smtpPort'           => '587',
        'smtpUser'           => '',
        'smtpPass'           => '',
        'smtpFrom'           => '',
        'smtpFromName'       => 'KS Service',
        'smtpTo'             => '',
        'smtpEnabled'        => false,
    ];
    $saved = read_json('settings.json');
    return array_merge($defaults, $saved);
}

function get_staplers(): array {
    return read_json('stapler.json');
}

function get_stapler(string $id): ?array {
    foreach (get_staplers() as $s) {
        if ($s['id'] === $id) return $s;
    }
    return null;
}

function save_stapler(array $stapler): void {
    $all = get_staplers();
    $found = false;
    foreach ($all as &$s) {
        if ($s['id'] === $stapler['id']) {
            $s = $stapler;
            $found = true;
            break;
        }
    }
    if (!$found) array_unshift($all, $stapler);
    write_json('stapler.json', $all);
}

function delete_stapler(string $id): void {
    $all = array_filter(get_staplers(), fn($s) => $s['id'] !== $id);
    write_json('stapler.json', array_values($all));
}

function get_inquiries(): array {
    return read_json('anfragen.json');
}

function save_inquiry(array $inq): void {
    $all = get_inquiries();
    $found = false;
    foreach ($all as &$q) {
        if ($q['id'] === $inq['id']) {
            $q = $inq;
            $found = true;
            break;
        }
    }
    if (!$found) array_unshift($all, $inq);
    write_json('anfragen.json', $all);
}

function delete_inquiry(string $id): void {
    $all = array_filter(get_inquiries(), fn($q) => $q['id'] !== $id);
    write_json('anfragen.json', array_values($all));
}

function new_id(): string {
    return uniqid('', true);
}

function now_iso(): string {
    return (new DateTime())->format(DateTime::ATOM);
}
