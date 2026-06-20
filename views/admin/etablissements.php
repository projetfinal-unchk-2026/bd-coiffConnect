<?php
$title = 'Établissements';
ob_start();
?>
<div class="admin-layout">
  <aside class="sidebar">
    <div class="sidebar-title">CoiffConnect</div>
    <a href="?page=admin&action=dashboard"><span class="icon">📊</span> Tableau de bord</a>
    <a href="?page=admin&action=utilisateurs"><span class="icon">👥</span> Utilisateurs</a>
    <a href="?page=admin&action=etablissements" class="active"><span class="icon">🏪</span> Établissements</a>
    <a href="?page=admin&action=reservations"><span class="icon">📅</span> Réservations</a>
    <a href="?page=admin&action=avisClients"><span class="icon">⭐</span> Avis clients</a>
    <div style="border-top:1px solid rgba(255,255,255,.1);margin:1rem 0"></div>
    <a href="?page=logout"><span class="icon">🚪</span> Déconnexion</a>
  </aside>

  <main class="admin-main">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
      <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--primary)">🏪 Établissements</h1>
        <p style="color:var(--gray);font-size:.9rem"><?= count($salons) ?> salon(s) enregistré(s)</p>
      </div>
    </div>

    <div class="card" style="padding:0">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Salon</th>
              <th>Propriétaire</th>
              <th>Localisation</th>
              <th>Contact</th>
              <th>Horaires</th>
              <th>Note</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($salons as $s): ?>
            <tr>
              <td style="color:var(--gray);font-size:.85rem">#<?= $s['id'] ?></td>
              <td>
                <div style="display:flex;align-items:center;gap:.6rem">
                  <div style="width:38px;height:38px;border-radius:8px;background:linear-gradient(135deg,var(--primary),#5a3060);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">✂️</div>
                  <div>
                    <div style="font-weight:700;font-size:.9rem"><?= htmlspecialchars($s['nom']) ?></div>
                    <div style="font-size:.78rem;color:var(--gray)"><?= mb_substr(htmlspecialchars($s['description'] ?? ''), 0, 40) ?>…</div>
                  </div>
                </div>
              </td>
              <td style="font-size:.88rem">
                <?= htmlspecialchars($s['proprietaire_prenom'] . ' ' . $s['proprietaire_nom']) ?>
              </td>
              <td style="font-size:.85rem">
                📍 <?= htmlspecialchars($s['quartier']) ?>, <?= htmlspecialchars($s['ville']) ?>
              </td>
              <td style="font-size:.85rem"><?= htmlspecialchars($s['telephone'] ?? '—') ?></td>
              <td style="font-size:.82rem;color:var(--gray)">
                <?= formatHeure($s['heure_ouverture']) ?> – <?= formatHeure($s['heure_fermeture']) ?>
              </td>
              <td>
                <div style="display:flex;align-items:center;gap:.3rem">
                  <span style="color:var(--gold);font-size:.9rem">★</span>
                  <span style="font-weight:700"><?= $s['note_moyenne'] ?></span>
                </div>
              </td>
              <td>
                <?php if ($s['est_actif']): ?>
                  <span class="badge badge-success">Actif</span>
                <?php else: ?>
                  <span class="badge badge-danger">Inactif</span>
                <?php endif; ?>
              </td>
              <td>
                <div style="display:flex;gap:.4rem;flex-wrap:wrap">
                  <a href="?page=salons&action=show&id=<?= $s['id'] ?>" class="btn btn-outline btn-sm">👁 Voir</a>
                  <a href="?page=admin&action=toggleSalon&id=<?= $s['id'] ?>"
                     class="btn btn-sm <?= $s['est_actif'] ? 'btn-danger' : 'btn-success' ?>"
                     onclick="return confirm('Changer le statut de ce salon ?')">
                    <?= $s['est_actif'] ? '🚫 Désactiver' : '✅ Activer' ?>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($salons)): ?>
              <tr><td colspan="9" style="text-align:center;padding:3rem;color:var(--gray)">Aucun établissement trouvé</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>
<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
