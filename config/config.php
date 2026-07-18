<?php
/**
 * Basic application configuration.
 * This file stores simple settings used by the system.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Root directory path
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/..');
}

// Application base URL
if (!defined('BASE_URL')) {
    $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $appRootRealPath = realpath(APP_ROOT);

    if ($documentRoot !== '' && $appRootRealPath !== false && strpos($appRootRealPath, $documentRoot) === 0) {
        $relativePath = substr($appRootRealPath, strlen($documentRoot));
        $relativePath = str_replace('\\', '/', $relativePath);
        $basePath = '/' . ltrim($relativePath, '/');
    } else {
        $requestPath = $_SERVER['REQUEST_URI'] ?? $_SERVER['SCRIPT_NAME'] ?? '/';
        $requestPath = parse_url($requestPath, PHP_URL_PATH) ?: '/';
        $requestPath = rtrim($requestPath, '/');
        $basePath = dirname($requestPath);
        if ($basePath === '/' || $basePath === '\\') {
            $basePath = '';
        }
    }

    define('BASE_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $basePath . '/');
}

// Encryption settings for OpenSSL AES-256-CBC
if (!defined('ENCRYPTION_KEY')) {
    define('ENCRYPTION_KEY', 'employee-management-key-2026');
}

if (!defined('ENCRYPTION_METHOD')) {
    define('ENCRYPTION_METHOD', 'AES-256-CBC');
}

require_once __DIR__ . '/Database.php';

// Autoload classes from the classes folder
spl_autoload_register(function ($className) {
    $file = APP_ROOT . '/classes/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
