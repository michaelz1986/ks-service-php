<?php
require_once __DIR__ . '/config.php';

function is_logged_in(): bool {
    return !empty($_SESSION['ks_admin']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: /login.php');
        exit;
    }
}

function do_login(string $password): bool {
    $settings = read_json('settings.json');
    $hash = $settings['adminPasswordHash'] ?? DEFAULT_PASSWORD_HASH;
    if (password_verify($password, $hash)) {
        $_SESSION['ks_admin'] = true;
        return true;
    }
    return false;
}

function do_logout(): void {
    $_SESSION = [];
    session_destroy();
}
