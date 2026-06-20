<?php
$title = 'Salons & Services';
ob_start();
?>

<!-- ── Header page ── -->
<section style="background:linear-gradient(135deg,var(--primary) 0%,#5a3060 100%);padding:3rem 0 2rem">
  <div class="container">
    <h1 style="color:white;font-size:2rem;font-weight:800;margin-bottom:.5rem">
      Trouvez votre salon <span style="color:var(--gold)">idéal</span>
    </h1>
    <p style="color:rgba(255,255,255,.75);margin-bottom:1.5rem">Dakar et ses environs — <?= count($salons) ?> salon(s) disponible(s)</p>

    <form method="GET" action="">
      <input type="hidden" name="page" value="salons">
      <input type="hidden" name="action" value="index">
      <div class="search-bar" style="max-width:700px">
        <input type="text" name="service" placeholder="Service (tresses, barbier, massage…)"
               value="<?= htmlspecialchars($service) ?>">
        <input type="text" name="ville" placeholder="Quartier ou ville"
               value="<?= htmlspecialchars($ville) ?>"
               style="border-left:1px solid var(--gray-light)">
        <button type="submit" class="btn btn-primary">🔍 Chercher</button>
      </div>
    </form>
  </div>
</section>

<div class="container page-content">

  <!-- Filtres actifs -->
  <?php if ($service || $ville): ?>
    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem;flex-wrap:wrap">
      <span style="color:var(--gray);font-size:.9rem">Filtres actifs :</span>
      <?php if ($service): ?>
        <span style="background:var(--gold);color:var(--primary);padding:.25rem .8rem;border-radius:20px;font-size:.85rem;font-weight:600">
          ✂️ <?= htmlspecialchars($service) ?>
        </span>
      <?php endif; ?>
      <?php if ($ville): ?>
        <span style="background:var(--primary);color:white;padding:.25rem .8rem;border-radius:20px;font-size:.85rem;font-weight:600">
          📍 <?= htmlspecialchars($ville) ?>
        </span>
      <?php endif; ?>
      <a href="?page=salons&action=index" style="color:var(--danger);font-size:.85rem">✕ Effacer</a>
    </div>
  <?php endif; ?>

  <!-- Grille salons -->
  <?php if (!empty($salons)): ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem">
      <?php foreach ($salons as $s): ?>
        <div class="salon-card">
          <!-- Image / bannière -->
          <div class="salon-card-img" style="position:relative">
            <?php if ($s['photo_url']): ?>
              <img src="<?= htmlspecialchars($s['photo_url']) ?>" alt="<?= htmlspecialchars($s['nom']) ?>"
                   style="width:100%;height:100%;object-fit:cover">
            <?php else: ?>
              <span style="font-size:3.5rem">✂️</span>
            <?php endif; ?>
            <!-- Note badge -->
            <div style="position:absolute;top:.75rem;right:.75rem;background:white;border-radius:20px;
                        padding:.2rem .65rem;display:flex;align-items:center;gap:.3rem;box-shadow:0 2px 8px rgba(0,0,0,.15)">
              <span style="color:var(--gold);font-size:.9rem">★</span>
              <span style="font-size:.82rem;font-weight:700;color:var(--primary)"><?= $s['note_moyenne'] ?></span>
            </div>
            <?php if (!$s['est_actif']): ?>
              <div style="position:absolute;inset:0;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center">
                <span style="color:white;font-weight:700;font-size:.9rem;background:var(--danger);padding:.3rem .8rem;border-radius:6px">
                  Fermé temporairement
                </span>
              </div>
            <?php endif; ?>
          </div>

          <div class="salon-card-body">
            <div class="salon-card-title" style="font-size:1rem"><?= htmlspecialchars($s['nom']) ?></div>
            <div class="salon-card-loc">📍 <?= htmlspecialchars($s['quartier']) ?>, <?= htmlspecialchars($s['ville']) ?></div>

            <?php if ($s['description']): ?>
              <p style="font-size:.82rem;color:var(--gray);margin-bottom:.75rem;line-height:1.5">
                <?= htmlspecialchars(mb_substr($s['description'], 0, 80)) ?>…
              </p>
            <?php endif; ?>

            <!-- Horaires -->
            <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.75rem;font-size:.82rem;color:var(--gray)">
              🕐 <?= formatHeure($s['heure_ouverture']) ?> – <?= formatHeure($s['heure_fermeture']) ?>
            </div>

            <a href="<?= APP_URL ?>/index.php?page=salons&action=show&id=<?= $s['id'] ?>"
               class="btn btn-primary btn-sm" style="width:100%;justify-content:center">
              Voir & Réserver
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php else: ?>
    <div style="text-align:center;padding:5rem 0">
      <div style="font-size:4rem;margin-bottom:1rem">🔍</div>
      <h3 style="color:var(--primary);margin-bottom:.5rem">Aucun salon trouvé</h3>
      <p style="color:var(--gray)">Essayez d'autres mots-clés ou élargissez votre recherche.</p>
      <a href="?page=salons&action=index" class="btn btn-primary" style="margin-top:1.5rem">Voir tous les salons</a>
    </div>
  <?php endif; ?>
</div>

<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
