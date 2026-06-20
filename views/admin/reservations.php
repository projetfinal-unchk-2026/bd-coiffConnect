<?php
$title = 'Réservations';
ob_start();

function sBadge(string $s): string {
    $map = ['en_attente'=>['warning','⏳ En attente'],'confirmee'=>['success','✅ Confirmée'],'annulee'=>['danger','❌ Annulée'],'terminee'=>['info','🏁 Terminée']];
    [$cls,$label] = $map[$s] ?? ['gray',$s];
    return "<span class='badge badge-$cls'>$label</span>";
}
?>
<div class="admin-layout">
  <aside class="sidebar">
    <div class="sidebar-title">CoiffConnect</div>
    <a href="?page=admin&action=dashboard"><span class="icon">📊</span> Tableau de bord</a>
    <a href="?page=admin&action=utilisateurs"><span class="icon">👥</span> Utilisateurs</a>
    <a href="?page=admin&action=etablissements"><span class="icon">🏪</span> Établissements</a>
    <a href="?page=admin&action=reservations" class="active"><span class="icon">📅</span> Réservations</a>
    <a href="?page=admin&action=avisClients"><span class="icon">⭐</span> Avis clients</a>
    <div style="border-top:1px solid rgba(255,255,255,.1);margin:1rem 0"></div>
    <a href="?page=logout"><span class="icon">🚪</span> Déconnexion</a>
  </aside>

  <main class="admin-main">
    <div style="margin-bottom:1.5rem">
      <h1 style="font-size:1.5rem;font-weight:800;color:var(--primary)">📅 Réservations</h1>
      <p style="color:var(--gray);font-size:.9rem"><?= count($reservations) ?> résultat(s)</p>
    </div>

    <!-- Filtres -->
    <div class="card" style="margin-bottom:1.25rem;padding:1rem 1.25rem">
      <form method="GET" action="" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap">
        <input type="hidden" name="page" value="admin">
        <input type="hidden" name="action" value="reservations">

        <div class="form-group" style="margin:0;flex:1;min-width:140px">
          <label style="font-size:.82rem">Statut</label>
          <select name="statut" class="form-control" style="padding:.45rem .75rem">
            <option value="">Tous les statuts</option>
            <?php foreach (['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'] as $v=>$l): ?>
              <option value="<?= $v ?>" <?= ($filters['statut']==$v)?'selected':'' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group" style="margin:0;flex:1;min-width:140px">
          <label style="font-size:.82rem">Date début</label>
          <input type="date" name="date_debut" class="form-control" style="padding:.45rem .75rem" value="<?= htmlspecialchars($filters['date_debut']) ?>">
        </div>
        <div class="form-group" style="margin:0;flex:1;min-width:140px">
          <label style="font-size:.82rem">Date fin</label>
          <input type="date" name="date_fin" class="form-control" style="padding:.45rem .75rem" value="<?= htmlspecialchars($filters['date_fin']) ?>">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">🔍 Filtrer</button>
        <a href="?page=admin&action=reservations" class="btn btn-outline btn-sm">↺ Reset</a>
      </form>
    </div>

    <div class="card" style="padding:0">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Client</th>
              <th>Service</th>
              <th>Salon</th>
              <th>Date & Heure</th>
              <th>Montant</th>
              <th>Statut</th>
              <th>Changer statut</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $r): ?>
            <tr>
              <td style="color:var(--gray);font-size:.85rem">#<?= $r['id'] ?></td>
              <td>
                <div style="font-weight:600;font-size:.9rem"><?= htmlspecialchars($r['client_prenom'] . ' ' . $r['client_nom']) ?></div>
              </td>
              <td style="font-size:.88rem"><?= htmlspecialchars($r['service_nom']) ?></td>
              <td style="font-size:.88rem"><?= htmlspecialchars($r['salon_nom']) ?></td>
              <td style="font-size:.85rem">
                <div style="font-weight:600"><?= formatDate($r['date_reservation']) ?></div>
                <div style="color:var(--gray)"><?= formatHeure($r['heure_reservation']) ?></div>
              </td>
              <td style="font-weight:700;color:var(--primary)"><?= formatMontant($r['montant']) ?></td>
              <td><?= sBadge($r['statut']) ?></td>
              <td>
                <form method="POST" action="?page=admin&action=updateStatutReservation" style="display:flex;gap:.4rem;align-items:center">
                  <input type="hidden" name="id" value="<?= $r['id'] ?>">
                  <select name="statut" class="form-control" style="padding:.3rem .5rem;font-size:.8rem;width:130px">
                    <?php foreach (['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'] as $v=>$l): ?>
                      <option value="<?= $v ?>" <?= $r['statut']==$v?'selected':'' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit" class="btn btn-primary btn-sm">✓</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($reservations)): ?>
              <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--gray)">Aucune réservation trouvée</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>
<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
