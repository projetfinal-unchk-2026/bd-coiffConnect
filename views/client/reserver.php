<?php
$title = 'Réserver — ' . htmlspecialchars($salon['nom']);
$preselectedService = (int)($_GET['service_id'] ?? 0);
ob_start();
?>

<section style="background:linear-gradient(135deg,var(--primary),#5a3060);padding:2.5rem 0">
  <div class="container">
    <div style="display:flex;align-items:center;gap:1rem">
      <a href="<?= APP_URL ?>/index.php?page=salons&action=show&id=<?= $salon['id'] ?>"
         style="color:rgba(255,255,255,.7);text-decoration:none;font-size:.9rem">← Retour</a>
      <span style="color:rgba(255,255,255,.4)">|</span>
      <div>
        <h1 style="color:white;font-size:1.5rem;font-weight:800">Réserver chez <?= htmlspecialchars($salon['nom']) ?></h1>
        <p style="color:rgba(255,255,255,.7);font-size:.88rem">
          📍 <?= htmlspecialchars($salon['quartier']) ?>, <?= htmlspecialchars($salon['ville']) ?>
          &nbsp;·&nbsp; 🕐 <?= formatHeure($salon['heure_ouverture']) ?> – <?= formatHeure($salon['heure_fermeture']) ?>
        </p>
      </div>
    </div>
  </div>
</section>

<div class="container page-content">
  <div style="display:grid;grid-template-columns:3fr 2fr;gap:2rem;align-items:start;max-width:900px;margin:0 auto">

    <!-- Formulaire -->
    <div class="card">
      <div class="card-header">📅 Détails de la réservation</div>

      <form method="POST" action="<?= APP_URL ?>/index.php?page=reservations&action=store" id="formResa">
        <input type="hidden" name="salon_id" value="<?= $salon['id'] ?>">

        <!-- Étape 1 — Service -->
        <div style="margin-bottom:1.5rem">
          <div style="font-weight:700;color:var(--primary);margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem">
            <span style="background:var(--gold);color:var(--primary);width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:800">1</span>
            Choisissez votre service
          </div>
          <div style="display:grid;gap:.6rem" id="services-list">
            <?php foreach ($services as $sv): ?>
              <label style="cursor:pointer">
                <input type="radio" name="service_id" value="<?= $sv['id'] ?>"
                       data-duree="<?= $sv['duree_minutes'] ?>"
                       <?= $sv['id'] === $preselectedService ? 'checked' : '' ?>
                       style="display:none" class="service-radio">
                <div class="service-item" data-id="<?= $sv['id'] ?>"
                     style="display:flex;justify-content:space-between;align-items:center;
                            padding:.85rem 1rem;border:2px solid var(--gray-light);
                            border-radius:var(--radius);transition:all .2s;
                            <?= $sv['id']===$preselectedService?'border-color:var(--gold);background:#fef9f0':'' ?>">
                  <div>
                    <div style="font-weight:700;font-size:.95rem"><?= htmlspecialchars($sv['nom']) ?></div>
                    <div style="font-size:.8rem;color:var(--gray)">⏱ <?= $sv['duree_minutes'] ?> min</div>
                  </div>
                  <div style="font-weight:800;color:var(--gold);font-size:1rem"><?= formatMontant($sv['prix']) ?></div>
                </div>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Étape 2 — Date -->
        <div style="margin-bottom:1.5rem">
          <div style="font-weight:700;color:var(--primary);margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem">
            <span style="background:var(--gold);color:var(--primary);width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:800">2</span>
            Choisissez la date
          </div>
          <input type="date" name="date_reservation" id="date_reservation" class="form-control"
                 min="<?= date('Y-m-d') ?>" required>
        </div>

        <!-- Étape 3 — Créneau -->
        <div style="margin-bottom:1.5rem">
          <div style="font-weight:700;color:var(--primary);margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem">
            <span style="background:var(--gold);color:var(--primary);width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:800">3</span>
            Choisissez l'heure
          </div>
          <input type="hidden" name="heure_reservation" id="heure_reservation" required>
          <div id="creneaux-container" style="min-height:50px">
            <p style="color:var(--gray);font-size:.88rem;font-style:italic">
              Sélectionnez d'abord un service et une date.
            </p>
          </div>
        </div>

        <!-- Notes -->
        <div class="form-group">
          <label>Notes (optionnel)</label>
          <textarea name="notes" class="form-control" rows="3"
                    placeholder="Précisions pour le coiffeur…"></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.85rem;font-size:1rem" id="btn-submit" disabled>
          ✅ Confirmer la réservation
        </button>
      </form>
    </div>

    <!-- Récap -->
    <div style="position:sticky;top:80px">
      <div class="card" style="background:var(--primary);color:white">
        <div style="font-weight:800;font-size:1rem;color:var(--gold);margin-bottom:1rem">📋 Récapitulatif</div>
        <div style="display:grid;gap:.6rem;font-size:.88rem">
          <div style="display:flex;justify-content:space-between">
            <span style="opacity:.75">Salon</span>
            <span style="font-weight:600"><?= htmlspecialchars($salon['nom']) ?></span>
          </div>
          <div style="display:flex;justify-content:space-between" id="recap-service">
            <span style="opacity:.75">Service</span>
            <span style="font-weight:600;color:var(--gold)">—</span>
          </div>
          <div style="display:flex;justify-content:space-between" id="recap-date">
            <span style="opacity:.75">Date</span>
            <span style="font-weight:600">—</span>
          </div>
          <div style="display:flex;justify-content:space-between" id="recap-heure">
            <span style="opacity:.75">Heure</span>
            <span style="font-weight:600">—</span>
          </div>
          <div style="border-top:1px solid rgba(255,255,255,.2);margin:.5rem 0;padding-top:.5rem;display:flex;justify-content:space-between">
            <span style="opacity:.75">Total</span>
            <span style="font-weight:800;font-size:1.1rem;color:var(--gold)" id="recap-prix">—</span>
          </div>
        </div>
      </div>

      <div class="card" style="margin-top:1rem;font-size:.82rem;color:var(--gray)">
        <div style="font-weight:700;color:var(--primary);margin-bottom:.5rem">ℹ️ Information</div>
        <p>Vous pouvez annuler gratuitement votre réservation jusqu'à 2h avant le rendez-vous.</p>
      </div>
    </div>

  </div>
