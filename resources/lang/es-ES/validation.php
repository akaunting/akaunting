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

    'accepted' => ':attribute debe ser aceptado.',
    'active_url' => ':attribute no es una URL correcta.',
    'after' => ':attribute debe ser posterior a :date.',
    'after_or_equal' => ':attribute debe ser una fecha posterior o igual a :date.',
    'alpha' => ':attribute solo acepta letras.',
    'alpha_dash' => ':attribute solo acepta letras, números y guiones.',
    'alpha_num' => ':attribute solo acepta letras y números.',
    'array' => ':attribute debe ser un array.',
    'before' => ':attribute debe ser anterior a :date.',
    'before_or_equal' => ':attribute debe ser anterior o igual a :date.',
    'between' => [
        'numeric' => ':attribute debe estar entre :min y :max.',
        'file' => ':attribute debe estar entre :min - :max kilobytes.',
        'string' => ':attribute debe estar entre :min - :max caracteres.',
        'array' => ':attribute debe tener entre :min y :max items.',
    ],
    'boolean' => ':attribute debe ser verdadero o falso.',
    'confirmed' => ':attribute la confirmación no coincide.',
    'current_password' => 'La contraseña es incorrecta.',
    'date' => ':attribute no es una fecha válida.',
    'date_equals' => ':attribute debe ser una fecha igual a :date.',
    'date_format' => ':attribute no cumple el formato :format.',
    'different' => ':attribute y :other deben ser <strong>diferentes</strong>.',
    'digits' => ':attribute debe tener :digits dígitos.',
    'digits_between' => ':attribute debe tener entre :min y :max dígitos.',
    'dimensions' => ':attribute tiene dimensiones de imagen no válido.',
    'distinct' => ':attribute tiene un valor duplicado.',
    'email' => ':attribute debe ser una <strong>dirección de correo electrónico</strong> válida.',
    'ends_with' => ':attribute debe terminar con uno de los siguientes: :values',
    'exists' => 'El :attribute seleccionado no es correcto.',
    'file' => ':attribute debe ser un <strong>archivo</strong>.',
    'filled' => ':attribute debe tener un <strong>valor</strong>.',
    'gt' => [
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'file' => ':attribute debe ser mayor que :value kilobytes.',
        'string' => ':attribute debe ser mayor que :value caracteres.',
        'array' => 'El campo :attribute debe tener más de :value items.',
    ],
    'gte' => [
        'numeric' => 'El campo :attribute debe ser mayor o igual a :value.',
        'file' => ':attribute debe ser mayor o igual a :value kilobytes.',
        'string' => 'El campo :attribute debe ser mayor o igual a :value caracteres.',
        'array' => ':attribute debe tener :value o más.',
    ],
    'image' => ':attribute debe ser una <strong>imagen</strong>.',
    'in' => 'El :attribute seleccionado es inválido.',
    'in_array' => ':attribute no existe en :other.',
    'integer' => ':attribute debe ser un <strong>entero</strong>.',
    'ip' => ':attribute debe ser una dirección IP válida.',
    'ipv4' => ':attribute debe ser una dirección IPv4 válida.',
    'ipv6' => ':attribute debe ser una dirección IPv6 válida.',
    'json' => ':attribute debe ser una cadena JSON válida.',
    'lt' => [
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'file' => ':attribute debe ser menor que :value kilobytes.',
        'string' => ':attribute debe tener menos de :value caracteres.',
        'array' => ':attribute debe tener menos de :value elementos.',
    ],
    'lte' => [
        'numeric' => 'El campo :attribute debe ser menor o igual a :value.',
        'file' => ':attribute debe ser menor o igual a :value kilobytes.',
        'string' => ':attribute debe ser menor o igual a :value caracteres.',
        'array' => 'El campo :attribute no debe tener más de :value items.',
    ],
    'max' => [
        'numeric' => ':attribute no debe ser mayor que :max.',
        'file' => ':attribute no debe ser mayor que :max kilobytes.',
        'string' => ':attribute no debe tener como máximo :max caracteres.',
        'array' => ':attribute no debe tener más de :max items.',
    ],
    'mimes' => ':attribute debe ser un archivo del tipo: :values.',
    'mimetypes' => ':attribute debe ser un archivo del tipo: :values.',
    'min' => [
        'numeric' => ':attribute debe ser como mínimo :min.',
        'file' => ':attribute debe ser como mínimo de :min kilobytes.',
        'string' => ':attribute debe contener como mínimo :min caracteres.',
        'array' => ':attribute debe contener como mínimo :min items.',
    ],
    'multiple_of' => ':attribute debe ser un múltiplo de :value.',
    'not_in' => 'El :attribute seleccionado es inválido.',
    'not_regex' => 'El formato :attribute no es válido.',
    'numeric' => ':attribute debe ser un número.',
    'password' => 'La contraseña es incorrecta.',
    'present' => ':attribute debe estar <strong>presente</strong>.',
    'regex' => 'El formato de :attribute es <strong>inválido</strong>.',
    'required' => 'El campo :attribute es <strong>obligatorio</strong>.',
    'required_if' => ':attribute es obligatorio cuando :other es :value.',
    'required_unless' => ':attribute es obligatorio a menos que :other esté en :values.',
    'required_with' => ':attribute es obligatorio cuando :values está presente.',
    'required_with_all' => ':attribute es obligatorio cuando :values está presente.',
    'required_without' => ':attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => ':attribute es obligatorio cuando ningún :values está presente.',
    'prohibited' => 'El campo :attribute está prohibido.',
    'prohibited_if' => 'El campo :attribute está prohibido cuando :other es :value.',
    'prohibited_unless' => 'El campo :attribute está prohibido a menos que :other esté en :values.',
    'same' => ':attribute y :other deben coincidir.',
    'size' => [
        'numeric' => ':attribute debe ser :size.',
        'file' => ':attribute debe tener :size kilobytes.',
        'string' => ':attribute debe tener <strong>:size caracteres</strong>.',
        'array' => ':attribute debe tener al menos :size items.',
    ],
    'starts_with' => 'El campo :attribute debe comenzar con uno de los siguientes: :values.',
    'string' => ':attribute debe ser una <strong>cadena de caracteres</strong>.',
    'timezone' => ':attribute debe ser una zona válida.',
    'unique' => ':attribute ya ha sido <strong>tomado</strong>.',
    'uploaded' => ':attribute <strong>falló</strong> al subirlo.',
    'url' => 'El formato de :attribute es <strong>inválido</strong>.',
    'uuid' => ':attribute debe ser un UUID válido.',

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
        'invalid_currency'      => 'El código de :attribute es incorrecto.',
        'invalid_amount'        => 'El monto :attribute es inválido.',
        'invalid_extension'     => 'La extensión del archivo no es válida.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
