<?php

namespace App\Http\Requests\Uic\ProjectPlan;

use App\Http\Requests\Uic\UicFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectPlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'projectPlan.title' => [
                'required',
                'string'
            ],
            'projectPlan.description' => [
                'required',
                'string'
            ],
            'projectPlan.act_code' => [
                'required',
                'int'
            ],
            'projectPlan.approval_date' => [
                'required',
                'date'
            ],
            'projectPlan.is_approved' => [
                'required',
                'bool'
            ],

        ];
        return UicFormRequest::rules($rules);
    }

    public function attributes()
    {
        $attributes = [
            'projectPlan.title' => 'título',
            'projectPlan.description' => 'descripción',
            'projectPlan.act_code' => 'codigo acta',
            'projectPlan.approval_date' => 'fecha aprovacion',
            'projectPlan.is_approved' => 'esta aprobado',
        ];
        return UicFormRequest::attributes($attributes);
    }
}
