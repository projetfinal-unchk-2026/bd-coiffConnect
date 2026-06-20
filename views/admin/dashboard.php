<?php
$title = 'Dashboard Admin';
ob_start();

function statutBadge(string $s): string {
    $map = [
        'en_attente' => ['warning', 'En attente'],
        'confirmee'  => ['success', 'Confirmée'],
        'annulee'    => ['danger',  'Annulée'],
        'terminee'   => ['info',    'Terminée'],
    ];
    [$cls, $label] = $map[$s] ?? ['gray', $s];
    return "<span class='badge badge-$cls'>$label</span>";
}

$user = currentUser();
?>

<style>
/* ── Sidebar caramel comme Figma ── */
.admin-layout { display:flex; min-height:calc(100vh - 60px); }

.sidebar {
  width: 220px;
  background: linear-gradient(180deg, #3D2314 0%, #6B3A1F 50%, #8B5A2B 100%);
  padding: 1.5rem 0;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
}

.sidebar-brand {
  text-align: center;
  padding: 0 1rem 1.5rem;
  border-bottom: 1px solid rgba(255,255,255,.15);
  margin-bottom: .75rem;
}
.sidebar-brand h2 {
  color: white;
  font-size: 1.1rem;
  font-weight: 900;
  letter-spacing: 1px;
}

.sidebar-section {
  padding: .4rem 1rem .2rem;
  font-size: .7rem;
  color: rgba(255,255,255,.45);
  text-transform: uppercase;
  letter-spacing: 1.5px;
  font-weight: 700;
}

.sidebar a {
  display: flex;
  align-items: center;
  gap: .65rem;
  padding: .6rem 1.25rem;
  color: rgba(255,255,255,.8);
  font-size: .88rem;
  text-decoration: none;
  transition: all .2s;
  border-left: 3px solid transparent;
}
.sidebar a:hover {
  background: rgba(255,255,255,.1);
  color: white;
  border-left-color: var(--gold);
}
.sidebar a.active {
  background: rgba(255,255,255,.15);
  color: white;
  border-left-color: var(--gold);
  font-weight: 600;
}
.sidebar a .ico { font-size: 1rem; width: 20px; text-align: center; }

.sidebar-bottom {
  margin-top: auto;
  padding: 1rem 1.25rem;
  border-top: 1px solid rgba(255,255,255,.15);
  display: flex;
  align-items: center;
  gap: .75rem;
}
.sidebar-avatar {
  width: 38px; height: 38px;
  border-radius: 50%;
  background: var(--gold);
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; color: #3D2314; font-size: .9rem;
  flex-shrink: 0;
}
.sidebar-user-name { font-size: .82rem; font-weight: 700; color: white; }
.sidebar-user-role { font-size: .72rem; color: rgba(255,255,255,.55); }

/* ── Main ── */
.admin-main { flex: 1; padding: 1.75rem; background: #F4F1EE; overflow-auto; }

/* ── Top bar ── */
.admin-topbar {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;
}
.admin-topbar h1 { font-size: 1.2rem; font-weight: 800; color: #1a1a1a; }
.admin-topbar p  { font-size: .83rem; color: #888; margin-top:.1rem }

.date-filters { display: flex; gap: .5rem; align-items: center; }
.date-filter-btn {
  background: white; border: 1px solid #ddd; border-radius: 8px;
  padding: .4rem .9rem; font-size: .82rem; font-weight: 600; color: #444;
  cursor: pointer; display: flex; align-items: center; gap: .4rem;
  box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.date-filter-btn:hover { border-color: var(--gold); }

/* ── Stat cards ── */
.stat-grid-new {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
  margin-bottom: 1.25rem;
}
.stat-card-new {
  background: white;
  border-radius: 14px;
  padding: 1.1rem 1.25rem;
  box-shadow: 0 2px 10px rgba(0,0,0,.06);
  display: flex; align-items: center; gap: .9rem;
}
.stat-icon-new {
  width: 46px; height: 46px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem; flex-shrink: 0;
}
.stat-val { font-size: 1.6rem; font-weight: 900; color: #1a1a1a; line-height: 1; }
.stat-lbl { font-size: .78rem; color: #999; margin-top: .2rem; }

/* ── Charts row ── */
.charts-row {
  display: grid;
  grid-template-columns: 3fr 2fr;
  gap: 1rem;
  margin-bottom: 1.25rem;
}
.chart-card {
  background: white; border-radius: 14px; padding: 1.25rem;
  box-shadow: 0 2px 10px rgba(0,0,0,.06);
}
.chart-card-title {
  font-size: .92rem; font-weight: 700; color: #1a1a1a;
  margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;
}
.chart-card-title span { color: #aaa; font-size: .8rem; cursor: pointer; }

/* ── Bottom row ── */
.bottom-row {
  display: grid;
  grid-template-columns: 3fr 2fr;
  gap: 1rem;
}
.table-card {
  background: white; border-radius: 14px;
  box-shadow: 0 2px 10px rgba(0,0,0,.06);
  overflow: hidden;
}
.table-card-header {
  padding: 1rem 1.25rem;
  font-size: .92rem; font-weight: 700; color: #1a1a1a;
  border-bottom: 1px solid #f0f0f0;
  display: flex; justify-content: space-between; align-items: center;
}
.table-card-header a { font-size: .78rem; color: var(--gold); font-weight: 600; }

.pop-card {
  background: white; border-radius: 14px; padding: 1.25rem;
  box-shadow: 0 2px 10px rgba(0,0,0,.06);
}
.pop-item {
  display: flex; align-items: center; gap: .75rem;
  padding: .6rem 0; border-bottom: 1px solid #f5f5f5;
}
.pop-item:last-child { border-bottom: none; }
.pop-thumb {
  width: 44px; height: 44px; border-radius: 10px;
  background: linear-gradient(135deg, #3D2314, #8B5A2B);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem; flex-shrink: 0;
}
.pop-name { font-size: .88rem; font-weight: 700; color: #1a1a1a; }
.pop-stars { color: var(--gold); font-size: .8rem; }
.pop-note  { font-size: .78rem; color: #999; }

/* Table styles override */
.table-card table { font-size: .83rem; }
.table-card thead th { background: #faf9f7; color: #666; font-weight: 600; padding: .6rem 1rem; }
.table-card tbody td { padding: .65rem 1rem; border-bottom: 1px solid #f5f5f5; }
.table-card tbody tr:last-child td { border-bottom: none; }
.table-card tbody tr:hover { background: #faf9f7; }
</style>

<div class="admin-layout">

  <!-- ══ SIDEBAR ══ -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <h2>COIFFCONNECT</h2>
    </div>

    <div class="sidebar-section">Menu principal</div>

    <a href="?page=admin&action=dashboard" class="active">
      <span class="ico">📊</span> Tableau de bord
    </a>
    <a href="?page=admin&action=utilisateurs">
      <span class="ico">👥</span> Utilisateurs
    </a>
    <a href="?page=admin&action=etablissements">
      <span class="ico">🏪</span> Établissements
    </a>
    <a href="?page=admin&action=reservations">
      <span class="ico">📅</span> Réservations
    </a>
    <a href="?page=admin&action=reservations">
      <span class="ico">💳</span> Paiements
    </a>

    <div class="sidebar-section" style="margin-top:.5rem">Analyse</div>

    <a href="?page=admin&action=avisClients">
      <span class="ico">⭐</span> Avis clients
    </a>
    <a href="?page=admin&action=dashboard">
      <span class="ico">📈</span> Statistiques
    </a>
    <a href="#">
      <span class="ico">⚙️</span> Paramètres
    </a>

    <!-- Avatar admin en bas -->
    <div class="sidebar-bottom">
      <div class="sidebar-avatar">
        <?= strtoupper(substr($user['prenom'] ?? 'A', 0, 1) . substr($user['nom'] ?? 'D', 0, 1)) ?>
      </div>
      <div>
        <div class="sidebar-user-name"><?= htmlspecialchars(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?></div>
        <div class="sidebar-user-role">Admin CoiffConnect</div>
      </div>
    </div>
  </aside>

  <!-- ══ MAIN ══ -->
  <main class="admin-main">

    <!-- Top bar -->
    <div class="admin-topbar">
      <div>
        <h1>Bienvenue Administrateur 👋</h1>
        <p>Gérez les utilisateurs, établissements et réservations.</p>
      </div>
      <div class="date-filters">
        <div class="date-filter-btn">
          📅 <?= date('d-m-Y') ?>
        </div>
        <div class="date-filter-btn">
          📅 <?= date('d-m-Y', strtotime('+3 days')) ?>
        </div>
      </div>
    </div>

    <!-- Stat cards -->
    <div class="stat-grid-new">
      <div class="stat-card-new">
        <div class="stat-icon-new" style="background:#EEF2FF">💙</div>
        <div>
          <div class="stat-val"><?= number_format($stats['utilisateurs']) ?></div>
          <div class="stat-lbl">Utilisateurs</div>
        </div>
      </div>
      <div class="stat-card-new">
        <div class="stat-icon-new" style="background:#FFF8E1">🏪</div>
        <div>
          <div class="stat-val"><?= number_format($stats['salons']) ?></div>
          <div class="stat-lbl">Établissements</div>
        </div>
      </div>
      <div class="stat-card-new">
        <div class="stat-icon-new" style="background:#FFF0F0">🛍️</div>
        <div>
          <div class="stat-val"><?= number_format($stats['reservations']) ?></div>
          <div class="stat-lbl">Réservations</div>
        </div>
      </div>
      <div class="stat-card-new">
        <div class="stat-icon-new" style="background:#F0FFF4">💰</div>
        <div>
          <div class="stat-val" style="font-size:1rem"><?= formatMontant($stats['revenu']) ?></div>
          <div class="stat-lbl">Revenus</div>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="charts-row">

      <!-- Graphique linéaire -->
      <div class="chart-card">
        <div class="chart-card-title">
          Évolution des réservations
          <span>···</span>
        </div>
        <canvas id="lineChart" height="110"></canvas>
      </div>

      <!-- Graphique donut -->
      <div class="chart-card">
        <div class="chart-card-title">
          Répartition des services
          <span>···</span>
        </div>
        <div style="position:relative;display:flex;align-items:center;justify-content:center;height:160px">
          <canvas id="donutChart"></canvas>
          <div style="position:absolute;text-align:center;pointer-events:none">
            <div style="font-size:1.4rem;font-weight:900;color:#1a1a1a">80%</div>
            <div style="font-size:.72rem;color:#aaa">Réservations</div>
          </div>
        </div>
        <div style="display:flex;justify-content:center;gap:1.25rem;margin-top:.5rem;font-size:.78rem">
          <span style="display:flex;align-items:center;gap:.3rem"><span style="width:10px;height:10px;border-radius:50%;background:#6C63FF;display:inline-block"></span>Coiffure</span>
          <span style="display:flex;align-items:center;gap:.3rem"><span style="width:10px;height:10px;border-radius:50%;background:#FFB347;display:inline-block"></span>Spa</span>
          <span style="display:flex;align-items:center;gap:.3rem"><span style="width:10px;height:10px;border-radius:50%;background:#FF6B6B;display:inline-block"></span>Massage</span>
        </div>
      </div>
    </div>

    <!-- Bottom row -->
    <div class="bottom-row">

      <!-- Réservations récentes -->
      <div class="table-card">
        <div class="table-card-header">
          Réservations récentes
          <a href="?page=admin&action=reservations">Voir tout →</a>
        </div>
        <table style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th>Client</th>
              <th>Service</th>
              <th>Établissement</th>
              <th>Date</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservationsRecentes as $r): ?>
            <tr>
              <td style="font-weight:600"><?= htmlspecialchars($r['client_prenom'].' '.$r['client_nom']) ?></td>
              <td>
                <span style="display:inline-flex;align-items:center;gap:.3rem">
                  <?php
                  $sIco = match(true) {
                    str_contains(strtolower($r['service_nom']),'coiff') || str_contains(strtolower($r['service_nom']),'coupe') => '✂️',
                    str_contains(strtolower($r['service_nom']),'mass') => '💆',
                    str_contains(strtolower($r['service_nom']),'maqui') => '💄',
                    str_contains(strtolower($r['service_nom']),'barb') => '💈',
                    str_contains(strtolower($r['service_nom']),'tress') => '💇',
                    default => '✨'
                  };
                  ?>
                  <?= $sIco ?> <?= htmlspecialchars($r['service_nom']) ?>
                </span>
              </td>
              <td><?= htmlspecialchars($r['salon_nom']) ?></td>
              <td style="color:var(--gold);font-weight:600"><?= formatDate($r['date_reservation']) ?></td>
              <td><?= statutBadge($r['statut']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($reservationsRecentes)): ?>
              <tr><td colspan="5" style="text-align:center;padding:2rem;color:#aaa">Aucune réservation</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Établissements populaires -->
      <div class="pop-card">
        <div style="font-size:.92rem;font-weight:700;color:#1a1a1a;margin-bottom:1rem;display:flex;justify-content:space-between">
          Établissements populaires
          <a href="?page=admin&action=etablissements" style="font-size:.78rem;color:var(--gold);font-weight:600">Voir tout →</a>
        </div>
        <?php foreach ($salonsPopulaires as $s): ?>
          <div class="pop-item">
            <div class="pop-thumb">✂️</div>
            <div style="flex:1">
              <div class="pop-name"><?= htmlspecialchars(mb_substr($s['nom'], 0, 25)) ?></div>
              <div class="pop-stars">
                <?php for ($i=1;$i<=5;$i++): ?>
                  <span style="color:<?= $i<=$s['note_moyenne']?'var(--gold)':'#ddd' ?>">★</span>
                <?php endfor; ?>
              </div>
              <div class="pop-note"><?= $s['note_moyenne'] ?></div>
            </div>
          </div>
        <?php endforeach; ?>
        <?php if (empty($salonsPopulaires)): ?>
          <p style="color:#aaa;text-align:center;font-size:.85rem;padding:1rem">Aucun salon</p>
        <?php endif; ?>
      </div>

    </div>
  </main>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Données évolution depuis PHP ──
const evolutionData = <?= json_encode($evolutionMensuelle) ?>;

const labels = evolutionData.length
  ? evolutionData.map(d => d.mois)
  : ['Jan','Fév','Mar','Avr','Mai','Jun'];

const values = evolutionData.length
  ? evolutionData.map(d => parseInt(d.nb))
  : [40, 65, 45, 70, 55, 80];

// ── Graphique linéaire ──
new Chart(document.getElementById('lineChart'), {
  type: 'line',
  data: {
    labels: labels,
    datasets: [{
      label: 'Réservations',
      data: values,
      borderColor: '#6C63FF',
      backgroundColor: 'rgba(108,99,255,.08)',
      borderWidth: 2.5,
      pointBackgroundColor: '#6C63FF',
      pointRadius: 4,
      pointHoverRadius: 6,
      tension: 0.4,
      fill: true,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: '#1a1a1a',
        padding: 10,
        callbacks: {
          label: ctx => ` ${ctx.parsed.y} réservations`
        }
      }
    },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 11 } } },
      y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 } }, beginAtZero: true }
    }
  }
});

// ── Graphique donut ──
new Chart(document.getElementById('donutChart'), {
  type: 'doughnut',
  data: {
    labels: ['Coiffure', 'Spa', 'Massage'],
    datasets: [{
      data: [80, 12, 8],
      backgroundColor: ['#6C63FF', '#FFB347', '#FF6B6B'],
      borderWidth: 0,
      hoverOffset: 6,
    }]
  },
  options: {
    responsive: true,
    cutout: '72%',
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: { label: ctx => ` ${ctx.label} : ${ctx.parsed}%` }
      }
    }
  }
});
</script>

<?php
$content = ob_get_clean();
require ROOT_PATH . '/views/shared/layout.php';
?>
