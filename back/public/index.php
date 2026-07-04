<?php

declare(strict_types=1);

require __DIR__ . '/../config/env.php';
require __DIR__ . '/../src/autoload.php';

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

// CORS basique (adapter en production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$router = new Router();
require __DIR__ . '/../src/routes.php';

$request = new Request();

try {
    // Detecte automatiquement le sous-dossier de deploiement (ex: /AgriGestion/public
    // sous XAMPP/WAMP) pour le retirer avant le routage. Fonctionne aussi a la racine
    // d'un domaine ou avec le serveur integre `php -S localhost:8000 -t public`.
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $basePath = $scriptDir === '/' ? '' : $scriptDir;

    $router->dispatch($request, $basePath);
} catch (\Throwable $e) {
    $debug = env('APP_ENV', 'production') === 'local';
    Response::error(
        'Erreur serveur',
        500,
        $debug ? ['detail' => $e->getMessage()] : []
    );
}
