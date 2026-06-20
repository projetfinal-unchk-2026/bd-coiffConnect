<?php
require_once ROOT_PATH . '/models/Model.php';
require_once ROOT_PATH . '/models/SalonModel.php';
require_once ROOT_PATH . '/models/ServiceModel.php';
require_once ROOT_PATH . '/models/ReservationModel.php';
require_once ROOT_PATH . '/models/AvisModel.php';

class SalonController {
    private SalonModel       $model;
    private ServiceModel     $serviceModel;
    private ReservationModel $reservModel;
    private AvisModel        $avisModel;

    public function __construct() {
        $this->model        = new SalonModel();
        $this->serviceModel = new ServiceModel();
        $this->reservModel  = new ReservationModel();
        $this->avisModel    = new AvisModel();
    }

    // ── Liste publique des salons ──────────────────────────────────
    public function index(): void {
        $service = sanitize($_GET['service'] ?? '');
        $ville   = sanitize($_GET['ville'] ?? '');
        $salons  = ($service || $ville)
            ? $this->model->search($service, $ville)
            : $this->model->getAll();

        view('client/salons', compact('salons', 'service', 'ville'));
    }

    // ── Détail public d'un salon ───────────────────────────────────
    public function show(): void {
        $id = (int) ($_GET['id'] ?? 0);
        $salon = $this->model->getById($id);

        if (!$salon) {
            setFlash('error', 'Salon introuvable.');
            redirect('/index.php?page=salons&action=index');
        }

        $services = $this->serviceModel->getBySalon($id);
        $avis     = $this->avisModel->getBySalon($id);

        view('client/salon_detail', compact('salon', 'services', 'avis'));
    }

    // ── Dashboard coiffeur ─────────────────────────────────────────
    public function dashboard(): void {
        requireRole('coiffeur');
        $salons = $this->model->getByProprietaire($_SESSION['user_id']);
        $salonId = $salons[0]['id'] ?? null;

        $reservations = $salonId
            ? $this->reservModel->getBySalon($salonId)
            : [];
        $avis = $salonId ? $this->avisModel->getBySalon($salonId) : [];

        view('coiffeur/dashboard', compact('salons', 'salonId', 'reservations', 'avis'));
    }

    // ── Créer un salon (coiffeur) ──────────────────────────────────
    public function create(): void {
        requireRole('coiffeur');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'proprietaire_id' => $_SESSION['user_id'],
                'nom'             => sanitize($_POST['nom'] ?? ''),
                'description'     => sanitize($_POST['description'] ?? ''),
                'adresse'         => sanitize($_POST['adresse'] ?? ''),
                'quartier'        => sanitize($_POST['quartier'] ?? ''),
                'ville'           => sanitize($_POST['ville'] ?? 'Dakar'),
                'telephone'       => sanitize($_POST['telephone'] ?? ''),
                'email'           => sanitize($_POST['email'] ?? ''),
                'heure_ouverture' => $_POST['heure_ouverture'] ?? '08:00',
                'heure_fermeture' => $_POST['heure_fermeture'] ?? '20:00',
            ];

            if (empty($data['nom']) || empty($data['quartier'])) {
                setFlash('error', 'Le nom et le quartier sont obligatoires.');
                view('coiffeur/salon_form', ['data' => $data, 'mode' => 'create']);
                return;
            }

            $id = $this->model->create($data);
            setFlash('success', 'Salon créé avec succès !');
            redirect('/index.php?page=salons&action=editSalon&id=' . $id);
        }

        view('coiffeur/salon_form', ['data' => [], 'mode' => 'create']);
    }

    // ── Modifier un salon ──────────────────────────────────────────
    public function editSalon(): void {
        requireRole('coiffeur');
        $id    = (int) ($_GET['id'] ?? 0);
        $salon = $this->model->getById($id);

        if (!$salon || (int) $salon['proprietaire_id'] !== $_SESSION['user_id'] && !isAdmin()) {
            setFlash('error', 'Accès refusé.');
            redirect('/index.php?page=salons&action=dashboard');
        }

        $services = $this->serviceModel->getBySalon($id, false);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom'             => sanitize($_POST['nom'] ?? ''),
                'description'     => sanitize($_POST['description'] ?? ''),
                'adresse'         => sanitize($_POST['adresse'] ?? ''),
                'quartier'        => sanitize($_POST['quartier'] ?? ''),
                'ville'           => sanitize($_POST['ville'] ?? 'Dakar'),
                'telephone'       => sanitize($_POST['telephone'] ?? ''),
                'email'           => sanitize($_POST['email'] ?? ''),
                'heure_ouverture' => $_POST['heure_ouverture'] ?? '08:00',
                'heure_fermeture' => $_POST['heure_fermeture'] ?? '20:00',
                'est_actif'       => $salon['est_actif'],
            ];
            $this->model->update($id, $data);
            setFlash('success', 'Salon mis à jour.');
            redirect('/index.php?page=salons&action=editSalon&id=' . $id);
        }

        view('coiffeur/salon_form', ['data' => $salon, 'mode' => 'edit', 'services' => $services]);
    }

    // ── Ajouter un service ─────────────────────────────────────────
    public function addService(): void {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $salonId = (int) ($_POST['salon_id'] ?? 0);
            $salon   = $this->model->getById($salonId);

            if (!$salon || ((int) $salon['proprietaire_id'] !== $_SESSION['user_id'] && !isAdmin())) {
                jsonResponse(['error' => 'Accès refusé'], 403);
            }

            $id = $this->serviceModel->create([
                'salon_id'       => $salonId,
                'nom'            => sanitize($_POST['nom'] ?? ''),
                'description'    => sanitize($_POST['description'] ?? ''),
                'prix'           => (float) ($_POST['prix'] ?? 0),
                'duree_minutes'  => (int) ($_POST['duree_minutes'] ?? 30),
            ]);

            setFlash('success', 'Service ajouté.');
            redirect('/index.php?page=salons&action=editSalon&id=' . $salonId);
        }
    }

    // ── Supprimer un service ───────────────────────────────────────
    public function deleteService(): void {
        requireLogin();
        $id        = (int) ($_GET['id'] ?? 0);
        $salonId   = (int) ($_GET['salon_id'] ?? 0);
        if ($id) {
            $this->serviceModel->delete($id);
            setFlash('success', 'Service supprimé.');
        }
        redirect('/index.php?page=salons&action=editSalon&id=' . $salonId);
    }

    // ── Confirmer/Terminer une réservation (coiffeur) ─────────────
    public function updateReservation(): void {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id     = (int) ($_POST['id'] ?? 0);
            $statut = sanitize($_POST['statut'] ?? '');
            $this->reservModel->updateStatut($id, $statut);
            setFlash('success', 'Statut mis à jour.');
        }
        redirect('/index.php?page=salons&action=dashboard');
    }
}
