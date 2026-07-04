# AgriGestion — Backend PHP (PDO)

Backend REST pour la gestion des exploitations agricoles (coopératives, parcelles,
mises en culture, intrants, récoltes) — Togo.

---

## 1. Architecture

```
back/
├── config/            configuration (.env, connexion PDO)
├── database/           scripts SQL (schéma original + corrections)
├── docs/               diagramme UML corrigé (SVG)
├── public/              point d'entrée HTTP (index.php, .htaccess)
├── src/
│   ├── Core/            Router, Request, Response, Database (PDO), JWT
│   ├── Middleware/       vérification du token (Auth)
│   ├── Models/           accès aux données (PDO préparé)
│   ├── Controllers/      logique métier par ressource
│   └── routes.php        déclaration des routes + rôles autorisés
└── .env.example
```

Pas de dépendance Composer : autoload PSR-4 maison (`src/autoload.php`) et
implémentation JWT minimaliste (`src/Core/JWT.php`), pour rester compatible
avec un hébergement mutualisé simple (PHP 8.1+, MariaDB/MySQL, module
`mod_rewrite`).

Authentification par **Bearer Token (JWT)** : chaque route protégée exige
l'en-tête `Authorization: Bearer <token>`. Le token contient `sub` (Id_util),
`role` et `coop` (Id_coop, utile pour le Responsable de coopérative).

---

## 3. Installation

### Prérequis                                                    
- PHP ≥ 8.1 avec extensions `pdo_mysql`                           ]
- MariaDB/MySQL ≥ 10.4                                            |> WAMPserver 
- Apache avec `mod_rewrite` (ou Nginx équivalent — voir plus bas) ]

### Étapes

```bash
# 1. Cloner Le repository(de préférence dans le dossier wamp64/www/ (Le dossier ou est installé wampserver))

git clone https://github.com/OrVandal-17/AgriGestion.git
```

```bash
# 2. Créer la base et importer le schéma avec l'outil PhpMyAdmin de WAMP / la ligne de commande
mysql -u root -p -e "CREATE DATABASE gestion_agricole_togo CHARACTER SET utf8mb4"
mysql -u root -p gestion_agricole_togo < database/gestion_agricole_togo.sql

# 3. Configurer l'environnement
cp .env.example .env
# éditer .env : DB_HOST, DB_USER, DB_PASS, JWT_SECRET (obligatoire à changer)

# 4. Pointer le vhost / DocumentRoot vers AgriGestion/public
```
---

## 4. Utilisation / Routes de l'API

Base URL : `http://<votre-domaine>/` (le front-controller est dans `public/`).
Toutes les réponses sont en JSON.

### Utiliser Le compte admin par défaut pour se connecter et effectuer des modifications

-- Compte Administrateur initial
-- Email    : admin@agrigestion.tg
-- Mot de passe : Admin@2026

---

### Authentification

| Méthode | Route | Rôle | Description |
|---|---|---|---|
| POST | `/auth/login` | public | `{ "email": "...", "mot_de_passe": "..." }` → `{ token, utilisateur }` |
| GET | `/auth/me` | authentifié | Profil de l'utilisateur connecté (+ fiche agriculteur si applicable) |

### Administrateur — comptes & référentiels

| Méthode | Route | Rôle |
|---|---|---|
| GET/POST | `/utilisateurs` | Administrateur |
| GET/PUT/DELETE | `/utilisateurs/{id}` | Administrateur |
| GET | `/cooperatives` | tout utilisateur authentifié (lecture) |
| POST/PUT/DELETE | `/cooperatives(/{id})` | Administrateur |
| GET/POST/PUT/DELETE | `/agriculteurs(/{id})` | Administrateur |
| GET/POST/PUT/DELETE | `/parcelles(/{id})` | Administrateur |
| GET | `/zones`, `/cultures`, `/intrants`, `/saisons` | tout utilisateur authentifié (lecture, pour les listes déroulantes) |
| POST/PUT/DELETE | `/zones`, `/cultures`, `/intrants`, `/saisons` | Administrateur |

### Agriculteur — activités agricoles

| Méthode | Route | Description |
|---|---|---|
| GET | `/mes-parcelles` | Parcelles de l'agriculteur connecté uniquement |
| GET/POST | `/mises-en-culture` | Historique / saisie d'un semis (déclenche l'alerte de rotation si même culture que la dernière fois sur la parcelle) |
| GET/POST | `/intrants-utilises` | Historique / saisie d'un traitement (engrais, pesticide...) |
| GET/POST | `/recoltes` | Historique / saisie d'une récolte |

Toutes les écritures vérifient que la parcelle appartient bien à
l'agriculteur connecté (403 sinon).

### Responsable de coopérative

| Méthode | Route | Description |
|---|---|---|
| GET | `/bilan-production?saison_id=&culture_id=&date_debut=&date_fin=` | Bilan agrégé (rendement, coût) des récoltes de sa coopérative, filtrable |
| GET | `/agriculteurs-cooperative` | Liste des agriculteurs de sa coopérative |

### Exemple de requête

```bash
curl -X POST http://localhost/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@agrigestion.tg","mot_de_passe":"..."}'

curl http://localhost/mes-parcelles \
  -H "Authorization: Bearer <token>"

curl -X POST http://localhost/mises-en-culture \
  -H "Authorization: Bearer <token>" -H "Content-Type: application/json" \
  -d '{"Id_parcelle":1,"Id_culture":3,"Id_saison":2,"DateMiseEnCulture":"2026-06-01"}'
```

---

## 5. Sécurité

- Mots de passe hashés avec `password_hash` (bcrypt).
- Jetons JWT signés HS256, expiration configurable (`JWT_TTL`, en secondes).
- Toutes les requêtes SQL utilisent des requêtes préparées PDO (protection injection SQL).
- Contrôle d'accès par rôle sur chaque route (`src/routes.php`) + vérification
  de propriété des données (un agriculteur ne peut agir que sur ses parcelles ;
  un responsable ne voit que sa coopérative).
- Penser à changer `JWT_SECRET` avant toute mise en production.
