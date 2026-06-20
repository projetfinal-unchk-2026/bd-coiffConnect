<?php
// Constantes de l'application
define('APP_NAME', 'CoiffConnect');
define('APP_URL', 'http://localhost/coiffconnect/public');
define('ROOT_PATH', dirname(__DIR__));

// Démarrage de session sécurisé
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader simple
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/models/' . $class . '.php',
        ROOT_PATH . '/controllers/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

require_once ROOT_PATH . '/config/database.php';

// ──────────────────────────────────────────────
// Helpers globaux
// ──────────────────────────────────────────────

function redirect(string $url): void {
    header("Location: " . APP_URL . $url);
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect('/index.php?page=login');
    }
}

function requireRole(string $role): void {
    requireLogin();
    if ($_SESSION['user_role'] !== $role) {
        redirect('/index.php?page=403');
    }
}

function sanitize(string $value): string {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

function hashPassword(string $password): string {
    return hash('sha256', $password); // Compatible avec la BDD existante
}

function verifyPassword(string $password, string $hash): bool {
    return hash('sha256', $password) === $hash;
}

function formatMontant(float $amount): string {
    return number_format($amount, 0, ',', ' ') . ' FCFA';
}

function formatDate(string $date): string {
    return date('d/m/Y', strtotime($date));
}

function formatHeure(string $time): string {
    return substr($time, 0, 5); // HH:MM
}

function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function isAdmin(): bool {
    return ($_SESSION['user_role'] ?? '') === 'admin';
}

function isCoiffeur(): bool {
    return ($_SESSION['user_role'] ?? '') === 'coiffeur';
}

function isClient(): bool {
    return ($_SESSION['user_role'] ?? '') === 'client';
}

// Renvoie JSON et termine le script (pour les appels AJAX)
function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function view(string $template, array $data = []): void {
    extract($data);
    $file = ROOT_PATH . '/views/' . $template . '.php';
    if (!file_exists($file)) {
        die("Vue introuvable : $template");
    }
    require $file;
}
