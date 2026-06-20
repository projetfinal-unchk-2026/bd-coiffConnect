<?php
require_once ROOT_PATH . '/models/Model.php';
require_once ROOT_PATH . '/models/ReservationModel.php';
require_once ROOT_PATH . '/models/SalonModel.php';
require_once ROOT_PATH . '/models/ServiceModel.php';

class ReservationController {
    private ReservationModel $model;
    private SalonModel       $salonModel;
    private ServiceModel     $serviceModel;

    public function __construct() {
        $this->model        = new ReservationModel();
        $this->salonModel   = new SalonModel();
        $this->serviceModel = new ServiceModel();
    }

    // ── Liste des réservations du client connecté ──────────────────
    public function index(): void {
        $reservations = $this->model->getByClient($_SESSION['user_id']);
        view('client/mes_reservations', compact('reservations'));
    }

    // ── Formulaire de réservation ──────────────────────────────────
    public function create(): void {
        $salonId = (int) ($_GET['salon_id'] ?? 0);

        if (!$salonId) {
            setFlash('error', 'Salon non spécifié.');
            redirect('/index.php?page=salons&action=index');
        }

        $salon    = $this->salonModel->getById($salonId);
        $services = $this->serviceModel->getBySalon($salonId);

        if (!$salon) {
            setFlash('error', 'Salon introuvable.');
            redirect('/index.php?page=salons&action=index');
        }

        view('client/reserver', compact('salon', 'services'));
    }

    // ── Enregistrement d'une réservation ──────────────────────────
    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/index.php?page=reservations&action=index');
        }

        $salonId   = (int) ($_POST['salon_id'] ?? 0);
        $serviceId = (int) ($_POST['service_id'] ?? 0);
        $date      = sanitize($_POST['date_reservation'] ?? '');
        $heure     = sanitize($_POST['heure_reservation'] ?? '');
        $notes     = sanitize($_POST['notes'] ?? '');

        // Validations
        $errors = [];

        $salon   = $this->salonModel->getById($salonId);
        $service = $this->serviceModel->getById($serviceId);

        if (!$salon)   $errors[] = 'Salon invalide.';
        if (!$service) $errors[] = 'Service invalide.';
        if (empty($date) || strtotime($date) < strtotime('today'))
            $errors[] = 'La date doit être aujourd\'hui ou dans le futur.';
        if (empty($heure)) $errors[] = 'Heure obligatoire.';

        if (empty($errors)) {
            // Vérifier disponibilité
            if (!$this->model->isCreneauDisponible($salonId, $date, $heure, $service['duree_minutes'])) {
                $errors[] = 'Ce créneau est déjà pris. Choisissez un autre horaire.';
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $e) setFlash('error', $e);
            redirect("/index.php?page=reservations&action=create&salon_id=$salonId");
        }

        $id = $this->model->create([
            'client_id'        => $_SESSION['user_id'],
            'salon_id'         => $salonId,
            'service_id'       => $serviceId,
            'date_reservation' => $date,
            'heure_reservation'=> $heure . ':00',
            'notes'            => $notes ?: null,
            'montant'          => $service['prix'],
        ]);

        setFlash('success', 'Réservation effectuée avec succès ! Numéro : #' . $id);
        redirect('/index.php?page=reservations&action=index');
    }

    // ── Annuler une réservation ────────────────────────────────────
    public function annuler(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            if ($this->model->annuler($id, $_SESSION['user_id'])) {
                setFlash('success', 'Réservation annulée.');
            } else {
                setFlash('error', 'Impossible d\'annuler cette réservation.');
            }
        }
        redirect('/index.php?page=reservations&action=index');
    }

    // ── Créneaux disponibles (AJAX JSON) ──────────────────────────
    public function creneaux(): void {
        $salonId   = (int) ($_GET['salon_id'] ?? 0);
        $serviceId = (int) ($_GET['service_id'] ?? 0);
        $date      = sanitize($_GET['date'] ?? '');

        if (!$salonId || !$serviceId || !$date) {
            jsonResponse(['error' => 'Paramètres manquants'], 400);
        }

        $salon   = $this->salonModel->getById($salonId);
        $service = $this->serviceModel->getById($serviceId);

        if (!$salon || !$service) {
            jsonResponse(['error' => 'Ressource introuvable'], 404);
        }

        $ouverture = strtotime($date . ' ' . $salon['heure_ouverture']);
        $fermeture = strtotime($date . ' ' . $salon['heure_fermeture']);
        $duree     = $service['duree_minutes'] * 60;
        $creneaux  = [];

        for ($t = $ouverture; $t + $duree <= $fermeture; $t += 1800) { // slots de 30 min
            $heure = date('H:i', $t);
            $disponible = $this->model->isCreneauDisponible(
                $salonId, $date, $heure, $service['duree_minutes']
            );
            $creneaux[] = ['heure' => $heure, 'disponible' => $disponible];
        }

        jsonResponse(['creneaux' => $creneaux]);
    }

    // ── Détail d'une réservation ───────────────────────────────────
    public function show(): void {
        $id = (int) ($_GET['id'] ?? 0);
        $reservation = $this->model->getById($id);

        if (!$reservation || (int) $reservation['client_id'] !== $_SESSION['user_id'] && !isAdmin()) {
            setFlash('error', 'Réservation introuvable.');
            redirect('/index.php?page=reservations&action=index');
        }

        view('client/reservation_detail', compact('reservation'));
    }
}
