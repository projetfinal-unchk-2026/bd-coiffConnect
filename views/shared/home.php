<?php
$title = 'Accueil';
$imgBase = APP_URL . '/images';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>CoiffConnect — Réservez vos services beauté</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', system-ui, sans-serif; background: #F8F5F0; }
    a { text-decoration: none; }
    img { max-width: 100%; display: block; }

    :root {
      --primary: #2D1B2E;
      --gold:    #C9A96E;
      --brown:   #B5824A;
      --bg:      #F8F5F0;
    }

    /* ── NAVBAR ── */
    .navbar {
      background: white;
      padding: .8rem 3rem;
      display: flex; align-items: center; justify-content: space-between;
      box-shadow: 0 1px 8px rgba(0,0,0,.08);
      position: sticky; top: 0; z-index: 100;
    }
    .navbar-brand {
      font-size: 1rem; font-weight: 900; color: var(--primary);
      display: flex; align-items: center; gap: .3rem;
    }
    .navbar-brand span { color: var(--gold); }
    .navbar-links { display: flex; gap: 2rem; align-items: center; }
    .navbar-links a { color: #444; font-size: .9rem; font-weight: 500; transition: color .2s; }
    .navbar-links a:hover { color: var(--primary); }
    .navbar-btns { display: flex; gap: .75rem; }
    .btn-resa {
      border: 1.5px solid var(--primary); color: var(--primary);
      padding: .45rem 1.1rem; border-radius: 6px; font-size: .85rem; font-weight: 600;
      transition: all .2s;
    }
    .btn-resa:hover { background: var(--primary); color: white; }
    .btn-cnx {
      background: var(--gold); color: var(--primary);
      padding: .45rem 1.1rem; border-radius: 6px; font-size: .85rem; font-weight: 700;
      transition: all .2s;
    }
    .btn-cnx:hover { background: #b8935c; }

    /* ── HERO ── */
    .hero {
      display: grid; grid-template-columns: 1fr 1fr;
      min-height: 500px;
    }
    .hero-left {
      background: var(--primary);
      padding: 4rem 3rem;
      display: flex; flex-direction: column; justify-content: center;
    }
    .hero-tag {
      font-size: .72rem; color: rgba(255,255,255,.6);
      text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1rem;
    }
    .hero-left h1 {
      font-size: 2.3rem; font-weight: 900; color: white; line-height: 1.2; margin-bottom: 1rem;
    }
    .hero-left h1 em { color: var(--gold); font-style: normal; }
    .hero-left p { color: rgba(255,255,255,.7); font-size: .9rem; margin-bottom: 2rem; line-height: 1.6; }
    .hero-search {
      display: flex; background: white; border-radius: 8px; overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,.25);
    }
    .hero-search input {
      flex: 1; border: none; outline: none;
      padding: .75rem 1rem; font-size: .88rem; color: #333;
    }
    .hero-search input:nth-child(2) { border-left: 1px solid #eee; }
    .hero-search button {
      background: var(--gold); color: var(--primary);
      border: none; padding: .75rem 1.25rem;
      font-weight: 700; font-size: .85rem; cursor: pointer;
      white-space: nowrap; transition: background .2s;
    }
    .hero-search button:hover { background: #b8935c; }
    .hero-right img {
      width: 100%; height: 100%; object-fit: cover;
    }

    /* ── CATEGORIES ── */
    .categories {
      background: white; padding: 1.5rem 3rem;
      border-bottom: 1px solid #f0ebe5;
      display: flex; gap: 2.5rem; align-items: center; justify-content: center;
      flex-wrap: wrap;
    }
    .cat-arrow { font-size: 1.3rem; color: #ccc; cursor: pointer; user-select: none; }
    .cat-arrow:hover { color: var(--gold); }
    .cat-item {
      display: flex; flex-direction: column; align-items: center; gap: .4rem;
      cursor: pointer; transition: transform .2s;
    }
    .cat-item:hover { transform: translateY(-3px); }
    .cat-icon {
      width: 58px; height: 58px; border-radius: 50%;
      background: #FDF5EC;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem;
    }
    .cat-label { font-size: .75rem; font-weight: 600; color: #666; }

    /* ── EXPERTISE ── */
    .expertise { background: white; padding: 4rem 3rem; }
    .section-head { text-align: center; margin-bottom: 2rem; }
    .section-head h2 { font-size: 1.55rem; font-weight: 800; color: var(--primary); }
    .section-head p  { color: #999; font-size: .88rem; margin-top: .4rem; }
    .exp-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-template-rows: 200px 200px;
      gap: .75rem; max-width: 900px; margin: 0 auto;
    }
    .exp-item { border-radius: 12px; overflow: hidden; cursor: pointer; }
    .exp-item.tall { grid-row: span 2; }
    .exp-item img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
    .exp-item:hover img { transform: scale(1.06); }

    /* ── RECOMMANDATIONS ── */
    .recommandations { padding: 4rem 3rem; background: var(--bg); }
    .small-tag {
      text-align: center; font-size: .72rem; font-weight: 700;
      color: var(--gold); text-transform: uppercase; letter-spacing: 2px; margin-bottom: .5rem;
    }
    .salons-row {
      display: flex; gap: 1.25rem; overflow-x: auto;
      padding-bottom: .5rem; scrollbar-width: none;
      max-width: 1100px; margin: 1.5rem auto 0;
    }
    .salons-row::-webkit-scrollbar { display: none; }
    .salon-card {
      min-width: 220px; background: white; border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,.08); overflow: hidden; flex-shrink: 0;
    }
    .salon-card-img { height: 155px; overflow: hidden; }
    .salon-card-img img { width: 100%; height: 100%; object-fit: cover; }
    .salon-card-body { padding: .85rem 1rem; }
    .salon-note { display: flex; align-items: center; gap: .3rem; font-size: .78rem; color: #666; margin-bottom: .3rem; }
    .salon-note .s { color: var(--gold); }
    .salon-name { font-weight: 700; font-size: .9rem; color: var(--primary); margin-bottom: .2rem; }
    .salon-loc  { font-size: .78rem; color: #999; margin-bottom: .65rem; }
    .btn-book {
      display: block; text-align: center; background: var(--primary); color: white;
      padding: .45rem; border-radius: 6px; font-size: .8rem; font-weight: 600;
      transition: background .2s;
    }
    .btn-book:hover { background: var(--gold); color: var(--primary); }

    /* ── TEMOIGNAGES ── */
    .temoignages { padding: 4rem 3rem; background: white; }
    .temo-wrap { max-width: 820px; margin: 1.5rem auto 0; }
    .temo-card {
      background: var(--brown); border-radius: 16px; padding: 2.5rem;
      display: grid; grid-template-columns: 140px 1fr; gap: 2rem; align-items: center;
    }
    .temo-left { position: relative; height: 100px; }
    .temo-bars { display: flex; gap: 8px; }
    .temo-bar  { width: 14px; height: 75px; background: white; border-radius: 20px; opacity: .9; }
    .temo-avatar {
      position: absolute; top: -5px; left: 25px;
      width: 85px; height: 85px; border-radius: 50%;
      overflow: hidden; border: 3px solid white;
    }
    .temo-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .temo-right { color: white; }
    .temo-name  { font-weight: 700; font-size: .88rem; }
    .temo-city  { font-size: .75rem; opacity: .7; margin-bottom: .5rem; }
    .temo-title { font-size: 1rem; font-weight: 800; margin-bottom: .5rem; line-height: 1.4; }
    .temo-text  { font-size: .85rem; opacity: .85; line-height: 1.6; }
    .temo-qdeb, .temo-qfin { font-size: 1.8rem; opacity: .5; line-height: 1; }
    .temo-qfin { text-align: right; }
    .temo-dots { display: flex; justify-content: center; gap: .5rem; margin-top: 1.25rem; }
    .temo-dot  { width: 10px; height: 10px; border-radius: 50%; background: #ddd; border: none; cursor: pointer; }
    .temo-dot.on { background: var(--brown); }

    /* ── NEWSLETTER ── */
    .newsletter { padding: 3rem 3rem; background: white; }
    .newsletter-inner {
      max-width: 820px; margin: 0 auto;
      display: grid; grid-template-columns: 1fr 1.3fr; gap: 3rem; align-items: center;
    }
    .nl-img { border-radius: 12px; overflow: hidden; height: 185px; }
    .nl-img img { width: 100%; height: 100%; object-fit: cover; }
    .nl-text h3 { font-size: 1.2rem; font-weight: 800; color: var(--primary); margin-bottom: .4rem; }
    .nl-text p  { font-size: .84rem; color: #999; margin-bottom: 1rem; line-height: 1.6; }
    .nl-form { display: flex; gap: .5rem; }
    .nl-form input {
      flex: 1; border: 1px solid #e0e0e0; border-radius: 6px;
      padding: .5rem .8rem; font-size: .84rem; outline: none;
    }
    .nl-form input:focus { border-color: var(--gold); }
    .nl-form button {
      background: var(--primary); color: white; border: none;
      padding: .5rem 1rem; border-radius: 6px; font-size: .82rem;
      font-weight: 600; cursor: pointer; white-space: nowrap;
      transition: background .2s;
    }
    .nl-form button:hover { background: var(--gold); color: var(--primary); }

    /* ── FOOTER ── */
    .footer { background: #1A0D1B; color: rgba(255,255,255,.65); padding: 2.5rem 3rem 1rem; }
    .footer-social { display: flex; gap: .5rem; margin-bottom: 1.5rem; }
    .footer-social a {
      width: 30px; height: 30px; border: 1px solid rgba(255,255,255,.2);
      border-radius: 5px; display: flex; align-items: center; justify-content: center;
      color: white; font-size: .8rem; transition: all .2s;
    }
    .footer-social a:hover { border-color: var(--gold); color: var(--gold); }
    .footer-cols {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 2rem; margin-bottom: 1.5rem;
    }
    .footer-cols h4 { color: white; font-size: .82rem; font-weight: 700; margin-bottom: .6rem; }
    .footer-cols a  { display: block; color: rgba(255,255,255,.55); font-size: .82rem; margin-bottom: .3rem; }
    .footer-cols a:hover { color: var(--gold); }
    .footer-cols p  { color: rgba(255,255,255,.55); font-size: .82rem; margin-bottom: .3rem; }
    .footer-bottom {
      border-top: 1px solid rgba(255,255,255,.1); padding-top: 1rem;
      text-align: center; font-size: .75rem; color: rgba(255,255,255,.35);
    }
  </style>
</head>
<body>

<?php $flash = getFlash(); if ($flash): ?>
<div style="padding:.5rem 2rem;background:<?= $flash['type']==='error'?'#fee2e2':'#dcfce7' ?>;color:<?= $flash['type']==='error'?'#991b1b':'#166534' ?>;font-size:.88rem">
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<!-- ══ NAVBAR ══ -->
<nav class="navbar">
  <a href="<?= APP_URL ?>/index.php?page=home" class="navbar-brand">
    ✦ COIFF <span>CONNECT</span>
  </a>
  <div class="navbar-links">
    <a href="<?= APP_URL ?>/index.php?page=home">Accueil</a>
    <a href="<?= APP_URL ?>/index.php?page=salons&action=index">Services</a>
    <a href="<?= APP_URL ?>/index.php?page=contact">Contact</a>
    <a href="#">FR ▾</a>
  </div>
  <div class="navbar-btns">
    <a href="<?= APP_URL ?>/index.php?page=reservations&action=index" class="btn-resa">Réservation</a>
    <a href="<?= APP_URL ?>/index.php?page=login" class="btn-cnx">Connexion</a>
  </div>
</nav>

<!-- ══ HERO ══ -->
<section class="hero">
  <div class="hero-left">
    <p class="hero-tag">Salon de coiffure (Homme, Femme) • Salon de beauté</p>
    <h1>Réservez vos <em>services beauté</em> et bien-être facilement</h1>
    <p>Trouvez les meilleurs salons, spas et barbershops près de chez vous.</p>
    <form action="<?= APP_URL ?>/index.php" method="GET">
      <input type="hidden" name="page" value="salons">
      <input type="hidden" name="action" value="index">
      <div class="hero-search">
        <input type="text" name="service" placeholder="Réservez vos services…">
        <input type="text" name="ville" placeholder="Lieu">
        <button type="submit">Rechercher 🔍</button>
      </div>
    </form>
  </div>
  <div class="hero-right">
    <img src="<?= $imgBase ?>/hero.png" alt="Salon de coiffure">
  </div>
</section>

<!-- ══ CATEGORIES ══ -->
<section class="categories">
  <span class="cat-arrow">‹</span>
  <?php
  $cats = [
    ['💄','Maquillage'],['🧖','Bien-être'],['💈','Barbier'],
    ['✂️','Salon de coiffure'],['💆','Cabinet de massage'],['💅','Pédicure'],
  ];
  foreach ($cats as [$ico, $lbl]):
  ?>
    <a href="<?= APP_URL ?>/index.php?page=salons&action=index&service=<?= urlencode($lbl) ?>" class="cat-item">
      <div class="cat-icon"><?= $ico ?></div>
      <span class="cat-label"><?= $lbl ?></span>
    </a>
  <?php endforeach; ?>
  <span class="cat-arrow">›</span>
</section>

<!-- ══ EXPERTISE ══ -->
<section class="expertise">
  <div class="section-head">
    <h2>Votre beauté, notre expertise.</h2>
    <p>Découvrez des services beauté et bien-être adaptés à vos besoins, rapidement et facilement.</p>
  </div>
  <div class="exp-grid">
    <div class="exp-item tall">
      <img src="<?= $imgBase ?>/soin_visage.png" alt="Soin visage">
    </div>
    <div class="exp-item">
      <img src="<?= $imgBase ?>/tresses.png" alt="Tresses">
    </div>
    <div class="exp-item">
      <img src="<?= $imgBase ?>/massage.png" alt="Massage">
    </div>
    <div class="exp-item">
      <img src="<?= $imgBase ?>/coupe_homme.png" alt="Coupe homme">
    </div>
    <div class="exp-item">
      <img src="<?= $imgBase ?>/massage2.png" alt="Spa">
    </div>
  </div>
</section>

<!-- ══ RECOMMANDATIONS ══ -->
<section class="recommandations">
  <div class="small-tag">Services populaires</div>
  <div class="section-head">
    <h2>Nos recommandations</h2>
    <p>Des services beauté et bien-être conçus pour révéler votre élégance.</p>
  </div>

  <div class="salons-row">
    <?php
    $sImgs = ['maquillage.png','barbershop.png','salon2.png','salon3.png'];
    $demoSalons = [
      [1,'Wizz Mo barbershop','Keur Massar','3.0','02','barbershop.png'],
      [2,'Maison Diodio Glow Skin','Dakar','3.0','01','diodio.png'],
      [3,'Awaty Beauty Spa','Cité Makak','4.6','55','salon2.png'],
      [4,'ELITE MEN','Saint Louis','3.0','01','salon3.png'],
    ];
    $liste = !empty($salonsPopulaires) ? $salonsPopulaires : [];
    if (!empty($liste)):
      foreach ($liste as $idx => $s):
        $img = $sImgs[$idx % count($sImgs)];
    ?>
      <div class="salon-card">
        <div class="salon-card-img">
          <img src="<?= $imgBase ?>/<?= $img ?>" alt="<?= htmlspecialchars($s['nom']) ?>">
        </div>
        <div class="salon-card-body">
          <div class="salon-note"><span class="s">★</span> <?= $s['note_moyenne'] ?> <span style="color:#ccc">(<?= rand(1,55) ?> avis)</span></div>
          <div class="salon-name"><?= htmlspecialchars($s['nom']) ?></div>
          <div class="salon-loc">📍 <?= htmlspecialchars($s['quartier']) ?>, <?= htmlspecialchars($s['ville']) ?></div>
          <a href="<?= APP_URL ?>/index.php?page=salons&action=show&id=<?= $s['id'] ?>" class="btn-book">Réserver maintenant</a>
        </div>
      </div>
    <?php endforeach; else:
      foreach ($demoSalons as [$id,$nom,$loc,$note,$nb,$img]):
    ?>
      <div class="salon-card">
        <div class="salon-card-img">
          <img src="<?= $imgBase ?>/<?= $img ?>" alt="<?= $nom ?>">
        </div>
        <div class="salon-card-body">
          <div class="salon-note"><span class="s">★</span> <?= $note ?> <span style="color:#ccc">(<?= $nb ?> avis)</span></div>
          <div class="salon-name"><?= $nom ?></div>
          <div class="salon-loc">📍 <?= $loc ?></div>
          <a href="<?= APP_URL ?>/index.php?page=salons&action=index" class="btn-book">Réserver maintenant</a>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</section>

<!-- ══ TEMOIGNAGES ══ -->
<section class="temoignages">
  <div class="small-tag">Témoignages</div>
  <div class="section-head"><h2>Avis de nos clients</h2></div>
  <div class="temo-wrap">
    <div class="temo-card">
      <div class="temo-left">
        <div class="temo-bars">
          <div class="temo-bar"></div>
          <div class="temo-bar"></div>
        </div>
        <div class="temo-avatar">
          <img src="<?= $imgBase ?>/temoignage.png" alt="Astou Gaye">
        </div>
      </div>
      <div class="temo-right">
        <div class="temo-qdeb">"</div>
        <div class="temo-name">Astou Gaye</div>
        <div class="temo-city">Dakar, Sénégal</div>
        <div class="temo-title">Profitez d'une expérience beauté unique et professionnelle.</div>
        <div class="temo-text">
          "Une plateforme moderne et facile à utiliser. J'ai pu réserver mon rendez-vous rapidement et profiter d'un excellent service dans un salon professionnel."
        </div>
        <div class="temo-qfin">"</div>
      </div>
    </div>
    <div class="temo-dots">
      <button class="temo-dot"></button>
      <button class="temo-dot on"></button>
    </div>
  </div>
</section>

<!-- ══ NEWSLETTER ══ -->
<section class="newsletter">
  <div class="newsletter-inner">
    <div class="nl-img">
      <img src="<?= $imgBase ?>/newsletter.png" alt="Newsletter">
    </div>
    <div class="nl-text">
      <h3>S'abonner à la newsletter</h3>
      <p>Inscrivez-vous à notre newsletter pour rester informé des dernières promotions, réductions et nouvelles fonctionnalités.</p>
      <div class="nl-form">
        <input type="email" placeholder="Entrez votre adresse e-mail">
        <button>S'abonner</button>
      </div>
    </div>
  </div>
</section>

<!-- ══ FOOTER ══ -->
<footer class="footer">
  <div class="footer-social">
    <a href="#">f</a><a href="#">t</a><a href="#">in</a><a href="#">📷</a>
  </div>
  <div class="footer-cols">
    <div>
      <h4>Explorer</h4>
      <a href="<?= APP_URL ?>/index.php?page=home">Accueil</a>
      <a href="<?= APP_URL ?>/index.php?page=salons&action=index">Services</a>
      <a href="<?= APP_URL ?>/index.php?page=contact">Contact</a>
      <a href="<?= APP_URL ?>/index.php?page=reservations&action=index">Réservation</a>
      <a href="<?= APP_URL ?>/index.php?page=login">Connexion</a>
    </div>
    <div>
      <h4>Pages utilitaires</h4>
      <a href="#">Politique de confidentialité</a>
      <a href="#">Conditions d'utilisation</a>
    </div>
    <div>
      <h4>Contactez Nous</h4>
      <p>Mail : coiffconnect@gmail.com</p>
      <p>Tél : (+221) 78 304 79 41</p>
    </div>
  </div>
  <div class="footer-bottom">© 2026, CoiffConnect | Tout Droit Réservé.</div>
</footer>

</body>
</html>
