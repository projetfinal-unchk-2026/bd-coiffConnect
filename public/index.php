<?php
require_once dirname(__DIR__) . '/config/app.php';

// Routeur simple basé sur ?page=xxx&action=yyy
$page   = $_GET['page']   ?? 'home';
$action = $_GET['action'] ?? 'index';

// Routes publiques (pas besoin d'être connecté)
$publicRoutes = ['home', 'login', 'register', 'logout', 'contact', '403', '404'];

// Sécurisation : si la page n'est pas publique, vérifier la connexion
if (!in_array($page, $publicRoutes) && !isLoggedIn()) {
    redirect('/index.php?page=login');
}

// Dispatch
switch ($page) {

    // ── Authentification ──────────────────────────────────────────
    case 'login':
        require_once ROOT_PATH . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        $ctrl->login();
        break;

    case 'register':
        require_once ROOT_PATH . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        $ctrl->register();
        break;

    case 'logout':
        require_once ROOT_PATH . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        $ctrl->logout();
        break;

    case 'contact':
        view('shared/contact');
        break;

    // ── Admin ─────────────────────────────────────────────────────
    case 'admin':
        requireRole('admin');
        require_once ROOT_PATH . '/controllers/AdminController.php';
        $ctrl = new AdminController();
        $ctrl->$action();
        break;

    // ── Salons (coiffeur ou admin) ────────────────────────────────
    case 'salons':
        require_once ROOT_PATH . '/controllers/SalonController.php';
        $ctrl = new SalonController();
        $ctrl->$action();
        break;

    // ── Réservations ──────────────────────────────────────────────
    case 'reservations':
        requireLogin();
        require_once ROOT_PATH . '/controllers/ReservationController.php';
        $ctrl = new ReservationController();
        $ctrl->$action();
        break;

    // ── Services ──────────────────────────────────────────────────
    case 'services':
        requireLogin();
        require_once ROOT_PATH . '/controllers/ServiceController.php';
        $ctrl = new ServiceController();
        $ctrl->$action();
        break;

    // ── Avis ──────────────────────────────────────────────────────
    case 'avis':
        requireLogin();
        require_once ROOT_PATH . '/controllers/AvisController.php';
        $ctrl = new AvisController();
        $ctrl->$action();
        break;

    // ── Accueil ───────────────────────────────────────────────────
    case 'home':
    default:
        require_once ROOT_PATH . '/controllers/HomeController.php';
        $ctrl = new HomeController();
        $ctrl->index();
        break;

    case '403':
        http_response_code(403);
        view('shared/403');
        break;

    case '404':
        http_response_code(404);
        view('shared/404');
        break;
}
