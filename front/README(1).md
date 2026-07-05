# AGRI-TOGO

Plateforme web de gestion agricole conçue pour le contexte togolais (agriculteurs, parcelles, cultures, coopératives, récoltes, intrants...).

Ce dépôt contient uniquement le **front-end** (HTML / CSS / JS ). Les données affichées sont actuellement statiques ; le branchement à une base de données (PHP + MySQL) est prévu dans une prochaine étape.

---

## Structure du projet

```
agritogo/
├── index.html                Tableau de bord (statistiques + graphique)
├── connexion.html             Page de connexion
├── inscription.html           Page de création de compte
│
├── agriculture.html            Liste des agriculteurs
├── ajoutAgriculteur.html       Formulaire d'ajout d'un agriculteur
│
├── gestionparcelles.html       Liste des parcelles
├── ajoutParcelle.html          Formulaire d'ajout d'une parcelle
│
├── cultures.html               Liste des cultures
├── ajoutCulture.html           Formulaire d'ajout d'une culture
│
├── intrants.html               Liste des intrants (semences, engrais...)
├── ajoutIntrant.html           Formulaire d'ajout d'un intrant
│
├── recolte.html                Formulaire de saisie d'une récolte
│
├── saisons.html                Liste des saisons culturales
├── ajoutSaison.html            Formulaire d'ajout d'une saison
│
├── zones.html                  Liste des zones agroécologiques
├── ajoutZone.html               Formulaire d'ajout d'une zone
│
├── cooperatives.html           Liste des coopératives
├── ajoutCooperative.html       Formulaire d'ajout d'une coopérative
│
├── utilisateurs.html           Liste des utilisateurs de la plateforme
├── ajoutUtilisateur.html       Formulaire d'ajout d'un utilisateur
│
├── parametres.html             Paramètres de la plateforme
├── rapport.html                Bilan & rapports de campagne (stats, graphiques)
│
├── style.css                   Feuille de style unique (variables + responsive)
├── app.js                      Menu mobile + surbrillance du lien actif
└── myChart.js                  Configuration du graphique (Chart.js) du tableau de bord
```

---

## Technologies utilisées

- **HTML5 / CSS3** — pas de framework, CSS pur avec variables (`:root`)
- **JavaScript vanilla** — pas de dépendance de build (pas de npm/webpack)
- **[Font Awesome 7](https://fontawesome.com/)** — icônes (chargées via CDN)
- **[Chart.js 4](https://www.chartjs.org/)** — graphique du tableau de bord (chargé via CDN)
- **Police Figtree** (Google Fonts)

---

## Système de design

Toutes les pages partagent le même fichier `style.css`, organisé en sections :

1. Variables (couleurs, rayons, ombres)
2. Reset
3. Sidebar (menu latéral)
4. Topbar (barre supérieure)
5. Contenu principal
6. Cartes du tableau de bord
7. Tableaux
8. En-têtes de page / cartes / barre d'outils
9. Boutons
10. Formulaires
11. Cartes statistiques (rapports)
12. État vide
13. Media queries (responsive)
14. Page de connexion / inscription

**Palette principale :**

| Variable                 | Couleur               | Usage                                    |
| ------------------------ | --------------------- | ---------------------------------------- |
| `--forest`               | `#0a330d`             | Sidebar, titres                          |
| `--leaf` / `--leaf-dark` | `#3e9e5f` / `#2f8a4f` | Boutons primaires, accents               |
| `--soil`                 | `#b07a45`             | Statuts "en jachère"                     |
| `--danger`               | `#c0533e`             | Actions destructrices, ruptures de stock |
| `--gray`                 | `#6c7a70`             | Textes secondaires                       |

---

## 📱 Responsive

- **Sidebar** : fixe sur desktop, se transforme en menu coulissant sur mobile (< 900px), avec un bouton dans la barre du haut et un fond sombre cliquable pour la fermer.
- **Tableaux** : défilement horizontal sur petit écran (`.table-wrap`).
- **Formulaires / grilles / cartes statistiques** : passent en une seule colonne sous 900px / 640px.

---

## Authentification (front uniquement pour l'instant)

- `connexion.html` → à la soumission, redirige vers `index.html` (redirection JS temporaire).
- `inscription.html` → vérifie que les deux mots de passe correspondent, puis redirige vers `index.html`.
- Le lien **Déconnexion** du menu (présent sur toutes les pages internes) ramène vers `connexion.html`.
- Chaque page a un lien croisé : _"Pas encore de compte ?"_ / _"Vous avez déjà un compte ?"_.

---
