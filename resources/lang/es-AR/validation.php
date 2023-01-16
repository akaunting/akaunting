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

    'accepted'             => 'El campo :attribute debe ser aceptado.',
    'active_url'           => ':attribute no es una URL correcta.',
    'after'                => ':attribute debe ser posterior a :date.',
    'after_or_equal'       => ':attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => ':attribute sólo puede contener letras.',
    'alpha_dash'           => ':attribute sólo puede contener letras, números y guiones.',
    'alpha_num'            => ':attribute solo acepta letras y números.',
    'array'                => ':attribute debe ser un array.',
    'before'               => ':attribute debe ser una fecha anterior a :date.',
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
    'different'            => ':attribute y :other deben ser <strong>diferentes</strong>.',
    'digits'               => ':attribute debe tener :digits dígitos.',
    'digits_between'       => ':attribute debe tener entre :min y :max dígitos.',
    'dimensions'           => 'El campo :attribute tiene dimensiones de imagen inválidas.',
    'distinct'             => 'El campo :attribute tiene un valor duplicado.',
    'email'                => ':attribute debe ser una <strong>dirección de correo electrónico</strong> válida.',
    'ends_with'            => ':attribute debe terminar con uno de los siguientes: :values',
    'exists'               => 'El :attribute seleccionado no es válido.',
    'file'                 => ':attribute debe ser un <strong>archivo</strong>.',
    'filled'               => ':attribute debe tener un <strong>valor</strong>.',
    'image'                => ':attribute debe ser una <strong>imagen</strong>.',
    'in'                   => 'El :attribute seleccionado es inválido.',
    'in_array'             => 'El campo :attribute no existe en :other.',
    'integer'              => 'El atributo :attribute debe ser un <strong>entero</strong>.',
    'ip'                   => ':attribute debe ser una dirección IP válida.',
    'json'                 => ':attribute debe ser una cadena JSON válida.',
    'max'                  => [
        'numeric' => ':attribute no puede ser mayor que :max.',
        'file'    => ':attribute no puede ser mayor que :max kilobytes.',
        'string'  => ':attribute no puede ser mayor que :max caracteres.',
        'array'   => ':attribute no debe tener más de :max items.',
    ],
    'mimes'                => ':attribute debe ser un archivo del tipo: :values.',
    'mimetypes'            => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'numeric' => ':attribute debe ser al menos :min.',
        'file'    => ':attribute debe ser como mínimo de :min kilobytes.',
        'string'  => ':attribute debe contener como mínimo :min caracteres.',
        'array'   => ':attribute debe tener al menos :min items.',
    ],
    'not_in'               => 'El :attribute seleccionado no es válido.',
    'numeric'              => ':attribute debe ser un número.',
    'present'              => ':attribute debe estar <strong>presente</strong>.',
    'regex'                => 'El formato de :attribute es <strong>inválido</strong>.',
    'required'             => 'El campo :attribute es <strong>obligatorio</strong>.',
    'required_if'          => ':attribute es obligatorio cuando :other es :value.',
    'required_unless'      => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values están presentes.',
    'same'                 => ':attribute y :other deben coincidir.',
    'size'                 => [
        'numeric' => ':attribute debe ser :size.',
        'file'    => ':attribute debe tener :size kilobytes.',
        'string'  => ':attribute debe tener <strong>:size caracteres</strong>.',
        'array'   => ':attribute debe tener al menos :size items.',
    ],
    'string'               => 'El campo :attribute debe ser una cadena <strong></strong>.',
    'timezone'             => ':attribute debe ser una zona válida.',
    'unique'               => ':attribute ya ha sido <strong>tomado</strong>.',
    'uploaded'             => 'El atributo :attribute <strong>falló</strong> al subir.',
    'url'                  => 'El formato de :attribute es <strong>inválido</strong>.',

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
            'rule-name'             => 'mensaje personalizado',
        ],
        'invalid_currency'      => 'El código de :attribute es incorrecto.',
        'invalid_amount'        => 'El monto :attribute es inválido.',
        'invalid_extension'     => 'La extensión del archivo no es válida.',
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
