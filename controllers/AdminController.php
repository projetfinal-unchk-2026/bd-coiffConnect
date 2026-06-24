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

    // ── Statistiques détaillées (page Statistiques) ─────────────────
    public function statistiques(): void {
        $evolution = $this->reservModel->getEvolutionMensuelle(); // 6 derniers mois : mois, nb, total

        $evolutionCA = array_map(fn($r) => [
            'mois'   => $this->moisFr($r['mois']),
            'revenu' => (float) $r['total'],
        ], $evolution);

        $dernier      = end($evolution) ?: ['nb' => 0, 'total' => 0];
        $avantDernier = count($evolution) >= 2 ? $evolution[count($evolution) - 2] : ['nb' => 0, 'total' => 0];

        $revenuActuel    = (float) $dernier['total'];
        $revenuPrecedent = (float) $avantDernier['total'];
        $croissance = $revenuPrecedent > 0
            ? round((($revenuActuel - $revenuPrecedent) / $revenuPrecedent) * 100, 1)
            : 0;

        $comparaisonMois = [
            'mois_precedent' => [
                'label'        => $this->moisFr($avantDernier['mois'] ?? ''),
                'revenu'       => (float) ($avantDernier['total'] ?? 0),
                'reservations' => (int) ($avantDernier['nb'] ?? 0),
            ],
            'mois_actuel' => [
                'label'        => $this->moisFr($dernier['mois'] ?? ''),
                'revenu'       => $revenuActuel,
                'reservations' => (int) ($dernier['nb'] ?? 0),
            ],
        ];

        $statsGlobales = [
            'revenu_total'         => $this->reservModel->getTotalRevenu(),
            'croissance'           => $croissance,
            'reservations_totales' => $this->reservModel->countAll(),
            'note_moyenne'         => $this->salonModel->getNoteMoyenneGlobale(),
        ];

        $topEtablissements = $this->salonModel->getTopParRevenu(5);
        $topServices       = $this->getRepartitionServicesCategorisee();

        view('admin/statistiques', compact(
            'statsGlobales', 'evolutionCA', 'comparaisonMois', 'topEtablissements', 'topServices'
        ));
    }

    // ── Privé : regroupe les services par grande catégorie (Coiffure/Spa/Massage) ──
    private function getRepartitionServicesCategorisee(): array {
        $rows = $this->reservModel->getRepartitionServices();
        $categories = ['Coiffure' => 0, 'Spa' => 0, 'Massage' => 0, 'Autre' => 0];

        foreach ($rows as $r) {
            $nom = strtolower($r['service_nom']);
            if (str_contains($nom, 'coiff') || str_contains($nom, 'coupe') || str_contains($nom, 'tress') || str_contains($nom, 'barb')) {
                $categories['Coiffure'] += (int) $r['nb'];
            } elseif (str_contains($nom, 'spa')) {
                $categories['Spa'] += (int) $r['nb'];
            } elseif (str_contains($nom, 'mass')) {
                $categories['Massage'] += (int) $r['nb'];
            } else {
                $categories['Autre'] += (int) $r['nb'];
            }
        }

        $total = array_sum($categories) ?: 1;
        $result = [];
        foreach ($categories as $nom => $nb) {
            if ($nb > 0) {
                $result[] = ['nom' => $nom, 'valeur' => round($nb / $total * 100)];
            }
        }
        return $result;
    }

    // ── Privé : convertit "2026-06" en "Jun" pour l'affichage ──────────
    private function moisFr(string $ym): string {
        $mois = [
            '01' => 'Jan', '02' => 'Fév', '03' => 'Mar', '04' => 'Avr',
            '05' => 'Mai', '06' => 'Jun', '07' => 'Jul', '08' => 'Aoû',
            '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Déc',
        ];
        $parts = explode('-', $ym);
        return $mois[$parts[1] ?? ''] ?? $ym;
    }
}