</div>

<script>
const salonId    = <?= $salon['id'] ?>;
const appUrl     = '<?= APP_URL ?>';
const heureInput = document.getElementById('heure_reservation');
const btnSubmit  = document.getElementById('btn-submit');

// Prix des services
const serviceData = {
  <?php foreach ($services as $sv): ?>
  <?= $sv['id'] ?>: { nom: '<?= addslashes($sv['nom']) ?>', prix: '<?= formatMontant($sv['prix']) ?>' },
  <?php endforeach; ?>
};

// Sélection service
document.querySelectorAll('.service-radio').forEach(radio => {
  radio.addEventListener('change', () => {
    // Style
    document.querySelectorAll('.service-item').forEach(el => {
      el.style.borderColor = 'var(--gray-light)';
      el.style.background  = 'white';
    });
    radio.closest('label').querySelector('.service-item').style.borderColor = 'var(--gold)';
    radio.closest('label').querySelector('.service-item').style.background  = '#fef9f0';

    // Récap
    const d = serviceData[radio.value];
    document.querySelector('#recap-service span:last-child').textContent = d.nom;
    document.querySelector('#recap-prix').textContent = d.prix;

    heureInput.value = '';
    btnSubmit.disabled = true;
    loadCreneaux();
  });
});

document.getElementById('date_reservation').addEventListener('change', function() {
  document.querySelector('#recap-date span:last-child').textContent = this.value;
  heureInput.value = '';
  btnSubmit.disabled = true;
  loadCreneaux();
});

function loadCreneaux() {
  const serviceId = document.querySelector('.service-radio:checked')?.value;
  const date      = document.getElementById('date_reservation').value;
  if (!serviceId || !date) return;

  const container = document.getElementById('creneaux-container');
  container.innerHTML = '<p style="color:var(--gray);font-size:.88rem">⏳ Chargement des créneaux…</p>';

  fetch(`${appUrl}/index.php?page=reservations&action=creneaux&salon_id=${salonId}&service_id=${serviceId}&date=${date}`)
    .then(r => r.json())
    .then(data => {
      if (data.error) { container.innerHTML = `<p style="color:var(--danger)">${data.error}</p>`; return; }

      if (!data.creneaux.length) {
        container.innerHTML = '<p style="color:var(--gray);font-size:.88rem">Aucun créneau disponible ce jour.</p>';
        return;
      }

      container.innerHTML = '<div class="creneaux-grid"></div>';
      const grid = container.querySelector('.creneaux-grid');

      data.creneaux.forEach(c => {
        const btn = document.createElement('button');
        btn.type        = 'button';
        btn.textContent = c.heure;
        btn.className   = 'creneau-btn' + (c.disponible ? '' : ' unavailable');
        btn.disabled    = !c.disponible;
        if (c.disponible) {
          btn.addEventListener('click', () => {
            document.querySelectorAll('.creneau-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            heureInput.value = c.heure;
            document.querySelector('#recap-heure span:last-child').textContent = c.heure;
            btnSubmit.disabled = false;
          });
        }
        grid.appendChild(btn);
      });
    })
    .catch(() => {
      container.innerHTML = '<p style="color:var(--danger)">Erreur lors du chargement des créneaux.</p>';
    });
}

// Si service pré-sélectionné, init le récap
const preSelected = document.querySelector('.service-radio:checked');
if (preSelected) {
  const d = serviceData[preSelected.value];
  if (d) {
    document.querySelector('#recap-service span:last-child').textContent = d.nom;
    document.querySelector('#recap-prix').textContent = d.prix;
  }
}
</script>

<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
