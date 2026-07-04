<?php

use App\Controllers\AgriculteurController;
use App\Controllers\AuthController;
use App\Controllers\CooperativeController;
use App\Controllers\CultureController;
use App\Controllers\IntrantController;
use App\Controllers\MiseEnCultureController;
use App\Controllers\ParcelleController;
use App\Controllers\RapportController;
use App\Controllers\RecolteController;
use App\Controllers\SaisonController;
use App\Controllers\UtilisateurController;
use App\Controllers\UtilisationController;
use App\Controllers\ZoneController;
use App\Core\Router;

/** @var Router $router */

const ADMIN = ['Administrateur'];
const AGRICULTEUR = ['Agriculteur'];
const RESPONSABLE = ['Responsable'];
const ANY = ['any'];

// ---- Authentification ----
$router->post('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/me', [AuthController::class, 'me'], ANY);

// ---- Administrateur : comptes utilisateurs ----
$router->get('/utilisateurs', [UtilisateurController::class, 'index'], ADMIN);
$router->get('/utilisateurs/{id}', [UtilisateurController::class, 'show'], ADMIN);
$router->post('/utilisateurs', [UtilisateurController::class, 'store'], ADMIN);
$router->put('/utilisateurs/{id}', [UtilisateurController::class, 'update'], ADMIN);
$router->delete('/utilisateurs/{id}', [UtilisateurController::class, 'destroy'], ADMIN);

// ---- Administrateur : cooperatives ----
$router->get('/cooperatives', [CooperativeController::class, 'index'], ANY);
$router->get('/cooperatives/{id}', [CooperativeController::class, 'show'], ANY);
$router->post('/cooperatives', [CooperativeController::class, 'store'], ADMIN);
$router->put('/cooperatives/{id}', [CooperativeController::class, 'update'], ADMIN);
$router->delete('/cooperatives/{id}', [CooperativeController::class, 'destroy'], ADMIN);

// ---- Administrateur : fiches agriculteurs ----
$router->get('/agriculteurs', [AgriculteurController::class, 'index'], ADMIN);
$router->get('/agriculteurs/{id}', [AgriculteurController::class, 'show'], ADMIN);
$router->post('/agriculteurs', [AgriculteurController::class, 'store'], ADMIN);
$router->put('/agriculteurs/{id}', [AgriculteurController::class, 'update'], ADMIN);
$router->delete('/agriculteurs/{id}', [AgriculteurController::class, 'destroy'], ADMIN);

// ---- Administrateur : parcelles (creation/assignation) ----
$router->get('/parcelles', [ParcelleController::class, 'index'], ADMIN);
$router->get('/parcelles/{id}', [ParcelleController::class, 'show'], ADMIN);
$router->post('/parcelles', [ParcelleController::class, 'store'], ADMIN);
$router->put('/parcelles/{id}', [ParcelleController::class, 'update'], ADMIN);
$router->delete('/parcelles/{id}', [ParcelleController::class, 'destroy'], ADMIN);

// ---- Administrateur : zones agroecologiques ----
$router->get('/zones', [ZoneController::class, 'index'], ANY);
$router->post('/zones', [ZoneController::class, 'store'], ADMIN);
$router->put('/zones/{id}', [ZoneController::class, 'update'], ADMIN);
$router->delete('/zones/{id}', [ZoneController::class, 'destroy'], ADMIN);

// ---- Administrateur : referentiel cultures (lecture ouverte a tous, ecriture admin) ----
$router->get('/cultures', [CultureController::class, 'index'], ANY);
$router->post('/cultures', [CultureController::class, 'store'], ADMIN);
$router->put('/cultures/{id}', [CultureController::class, 'update'], ADMIN);
$router->delete('/cultures/{id}', [CultureController::class, 'destroy'], ADMIN);

// ---- Administrateur : referentiel intrants ----
$router->get('/intrants', [IntrantController::class, 'index'], ANY);
$router->post('/intrants', [IntrantController::class, 'store'], ADMIN);
$router->put('/intrants/{id}', [IntrantController::class, 'update'], ADMIN);
$router->delete('/intrants/{id}', [IntrantController::class, 'destroy'], ADMIN);

// ---- Administrateur : saisons ----
$router->get('/saisons', [SaisonController::class, 'index'], ANY);
$router->post('/saisons', [SaisonController::class, 'store'], ADMIN);
$router->put('/saisons/{id}', [SaisonController::class, 'update'], ADMIN);
$router->delete('/saisons/{id}', [SaisonController::class, 'destroy'], ADMIN);

// ---- Agriculteur : mes parcelles ----
$router->get('/mes-parcelles', [ParcelleController::class, 'mine'], AGRICULTEUR);

// ---- Agriculteur : mises en culture (semis) + alerte de rotation ----
$router->get('/mises-en-culture', [MiseEnCultureController::class, 'index'], AGRICULTEUR);
$router->post('/mises-en-culture', [MiseEnCultureController::class, 'store'], AGRICULTEUR);

// ---- Agriculteur : intrants utilises (traitements) ----
$router->get('/intrants-utilises', [UtilisationController::class, 'index'], AGRICULTEUR);
$router->post('/intrants-utilises', [UtilisationController::class, 'store'], AGRICULTEUR);

// ---- Agriculteur : recoltes ----
$router->get('/recoltes', [RecolteController::class, 'index'], AGRICULTEUR);
$router->post('/recoltes', [RecolteController::class, 'store'], AGRICULTEUR);

// ---- Responsable de cooperative : bilans / rapports ----
$router->get('/bilan-production', [RapportController::class, 'bilanProduction'], RESPONSABLE);
$router->get('/agriculteurs-cooperative', [RapportController::class, 'listeAgriculteurs'], RESPONSABLE);
