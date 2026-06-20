<?php
$title = 'Mon espace coiffeur';
ob_start();

function sBadge(string $s): string {
  $map = ['en_attente'=>['warning','⏳ En attente'],'confirmee'=>['success','✅ Confirmée'],'annulee'=>['danger','❌ Annulée'],'terminee'=>['info','🏁 Terminée']];
  [$cls,$label] = $map[$s] ?? ['gray',$s];
  return "<span class='badge badge-$cls'>$label</span>";
}
?>

<div class="admin-layout">
  <!-- Sidebar coiffeur -->
  <aside class="sidebar">
    <div class="sidebar-title">Mon Salon</div>
    <a href="?page=salons&action=dashboard" class="active"><span class="icon">🏠</span> Tableau de bord</a>
    <a href="?page=salons&action=create"><span class="icon">➕</span> Créer un salon</a>
    <div style="border-top:1px solid rgba(255,255,255,.1);margin:1rem 0"></div>
    <a href="?page=home"><span class="icon">🌐</span> Accueil public</a>
    <a href="?page=logout"><span class="icon">🚪</span> Déconnexion</a>
  </aside>

  <main class="admin-main">

    <?php if (empty($salons)): ?>
      <!-- Aucun salon — onboarding -->
      <div style="text-align:center;padding:4rem 2rem">
        <div style="font-size:4rem;margin-bottom:1rem">✂️</div>
        <h2 style="color:var(--primary);margin-bottom:.5rem">Bienvenue sur CoiffConnect !</h2>
        <p style="color:var(--gray);margin-bottom:2rem;max-width:400px;margin-inline:auto">
          Vous n'avez pas encore de salon enregistré. Créez-en un pour commencer à recevoir des réservations.
        </p>
        <a href="?page=salons&action=create" class="btn btn-primary" style="padding:.9rem 2rem;font-size:1rem">
          🏪 Créer mon premier salon
        </a>
      </div>

    <?php else:
      $salon = $salons[0];
      // Stats
      $total     = count($reservations);
      $enAttente = count(array_filter($reservations, fn($r) => $r['statut']==='en_attente'));
      $confirmee = count(array_filter($reservations, fn($r) => $r['statut']==='confirmee'));
      $terminee  = count(array_filter($reservations, fn($r) => $r['statut']==='terminee'));
      $revenu    = array_sum(array_column(array_filter($reservations, fn($r) => $r['statut']==='terminee'), 'montant'));
    ?>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
      <div>
        <h1 style="font-size:1.4rem;font-weight:800;color:var(--primary)"><?= htmlspecialchars($salon['nom']) ?></h1>
        <p style="color:var(--gray);font-size:.88rem">
          📍 <?= htmlspecialchars($salon['quartier']) ?>, <?= htmlspecialchars($salon['ville']) ?>
          &nbsp;·&nbsp; 🕐 <?= formatHeure($salon['heure_ouverture']) ?> – <?= formatHeure($salon['heure_fermeture']) ?>
        </p>
      </div>
      <div style="display:flex;gap:.5rem">
        <a href="?page=salons&action=editSalon&id=<?= $salon['id'] ?>" class="btn btn-outline btn-sm">⚙️ Gérer le salon</a>
        <a href="?page=salons&action=show&id=<?= $salon['id'] ?>" class="btn btn-dark btn-sm">👁 Page publique</a>
      </div>
    </div>

    <!-- Stat cards -->
    <div class="stat-grid" style="margin-bottom:1.5rem">
      <div class="stat-card">
        <div class="stat-icon blue">📅</div>
        <div><div class="stat-value"><?= $total ?></div><div class="stat-label">Total réservations</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon gold">⏳</div>
        <div><div class="stat-value"><?= $enAttente ?></div><div class="stat-label">En attente</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div><div class="stat-value"><?= $confirmee ?></div><div class="stat-label">Confirmées</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon purple">💰</div>
        <div>
          <div class="stat-value" style="font-size:1rem"><?= formatMontant($revenu) ?></div>
          <div class="stat-label">Revenus (terminées)</div>
        </div>
      </div>
    </div>

    <div class="grid-2" style="align-items:start">

      <!-- Réservations -->
      <div class="card" style="padding:0">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--gray-light);font-weight:700;color:var(--primary)">
          📅 Réservations récentes
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>Client</th><th>Service</th><th>Date</th><th>Heure</th><th>Statut</th><th>Action</th></tr>
            </thead>
            <tbody>
              <?php foreach (array_slice($reservations, 0, 15) as $r): ?>
              <tr>
                <td style="font-weight:600;font-size:.88rem"><?= htmlspecialchars($r['client_prenom'].' '.$r['client_nom']) ?></td>
                <td style="font-size:.85rem"><?= htmlspecialchars($r['service_nom']) ?></td>
                <td style="font-size:.85rem"><?= formatDate($r['date_reservation']) ?></td>
                <td style="font-size:.85rem"><?= formatHeure($r['heure_reservation']) ?></td>
                <td><?= sBadge($r['statut']) ?></td>
                <td>
                  <?php if ($r['statut'] === 'en_attente'): ?>
                    <form method="POST" action="?page=salons&action=updateReservation" style="display:flex;gap:.3rem">
                      <input type="hidden" name="id" value="<?= $r['id'] ?>">
                      <input type="hidden" name="statut" value="confirmee">
                      <button type="submit" class="btn btn-success btn-sm">✓</button>
                    </form>
                  <?php elseif ($r['statut'] === 'confirmee'): ?>
                    <form method="POST" action="?page=salons&action=updateReservation" style="display:flex;gap:.3rem">
                      <input type="hidden" name="id" value="<?= $r['id'] ?>">
                      <input type="hidden" name="statut" value="terminee">
                      <button type="submit" class="btn btn-primary btn-sm">🏁</button>
                    </form>
                  <?php else: ?>
                    <span style="font-size:.78rem;color:var(--gray)">—</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($reservations)): ?>
                <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--gray)">Aucune réservation pour le moment</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Avis -->
      <div class="card">
        <div class="card-header">⭐ Avis clients (<?= count($avis) ?>)</div>
        <?php if (!empty($avis)): ?>
          <?php foreach (array_slice($avis,0,5) as $a): ?>
            <div style="padding:.75rem 0;border-bottom:1px solid var(--gray-light)">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.3rem">
                <span style="font-weight:600;font-size:.88rem"><?= htmlspecialchars($a['client_prenom'].' '.$a['client_nom']) ?></span>
                <div>
                  <?php for ($i=1;$i<=5;$i++): ?>
                    <span style="color:<?= $i<=$a['note']?'var(--gold)':'var(--gray-light)' ?>;font-size:.9rem">★</span>
                  <?php endfor; ?>
                </div>
              </div>
              <p style="font-size:.82rem;color:var(--gray);margin:0"><?= htmlspecialchars($a['commentaire'] ?? '') ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color:var(--gray);text-align:center;padding:1.5rem;font-size:.88rem">Aucun avis pour le moment.</p>
        <?php endif; ?>

        <?php if (count($salons) > 1): ?>
          <div style="margin-top:1rem">
            <?php foreach (array_slice($salons,1) as $s): ?>
              <a href="?page=salons&action=editSalon&id=<?= $s['id'] ?>"
                 style="font-size:.85rem;color:var(--gold)">
                + <?= htmlspecialchars($s['nom']) ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <?php endif; ?>
  </main>
</div>

<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
