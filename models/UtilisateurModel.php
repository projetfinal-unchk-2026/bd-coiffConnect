<?php
class UtilisateurModel extends Model {

    public function findByEmail(string $email): ?array {
        return $this->fetch(
            "SELECT * FROM utilisateurs WHERE email = ? AND est_actif = 1",
            [$email]
        );
    }

    public function findById(int $id): ?array {
        return $this->fetch(
            "SELECT id, nom, prenom, email, telephone, role, est_actif, created_at
             FROM utilisateurs WHERE id = ?",
            [$id]
        );
    }

    public function create(array $data): int {
        $this->execute(
            "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role)
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['nom'],
                $data['prenom'],
                $data['email'],
                hashPassword($data['mot_de_passe']),
                $data['telephone'] ?? null,
                $data['role'] ?? 'client',
            ]
        );
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [];

        foreach (['nom', 'prenom', 'telephone'] as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }

        if (isset($data['mot_de_passe']) && $data['mot_de_passe'] !== '') {
            $fields[] = "mot_de_passe = ?";
            $params[] = hashPassword($data['mot_de_passe']);
        }

        if (empty($fields)) return false;

        $params[] = $id;
        return $this->execute(
            "UPDATE utilisateurs SET " . implode(', ', $fields) . " WHERE id = ?",
            $params
        );
    }

    public function delete(int $id): bool {
        return $this->execute(
            "UPDATE utilisateurs SET est_actif = 0 WHERE id = ?",
            [$id]
        );
    }

    // ── Admin ──────────────────────────────────────────────────────

    public function getAll(int $limit = 50, int $offset = 0): array {
        return $this->fetchAll(
            "SELECT id, nom, prenom, email, telephone, role, est_actif, created_at
             FROM utilisateurs
             ORDER BY created_at DESC
             LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public function countAll(): int {
        return $this->count("SELECT COUNT(*) FROM utilisateurs WHERE est_actif = 1");
    }

    public function countByRole(string $role): int {
        return $this->count(
            "SELECT COUNT(*) FROM utilisateurs WHERE role = ? AND est_actif = 1",
            [$role]
        );
    }

    public function emailExists(string $email, ?int $excludeId = null): bool {
        if ($excludeId) {
            return $this->count(
                "SELECT COUNT(*) FROM utilisateurs WHERE email = ? AND id != ?",
                [$email, $excludeId]
            ) > 0;
        }
        return $this->count(
            "SELECT COUNT(*) FROM utilisateurs WHERE email = ?",
            [$email]
        ) > 0;
    }
}
