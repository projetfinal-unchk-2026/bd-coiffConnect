<?php
$title = 'Mes réservations';
ob_start();

function rBadge(string $s): string {
  $map = [
    'en_attente' => ['#fef3c7','#92400e','⏳'],
    'confirmee'  => ['#dcfce7','#166534','✅'],
    'annulee'    => ['#fee2e2','#991b1b','❌'],
    'terminee'   => ['#dbeafe','#1e40af','🏁'],
  ];
  [$bg,$color,$icon] = $map[$s] ?? ['#eee','#333','?'];
  $labels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'];
  return "<span style='background:$bg;color:$color;padding:.3rem .8rem;border-radius:20px;font-size:.78rem;font-weight:700'>$icon {$labels[$s]}</span>";
}
?>

<section style="background:linear-gradient(135deg,var(--primary),#5a3060);padding:2.5rem 0">
  <div class="container">
    <h1 style="color:white;font-size:1.6rem;font-weight:800">📅 Mes réservations</h1>
    <p style="color:rgba(255,255,255,.7);font-size:.9rem">
      Bonjour <?= htmlspecialchars(currentUser()['prenom']) ?> — <?= count($reservations) ?> réservation(s)
    </p>
  </div>
</section>

<div class="container page-content">

  <div style="display:flex;justify-content:flex-end;margin-bottom:1.5rem">
    <a href="<?= APP_URL ?>/index.php?page=salons&action=index" class="btn btn-primary">
      + Nouvelle réservation
    </a>
  </div>

  <?php if (!empty($reservations)): ?>

    <!-- Stats rapides -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem">
      <?php
      $counts = ['en_attente'=>0,'confirmee'=>0,'terminee'=>0,'annulee'=>0];
      foreach ($reservations as $r) $counts[$r['statut']] = ($counts[$r['statut']] ?? 0) + 1;
      $statCards = [
        ['⏳','En attente',$counts['en_attente'],'#fef3c7','#92400e'],
        ['✅','Confirmées', $counts['confirmee'], '#dcfce7','#166534'],
        ['🏁','Terminées',  $counts['terminee'],  '#dbeafe','#1e40af'],
        ['❌','Annulées',   $counts['annulee'],   '#fee2e2','#991b1b'],
      ];
      foreach ($statCards as [$icon,$label,$val,$bg,$color]):
      ?>
        <div style="background:$bg;background:<?= $bg ?>;border-radius:var(--radius);padding:1rem;text-align:center">
          <div style="font-size:1.5rem"><?= $icon ?></div>
          <div style="font-size:1.5rem;font-weight:800;color:<?= $color ?>"><?= $val ?></div>
          <div style="font-size:.8rem;color:<?= $color ?>;opacity:.8"><?= $label ?></div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Liste réservations -->
    <div style="display:grid;gap:1.25rem">
      <?php foreach ($reservations as $r): ?>
        <div class="card" style="padding:0;overflow:hidden">
          <div style="display:grid;grid-template-columns:6px 1fr auto;gap:0">

            <!-- Barre couleur statut -->
            <div style="background:<?php
              echo match($r['statut']) {
                'confirmee'  => '#16a34a',
                'en_attente' => '#d97706',
                'annulee'    => '#dc2626',
                'terminee'   => '#2563eb',
                default      => '#6b7280',
              };
            ?>"></div>

            <!-- Contenu -->
            <div style="padding:1.25rem">
              <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:.75rem;flex-wrap:wrap;gap:.5rem">
                <div>
                  <div style="font-weight:800;font-size:1rem;color:var(--primary)">
                    <?= htmlspecialchars($r['service_nom']) ?>
                  </div>
                  <div style="font-size:.88rem;color:var(--gray);margin-top:.15rem">
                    🏪 <?= htmlspecialchars($r['salon_nom']) ?>
                  </div>
                </div>
                <?= rBadge($r['statut']) ?>
              </div>

              <div style="display:flex;gap:2rem;flex-wrap:wrap;font-size:.88rem">
                <span style="color:var(--gray)">📅 <strong style="color:var(--primary)"><?= formatDate($r['date_reservation']) ?></strong></span>
                <span style="color:var(--gray)">🕐 <strong style="color:var(--primary)"><?= formatHeure($r['heure_reservation']) ?></strong></span>
                <span style="color:var(--gray)">⏱ <strong style="color:var(--primary)"><?= $r['duree_minutes'] ?> min</strong></span>
                <span style="color:var(--gray)">💰 <strong style="color:var(--gold)"><?= formatMontant($r['montant']) ?></strong></span>
              </div>

              <!-- Actions -->
              <div style="margin-top:1rem;display:flex;gap:.75rem;flex-wrap:wrap;align-items:center">

                <?php if (in_array($r['statut'], ['en_attente','confirmee'])): ?>
                  <a href="<?= APP_URL ?>/index.php?page=reservations&action=annuler&id=<?= $r['id'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('Confirmer l\'annulation de cette réservation ?')">
                    ❌ Annuler
                  </a>
                <?php endif; ?>

                <?php if ($r['statut'] === 'terminee'): ?>
                  <!-- Bouton laisser un avis -->
                  <button type="button" class="btn btn-primary btn-sm"
                          onclick="toggleAvis(<?= $r['id'] ?>)">
                    ⭐ Laisser un avis
                  </button>
                <?php endif; ?>

                <a href="<?= APP_URL ?>/index.php?page=salons&action=show&id=<?= $r['salon_id'] ?>"
                   class="btn btn-outline btn-sm">
                  Voir le salon
                </a>
              </div>

              <!-- Formulaire avis (caché par défaut) -->
              <?php if ($r['statut'] === 'terminee'): ?>
                <div id="avis-form-<?= $r['id'] ?>" style="display:none;margin-top:1rem;
                     background:var(--bg);border-radius:var(--radius);padding:1rem">
                  <div style="font-weight:700;color:var(--primary);margin-bottom:.75rem">Votre avis sur ce service :</div>
                  <form method="POST" action="<?= APP_URL ?>/index.php?page=avis&action=store">
                    <input type="hidden" name="reservation_id" value="<?= $r['id'] ?>">

                    <!-- Étoiles interactives -->
                    <div style="margin-bottom:.75rem">
                      <div style="font-size:.88rem;font-weight:600;margin-bottom:.4rem">Note *</div>
                      <div style="display:flex;gap:.3rem" id="stars-<?= $r['id'] ?>">
                        <?php for ($i=1;$i<=5;$i++): ?>
                          <label style="cursor:pointer">
                            <input type="radio" name="note" value="<?= $i ?>" required style="display:none">
                            <span class="star-icon" data-val="<?= $i ?>"
                                  style="font-size:1.8rem;color:var(--gray-light);transition:color .15s">★</span>
                          </label>
                        <?php endfor; ?>
                      </div>
                    </div>

                    <div class="form-group" style="margin-bottom:.75rem">
                      <label style="font-size:.88rem">Commentaire (optionnel)</label>
                      <textarea name="commentaire" class="form-control" rows="2"
                                placeholder="Partagez votre expérience…"></textarea>
                    </div>

                    <div style="display:flex;gap:.5rem">
                      <button type="submit" class="btn btn-primary btn-sm">Publier l'avis</button>
                      <button type="button" class="btn btn-outline btn-sm"
                              onclick="toggleAvis(<?= $r['id'] ?>)">Annuler</button>
                    </div>
                  </form>
                </div>
              <?php endif; ?>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php else: ?>
    <div style="text-align:center;padding:5rem 0">
      <div style="font-size:4rem;margin-bottom:1rem">📅</div>
      <h3 style="color:var(--primary);margin-bottom:.5rem">Aucune réservation</h3>
      <p style="color:var(--gray);margin-bottom:1.5rem">Vous n'avez pas encore réservé de service.</p>
      <a href="<?= APP_URL ?>/index.php?page=salons&action=index" class="btn btn-primary">
        Trouver un salon
      </a>
    </div>
  <?php endif; ?>
</div>

<script>
function toggleAvis(id) {
  const el = document.getElementById('avis-form-' + id);
  el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

// Étoiles interactives
document.querySelectorAll('[id^="stars-"]').forEach(container => {
  const stars = container.querySelectorAll('.star-icon');
  stars.forEach((star, idx) => {
    star.addEventListener('mouseover', () => {
      stars.forEach((s, i) => s.style.color = i <= idx ? 'var(--gold)' : 'var(--gray-light)');
    });
    star.addEventListener('mouseout', () => {
      const checked = container.querySelector('input[type=radio]:checked');
      const val = checked ? parseInt(checked.value) - 1 : -1;
      stars.forEach((s, i) => s.style.color = i <= val ? 'var(--gold)' : 'var(--gray-light)');
    });
    star.addEventListener('click', () => {
      star.closest('label').querySelector('input').checked = true;
      stars.forEach((s, i) => s.style.color = i <= idx ? 'var(--gold)' : 'var(--gray-light)');
    });
  });
});
</script>

<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
