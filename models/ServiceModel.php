<?php
class ServiceModel extends Model {

    public function getBySalon(int $salonId, bool $activeOnly = true): array {
        $where = $activeOnly ? "AND est_actif = 1" : "";
        return $this->fetchAll(
            "SELECT * FROM services WHERE salon_id = ? $where ORDER BY nom",
            [$salonId]
        );
    }

    public function getById(int $id): ?array {
        return $this->fetch("SELECT * FROM services WHERE id = ?", [$id]);
    }

    public function create(array $data): int {
        $this->execute(
            "INSERT INTO services (salon_id, nom, description, prix, duree_minutes)
             VALUES (?, ?, ?, ?, ?)",
            [
                $data['salon_id'],
                $data['nom'],
                $data['description'] ?? null,
                $data['prix'],
                $data['duree_minutes'] ?? 30,
            ]
        );
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        return $this->execute(
            "UPDATE services SET nom=?, description=?, prix=?, duree_minutes=?, est_actif=?
             WHERE id = ?",
            [
                $data['nom'],
                $data['description'] ?? null,
                $data['prix'],
                $data['duree_minutes'] ?? 30,
                $data['est_actif'] ?? 1,
                $id,
            ]
        );
    }

    public function delete(int $id): bool {
        return $this->execute(
            "UPDATE services SET est_actif = 0 WHERE id = ?",
            [$id]
        );
    }
}
