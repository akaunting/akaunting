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

    'accepted' => 'O :attribute deve ser aceito.',
    'accepted_if' => 'O :attribute deve ser aceito quando :other é :value.',
    'active_url' => 'O :attribute não é uma URL válida.',
    'after' => 'O :attribute deve ser uma data posterior a :date.',
    'after_or_equal' => 'O :attribute deve ser uma data posterior ou igual a :date.',
    'alpha' => 'O :attribute deve conter somente letras.',
    'alpha_dash' => 'O :attribute deve conter letras, números e traços.',
    'alpha_num' => 'O :attribute deve conter somente letras e números.',
    'array' => 'O :attribute deve ser um array.',
    'before' => 'O :attribute deve ser uma data anterior a :date.',
    'before_or_equal' => 'O :attribute deve ser uma data anterior ou igual a :date.',
    'between' => [
        'array' => 'O :attribute deve ter entre :min e :max itens.',
        'file' => 'O :attribute deve estar entre :min e :max kilobytes.',
        'numeric' => 'O :attribute deve estar entre :min e :max.',
        'string' => 'O :attribute deve estar entre :min e :max caracteres.',
    ],
    'boolean' => 'O :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação de :attribute não confere.',
    'current_password' => 'A senha está incorreta.',
    'date' => 'O :attribute não é uma data válida.',
    'date_equals' => 'O campo :attribute deve ser uma data igual a :date.',
    'date_format' => 'O :attribute não confere com o formato :format.',
    'declined' => 'O :attribute deve ser recusado.',
    'declined_if' => 'O :attribute deve ser recusado quando :other é :value.',
    'different' => 'O :attribute e :other devem ser <strong>diferentes</strong>.',
    'digits' => 'O :attribute deve ter :digits dígitos.',
    'digits_between' => 'O :attribute deve ter entre :min e :max dígitos.',
    'dimensions' => 'O :attribute não tem dimensões válidas.',
    'distinct' => 'O :attribute campo contém um valor duplicado.',
    'doesnt_start_with' => 'O :attribute não pode começar com um dos seguintes: :values.',
    'email' => 'O :attribute deve ser um endereço de e-mail <strong>válido</strong>.',
    'ends_with' => 'O :attribute deve terminar com um dos seguintes: :values',
    'enum' => 'O :attribute selecionado é inválido.',
    'exists' => 'O :attribute selecionado é inválido.',
    'file' => 'O :attribute deve ser um <strong>arquivo</strong>.',
    'filled' => 'O campo :attribute deve ter um <strong>valor</strong>.',
    'gt' => [
        'array' => 'O campo :attribute deve ter mais que :value itens.',
        'file' => 'O arquivo :attribute deve ser maior que :value kilobytes.',
        'numeric' => 'O campo :attribute deve ser maior que :value.',
        'string' => 'O campo :attribute deve ser maior que :value caracteres.',
    ],
    'gte' => [
        'array' => 'O campo :attribute deve ter :value itens ou mais.',
        'file' => 'O arquivo :attribute deve ser maior ou igual a :value kilobytes.',
        'numeric' => 'O campo :attribute deve ser maior ou igual a :value.',
        'string' => 'O campo :attribute deve ser maior ou igual a :value caracteres.',
    ],
    'image' => 'O :attribute deve ser uma <strong>imagem</strong>.',
    'in' => 'O :attribute é inválido.',
    'in_array' => 'O :attribute campo não existe em :other.',
    'integer' => 'O :attribute deve ser um <strong>inteiro</strong>.',
    'ip' => 'O :attribute deve ser um endereço IP válido.',
    'ipv4' => 'O campo :attribute deve ter um endereço IPv4 válido.',
    'ipv6' => 'O :attribute deve ter um IPv6 válido.',
    'json' => 'O :attribute deve ser um JSON válido.',
    'lt' => [
        'array' => 'O :attribute deve ter menos de :value items.',
        'file' => 'O :attribute deve ter menos de :value kilobytes.',
        'numeric' => 'O :attribute deve ser menor que :value.',
        'string' => 'O :attribute deve ser menor que :value characters.',
    ],
    'lte' => [
        'array' => 'O :attribute não deve ter mais de :value items.',
        'file' => 'O campo :attribute deve ser menor ou igual a :value caracteres.',
        'numeric' => 'O campo :attribute deve ser menor ou igual a :value.',
        'string' => 'O campo :attribute deve ser menor ou igual a :value caracteres.',
    ],
    'mac_address' => 'O :attribute deve ser um endereço MAC válido.',
    'max' => [
        'array' => 'O :attribute não pode ter mais que :max itens.',
        'file' => 'O :attribute não deve ter mais que :max kilobytes.',
        'numeric' => 'O :attribute não deve ser maior que :max.',
        'string' => 'O :attribute não deve ter mais que :max caracteres.',
    ],
    'mimes' => 'O :attribute deve ser um arquivo do tipo: :values.',
    'mimetypes' => 'O :attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'array' => 'O :attribute deve ter no mínimo :min itens.',
        'file' => 'O :attribute deve ter no mínimo :min kilobytes.',
        'numeric' => 'O :attribute deve ser no mínimo :min.',
        'string' => 'O :attribute deve ter no mínimo :min caracteres.',
    ],
    'multiple_of' => 'O :attribute deve ser um múltiplo de :value.',
    'not_in' => 'O :attribute selecionado é inválido.',
    'not_regex' => 'O formato do :attribute é inválido.',
    'numeric' => 'O :attribute deve ser um número.',
    'password' => [
        'letters' => 'A :attribute deve conter pelo menos uma letra.',
        'mixed' => 'A :attribute deve conter pelo menos uma letra maiúscula e uma minúscula.',
        'numbers' => 'A :attribute deve conter pelo menos um número.',
        'symbols' => 'A :attribute deve conter pelo menos um símbolo.',
        'uncompromised' => 'A :attribute apareceu em um vazamento de dados. Por favor, escolha uma :attribute diferente.',
    ],
    'present' => 'O campo :attribute deve estar <strong>presente</strong>.',
    'prohibited' => 'O campo :attribute é proibido.',
    'prohibited_if' => 'O campo :attribute é proibido quando :other é :value.',
    'prohibited_unless' => 'O campo :attribute é proibido a não ser que :other esteja em :values.',
    'prohibits' => 'O campo :attribute proíbe :other de estar presente.',
    'regex' => 'O formato de :attribute é <strong>inválido</strong>.',
    'required' => 'O campo :attribute é <strong>obrigatório</strong>.',
    'required_array_keys' => 'O campo :attribute deve conter entradas para: :values.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_unless' => 'O :attribute é necessário a menos que :other esteja em :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values estão presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum destes estão presentes: :values.',
    'same' => 'O :attribute e :other devem ser iguais.',
    'size' => [
        'array' => 'O :attribute deve conter :size itens.',
        'file' => 'O :attribute deve ter :size kilobytes.',
        'numeric' => 'O :attribute deve ser :size.',
        'string' => 'O :attribute deve conter <strong>:size</strong> caracteres.',
    ],
    'starts_with' => 'O :attribute deve começar com um dos seguintes: :values.',
    'string' => 'O :attribute deve ser uma <strong>string</strong>.',
    'timezone' => 'O campo :attribute deve conter um fuso horário válido.',
    'unique' => 'O :attribute já foi <strong>usado</strong>.',
    'uploaded' => 'O :attribute <strong>falhou</strong> no upload.',
    'url' => 'O campo :attribute deve conter um endereço URL válido.',
    'uuid' => 'O :attribute deve ser um UUID válido.',

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
            'rule-name' => 'custom-message',
        ],
        'invalid_currency'      => 'O código :attribute é inválido.',
        'invalid_amount'        => 'O :attribute é inválido.',
        'invalid_extension'     => 'A extensão do arquivo é inválida.',
        'invalid_dimension'     => 'As dimensões :attribute devem ser no máximo :width x :height px.',
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
