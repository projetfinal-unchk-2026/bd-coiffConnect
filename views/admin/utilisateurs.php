<?php
$title = 'Gestion des utilisateurs';
ob_start();
?>
<div class="admin-layout">
  <aside class="sidebar">
    <div class="sidebar-title">CoiffConnect</div>
    <a href="?page=admin&action=dashboard"><span class="icon">📊</span> Tableau de bord</a>
    <a href="?page=admin&action=utilisateurs" class="active"><span class="icon">👥</span> Utilisateurs</a>
    <a href="?page=admin&action=etablissements"><span class="icon">🏪</span> Établissements</a>
    <a href="?page=admin&action=reservations"><span class="icon">📅</span> Réservations</a>
    <a href="?page=admin&action=avisClients"><span class="icon">⭐</span> Avis clients</a>
    <div style="border-top:1px solid rgba(255,255,255,.1);margin:1rem 0"></div>
    <a href="?page=logout"><span class="icon">🚪</span> Déconnexion</a>
  </aside>

  <main class="admin-main">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
      <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--primary)">👥 Utilisateurs</h1>
        <p style="color:var(--gray);font-size:.9rem"><?= count($users) ?> utilisateurs enregistrés</p>
      </div>
    </div>

    <!-- Filtres rôles -->
    <div style="display:flex;gap:.5rem;margin-bottom:1.25rem;flex-wrap:wrap">
      <?php
      $roleStats = ['Tous' => count($users)];
      foreach ($users as $u) $roleStats[ucfirst($u['role'])] = ($roleStats[ucfirst($u['role'])] ?? 0) + 1;
      foreach ($roleStats as $label => $count): ?>
        <span style="background:var(--white);border:1.5px solid var(--gray-light);border-radius:20px;padding:.3rem .9rem;font-size:.85rem;font-weight:600;color:var(--primary)">
          <?= $label ?> <span style="color:var(--gold)">(<?= $count ?>)</span>
        </span>
      <?php endforeach; ?>
    </div>

    <div class="card" style="padding:0">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nom complet</th>
              <th>Email</th>
              <th>Téléphone</th>
              <th>Rôle</th>
              <th>Statut</th>
              <th>Inscrit le</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
              <td style="color:var(--gray);font-size:.85rem">#<?= $u['id'] ?></td>
              <td>
                <div style="display:flex;align-items:center;gap:.6rem">
                  <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#5a3060);display:flex;align-items:center;justify-content:center;color:var(--gold);font-weight:700;font-size:.85rem;flex-shrink:0">
                    <?= strtoupper(substr($u['prenom'],0,1)) . strtoupper(substr($u['nom'],0,1)) ?>
                  </div>
                  <span style="font-weight:600"><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></span>
                </div>
              </td>
              <td style="font-size:.88rem"><?= htmlspecialchars($u['email']) ?></td>
              <td style="font-size:.88rem"><?= htmlspecialchars($u['telephone'] ?? '—') ?></td>
              <td>
                <?php
                $roleMeta = [
                  'admin'    => ['🛡️', '#ede9fe', '#6d28d9'],
                  'coiffeur' => ['✂️', '#fef3c7', '#92400e'],
                  'client'   => ['💅', '#dbeafe', '#1e40af'],
                ];
                [$icon, $bg, $color] = $roleMeta[$u['role']] ?? ['?', '#eee', '#333'];
                ?>
                <span style="background:<?= $bg ?>;color:<?= $color ?>;padding:.25rem .7rem;border-radius:20px;font-size:.78rem;font-weight:600">
                  <?= $icon ?> <?= ucfirst($u['role']) ?>
                </span>
              </td>
              <td>
                <?php if ($u['est_actif']): ?>
                  <span class="badge badge-success">Actif</span>
                <?php else: ?>
                  <span class="badge badge-danger">Désactivé</span>
                <?php endif; ?>
              </td>
              <td style="font-size:.85rem;color:var(--gray)"><?= formatDate($u['created_at']) ?></td>
              <td>
                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                  <a href="?page=admin&action=deleteUser&id=<?= $u['id'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('Désactiver cet utilisateur ?')">
                    🗑 Désactiver
                  </a>
                <?php else: ?>
                  <span style="font-size:.8rem;color:var(--gray)">Vous</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($users)): ?>
              <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--gray)">Aucun utilisateur trouvé</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>
<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
