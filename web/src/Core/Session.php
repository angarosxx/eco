<?php
// src/Core/Session.php or bootstrap/session.php

ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

// if HTTPS in production:
ini_set('session.cookie_secure', '1');

// Redis-backed sessions
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://redis:6379');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
