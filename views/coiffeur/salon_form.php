<?php
$title   = $mode === 'edit' ? 'Modifier le salon' : 'Créer un salon';
$isEdit  = $mode === 'edit';
$salonId = $data['id'] ?? null;
ob_start();
?>

<section style="background:linear-gradient(135deg,var(--primary),#5a3060);padding:2.5rem 0">
  <div class="container">
    <div style="display:flex;align-items:center;gap:1rem">
      <a href="<?= APP_URL ?>/index.php?page=salons&action=dashboard"
         style="color:rgba(255,255,255,.7);font-size:.9rem">← Mon espace</a>
      <span style="color:rgba(255,255,255,.4)">|</span>
      <h1 style="color:white;font-size:1.4rem;font-weight:800">
        <?= $isEdit ? '⚙️ Modifier — ' . htmlspecialchars($data['nom'] ?? '') : '🏪 Créer mon salon' ?>
      </h1>
    </div>
  </div>
</section>

<div class="container page-content">
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;align-items:start;max-width:960px;margin:0 auto">

    <!-- Formulaire principal -->
    <div>
      <div class="card" style="margin-bottom:1.5rem">
        <div class="card-header">📝 Informations du salon</div>
        <form method="POST" action="<?= $isEdit ? APP_URL.'/index.php?page=salons&action=editSalon&id='.$salonId : APP_URL.'/index.php?page=salons&action=create' ?>">

          <div class="form-group">
            <label>Nom du salon *</label>
            <input type="text" name="nom" class="form-control" required
                   placeholder="Ex: Élégance Coiffure"
                   value="<?= htmlspecialchars($data['nom'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"
                      placeholder="Décrivez votre salon, vos spécialités…"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
          </div>

          <div class="grid-2">
            <div class="form-group">
              <label>Quartier *</label>
              <input type="text" name="quartier" class="form-control" required
                     placeholder="Ex: Keur Massar"
                     value="<?= htmlspecialchars($data['quartier'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Ville</label>
              <input type="text" name="ville" class="form-control"
                     placeholder="Dakar"
                     value="<?= htmlspecialchars($data['ville'] ?? 'Dakar') ?>">
            </div>
          </div>

          <div class="form-group">
            <label>Adresse complète</label>
            <input type="text" name="adresse" class="form-control"
                   placeholder="Ex: Rue 10, Cité Lobatt Fall"
                   value="<?= htmlspecialchars($data['adresse'] ?? '') ?>">
          </div>

          <div class="grid-2">
            <div class="form-group">
              <label>Téléphone</label>
              <input type="tel" name="telephone" class="form-control"
                     placeholder="+221 77 000 0000"
                     value="<?= htmlspecialchars($data['telephone'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Email professionnel</label>
              <input type="email" name="email" class="form-control"
                     placeholder="salon@exemple.com"
                     value="<?= htmlspecialchars($data['email'] ?? '') ?>">
            </div>
          </div>

          <div class="grid-2">
            <div class="form-group">
              <label>Heure d'ouverture</label>
              <input type="time" name="heure_ouverture" class="form-control"
                     value="<?= htmlspecialchars(substr($data['heure_ouverture'] ?? '08:00:00', 0, 5)) ?>">
            </div>
            <div class="form-group">
              <label>Heure de fermeture</label>
              <input type="time" name="heure_fermeture" class="form-control"
                     value="<?= htmlspecialchars(substr($data['heure_fermeture'] ?? '20:00:00', 0, 5)) ?>">
            </div>
          </div>

          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.8rem;font-size:1rem">
            <?= $isEdit ? '💾 Enregistrer les modifications' : '🏪 Créer le salon' ?>
          </button>
        </form>
      </div>

      <!-- Services (mode édition seulement) -->
      <?php if ($isEdit && isset($services)): ?>
        <div class="card">
          <div class="card-header" style="display:flex;justify-content:space-between;align-items:center">
            <span>💼 Services (<?= count($services) ?>)</span>
          </div>

          <!-- Ajouter un service -->
          <form method="POST" action="<?= APP_URL ?>/index.php?page=salons&action=addService"
                style="background:var(--bg);border-radius:var(--radius);padding:1rem;margin-bottom:1rem">
            <input type="hidden" name="salon_id" value="<?= $salonId ?>">
            <div style="font-weight:700;color:var(--primary);margin-bottom:.75rem;font-size:.9rem">+ Nouveau service</div>
            <div class="grid-2" style="gap:.75rem">
              <div class="form-group" style="margin:0">
                <input type="text" name="nom" class="form-control" placeholder="Nom du service *" required>
              </div>
              <div class="form-group" style="margin:0">
                <input type="number" name="prix" class="form-control" placeholder="Prix (FCFA) *" min="0" step="100" required>
              </div>
            </div>
            <div class="grid-2" style="gap:.75rem;margin-top:.5rem">
              <div class="form-group" style="margin:0">
                <input type="number" name="duree_minutes" class="form-control" placeholder="Durée (min)" min="15" value="30">
              </div>
              <div class="form-group" style="margin:0">
                <input type="text" name="description" class="form-control" placeholder="Description (optionnel)">
              </div>
            </div>
            <button type="submit" class="btn btn-success btn-sm" style="margin-top:.75rem">✓ Ajouter</button>
          </form>

          <!-- Liste des services existants -->
          <?php if (!empty($services)): ?>
            <div style="display:grid;gap:.5rem">
              <?php foreach ($services as $sv): ?>
                <div style="display:flex;align-items:center;justify-content:space-between;
                            padding:.75rem 1rem;border:1.5px solid var(--gray-light);border-radius:var(--radius)">
                  <div>
                    <div style="font-weight:700;font-size:.9rem"><?= htmlspecialchars($sv['nom']) ?></div>
                    <div style="font-size:.78rem;color:var(--gray)">
                      ⏱ <?= $sv['duree_minutes'] ?> min
                      <?= $sv['est_actif'] ? '' : ' · <span style="color:var(--danger)">Inactif</span>' ?>
                    </div>
                  </div>
                  <div style="display:flex;align-items:center;gap:.75rem">
                    <span style="font-weight:800;color:var(--gold)"><?= formatMontant($sv['prix']) ?></span>
                    <a href="<?= APP_URL ?>/index.php?page=salons&action=deleteService&id=<?= $sv['id'] ?>&salon_id=<?= $salonId ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Supprimer ce service ?')">🗑</a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p style="color:var(--gray);text-align:center;font-size:.88rem;padding:.5rem">Aucun service — ajoutez-en un ci-dessus.</p>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Sidebar infos -->
    <div style="position:sticky;top:80px">
      <div class="card" style="background:var(--primary);color:white">
        <div style="color:var(--gold);font-weight:800;margin-bottom:.75rem">💡 Conseils</div>
        <ul style="list-style:none;font-size:.85rem;opacity:.85;display:grid;gap:.5rem">
          <li>✅ Remplissez bien la description pour attirer plus de clients</li>
          <li>✅ Ajoutez tous vos services avec des prix précis</li>
          <li>✅ Mettez à jour vos horaires d'ouverture</li>
          <li>✅ Répondez rapidement aux demandes de réservation</li>
        </ul>
      </div>
      <?php if ($isEdit): ?>
        <div class="card" style="margin-top:1rem;text-align:center">
          <a href="<?= APP_URL ?>/index.php?page=salons&action=show&id=<?= $salonId ?>"
             class="btn btn-dark" style="width:100%;justify-content:center">
            👁 Voir la page publique
          </a>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
