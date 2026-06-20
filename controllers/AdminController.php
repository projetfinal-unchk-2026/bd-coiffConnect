<?php
require_once ROOT_PATH . '/models/Model.php';
require_once ROOT_PATH . '/models/UtilisateurModel.php';
require_once ROOT_PATH . '/models/SalonModel.php';
require_once ROOT_PATH . '/models/ReservationModel.php';
require_once ROOT_PATH . '/models/AvisModel.php';
require_once ROOT_PATH . '/models/ServiceModel.php';

class AdminController {
    private UtilisateurModel $userModel;
    private SalonModel       $salonModel;
    private ReservationModel $reservModel;
    private AvisModel        $avisModel;

    public function __construct() {
        $this->userModel   = new UtilisateurModel();
        $this->salonModel  = new SalonModel();
        $this->reservModel = new ReservationModel();
        $this->avisModel   = new AvisModel();
    }

    // ── Dashboard ──────────────────────────────────────────────────
    public function dashboard(): void {
        $stats = [
            'utilisateurs'  => $this->userModel->countAll(),
            'salons'        => $this->salonModel->countAll(),
            'reservations'  => $this->reservModel->countAll(),
            'revenu'        => $this->reservModel->getTotalRevenu(),
            'en_attente'    => $this->reservModel->countByStatut('en_attente'),
            'confirmees'    => $this->reservModel->countByStatut('confirmee'),
            'terminees'     => $this->reservModel->countByStatut('terminee'),
            'annulees'      => $this->reservModel->countByStatut('annulee'),
        ];

        $reservationsRecentes = $this->reservModel->getRecentes(10);
        $salonsPopulaires     = $this->salonModel->getPopulaires(5);
        $evolutionMensuelle   = $this->reservModel->getEvolutionMensuelle();

        view('admin/dashboard', compact(
            'stats', 'reservationsRecentes', 'salonsPopulaires', 'evolutionMensuelle'
        ));
    }

    // ── Utilisateurs ───────────────────────────────────────────────
    public function utilisateurs(): void {
        $users = $this->userModel->getAll(100);
        view('admin/utilisateurs', compact('users'));
    }

    public function deleteUser(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id && $id !== (int) $_SESSION['user_id']) {
            $this->userModel->delete($id);
            setFlash('success', 'Utilisateur désactivé.');
        }
        redirect('/index.php?page=admin&action=utilisateurs');
    }

    // ── Établissements (salons) ────────────────────────────────────
    public function etablissements(): void {
        $salons = $this->salonModel->getAll(false); // inclure inactifs
        view('admin/etablissements', compact('salons'));
    }

    public function toggleSalon(): void {
        $id    = (int) ($_GET['id'] ?? 0);
        $salon = $this->salonModel->getById($id);
        if ($salon) {
            $this->salonModel->update($id, array_merge($salon, [
                'est_actif' => $salon['est_actif'] ? 0 : 1
            ]));
            setFlash('success', 'Statut du salon mis à jour.');
        }
        redirect('/index.php?page=admin&action=etablissements');
    }

    // ── Réservations ───────────────────────────────────────────────
    public function reservations(): void {
        $filters = [
            'statut'     => $_GET['statut'] ?? '',
            'date_debut' => $_GET['date_debut'] ?? '',
            'date_fin'   => $_GET['date_fin'] ?? '',
        ];
        $reservations = $this->reservModel->getAll($filters);
        view('admin/reservations', compact('reservations', 'filters'));
    }

    public function updateStatutReservation(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id     = (int) ($_POST['id'] ?? 0);
            $statut = sanitize($_POST['statut'] ?? '');
            if ($id && $statut) {
                $this->reservModel->updateStatut($id, $statut);
                setFlash('success', 'Statut mis à jour.');
            }
        }
        redirect('/index.php?page=admin&action=reservations');
    }

    // ── Avis ───────────────────────────────────────────────────────
    public function avisClients(): void {
        $avis = $this->avisModel->getAll();
        view('admin/avis', compact('avis'));
    }

    public function deleteAvis(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            $this->avisModel->delete($id);
            setFlash('success', 'Avis supprimé.');
        }
        redirect('/index.php?page=admin&action=avisClients');
    }

    // ── Statistiques JSON (pour graphiques AJAX) ───────────────────
    public function statsJson(): void {
        $evolution = $this->reservModel->getEvolutionMensuelle();
        jsonResponse(['evolution' => $evolution]);
    }
}
