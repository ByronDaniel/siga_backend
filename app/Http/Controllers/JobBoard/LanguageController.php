<?php

namespace App\Http\Controllers\JobBoard;

// Controllers
use App\Http\Controllers\Controller;

// Models
use App\Models\App\Catalogue;
use App\Models\JobBoard\Professional;
use App\Models\JobBoard\Language;

// FormRequest

use App\Http\Requests\JobBoard\Laguage\IndexLanguageRequest;
use App\Http\Requests\JobBoard\Laguage\UpdateLanguageRequest;
use App\Http\Requests\JobBoard\Laguage\CreateLanguageRequest;
use Illuminate\Database\Eloquent\Model;

class LanguageController extends Controller
{
    function index(IndexLanguageRequest $request)
    {
        // Crea una instanacia del modelo Professional para poder insertar en el modelo language.
        $professional = Professional::getInstance($request->input('professional_id'));

        if ($request->has('search')) {
            $languages = $professional->languages()->get();
        } else {
            $languages = $professional->languages()->paginate($request->input('per_page'));
        }

        if (sizeof($languages) === 0) {
            return response()->json([
                'data' => $languages,
                'msg' => [
                    'summary' => 'success',
                    'detail' => '',
                    'code' => '200'
                ]
            ], 404);
        }
        return response()->json($languages, 200);
    }

    function show($languageId)
    {
        // Valida que el id se un número, si no es un número devuelve un mensaje de error
        if (!is_numeric($languageId)) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'ID no válido',
                    'detail' => 'Intente de nuevo',
                    'code' => '400'
                ]
            ], 400);
        }
        $language = Language::find($languageId);

        // Valida que exista el registro, si no encuentra el registro en la base devuelve un mensaje de error
        if (!$language) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Habilidad no encontrada',
                    'detail' => 'Vuelva a intentar',
                    'code' => '404'
                ]
            ], 404);
        }
        return response()->json([
            'data' => $language,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]
        ], 200);
    }

    function store(CreateLanguageRequest $request)
    {
        // Crea una instanacia del modelo Professional para poder insertar en el modelo skill.
        $professional = Professional::getInstance($request->input('professional.id'));
        $idiom = Catalogue::getInstance($request->input('idiom.id'));
        $writtenLevel = Catalogue::getInstance($request->input('writtenLevel.id'));
        $spokenLevel = Catalogue::getInstance($request->input('spokenLevel.id'));
        $readLevel = Catalogue::getInstance($request->input('readLevel.id'));

        $language = new Language();
        $language->professional()->associate($professional);
        $language->idiom()->associate($idiom);
        $language->writtenLevel()->associate($writtenLevel);
        $language->spokenLevel()->associate($spokenLevel);
        $language->readLevel()->associate($readLevel);
        $language->save();

        return response()->json([
            'data' => $language,
            'msg' => [
                'summary' => 'Lenguaje creada',
                'detail' => 'El registro fue creado',
                'code' => '201'
            ]
        ], 201);
    }

    function update(UpdateLanguageRequest $request, $languageId)
    {
        // Crea una instanacia del modelo Catalogue para poder insertar en el modelo language.

        $language = Language::find($languageId);

        // Valida que exista el registro, si no encuentra el registro en la base devuelve un mensaje de error
        if (!$language) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Habilidad no encontrada',
                    'detail' => 'Vuelva a intentar',
                    'code' => '404'
                ]
            ], 404);
        }
        $idiom = Catalogue::getInstance($request->input('idiom.id'));
        $writtenLevel = Catalogue::getInstance($request->input('writtenLevel.id'));
        $spokenLevel = Catalogue::getInstance($request->input('spokenLevel.id'));
        $readLevel = Catalogue::getInstance($request->input('readLevel.id'));

        $language = new Language();
        $language->idiom()->associate($idiom);
        $language->writtenLevel()->associate($writtenLevel);
        $language->spokenLevel()->associate($spokenLevel);
        $language->readLevel()->associate($readLevel);
        $language->save();

        return response()->json([
            'data' => $language,
            'msg' => [
                'summary' => 'Habilidad actualizada',
                'detail' => 'El registro fue actualizado',
                'code' => '201'
            ]
        ], 201);
    }

    function destroy($languageId)
    {
        // Valida que el id se un número, si no es un número devuelve un mensaje de error
        if (!is_numeric($languageId)) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'ID no válido',
                    'detail' => 'Intente de nuevo',
                    'code' => '400'
                ]
            ], 400);
        }

        $language = Language::find($languageId);

        // Valida que exista el registro, si no encuentra el registro en la base devuelve un mensaje de error
        if (!$language) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Lenguaje no encontrada',
                    'detail' => 'Vuelva a intentar',
                    'code' => '404'
                ]
            ], 404);
        }

        // Es una eliminación lógica
        $language->delete();

        return response()->json([
            'data' => $language,
            'msg' => [
                'summary' => 'Lenguaje eliminada',
                'detail' => 'El registro fue eliminado',
                'code' => '201'
            ]
        ], 201);
    }

}
