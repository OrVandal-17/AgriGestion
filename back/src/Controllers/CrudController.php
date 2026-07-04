<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

/**
 * Controleur CRUD generique pour les tables de reference simples
 * (cooperative, culture, intrant, saison, zone_agroecologique...).
 * Chaque sous-classe precise le Model et les champs autorises.
 */
abstract class CrudController
{
    /** @return class-string<\App\Models\Model> */
    abstract protected static function model(): string;

    /** Champs acceptes en creation/modification */
    abstract protected static function fillable(): array;

    protected static function primaryKey(): string
    {
        return 'id';
    }

    public static function index(Request $request): void
    {
        $model = static::model();
        Response::json($model::all());
    }

    public static function show(Request $request): void
    {
        $model = static::model();
        $row = $model::find($request->params['id']);
        if (!$row) {
            Response::error('Ressource introuvable', 404);
            return;
        }
        Response::json($row);
    }

    public static function store(Request $request): void
    {
        $data = self::filter($request->body);
        if (empty($data)) {
            Response::error('Aucune donnee valide fournie', 422);
            return;
        }
        $model = static::model();
        $id = $model::create($data);
        Response::json($model::find($id), 201);
    }

    public static function update(Request $request): void
    {
        $model = static::model();
        $id = $request->params['id'];
        if (!$model::find($id)) {
            Response::error('Ressource introuvable', 404);
            return;
        }
        $data = self::filter($request->body);
        if (empty($data)) {
            Response::error('Aucune donnee valide fournie', 422);
            return;
        }
        $model::update($id, $data);
        Response::json($model::find($id));
    }

    public static function destroy(Request $request): void
    {
        $model = static::model();
        $id = $request->params['id'];
        if (!$model::find($id)) {
            Response::error('Ressource introuvable', 404);
            return;
        }
        $model::delete($id);
        Response::json(['message' => 'Supprime avec succes']);
    }

    private static function filter(array $body): array
    {
        return array_intersect_key($body, array_flip(static::fillable()));
    }
}
