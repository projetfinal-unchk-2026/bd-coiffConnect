<?php
$title = htmlspecialchars($salon['nom']);
ob_start();
?>

<!-- ── Hero salon ── -->
<section style="background:linear-gradient(135deg,var(--primary) 0%,#5a3060 100%);padding:3rem 0">
  <div class="container">
    <div style="display:flex;align-items:flex-start;gap:2rem;flex-wrap:wrap">
      <!-- Avatar salon -->
      <div style="width:100px;height:100px;border-radius:16px;background:rgba(255,255,255,.15);
                  display:flex;align-items:center;justify-content:center;font-size:3rem;flex-shrink:0;
                  border:2px solid var(--gold)">
        ✂️
      </div>
      <div style="flex:1;min-width:200px">
        <h1 style="color:white;font-size:1.8rem;font-weight:800;margin-bottom:.3rem">
          <?= htmlspecialchars($salon['nom']) ?>
        </h1>
        <p style="color:rgba(255,255,255,.75);margin-bottom:.75rem">
          📍 <?= htmlspecialchars($salon['adresse'] ?? $salon['quartier']) ?>, <?= htmlspecialchars($salon['ville']) ?>
        </p>
        <div style="display:flex;gap:1.5rem;flex-wrap:wrap;font-size:.88rem;color:rgba(255,255,255,.8)">
          <span>📞 <?= htmlspecialchars($salon['telephone'] ?? 'N/A') ?></span>
          <span>🕐 <?= formatHeure($salon['heure_ouverture']) ?> – <?= formatHeure($salon['heure_fermeture']) ?></span>
          <span>
            <?php for ($i=1;$i<=5;$i++): ?>
              <span style="color:<?= $i<=$salon['note_moyenne']?'var(--gold)':'rgba(255,255,255,.3)' ?>">★</span>
            <?php endfor; ?>
            <strong style="color:var(--gold)"><?= $salon['note_moyenne'] ?></strong>
            <span style="color:rgba(255,255,255,.6)">(<?= count($avis) ?> avis)</span>
          </span>
        </div>
      </div>
      <div style="flex-shrink:0">
        <?php if ($salon['est_actif']): ?>
          <span style="background:#16a34a;color:white;padding:.4rem 1rem;border-radius:20px;font-size:.85rem;font-weight:600">
            ● Ouvert
          </span>
        <?php else: ?>
          <span style="background:var(--danger);color:white;padding:.4rem 1rem;border-radius:20px;font-size:.85rem;font-weight:600">
            ✕ Fermé
          </span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<div class="container page-content">
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;align-items:start">

    <!-- Colonne principale -->
    <div>
      <!-- Description -->
      <?php if ($salon['description']): ?>
        <div class="card" style="margin-bottom:1.5rem">
          <div class="card-header">📝 À propos</div>
          <p style="color:var(--gray);line-height:1.7"><?= htmlspecialchars($salon['description']) ?></p>
        </div>
      <?php endif; ?>

      <!-- Services -->
      <div class="card" style="margin-bottom:1.5rem">
        <div class="card-header">💼 Services disponibles</div>
        <?php if (!empty($services)): ?>
          <div style="display:grid;gap:.75rem">
            <?php foreach ($services as $sv): ?>
              <div style="display:flex;align-items:center;justify-content:space-between;
                          padding:.9rem 1rem;border:1.5px solid var(--gray-light);border-radius:var(--radius);
                          transition:border-color .2s,background .2s"
                   onmouseover="this.style.borderColor='var(--gold)';this.style.background='#fef9f0'"
                   onmouseout="this.style.borderColor='var(--gray-light)';this.style.background='white'">
                <div>
                  <div style="font-weight:700;color:var(--primary)"><?= htmlspecialchars($sv['nom']) ?></div>
                  <?php if ($sv['description']): ?>
                    <div style="font-size:.82rem;color:var(--gray);margin-top:.15rem">
                      <?= htmlspecialchars($sv['description']) ?>
                    </div>
                  <?php endif; ?>
                  <div style="font-size:.82rem;color:var(--gray);margin-top:.2rem">
                    ⏱ <?= $sv['duree_minutes'] ?> min
                  </div>
                </div>
                <div style="text-align:right;flex-shrink:0;margin-left:1rem">
                  <div style="font-size:1.1rem;font-weight:800;color:var(--gold)">
                    <?= formatMontant($sv['prix']) ?>
                  </div>
                  <?php if (isLoggedIn() && isClient()): ?>
                    <a href="<?= APP_URL ?>/index.php?page=reservations&action=create&salon_id=<?= $salon['id'] ?>&service_id=<?= $sv['id'] ?>"
                       class="btn btn-primary btn-sm" style="margin-top:.4rem">
                      Réserver
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p style="color:var(--gray);text-align:center;padding:1.5rem">Aucun service disponible pour le moment.</p>
        <?php endif; ?>
      </div>

      <!-- Avis -->
      <div class="card">
        <div class="card-header">⭐ Avis clients (<?= count($avis) ?>)</div>
        <?php if (!empty($avis)): ?>
          <div style="display:grid;gap:1rem">
            <?php foreach ($avis as $a): ?>
              <div style="padding:1rem;background:var(--bg);border-radius:var(--radius)">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                  <div style="display:flex;align-items:center;gap:.6rem">
                    <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);
                                display:flex;align-items:center;justify-content:center;color:var(--gold);
                                font-weight:700;font-size:.85rem">
                      <?= strtoupper(substr($a['client_prenom'],0,1).substr($a['client_nom'],0,1)) ?>
                    </div>
                    <div>
                      <div style="font-weight:700;font-size:.9rem">
                        <?= htmlspecialchars($a['client_prenom'].' '.$a['client_nom']) ?>
                      </div>
                      <div style="font-size:.75rem;color:var(--gray)"><?= formatDate($a['created_at']) ?></div>
                    </div>
                  </div>
                  <div>
                    <?php for ($i=1;$i<=5;$i++): ?>
                      <span style="color:<?= $i<=$a['note']?'var(--gold)':'var(--gray-light)' ?>">★</span>
                    <?php endfor; ?>
                  </div>
                </div>
                <?php if ($a['commentaire']): ?>
                  <p style="font-size:.88rem;color:var(--gray);line-height:1.5;margin:0">
                    "<?= htmlspecialchars($a['commentaire']) ?>"
                  </p>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p style="color:var(--gray);text-align:center;padding:2rem">
            Soyez le premier à laisser un avis !
          </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Sidebar réservation rapide -->
    <div style="position:sticky;top:80px">
      <div class="card" style="border:2px solid var(--gold)">
        <div style="text-align:center;margin-bottom:1.25rem">
          <div style="font-size:1.1rem;font-weight:800;color:var(--primary)">Réserver maintenant</div>
          <div style="font-size:.85rem;color:var(--gray)">Choisissez votre service</div>
        </div>

        <?php if (isLoggedIn() && isClient()): ?>
          <a href="<?= APP_URL ?>/index.php?page=reservations&action=create&salon_id=<?= $salon['id'] ?>"
             class="btn btn-primary" style="width:100%;justify-content:center;padding:.8rem;font-size:1rem;margin-bottom:.75rem">
            📅 Prendre rendez-vous
          </a>
        <?php elseif (!isLoggedIn()): ?>
          <a href="<?= APP_URL ?>/index.php?page=login"
             class="btn btn-primary" style="width:100%;justify-content:center;padding:.8rem;font-size:1rem;margin-bottom:.75rem">
            🔐 Se connecter pour réserver
          </a>
          <a href="<?= APP_URL ?>/index.php?page=register"
             class="btn btn-outline" style="width:100%;justify-content:center">
            Créer un compte gratuit
          </a>
        <?php endif; ?>

        <div style="border-top:1px solid var(--gray-light);margin-top:1rem;padding-top:1rem">
          <div style="font-size:.85rem;color:var(--gray);display:grid;gap:.5rem">
            <div style="display:flex;justify-content:space-between">
              <span>📍 Quartier</span>
              <span style="font-weight:600;color:var(--primary)"><?= htmlspecialchars($salon['quartier']) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between">
              <span>🕐 Ouverture</span>
              <span style="font-weight:600;color:var(--primary)"><?= formatHeure($salon['heure_ouverture']) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between">
              <span>🕐 Fermeture</span>
              <span style="font-weight:600;color:var(--primary)"><?= formatHeure($salon['heure_fermeture']) ?></span>
            </div>
            <?php if ($salon['telephone']): ?>
            <div style="display:flex;justify-content:space-between">
              <span>📞 Tél</span>
              <span style="font-weight:600;color:var(--primary)"><?= htmlspecialchars($salon['telephone']) ?></span>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Prix à partir de -->
      <?php if (!empty($services)): ?>
        <?php $minPrix = min(array_column($services, 'prix')); ?>
        <div class="card" style="text-align:center;margin-top:1rem;background:var(--primary);color:white">
          <div style="font-size:.82rem;opacity:.75;margin-bottom:.25rem">À partir de</div>
          <div style="font-size:1.8rem;font-weight:800;color:var(--gold)"><?= formatMontant($minPrix) ?></div>
          <div style="font-size:.82rem;opacity:.75"><?= count($services) ?> service(s) disponible(s)</div>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
