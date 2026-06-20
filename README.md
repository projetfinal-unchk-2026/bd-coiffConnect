# CoiffConnect — Backend PHP

Plateforme de réservation de salons de coiffure et de beauté à Dakar.

---

## 🗂 Structure du projet

```
coiffconnect/
├── config/
│   ├── database.php        ← Connexion PDO (singleton)
│   └── app.php             ← Constantes, helpers, autoloader
├── controllers/
│   ├── AuthController.php       ← Inscription / Connexion / Déconnexion
│   ├── AdminController.php      ← Dashboard admin complet
│   ├── SalonController.php      ← Salons (liste, détail, CRUD coiffeur)
│   ├── ReservationController.php← Réservations client + créneaux AJAX
│   ├── AvisController.php       ← Dépôt d'avis après réservation terminée
│   ├── ServiceController.php    ← API services par salon
│   └── HomeController.php       ← Page d'accueil
├── models/
│   ├── Model.php                ← Classe de base PDO
│   ├── UtilisateurModel.php
│   ├── SalonModel.php
│   ├── ReservationModel.php
│   ├── ServiceModel.php
│   └── AvisModel.php
├── views/
│   ├── shared/
│   │   ├── layout.php           ← Navbar + footer commun
│   │   ├── home.php             ← Page accueil (Figma)
│   │   ├── 403.php / 404.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── admin/
│   │   ├── dashboard.php        ← Stats, réservations récentes, salons top
│   │   ├── utilisateurs.php
│   │   ├── etablissements.php
│   │   ├── reservations.php
│   │   └── avis.php
│   ├── client/
│   │   ├── salons.php           ← Liste + recherche
│   │   ├── salon_detail.php     ← Fiche salon + services + avis
│   │   ├── reserver.php         ← Formulaire réservation + créneaux AJAX
│   │   └── mes_reservations.php ← Historique + formulaire avis ⭐
│   └── coiffeur/
│       ├── dashboard.php        ← Tableau de bord coiffeur
│       └── salon_form.php       ← Créer / modifier un salon + services
└── public/
    ├── index.php                ← Routeur principal
    ├── .htaccess
    └── css/
        └── style.css            ← Couleurs Figma : bordeaux #2D1B2E, or #C9A96E
```

---

## ⚙️ Installation

### 1. Prérequis
- PHP 8.0+
- MySQL 8.0+
- Apache avec mod_rewrite (MAMP, XAMPP, Laragon…)

### 2. Base de données
```sql
-- Importer le fichier fourni :
mysql -u root -p coiffconnect < coiffconnect.sql
```
Ou via phpMyAdmin : importer `coiffconnect.sql`.

### 3. Configuration
Éditer `config/database.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'coiffconnect');
define('DB_USER', 'root');
define('DB_PASS', '');  // ou 'root' sur MAMP
```

Éditer `config/app.php` :
```php
define('APP_URL', 'http://localhost/coiffconnect/public');
```

### 4. Placer le projet
```
htdocs/coiffconnect/   (XAMPP)
htdocs/coiffconnect/   (MAMP)
```

---

## 🔐 Comptes de test

| Rôle     | Email                     | Mot de passe |
|----------|---------------------------|--------------|
| Admin    | admin@coiffconnect.sn     | password     |
| Coiffeur | moussa@example.com        | password     |
| Coiffeur | fatou@example.com         | password     |
| Client   | aminata@example.com       | password     |
| Client   | ibrahima@example.com      | password     |

> Le hash SHA-256 de "password" est déjà dans la BDD.

---

## 🗺️ Routes principales

| URL                                              | Description                        |
|--------------------------------------------------|------------------------------------|
| `?page=home`                                     | Accueil                            |
| `?page=login`                                    | Connexion                          |
| `?page=register`                                 | Inscription                        |
| `?page=salons&action=index`                      | Liste des salons                   |
| `?page=salons&action=show&id=1`                  | Détail d'un salon                  |
| `?page=reservations&action=create&salon_id=1`    | Formulaire de réservation          |
| `?page=reservations&action=index`                | Mes réservations (client)          |
| `?page=reservations&action=creneaux` (AJAX GET)  | Créneaux disponibles (JSON)        |
| `?page=avis&action=store` (POST)                 | Déposer un avis                    |
| `?page=admin&action=dashboard`                   | Dashboard admin                    |
| `?page=admin&action=utilisateurs`               | Gestion utilisateurs               |
| `?page=admin&action=etablissements`             | Gestion salons                     |
| `?page=admin&action=reservations`               | Toutes les réservations            |
| `?page=admin&action=avisClients`                | Modération des avis                |
| `?page=salons&action=dashboard`                  | Espace coiffeur                    |
| `?page=salons&action=create`                     | Créer un salon                     |
| `?page=salons&action=editSalon&id=1`             | Modifier un salon + services       |

---

## 🎨 Charte graphique (Figma)

| Variable CSS       | Valeur     | Usage                  |
|--------------------|------------|------------------------|
| `--primary`        | `#2D1B2E`  | Bordeaux foncé (navbar, sidebar) |
| `--gold`           | `#C9A96E`  | Or (accents, boutons)  |
| `--accent`         | `#E8C4B8`  | Rose poudré            |
| `--bg`             | `#F8F5F0`  | Fond général           |

---

## ✅ Fonctionnalités implémentées

- [x] Inscription / Connexion / Déconnexion (SHA-256, sessions sécurisées)
- [x] 3 rôles : client, coiffeur, admin
- [x] Recherche de salons par service et ville
- [x] Réservation avec vérification des créneaux (AJAX)
- [x] Annulation de réservation
- [x] Avis clients avec étoiles interactives (uniquement après réservation terminée)
- [x] Dashboard admin complet (stats, graphiques, filtres)
- [x] Gestion utilisateurs / salons / réservations par l'admin
- [x] Espace coiffeur : gestion salon + services + planning
- [x] Triggers BDD pour la mise à jour automatique de la note moyenne
- [x] Protection CSRF (sessions) + XSS (htmlspecialchars partout)
- [x] Messages flash (succès / erreur)
- [x] Responsive mobile
