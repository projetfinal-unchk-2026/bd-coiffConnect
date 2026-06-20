<?php
$title = 'Inscription';
$flash = getFlash();
ob_start();
?>
<style>
.auth-page { min-height: calc(100vh - 60px); display: flex; align-items: center; justify-content: center; padding: 2rem; }
.auth-card { background: white; border-radius: 16px; box-shadow: 0 8px 40px rgba(45,27,46,.15); padding: 2.5rem; width: 100%; max-width: 500px; }
.auth-logo { text-align: center; margin-bottom: 1.5rem; }
.auth-logo h2 { font-size: 1.8rem; font-weight: 800; color: var(--primary); }
.auth-logo h2 span { color: var(--gold); }
.role-selector { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; margin-bottom: 1.5rem; }
.role-option input[type=radio] { display: none; }
.role-option label {
  display: flex; align-items: center; justify-content: center; flex-direction: column;
  padding: 1rem; border: 2px solid var(--gray-light); border-radius: var(--radius);
  cursor: pointer; font-weight: 600; font-size: .9rem; color: var(--gray);
  transition: all .2s;
}
.role-option input[type=radio]:checked + label { border-color: var(--gold); color: var(--primary); background: #fef9f0; }
.role-option label span { font-size: 1.6rem; margin-bottom: .3rem; }
</style>

<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <h2>Coiff<span>Connect</span></h2>
      <p>Créez votre compte gratuitement</p>
    </div>

    <?php if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
        <?= htmlspecialchars($flash['message']) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/index.php?page=register">

      <p style="font-weight:600;margin-bottom:.5rem;font-size:.9rem;">Je suis :</p>
      <div class="role-selector">
        <div class="role-option">
          <input type="radio" id="role_client" name="role" value="client"
                 <?= (($old['role'] ?? 'client') === 'client') ? 'checked' : '' ?>>
          <label for="role_client"><span>💅</span>Client</label>
        </div>
        <div class="role-option">
          <input type="radio" id="role_coiffeur" name="role" value="coiffeur"
                 <?= (($old['role'] ?? '') === 'coiffeur') ? 'checked' : '' ?>>
          <label for="role_coiffeur"><span>✂️</span>Coiffeur/se</label>
        </div>
      </div>

      <div class="grid-2" style="gap:.75rem">
        <div class="form-group">
          <label>Nom *</label>
          <input type="text" name="nom" class="form-control"
                 placeholder="Diallo" required value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Prénom *</label>
          <input type="text" name="prenom" class="form-control"
                 placeholder="Aminata" required value="<?= htmlspecialchars($old['prenom'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Email *</label>
        <input type="email" name="email" class="form-control"
               placeholder="email@exemple.com" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Téléphone</label>
        <input type="tel" name="telephone" class="form-control"
               placeholder="+221 77 000 0000" value="<?= htmlspecialchars($old['telephone'] ?? '') ?>">
      </div>

      <div class="grid-2" style="gap:.75rem">
        <div class="form-group">
          <label>Mot de passe *</label>
          <input type="password" name="mot_de_passe" class="form-control"
                 placeholder="Min. 6 caractères" required>
        </div>
        <div class="form-group">
          <label>Confirmer *</label>
          <input type="password" name="confirmer_mdp" class="form-control"
                 placeholder="Répéter le MDP" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.75rem;">
        Créer mon compte
      </button>
    </form>

    <p style="text-align:center;font-size:.9rem;color:var(--gray);margin-top:1.2rem;">
      Déjà un compte ?
      <a href="<?= APP_URL ?>/index.php?page=login">Se connecter</a>
    </p>
  </div>
</div>
<?php
$content = ob_get_clean();
require ROOT_PATH . '/views/shared/layout.php';
