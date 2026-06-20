<?php
class SalonModel extends Model {

    public function getAll(bool $activeOnly = true): array {
        $where = $activeOnly ? "WHERE s.est_actif = 1" : "";
        return $this->fetchAll(
            "SELECT s.*, u.nom AS proprietaire_nom, u.prenom AS proprietaire_prenom
             FROM salons s
             JOIN utilisateurs u ON u.id = s.proprietaire_id
             $where
             ORDER BY s.note_moyenne DESC"
        );
    }

    public function getById(int $id): ?array {
        return $this->fetch(
            "SELECT s.*, u.nom AS proprietaire_nom, u.prenom AS proprietaire_prenom
             FROM salons s
             JOIN utilisateurs u ON u.id = s.proprietaire_id
             WHERE s.id = ?",
            [$id]
        );
    }

    public function getByProprietaire(int $userId): array {
        return $this->fetchAll(
            "SELECT * FROM salons WHERE proprietaire_id = ? ORDER BY created_at DESC",
            [$userId]
        );
    }

    public function search(string $service = '', string $ville = ''): array {
        $conditions = ["s.est_actif = 1"];
        $params = [];

        if ($ville) {
            $conditions[] = "(s.ville LIKE ? OR s.quartier LIKE ?)";
            $params[] = "%$ville%";
            $params[] = "%$ville%";
        }

        if ($service) {
            $conditions[] = "EXISTS (
                SELECT 1 FROM services sv
                WHERE sv.salon_id = s.id
                AND sv.nom LIKE ?
                AND sv.est_actif = 1
            )";
            $params[] = "%$service%";
        }

        $where = "WHERE " . implode(" AND ", $conditions);
        return $this->fetchAll(
            "SELECT s.*, u.nom AS proprietaire_nom
             FROM salons s
             JOIN utilisateurs u ON u.id = s.proprietaire_id
             $where
             ORDER BY s.note_moyenne DESC",
            $params
        );
    }

    public function create(array $data): int {
        $this->execute(
            "INSERT INTO salons (proprietaire_id, nom, description, adresse, quartier, ville, telephone, email, photo_url, heure_ouverture, heure_fermeture)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['proprietaire_id'],
                $data['nom'],
                $data['description'] ?? null,
                $data['adresse'] ?? null,
                $data['quartier'],
                $data['ville'] ?? 'Dakar',
                $data['telephone'] ?? null,
                $data['email'] ?? null,
                $data['photo_url'] ?? null,
                $data['heure_ouverture'] ?? '08:00:00',
                $data['heure_fermeture'] ?? '20:00:00',
            ]
        );
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        return $this->execute(
            "UPDATE salons SET nom=?, description=?, adresse=?, quartier=?, ville=?,
             telephone=?, email=?, heure_ouverture=?, heure_fermeture=?, est_actif=?
             WHERE id = ?",
            [
                $data['nom'],
                $data['description'] ?? null,
                $data['adresse'] ?? null,
                $data['quartier'],
                $data['ville'] ?? 'Dakar',
                $data['telephone'] ?? null,
                $data['email'] ?? null,
                $data['heure_ouverture'] ?? '08:00:00',
                $data['heure_fermeture'] ?? '20:00:00',
                $data['est_actif'] ?? 1,
                $id,
            ]
        );
    }

    public function delete(int $id): bool {
        return $this->execute(
            "UPDATE salons SET est_actif = 0 WHERE id = ?",
            [$id]
        );
    }

    public function countAll(): int {
        return $this->count("SELECT COUNT(*) FROM salons WHERE est_actif = 1");
    }

    public function getPopulaires(int $limit = 5): array {
        return $this->fetchAll(
            "SELECT s.*, COUNT(r.id) AS nb_reservations
             FROM salons s
             LEFT JOIN reservations r ON r.salon_id = s.id
             WHERE s.est_actif = 1
             GROUP BY s.id
             ORDER BY s.note_moyenne DESC, nb_reservations DESC
             LIMIT ?",
            [$limit]
        );
    }
}
