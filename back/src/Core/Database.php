<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function connection(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $config['host'],
                $config['port'],
                $config['name']
            );
            $attempts = 0;
            $lastError = null;
            while ($attempts < 2 && self::$instance === null) {
                $attempts++;
                try {
                    self::$instance = new PDO($dsn, $config['user'], $config['pass'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]);
                } catch (PDOException $e) {
                    $lastError = $e;
                    // Sur un hebergement mutualise, un "too many connections"
                    // est souvent transitoire (pic de requetes simultanees) :
                    // on retente une fois apres une courte pause avant d'abandonner.
                    if ($attempts < 2) {
                        usleep(150000); // 150ms
                    }
                }
            }
            if (self::$instance === null) {
                // On journalise le vrai message cote serveur (utile pour distinguer
                // "mauvais identifiants", "hote injoignable" et "trop de connexions"),
                // sans exposer ce detail au client en production.
                error_log('[AgriGestion] Connexion base de donnees echouee: ' . $lastError->getMessage());
                $payload = ['error' => 'Connexion base de donnees impossible'];
                if (env('APP_ENV', 'local') === 'local') {
                    $payload['detail'] = $lastError->getMessage();
                }
                Response::json($payload, 500);
                exit;
            }
        }
        return self::$instance;
    }
}
