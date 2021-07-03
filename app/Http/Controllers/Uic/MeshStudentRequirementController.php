<?php

namespace App\Http\Controllers\Uic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Uic\MeshStudentRequirement\DeleteMeshStudentRequirementRequest;
use App\Http\Requests\Uic\MeshStudentRequirement\StoreMeshStudentRequirementRequest;
use App\Http\Requests\Uic\MeshStudentRequirement\UpdateMeshStudentRequirementRequest;
use App\Http\Requests\Uic\MeshStudentRequirement\IndexMeshStudentRequirementRequest;
use App\Models\Uic\MeshStudentRequirement;
use App\Models\Uic\Requirement;

class MeshStudentRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexMeshStudentRequirementRequest $request)
    {

        $meshStudentRequirements = MeshStudentRequirement::paginate($request->input('per_page'));
        if ($meshStudentRequirements->count() == 0) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'No se encontraron registros',
                    'detail' => 'Intentalo de nuevo',
                    'code' => '404'
                ]
            ], 404);
        }
        return response()->json($meshStudentRequirements, 200);
    }

    public function show(MeshStudentRequirement $meshStudentRequirement)
    {
        if (!$meshStudentRequirement) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'El registro no existe',
                    'detail' => 'Intente con otro registro',
                    'code' => '404'
                ]
            ], 404);
        }
        return response()->json([
            'data' => $meshStudentRequirement,
            'msg' => [
                'summary' => 'El registro no existe',
                'detail' => 'Intente con otro registro',
                'code' => '404'
            ]
        ], 200);
    }

    public function store(StoreMeshStudentRequirementRequest $request)
    {
        $meshStudentRequirement = new MeshStudentRequirement;
        $meshStudentRequirement->mesh_student_id = $request->input('meshStudentRequirement.mesh_student_id');
        $meshStudentRequirement->requirement_id = $request->input('meshStudentRequirement.requirement_id');
        $meshStudentRequirement->save();
        return response()->json([
            'data' => $meshStudentRequirement,
            'msg' => [
                'summary' => 'Registro creado',
                'detail' => 'El registro fue creado con exito',
                'code' => '201'
            ]
        ], 201);
    }

    public function update(UpdateMeshStudentRequirementRequest $request, $id)
    {
        $meshStudentRequirement = MeshStudentRequirement::find($id);
        if (!$meshStudentRequirement) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'El registro no existe',
                    'detail' => 'Intente con otro registro',
                    'code' => '404'
                ]
            ], 400);
        }
        $meshStudentRequirement->mesh_student_id = $request->input('meshStudentRequirement.mesh_student_id');
        $meshStudentRequirement->requirement_id = $request->input('meshStudentRequirement.requirement_id');
        $meshStudentRequirement->save();
        return response()->json([
            'data' => $meshStudentRequirement,
            'msg' => [
                'summary' => 'Registro actualizado',
                'detail' => 'El registro fue actualizado',
                'code' => '201'
            ]
        ], 201);
    }

    function delete(DeleteMeshStudentRequirementRequest $request)
    {
        MeshStudentRequirement::destroy($request->input('ids'));

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Rgistro eliminado',
                'detail' => 'Se eliminó correctamente',
                'code' => '201'
            ]
        ], 201);
    }
}
