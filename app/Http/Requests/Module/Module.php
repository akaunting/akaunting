<?php

namespace App\Http\Requests\Module;

use App\Http\Requests\Request;
use App\Traits\Modules;
use Illuminate\Validation\Factory as ValidationFactory;

class Module extends Request
{
    use Modules;

    public function __construct(ValidationFactory $validation)
    {

        $validation->extend(
            'check',
            function ($attribute, $value, $parameters) {
                return $this->checkToken($value);
            },
            trans('messages.error.invalid_token')
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
