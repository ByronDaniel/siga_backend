<?php

namespace App\Http\Controllers\JobBoard;

// Controllers
use App\Http\Controllers\Controller;
use App\Http\Controllers\App\FileController;
use App\Http\Controllers\App\ImageController;

// Models
use App\Http\Requests\JobBoard\Skill\StoreSkillRequest;
use App\Models\App\Catalogue;
use App\Models\JobBoard\Company;
use App\Models\JobBoard\Professional;
use App\Models\JobBoard\Skill;

// FormRequest
use App\Http\Requests\JobBoard\Skill\IndexSkillRequest;
use App\Http\Requests\JobBoard\Skill\UpdateSkillRequest;
use App\Http\Requests\App\Image\UpdateImageRequest;
use App\Http\Requests\App\Image\UploadImageRequest;
use App\Http\Requests\App\File\UpdateFileRequest;
use App\Http\Requests\App\File\UploadFileRequest;
use App\Http\Requests\App\File\IndexFileRequest;
use App\Http\Requests\App\Image\IndexImageRequest;

class SkillController extends Controller
{
    function index(IndexSkillRequest $request)
{
    // Crea una instanacia del modelo Professional para poder insertar en el modelo skill.
    $professional = Professional::getInstance($request->input('professional_id'));

    if ($request->has('search')) {
        $skills = $professional->skills()
            ->description($request->input('search'))
            ->paginate($request->input('per_page'));
    } else {
        $skills = $professional->skills()->paginate($request->input('per_page'));
    }

    if (sizeof($skills) === 0) {
        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'No se encontraron Habilidades',
                'detail' => 'Intente de nuevo',
                'code' => '404'
            ]], 404);
    }

    return response()->json($skills, 200);
}

    function show($skillId)
    {
        // Valida que el id se un número, si no es un número devuelve un mensaje de error
        if (!is_numeric($skillId)) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'ID no válido',
                    'detail' => 'Intente de nuevo',
                    'code' => '400'
                ]], 400);
        }
        $skill = Skill::find($skillId);

        // Valida que exista el registro, si no encuentra el registro en la base devuelve un mensaje de error
        if (!$skill) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Habilidad no encontrada',
                    'detail' => 'Vuelva a intentar',
                    'code' => '404'
                ]], 404);
        }

        return response()->json([
            'data' => $skill,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }

    function store(StoreSkillRequest $request)
    {
        // Crea una instanacia del modelo Professional para poder insertar en el modelo skill.
        $professional = Professional::getInstance($request->input('professional.id'));

        // Crea una instanacia del modelo Catalogue para poder insertar en el modelo skill.
        $type = Catalogue::getInstance($request->input('type.id'));

        $skill = new Skill();
        $skill->description = $request->input('skill.description');
        $skill->professional()->associate($professional);
        $skill->type()->associate($type);
        $skill->save();

        return response()->json([
            'data' => $skill,
            'msg' => [
                'summary' => 'Habilidad creada',
                'detail' => 'El registro fue creado',
                'code' => '201'
            ]], 201);
    }

    function update(UpdateSkillRequest $request, $skillId)
    {
        // Crea una instanacia del modelo Catalogue para poder insertar en el modelo skill.
        $type = Catalogue::getInstance($request->input('type.id'));

        $skill = Skill::find($skillId);

        // Valida que exista el registro, si no encuentra el registro en la base devuelve un mensaje de error
        if (!$skill) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Habilidad no encontrada',
                    'detail' => 'Vuelva a intentar',
                    'code' => '404'
                ]], 404);
        }

        $skill->description = $request->input('skill.description');
        $skill->type()->associate($type);
        $skill->save();

        return response()->json([
            'data' => $skill,
            'msg' => [
                'summary' => 'Habilidad actualizada',
                'detail' => 'El registro fue actualizado',
                'code' => '201'
            ]], 201);
    }

    function destroy($skillId)
    {
        // Valida que el id se un número, si no es un número devuelve un mensaje de error
        if (!is_numeric($skillId)) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'ID no válido',
                    'detail' => 'Intente de nuevo',
                    'code' => '400'
                ]], 400);
        }
        $skill = Skill::find($skillId);

        // Valida que exista el registro, si no encuentra el registro en la base devuelve un mensaje de error
        if (!$skill) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Habilidad no encontrada',
                    'detail' => 'Vuelva a intentar',
                    'code' => '404'
                ]], 404);
        }

        // Es una eliminación lógica
        $skill->delete();

        return response()->json([
            'data' => $skill,
            'msg' => [
                'summary' => 'Habilidad eliminada',
                'detail' => 'El registro fue eliminado',
                'code' => '201'
            ]], 201);
    }

    function uploadImages(UploadImageRequest $request)
    {
        return (new ImageController())->upload($request, Skill::getInstance($request->input('id')));
    }

    function updateImage(UpdateImageRequest $request, $imageId)
    {
        return (new ImageController())->update($request, $imageId);
    }

    function deleteImage($imageId)
    {
        return (new ImageController())->delete($imageId);
    }

    function indexImage(IndexImageRequest $request)
    {
        return (new FileController())->index($request, Skill::getInstance($request->input('id')));
    }

    function ShowImage($fileId)
    {
        return (new FileController())->show($fileId);
    }

    function uploadFiles(UploadFileRequest $request)
    {
        return (new FileController())->upload($request, Skill::getInstance($request->input('id')));
    }

    function updateFile(UpdateFileRequest $request, $fileId)
    {
        return (new FileController())->update($request, $fileId);
    }

    function deleteFile($fileId)
    {
        return (new FileController())->delete($fileId);
    }

    function indexFile(IndexFileRequest $request)
    {
        return (new FileController())->index($request, Skill::getInstance($request->input('id')));
    }

    function ShowFile($fileId)
    {
        return (new FileController())->show($fileId);
    }
}
