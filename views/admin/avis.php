<?php
$title = 'Avis clients';
ob_start();
?>
<div class="admin-layout">
  <aside class="sidebar">
    <div class="sidebar-title">CoiffConnect</div>
    <a href="?page=admin&action=dashboard"><span class="icon">📊</span> Tableau de bord</a>
    <a href="?page=admin&action=utilisateurs"><span class="icon">👥</span> Utilisateurs</a>
    <a href="?page=admin&action=etablissements"><span class="icon">🏪</span> Établissements</a>
    <a href="?page=admin&action=reservations"><span class="icon">📅</span> Réservations</a>
    <a href="?page=admin&action=avisClients" class="active"><span class="icon">⭐</span> Avis clients</a>
    <div style="border-top:1px solid rgba(255,255,255,.1);margin:1rem 0"></div>
    <a href="?page=logout"><span class="icon">🚪</span> Déconnexion</a>
  </aside>

  <main class="admin-main">
    <div style="margin-bottom:1.5rem">
      <h1 style="font-size:1.5rem;font-weight:800;color:var(--primary)">⭐ Avis clients</h1>
      <p style="color:var(--gray);font-size:.9rem"><?= count($avis) ?> avis publiés</p>
    </div>

    <div class="card" style="padding:0">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Client</th>
              <th>Salon</th>
              <th>Note</th>
              <th>Commentaire</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($avis as $a): ?>
            <tr>
              <td style="color:var(--gray);font-size:.85rem">#<?= $a['id'] ?></td>
              <td style="font-weight:600;font-size:.9rem">
                <?= htmlspecialchars($a['client_prenom'] . ' ' . $a['client_nom']) ?>
              </td>
              <td style="font-size:.88rem"><?= htmlspecialchars($a['salon_nom']) ?></td>
              <td>
                <div style="display:flex;gap:2px">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span style="color:<?= $i <= $a['note'] ? 'var(--gold)' : 'var(--gray-light)' ?>;font-size:1rem">★</span>
                  <?php endfor; ?>
                </div>
                <div style="font-size:.78rem;color:var(--gray)"><?= $a['note'] ?>/5</div>
              </td>
              <td style="font-size:.88rem;max-width:280px">
                <?= htmlspecialchars($a['commentaire'] ?? '—') ?>
              </td>
              <td style="font-size:.85rem;color:var(--gray)"><?= formatDate($a['created_at']) ?></td>
              <td>
                <a href="?page=admin&action=deleteAvis&id=<?= $a['id'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Supprimer cet avis définitivement ?')">
                  🗑 Supprimer
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($avis)): ?>
              <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--gray)">Aucun avis pour le moment</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>
<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
