<?php

namespace App\Http\Requests\Portfolio\MethodologicalStrategy;

use App\Http\Requests\Portfolio\PortfolioFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMethodologicalStrategyRequest extends FormRequest
{
    public function authorize()
        return true;
    {
    }

    public function rules()
    {
        $rules = [

            'purpose' => [
                'required',
                'max:1000',
                'min:10',
            ],

        ];
        return PortfolioFormRequest::rules($rules);
    }

    public function attributes()

    {
        $attributes = [
            'purpose' => 'purpose',

        ];
        return PortfolioFormRequest::attributes($attributes);
    }
}
