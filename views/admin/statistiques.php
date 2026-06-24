<?php
$title = 'Statistiques';
ob_start();

$user = currentUser();
?>

<style>
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

.admin-main { flex: 1; padding: 1.75rem; background: #F4F1EE; overflow:auto; }

.admin-topbar {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;
}
.admin-topbar h1 { font-size: 1.2rem; font-weight: 800; color: #1a1a1a; }
.admin-topbar p  { font-size: .83rem; color: #888; margin-top:.1rem }

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

.growth-badge {
  font-size: .85rem; font-weight: 700; padding: .15rem .55rem; border-radius: 20px;
  display: inline-flex; align-items: center; gap: .25rem;
}
.growth-up   { background:#F0FFF4; color:#2E7D32; }
.growth-down { background:#FFF0F0; color:#C62828; }

.rank-list { display:flex; flex-direction:column; padding: .25rem 1.25rem 1rem; }
.rank-item {
  display:flex; align-items:center; gap:.85rem; padding:.65rem 0;
  border-bottom:1px solid #f5f5f5;
}
.rank-item:last-child { border-bottom:none; }
.rank-num {
  width:26px; height:26px; border-radius:50%; background:#F4F1EE;
  display:flex; align-items:center; justify-content:center;
  font-size:.78rem; font-weight:800; color:#8B5A2B; flex-shrink:0;
}
.rank-name { font-size:.88rem; font-weight:700; color:#1a1a1a; }
.rank-sub  { font-size:.76rem; color:#999; }
.rank-value { font-size:.85rem; font-weight:700; color:var(--gold); margin-left:auto; white-space:nowrap; }
</style>

<div class="admin-layout">
      <h2>COIFFCONNECT</h2>
    </div>


  <aside class="sidebar">
    <div class="sidebar-brand">
    <div class="sidebar-section">Menu principal</div>

    <a href="?page=admin&action=dashboard">
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
    <a href="?page=admin&action=statistiques" class="active">
      <span class="ico">📈</span> Statistiques
    </a>
    <a href="#">
      <span class="ico">⚙️</span> Paramètres
    </a>

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

  <main class="admin-main">

    <div class="admin-topbar">
      <div>
        <h1>Statistiques 📈</h1>
        <p>Analyse détaillée de la performance de CoiffConnect.</p>
      </div>
    </div>

    <div class="stat-grid-new">
      <div class="stat-card-new">
        <div class="stat-icon-new" style="background:#F0FFF4">💰</div>
        <div>
          <div class="stat-val" style="font-size:1.05rem"><?= formatMontant($statsGlobales['revenu_total']) ?></div>
          <div class="stat-lbl">Revenu total</div>
        </div>
      </div>
      <div class="stat-card-new">
        <div class="stat-icon-new" style="background:<?= $statsGlobales['croissance'] >= 0 ? '#F0FFF4' : '#FFF0F0' ?>">
          <?= $statsGlobales['croissance'] >= 0 ? '📈' : '📉' ?>
        </div>
        <div>
          <span class="growth-badge <?= $statsGlobales['croissance'] >= 0 ? 'growth-up' : 'growth-down' ?>">
            <?= $statsGlobales['croissance'] >= 0 ? '+' : '' ?><?= $statsGlobales['croissance'] ?>%
          </span>
          <div class="stat-lbl">vs mois précédent</div>
        </div>
      </div>
      <div class="stat-card-new">
        <div class="stat-icon-new" style="background:#FFF0F0">🛍️</div>
        <div>
          <div class="stat-val"><?= number_format($statsGlobales['reservations_totales']) ?></div>
          <div class="stat-lbl">Réservations totales</div>
        </div>
      </div>
      <div class="stat-card-new">
        <div class="stat-icon-new" style="background:#FFF8E1">⭐</div>
        <div>
          <div class="stat-val"><?= number_format($statsGlobales['note_moyenne'], 1) ?> / 5</div>
          <div class="stat-lbl">Note moyenne globale</div>
        </div>
      </div>
    </div>

    <div class="charts-row">

      <div class="chart-card">
        <div class="chart-card-title">
          Évolution du chiffre d'affaires
          <span>···</span>
        </div>
        <canvas id="revenuChart" height="110"></canvas>
      </div>

      <div class="chart-card">
        <div class="chart-card-title">
          Comparaison mois par mois
          <span>···</span>
        </div>
        <canvas id="comparaisonChart" height="160"></canvas>
      </div>
    </div>

    <div class="bottom-row">

      <div class="table-card">
        <div class="table-card-header">
          Top établissements
        </div>
        <div class="rank-list">
          <?php foreach ($topEtablissements as $i => $e): ?>
            <div class="rank-item">
              <div class="rank-num"><?= $i + 1 ?></div>
              <div style="flex:1">
                <div class="rank-name"><?= htmlspecialchars($e['nom']) ?></div>
                <div class="rank-sub">★ <?= number_format($e['note_moyenne'], 1) ?> · <?= number_format($e['reservations']) ?> résa.</div>
              </div>
              <div class="rank-value"><?= formatMontant($e['revenu']) ?></div>
            </div>
          <?php endforeach; ?>
          <?php if (empty($topEtablissements)): ?>
            <p style="color:#aaa;text-align:center;font-size:.85rem;padding:1rem">Aucune donnée</p>
          <?php endif; ?>
        </div>
      </div>

      <div class="chart-card">
        <div class="chart-card-title">
          Top services
          <span>···</span>
        </div>
        <canvas id="servicesChart" height="180"></canvas>
      </div>

    </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const evolutionCA = <?= json_encode($evolutionCA ?? []) ?>;
const caLabels = evolutionCA.length ? evolutionCA.map(d => d.mois) : ['Jan','Fév','Mar','Avr','Mai','Jun'];
const caValues = evolutionCA.length ? evolutionCA.map(d => parseFloat(d.revenu)) : [1800000, 2100000, 1950000, 2400000, 3200000, 4500000];

new Chart(document.getElementById('revenuChart'), {
  type: 'line',
  data: {
    labels: caLabels,
    datasets: [{
      label: 'Chiffre d\'affaires (FCFA)',
      data: caValues,
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
        callbacks: { label: ctx => ` ${ctx.parsed.y.toLocaleString()} FCFA` }
      }
    },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 11 } } },
      y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 } }, beginAtZero: true }
    }
  }
});

