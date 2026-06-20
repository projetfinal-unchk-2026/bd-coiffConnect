<?php
require_once ROOT_PATH . '/models/Model.php';
require_once ROOT_PATH . '/models/UtilisateurModel.php';

class AuthController {
    private UtilisateurModel $model;

    public function __construct() {
        $this->model = new UtilisateurModel();
    }

    // ── GET/POST /index.php?page=login ────────────────────────────
    public function login(): void {
        // Déjà connecté → rediriger selon le rôle
        if (isLoggedIn()) {
            $this->redirectByRole();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = sanitize($_POST['email'] ?? '');
            $password = $_POST['mot_de_passe'] ?? '';

            if (empty($email) || empty($password)) {
                setFlash('error', 'Veuillez remplir tous les champs.');
                view('auth/login');
                return;
            }

            $user = $this->model->findByEmail($email);

            if (!$user || !verifyPassword($password, $user['mot_de_passe'])) {
                setFlash('error', 'Email ou mot de passe incorrect.');
                view('auth/login');
                return;
            }

            // Ouvrir la session
            $this->startSession($user);
            setFlash('success', 'Bienvenue, ' . $user['prenom'] . ' !');
            $this->redirectByRole();
        }

        view('auth/login');
    }

    // ── GET/POST /index.php?page=register ─────────────────────────
    public function register(): void {
        if (isLoggedIn()) {
            $this->redirectByRole();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom      = sanitize($_POST['nom'] ?? '');
            $prenom   = sanitize($_POST['prenom'] ?? '');
            $email    = sanitize($_POST['email'] ?? '');
            $tel      = sanitize($_POST['telephone'] ?? '');
            $password = $_POST['mot_de_passe'] ?? '';
            $confirm  = $_POST['confirmer_mdp'] ?? '';
            $role     = in_array($_POST['role'] ?? '', ['client', 'coiffeur']) ? $_POST['role'] : 'client';

            $errors = [];

            if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
                $errors[] = 'Tous les champs obligatoires doivent être remplis.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Adresse email invalide.';
            }
            if (strlen($password) < 6) {
                $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
            }
            if ($password !== $confirm) {
                $errors[] = 'Les mots de passe ne correspondent pas.';
            }
            if ($this->model->emailExists($email)) {
                $errors[] = 'Cet email est déjà utilisé.';
            }

            if (!empty($errors)) {
                foreach ($errors as $e) {
                    setFlash('error', $e);
                }
                view('auth/register', ['old' => $_POST]);
                return;
            }

            $id = $this->model->create([
                'nom'          => $nom,
                'prenom'       => $prenom,
                'email'        => $email,
                'mot_de_passe' => $password,
                'telephone'    => $tel ?: null,
                'role'         => $role,
            ]);

            $user = $this->model->findById($id);
            // Récupérer avec le mot de passe pour la session
            $userFull = $this->model->findByEmail($email);
            $this->startSession($userFull);

            setFlash('success', 'Compte créé avec succès ! Bienvenue ' . $prenom . ' !');
            $this->redirectByRole();
        }

        view('auth/register');
    }

    // ── GET /index.php?page=logout ────────────────────────────────
    public function logout(): void {
        session_destroy();
        redirect('/index.php?page=login');
    }

    // ── Privé ──────────────────────────────────────────────────────
    private function startSession(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user']      = [
            'id'       => $user['id'],
            'nom'      => $user['nom'],
            'prenom'   => $user['prenom'],
            'email'    => $user['email'],
            'role'     => $user['role'],
            'telephone'=> $user['telephone'] ?? '',
        ];
    }

    private function redirectByRole(): void {
        switch ($_SESSION['user_role']) {
            case 'admin':
                redirect('/index.php?page=admin&action=dashboard');
                break;
            case 'coiffeur':
                redirect('/index.php?page=salons&action=dashboard');
                break;
            default:
                redirect('/index.php?page=home');
        }
    }
}
