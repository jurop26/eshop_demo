<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 3600,
    'domain' => 'localhost',
    'path' => '/',
    'secure' => true,
    'httponly' => true,
]);

session_start();

if (!isset($_SESSION['last-regeneration'])) {
    session_regenerate_id();
    $_SESSION['last-regeneration'] = time();
} else {
    $regeneration_interval = 60 * 30;

    if (time() - $_SESSION['last-regeneration'] >= $regeneration_interval) {
        session_regenerate_id();
        $_SESSION['last-regeneration'] = time();
    }
}
