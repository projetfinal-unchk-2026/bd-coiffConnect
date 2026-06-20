<?php
require_once ROOT_PATH . '/models/Model.php';
require_once ROOT_PATH . '/models/AvisModel.php';
require_once ROOT_PATH . '/models/ReservationModel.php';

class AvisController {
    private AvisModel        $model;
    private ReservationModel $reservModel;

    public function __construct() {
        $this->model       = new AvisModel();
        $this->reservModel = new ReservationModel();
    }

    public function index(): void {
        redirect('/index.php?page=reservations&action=index');
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/index.php?page=reservations&action=index');
        }

        $reservationId = (int) ($_POST['reservation_id'] ?? 0);
        $note          = (int) ($_POST['note'] ?? 0);
        $commentaire   = sanitize($_POST['commentaire'] ?? '');

        $reservation = $this->reservModel->getById($reservationId);

        if (!$reservation || (int) $reservation['client_id'] !== $_SESSION['user_id']) {
            setFlash('error', 'Réservation invalide.');
            redirect('/index.php?page=reservations&action=index');
        }

        if ($reservation['statut'] !== 'terminee') {
            setFlash('error', 'Vous ne pouvez laisser un avis que pour une réservation terminée.');
            redirect('/index.php?page=reservations&action=index');
        }

        if ($note < 1 || $note > 5) {
            setFlash('error', 'La note doit être entre 1 et 5.');
            redirect('/index.php?page=reservations&action=index');
        }

        try {
            $this->model->create([
                'client_id'      => $_SESSION['user_id'],
                'salon_id'       => $reservation['salon_id'],
                'reservation_id' => $reservationId,
                'note'           => $note,
                'commentaire'    => $commentaire ?: null,
            ]);
            setFlash('success', 'Merci pour votre avis !');
        } catch (RuntimeException $e) {
            setFlash('error', $e->getMessage());
        }

        redirect('/index.php?page=reservations&action=index');
    }
}
