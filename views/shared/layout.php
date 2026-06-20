<?php
// views/shared/layout.php — inclus via view() dans chaque page
// Reçoit : $title, $bodyClass (optionnel)
$flash = getFlash();
$user  = currentUser();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'CoiffConnect') ?> — CoiffConnect</title>
  <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
  <?= $extraHead ?? '' ?>
</head>
<body class="<?= htmlspecialchars($bodyClass ?? '') ?>">

<!-- ── Navbar ── -->
<nav class="navbar">
  <a href="<?= APP_URL ?>/index.php" class="navbar-brand">Coiff<span>Connect</span></a>
  <div class="nav-links">
    <a href="<?= APP_URL ?>/index.php?page=home">Accueil</a>
    <a href="<?= APP_URL ?>/index.php?page=salons&action=index">Salons</a>
    <?php if ($user): ?>
      <?php if ($user['role'] === 'admin'): ?>
        <a href="<?= APP_URL ?>/index.php?page=admin&action=dashboard">Dashboard</a>
      <?php elseif ($user['role'] === 'coiffeur'): ?>
        <a href="<?= APP_URL ?>/index.php?page=salons&action=dashboard">Mon salon</a>
      <?php else: ?>
        <a href="<?= APP_URL ?>/index.php?page=reservations&action=index">Mes réservations</a>
      <?php endif; ?>
      <a href="<?= APP_URL ?>/index.php?page=logout" class="btn btn-outline btn-sm">
        Déconnexion (<?= htmlspecialchars($user['prenom']) ?>)
      </a>
    <?php else: ?>
      <a href="<?= APP_URL ?>/index.php?page=login" class="btn btn-outline btn-sm">Connexion</a>
      <a href="<?= APP_URL ?>/index.php?page=register" class="btn btn-primary btn-sm">S'inscrire</a>
    <?php endif; ?>
  </div>
</nav>

<!-- ── Flash ── -->
<div class="container" style="padding-top:.75rem">
<?php if ($flash): ?>
  <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : ($flash['type'] === 'success' ? 'success' : 'info') ?>">
    <?= htmlspecialchars($flash['message']) ?>
  </div>
<?php endif; ?>
</div>

<!-- ── Content ── -->
<?= $content ?? '' ?>

<!-- ── Footer ── -->
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <h4>Explorer</h4>
        <a href="<?= APP_URL ?>/index.php?page=home">Accueil</a>
        <a href="<?= APP_URL ?>/index.php?page=salons&action=index">Salons</a>
        <a href="<?= APP_URL ?>/index.php?page=reservations&action=index">Réservation</a>
        <a href="<?= APP_URL ?>/index.php?page=login">Connexion</a>
      </div>
      <div>
        <h4>Pages utilitaires</h4>
        <a href="#">Politique de confidentialité</a>
        <a href="#">Conditions d'utilisation</a>
      </div>
      <div>
        <h4>Contactez-nous</h4>
        <p>Mail : coiffconnect@gmail.com</p>
        <p>Tél : (+221) 78 304 79 41</p>
      </div>
    </div>
    <div class="footer-bottom">
      © 2026, CoiffConnect | Tout Droit Réservé.
    </div>
  </div>
</footer>

<?= $extraScript ?? '' ?>
</body>
</html>
