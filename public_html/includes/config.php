<?php
define('ROOT', dirname(__DIR__));
define('DATA_DIR', ROOT . '/data');
define('UPLOAD_DIR', ROOT . '/uploads');
define('UPLOAD_URL', '/uploads');

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default admin password hash (Password: ks2024)
// Change this in settings.json after first login
define('DEFAULT_PASSWORD_HASH', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
