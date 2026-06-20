<?php
$title = 'Page introuvable';
ob_start();
?>
<div style="min-height:60vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem">
  <div>
    <div style="font-size:5rem;margin-bottom:1rem">✂️</div>
    <h1 style="font-size:3rem;font-weight:900;color:var(--primary);margin-bottom:.5rem">404</h1>
    <h2 style="color:var(--gray);margin-bottom:1rem">Page introuvable</h2>
    <p style="color:var(--gray);max-width:400px;margin:0 auto 2rem">Cette page n'existe pas ou a été déplacée.</p>
    <a href="<?= APP_URL ?>/index.php" class="btn btn-primary">Retour à l'accueil</a>
  </div>
</div>
<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
