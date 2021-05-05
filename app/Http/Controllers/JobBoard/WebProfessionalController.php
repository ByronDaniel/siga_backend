<?php

namespace App\Http\Controllers\JobBoard;

// Controllers
use App\Http\Controllers\Controller;

// Models
use App\Models\JobBoard\Company;
use App\Models\JobBoard\Professional;
use App\Models\JobBoard\Offer;
use App\Models\JobBoard\Category;

use Illuminate\Http\Request;

class WebProfessionalController extends Controller
{
    function total()
    {
        $totalCompanies = Company::all()->count();
        $totalProfessionals = Professional::all()->count();
        $toalOffers = Offer::all()->count();

        return response()->json([
            'data' => [
                'totalCompanies' => $totalCompanies,
                'totalProfessionals' => $totalProfessionals,
                'totalOffers' => $toalOffers
            ],
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]
        ], 200);
    }

    function getProfessionals(Request $request)
    {
        if (!$request->parent_id && !$request->id)
        {
            // Consulta todos los profesionales (sin filtros)
            $professionals = Professional::with(['academicFormations' => function ($academicFormations) {
                $academicFormations->with('professionalDegree');
            }])->paginate($request->input('per_page'));
        }
        else if ($request->parent_id && !$request->id)
        {
            // Consulta todos los profesionales que concuerden con el parent_id (categoría padre)
            $professionals = Professional::with(['academicFormations' => function ($academicFormations) use($request) {
                $academicFormations->with(['professionalDegree' => function ($professionalDegree) use ($request) {
                    $professionalDegree->where('parent_id', $request->parent_id);
                }]);
            }])->paginate($request->input('per_page'));
        }
        else if (!$request->parent_id && $request->id)
        {
            // Consulta todos los profesionales que concuerden con el id (categoría hija)
            $professionals = Professional::with(['academicFormations' => function ($academicFormations) use($request) {
                $academicFormations->with(['professionalDegree' => function ($professionalDegree) use ($request) {
                    $professionalDegree->where('id', $request->id);
                }]);
            }])->paginate($request->input('per_page'));
        }
        else 
        {
            $professionals = null;
        }

        // Consulta todos los profesionales que coincidan con search
        // $professionals = Professional::with(['academicFormations' => function($academicFormations) use ($request) {
        //     $academicFormations->with(['professionalDegree' => function($professionalDegree) use ($request) {
        //         $professionalDegree->where('name', 'ilike', '%' . $request->search . '%');
        //     }]);
        // }])->paginate($request->input('per_page'));

        return response()->json($professionals, 200);
    }

    function filterCategories()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return response()->json($categories, 200);
    }

    function applyProfessional()
    {
        return 'Hello World!';
    }
}
