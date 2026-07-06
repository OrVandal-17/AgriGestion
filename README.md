# AgriGestion — Plateforme de gestion agricole (Togo)

AgriGestion est une application de gestion des exploitations agricoles
destinée aux coopératives togolaises : suivi des parcelles, des mises en
culture, des intrants utilisés et des récoltes, avec des tableaux de bord
adaptés à trois profils d'utilisateurs (Administrateur, Responsable de
coopérative, Agriculteur).

## Structure du dépôt

```
AgriGestion/
├── back/     API REST en PHP natif (PDO), authentification JWT
├── front/    Maquette d'interface statique (HTML/CSS/JS, Chart.js)
└── docs/     Diagrammes de conception (MCD, MLD, cas d'utilisation)
```

## Modèle de données (MLD)

Le schéma repose sur une spécialisation par héritage : `Utilisateur` porte
les informations de connexion communes (Nom, Prenom, Email, PassHash...),
et chaque rôle est représenté par une table dédiée dont la clé primaire
`IdUtil` est aussi une clé étrangère vers `Utilisateur` : `Administrateur`,
`Responsable` et `Agriculteur` (ces deux derniers rattachés à une
`Cooperative` via `IdCoop`). Le rôle d'un compte n'est donc pas stocké dans
une colonne, mais déduit de la table de spécialisation où apparaît son
`IdUtil`.

Autour de ce noyau : `Cooperative`, `Culture`, `ZoneAgroEcologique`,
`Intrant`, `Saison`, `Parcelle` (rattachée à un `Agriculteur`),
`Exploitation` (la mise en culture d'une `Parcelle` pour une `Culture` et
une `Saison` données) et enfin `Recolte` et `Utiliser`, tous deux rattachés
à une `Exploitation` plutôt que directement à la parcelle.

Le script SQL correspondant se trouve dans `back/database/gestion_agricole_togo.sql`
et les diagrammes de conception dans `docs/` (MCD, MLD, cas d'utilisation).

## Démarrage rapide

Le backend est un projet PHP autonome (sans dépendance Composer) — voir
**[`back/README.md`](back/README.md)** pour l'installation détaillée
(prérequis, création de la base, configuration `.env`, liste complète des
routes de l'API).

En résumé :

```bash
git clone https://github.com/OrVandal-17/AgriGestion.git
cd AgriGestion/back

mysql -u root -p -e "CREATE DATABASE gestion_agricole_togo CHARACTER SET utf8mb4"
mysql -u root -p gestion_agricole_togo < database/gestion_agricole_togo.sql

cp .env.example .env
# éditer .env : DB_HOST, DB_USER, DB_PASS, JWT_SECRET

php -S localhost:8000 -t public
```

Compte administrateur par défaut (issu du script SQL) :

- Email : `admin@agrigestion.tg`
- Mot de passe : `Admin@2026`

Une interface de test des routes (`back/public/test-interface.html`) est
fournie pour exercer l'API sans outil externe (type Postman).

Le dossier `front/` contient une maquette statique de tableau de bord
(HTML/CSS/JS + Chart.js) et n'est pas encore branché sur l'API.

## Sécurité

- Mots de passe hashés avec `password_hash` (bcrypt).
- Authentification par jeton JWT (HS256), signé côté serveur.
- Requêtes SQL préparées (PDO) partout — protection contre les injections.
- Contrôle d'accès par rôle sur chaque route, et vérification de propriété
  des données (un agriculteur n'agit que sur ses propres parcelles /
  exploitations ; un responsable ne voit que sa coopérative).

Voir `back/README.md` section « Sécurité » pour le détail.
