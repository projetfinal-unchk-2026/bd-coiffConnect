<?php
$title = 'Contact';
ob_start();
?>
<style>
.contact-hero {
  background: linear-gradient(135deg, var(--primary), #5a3060);
  padding: 3rem 0; text-align: center;
}
.contact-hero h1 { color: white; font-size: 1.8rem; font-weight: 800; margin-bottom: .5rem; }
.contact-hero p  { color: rgba(255,255,255,.75); font-size: .9rem; }

.contact-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;
  max-width: 900px; margin: 0 auto; padding: 3rem 1.5rem;
}
.contact-info-item {
  display: flex; gap: 1rem; align-items: flex-start;
  margin-bottom: 1.5rem;
}
.contact-icon {
  width: 48px; height: 48px; border-radius: 50%;
  background: #FDF5EC; display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem; flex-shrink: 0;
}
.contact-info-item h4 { color: var(--primary); font-size: .95rem; margin-bottom: .2rem; }
.contact-info-item p  { color: var(--gray); font-size: .85rem; }
</style>

<div class="contact-hero">
  <h1>📞 Contactez-nous</h1>
  <p>Une question ? Une suggestion ? Notre équipe est à votre écoute.</p>
</div>

<div class="contact-grid">
  <!-- Infos -->
  <div>
    <h3 style="color:var(--primary);font-size:1.2rem;font-weight:800;margin-bottom:1.5rem">Nos coordonnées</h3>

    <div class="contact-info-item">
      <div class="contact-icon">📧</div>
      <div>
        <h4>Email</h4>
        <p>coiffconnect@gmail.com</p>
      </div>
    </div>

    <div class="contact-info-item">
      <div class="contact-icon">📱</div>
      <div>
        <h4>Téléphone</h4>
        <p>(+221) 78 304 79 41</p>
      </div>
    </div>

    <div class="contact-info-item">
      <div class="contact-icon">📍</div>
      <div>
        <h4>Adresse</h4>
        <p>Dakar, Sénégal</p>
      </div>
    </div>

    <div class="contact-info-item">
      <div class="contact-icon">🕐</div>
      <div>
        <h4>Horaires</h4>
        <p>Lun - Sam : 8h00 - 20h00</p>
      </div>
    </div>
  </div>

  <!-- Formulaire -->
  <div class="card">
    <div class="card-header">✉️ Envoyez-nous un message</div>
    <form onsubmit="event.preventDefault(); alert('Message envoyé ! Nous vous répondrons rapidement.'); this.reset();">
      <div class="form-group">
        <label>Nom complet</label>
        <input type="text" class="form-control" placeholder="Votre nom" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" placeholder="votre@email.com" required>
      </div>
      <div class="form-group">
        <label>Message</label>
        <textarea class="form-control" rows="4" placeholder="Votre message…" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
        Envoyer le message
      </button>
    </form>
  </div>
</div>

<?php $content = ob_get_clean(); require ROOT_PATH . '/views/shared/layout.php'; ?>
