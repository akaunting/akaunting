<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute debe ser aceptado.',
    'active_url'           => ':attribute no es una URL correcta.',
    'after'                => ':attribute debe ser posterior a :date.',
    'after_or_equal'       => ':attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => ':attribute solo acepta letras.',
    'alpha_dash'           => ':attribute solo acepta letras, números y guiones.',
    'alpha_num'            => ':attribute solo acepta letras y números.',
    'array'                => ':attribute debe ser un array.',
    'before'               => ':attribute debe ser anterior a :date.',
    'before_or_equal'      => ':attribute debe ser anterior o igual a :date.',
    'between'              => [
        'numeric' => ':attribute debe estar entre :min y :max.',
        'file'    => ':attribute debe estar entre :min - :max kilobytes.',
        'string'  => ':attribute debe estar entre :min - :max caracteres.',
        'array'   => ':attribute debe tener entre :min y :max items.',
    ],
    'boolean'              => ':attribute debe ser verdadero o falso.',
    'confirmed'            => ':attribute la confirmación no coincide.',
    'date'                 => ':attribute no es una fecha válida.',
    'date_format'          => ':attribute no cumple el formato :format.',
    'different'            => ':attribute y :other deben ser diferentes.',
    'digits'               => ':attribute debe tener :digits dígitos.',
    'digits_between'       => ':attribute debe tener entre :min y :max dígitos.',
    'dimensions'           => ':attribute tiene dimensiones de imagen no válido.',
    'distinct'             => ':attribute tiene un valor duplicado.',
    'email'                => ':attribute debe ser una dirección de email válida.',
    'exists'               => 'El :attribute seleccionado no es correcto.',
    'file'                 => ':attribute debe ser un archivo.',
    'filled'               => ':attribute debe tener un valor.',
    'image'                => ':attribute debe ser una imagen.',
    'in'                   => 'El :attribute seleccionado es inválido.',
    'in_array'             => ':attribute no existe en :other.',
    'integer'              => ':attribute debe ser un número entero.',
    'ip'                   => ':attribute debe ser una dirección IP válida.',
    'json'                 => ':attribute debe ser una cadena JSON válida.',
    'max'                  => [
        'numeric' => ':attribute no debe ser mayor que :max.',
        'file'    => ':attribute no debe ser mayor que :max kilobytes.',
        'string'  => ':attribute no debe tener como máximo :max caracteres.',
        'array'   => ':attribute no debe tener más de :max items.',
    ],
    'mimes'                => ':attribute debe ser un archivo del tipo: :values.',
    'mimetypes'            => ':attribute debe ser un archivo del tipo: :values.',
    'min'                  => [
        'numeric' => ':attribute debe ser como mínimo :min.',
        'file'    => ':attribute debe ser como mínimo de :min kilobytes.',
        'string'  => ':attribute debe contener como mínimo :min caracteres.',
        'array'   => ':attribute debe contener como mínimo :min items.',
    ],
    'not_in'               => 'El :attribute seleccionado es inválido.',
    'numeric'              => ':attribute debe ser un número.',
    'present'              => ':attribute debe tener un valor.',
    'regex'                => ':attribute tiene un formato incorrecto.',
    'required'             => ':attribute es obligatorio.',
    'required_if'          => ':attribute es obligatorio cuando :other es :value.',
    'required_unless'      => ':attribute es obligatorio a menos que :other esté en :values.',
    'required_with'        => ':attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => ':attribute es obligatorio cuando :values está presente.',
    'required_without'     => ':attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => ':attribute es obligatorio cuando ningún :values está presente.',
    'same'                 => ':attribute y :other deben coincidir.',
    'size'                 => [
        'numeric' => ':attribute debe ser :size.',
        'file'    => ':attribute debe tener :size kilobytes.',
        'string'  => ':attribute debe tener :size caracteres.',
        'array'   => ':attribute debe tener al menos :size items.',
    ],
    'string'               => ':attribute debe ser una cadena de caracteres.',
    'timezone'             => ':attribute debe ser una zona válida.',
    'unique'               => ':attribute ya ha sido introducido.',
    'uploaded'             => ':attribute no se pudo cargar.',
    'url'                  => ':attribute tiene un formato incorrecto.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'mensaje personalizado',
        ],
        'invalid_currency' => 'El código de :attribute es incorrecto.',
        'invalid_amount'   => 'El monto :attribute es inválido.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
