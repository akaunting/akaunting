<?php

namespace App\Http\Requests\Module;

use App\Http\Requests\Request;
use Illuminate\Validation\Factory as ValidationFactory;
use App\Traits\Modules;

class Module extends Request
{
    use Modules;

    public function __construct(ValidationFactory $validationFactory)
    {

        $validationFactory->extend(
            'check',
            function ($attribute, $value, $parameters) {
                return $this->checkToken($value);
            },
            trans('modules.invalid_token')
        );

    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'api_token' => 'required|string|check',
        ];
    }
}
