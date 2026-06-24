<?php
class ReservationModel extends Model {

    public function getById(int $id): ?array {
        return $this->fetch(
            "SELECT r.*,
                    u.nom AS client_nom, u.prenom AS client_prenom, u.telephone AS client_tel,
                    s.nom AS salon_nom, s.adresse AS salon_adresse,
                    sv.nom AS service_nom, sv.duree_minutes
             FROM reservations r
             JOIN utilisateurs u  ON u.id  = r.client_id
             JOIN salons s        ON s.id  = r.salon_id
             JOIN services sv     ON sv.id = r.service_id
             WHERE r.id = ?",
            [$id]
        );
    }

    public function getByClient(int $clientId): array {
        return $this->fetchAll(
            "SELECT r.*,
                    s.nom AS salon_nom,
                    sv.nom AS service_nom, sv.duree_minutes
             FROM reservations r
             JOIN salons s    ON s.id  = r.salon_id
             JOIN services sv ON sv.id = r.service_id
             WHERE r.client_id = ?
             ORDER BY r.date_reservation DESC, r.heure_reservation DESC",
            [$clientId]
        );
    }

    public function getBySalon(int $salonId, ?string $date = null): array {
        $sql = "SELECT r.*,
                       u.nom AS client_nom, u.prenom AS client_prenom,
                       sv.nom AS service_nom, sv.duree_minutes
                FROM reservations r
                JOIN utilisateurs u ON u.id  = r.client_id
                JOIN services sv    ON sv.id = r.service_id
                WHERE r.salon_id = ?";
        $params = [$salonId];
        if ($date) {
            $sql .= " AND r.date_reservation = ?";
            $params[] = $date;
        }
        $sql .= " ORDER BY r.date_reservation, r.heure_reservation";
        return $this->fetchAll($sql, $params);
    }

    public function getAll(array $filters = []): array {
        $conditions = ["1=1"];
        $params = [];

        if (!empty($filters['statut'])) {
            $conditions[] = "r.statut = ?";
            $params[] = $filters['statut'];
        }
        if (!empty($filters['salon_id'])) {
            $conditions[] = "r.salon_id = ?";
            $params[] = $filters['salon_id'];
        }
        if (!empty($filters['date_debut'])) {
            $conditions[] = "r.date_reservation >= ?";
            $params[] = $filters['date_debut'];
        }
        if (!empty($filters['date_fin'])) {
            $conditions[] = "r.date_reservation <= ?";
            $params[] = $filters['date_fin'];
        }

        $where = implode(" AND ", $conditions);

        return $this->fetchAll(
            "SELECT r.*,
                    u.nom AS client_nom, u.prenom AS client_prenom,
                    s.nom AS salon_nom,
                    sv.nom AS service_nom
             FROM reservations r
             JOIN utilisateurs u ON u.id  = r.client_id
             JOIN salons s       ON s.id  = r.salon_id
             JOIN services sv    ON sv.id = r.service_id
             WHERE $where
             ORDER BY r.date_reservation DESC, r.heure_reservation DESC
             LIMIT 100",
            $params
        );
    }

    public function create(array $data): int {
        $this->execute(
            "INSERT INTO reservations (client_id, salon_id, service_id, date_reservation, heure_reservation, notes, montant)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $data['client_id'],
                $data['salon_id'],
                $data['service_id'],
                $data['date_reservation'],
                $data['heure_reservation'],
                $data['notes'] ?? null,
                $data['montant'],
            ]
        );
        return $this->lastInsertId();
    }

    public function updateStatut(int $id, string $statut): bool {
        $allowed = ['en_attente', 'confirmee', 'annulee', 'terminee'];
        if (!in_array($statut, $allowed)) return false;

        return $this->execute(
            "UPDATE reservations SET statut = ? WHERE id = ?",
            [$statut, $id]
        );
    }

    public function annuler(int $id, int $clientId): bool {
        return $this->execute(
            "UPDATE reservations SET statut = 'annulee'
             WHERE id = ? AND client_id = ? AND statut IN ('en_attente', 'confirmee')",
            [$id, $clientId]
        );
    }

    public function isCreneauDisponible(int $salonId, string $date, string $heure, int $dureeMinutes): bool {
        $heureDebut = $heure;
        $heureFin   = date('H:i:s', strtotime($heure) + $dureeMinutes * 60);

        $count = $this->count(
            "SELECT COUNT(*) FROM reservations r
             JOIN services sv ON sv.id = r.service_id
             WHERE r.salon_id = ?
               AND r.date_reservation = ?
               AND r.statut NOT IN ('annulee')
               AND (
                   (r.heure_reservation < ? AND ADDTIME(r.heure_reservation, SEC_TO_TIME(sv.duree_minutes * 60)) > ?)
               )",
            [$salonId, $date, $heureFin, $heureDebut]
        );
        return $count === 0;
    }

    public function countAll(): int {
        return $this->count("SELECT COUNT(*) FROM reservations");
    }

    public function countByStatut(string $statut): int {
        return $this->count(
            "SELECT COUNT(*) FROM reservations WHERE statut = ?",
            [$statut]
        );
    }

    public function getTotalRevenu(): float {
        $result = $this->fetch(
            "SELECT COALESCE(SUM(montant), 0) AS total FROM reservations WHERE statut = 'terminee'"
        );
        return (float) ($result['total'] ?? 0);
    }

    public function getRecentes(int $limit = 10): array {
        return $this->fetchAll(
            "SELECT r.*,
                    u.nom AS client_nom, u.prenom AS client_prenom,
                    s.nom AS salon_nom,
                    sv.nom AS service_nom
             FROM reservations r
             JOIN utilisateurs u ON u.id  = r.client_id
             JOIN salons s       ON s.id  = r.salon_id
             JOIN services sv    ON sv.id = r.service_id
             ORDER BY r.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    public function getEvolutionMensuelle(): array {
        return $this->fetchAll(
            "SELECT DATE_FORMAT(date_reservation, '%Y-%m') AS mois,
                    COUNT(*) AS nb,
                    SUM(montant) AS total
             FROM reservations
             WHERE date_reservation >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
             GROUP BY mois
             ORDER BY mois"
        );
    }

    // ── Ajouté pour la page Statistiques ─────────────────────────────
    public function getRepartitionServices(): array {
        return $this->fetchAll(
            "SELECT sv.nom AS service_nom, COUNT(*) AS nb
             FROM reservations r
             JOIN services sv ON sv.id = r.service_id
             WHERE r.statut != 'annulee'
             GROUP BY sv.nom
             ORDER BY nb DESC"
        );
    }
}