const comparaison = <?= json_encode($comparaisonMois ?? [
  'mois_precedent' => ['label' => 'Mois précédent', 'revenu' => 0, 'reservations' => 0],
  'mois_actuel'     => ['label' => 'Ce mois', 'revenu' => 0, 'reservations' => 0],
]) ?>;

new Chart(document.getElementById('comparaisonChart'), {
  type: 'bar',
  data: {
    labels: ['Réservations', 'Revenu (milliers FCFA)'],
    datasets: [
      {
        label: comparaison.mois_precedent.label,
        data: [comparaison.mois_precedent.reservations, Math.round(comparaison.mois_precedent.revenu / 1000)],
        backgroundColor: '#E0DCD6',
        borderRadius: 6,
      },
      {
        label: comparaison.mois_actuel.label,
        data: [comparaison.mois_actuel.reservations, Math.round(comparaison.mois_actuel.revenu / 1000)],
        backgroundColor: '#8B5A2B',
        borderRadius: 6,
      },
    ]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
    scales: {
      x: { grid: { display: false } },
      y: { grid: { color: '#f0f0f0' }, beginAtZero: true }
    }
  }
});

const topServices = <?= json_encode($topServices ?? [
  ['nom' => 'Coiffure', 'valeur' => 80],
  ['nom' => 'Spa', 'valeur' => 12],
  ['nom' => 'Massage', 'valeur' => 8],
]) ?>;

new Chart(document.getElementById('servicesChart'), {
  type: 'doughnut',
  data: {
    labels: topServices.map(s => s.nom),
    datasets: [{
      data: topServices.map(s => s.valeur),
      backgroundColor: ['#6C63FF', '#FFB347', '#FF6B6B', '#4ECDC4', '#A78BFA'],
      borderWidth: 0,
      hoverOffset: 6,
    }]
  },
  options: {
    responsive: true,
    cutout: '65%',
    plugins: {
      legend: { position: 'bottom', labels: { font: { size: 11 } } },
      tooltip: { callbacks: { label: ctx => ` ${ctx.label} : ${ctx.parsed}%` } }
    }
  }
});
</script>

<?php
$content = ob_get_clean();
require ROOT_PATH . '/views/shared/layout.php';
?>