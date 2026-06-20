<?php
class AvisModel extends Model {

    public function getBySalon(int $salonId): array {
        return $this->fetchAll(
            "SELECT a.*, u.nom AS client_nom, u.prenom AS client_prenom
             FROM avis a
             JOIN utilisateurs u ON u.id = a.client_id
             WHERE a.salon_id = ?
             ORDER BY a.created_at DESC",
            [$salonId]
        );
    }

    public function getAll(): array {
        return $this->fetchAll(
            "SELECT a.*, u.nom AS client_nom, u.prenom AS client_prenom,
                    s.nom AS salon_nom
             FROM avis a
             JOIN utilisateurs u ON u.id = a.client_id
             JOIN salons s       ON s.id = a.salon_id
             ORDER BY a.created_at DESC"
        );
    }

    public function create(array $data): int {
        // Vérifie qu'il n'y a pas déjà un avis pour cette réservation
        if ($this->existsByReservation($data['reservation_id'])) {
            throw new RuntimeException("Un avis existe déjà pour cette réservation.");
        }

        $this->execute(
            "INSERT INTO avis (client_id, salon_id, reservation_id, note, commentaire)
             VALUES (?, ?, ?, ?, ?)",
            [
                $data['client_id'],
                $data['salon_id'],
                $data['reservation_id'],
                $data['note'],
                $data['commentaire'] ?? null,
            ]
        );
        return $this->lastInsertId();
    }

    public function delete(int $id): bool {
        return $this->execute("DELETE FROM avis WHERE id = ?", [$id]);
    }

    public function existsByReservation(int $reservationId): bool {
        return $this->count(
            "SELECT COUNT(*) FROM avis WHERE reservation_id = ?",
            [$reservationId]
        ) > 0;
    }

    public function countAll(): int {
        return $this->count("SELECT COUNT(*) FROM avis");
    }

    public function getNoteMoyenneGlobale(): float {
        $result = $this->fetch("SELECT ROUND(AVG(note), 2) AS moy FROM avis");
        return (float) ($result['moy'] ?? 0);
    }
}
