<?php
$title   = 'Connexion';
$content = '';
ob_start();
$flash = getFlash();
?>

<style>
.auth-page { min-height: calc(100vh - 60px); display: flex; align-items: center; justify-content: center; padding: 2rem; }
.auth-card { background: white; border-radius: 16px; box-shadow: 0 8px 40px rgba(45,27,46,.15); padding: 2.5rem; width: 100%; max-width: 420px; }
.auth-logo { text-align: center; margin-bottom: 1.5rem; }
.auth-logo h2 { font-size: 1.8rem; font-weight: 800; color: var(--primary); }
.auth-logo h2 span { color: var(--gold); }
.auth-logo p { color: var(--gray); font-size: .9rem; }
.divider { text-align: center; color: var(--gray); font-size: .85rem; margin: 1.2rem 0; position: relative; }
.divider::before, .divider::after { content:''; position: absolute; top: 50%; width: 40%; height: 1px; background: var(--gray-light); }
.divider::before { left: 0; }
.divider::after  { right: 0; }
</style>

<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <h2>Coiff<span>Connect</span></h2>
      <p>Connectez-vous à votre compte</p>
    </div>

    <?php if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
        <?= htmlspecialchars($flash['message']) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/index.php?page=login">
      <div class="form-group">
        <label for="email">Adresse email</label>
        <input type="email" id="email" name="email" class="form-control"
               placeholder="exemple@email.com" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control"
               placeholder="Votre mot de passe" required>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.75rem;">
        Se connecter
      </button>
    </form>

    <div class="divider">ou</div>

    <a href="<?= APP_URL ?>/index.php?page=register" class="btn btn-outline" style="width:100%;justify-content:center;padding:.75rem;">
      Créer un compte
    </a>

    <p style="text-align:center;font-size:.8rem;color:var(--gray);margin-top:1.5rem;">
      Comptes de test :<br>
      <strong>admin@coiffconnect.sn</strong> / <strong>aminata@example.com</strong><br>
      MDP : <em>password</em>
    </p>
  </div>
</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . '/views/shared/layout.php';
