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

    'accepted'             => ':attribute mesti diterima pakai.',
    'active_url'           => ':attribute bukan URL yang sah.',
    'after'                => ':attribute mesti tarikh selepas :date.',
    'after_or_equal'       => ':attribute mesti tarikh selepas atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh mengandungi huruf.',
    'alpha_dash'           => ':attribute boleh mengandungi huruf, nombor, dan sengkang.',
    'alpha_num'            => ':attribute hanya boleh mengandungi huruf dan nombor.',
    'array'                => ':attribute mesti jujukan.',
    'before'               => ':attribute mesti tarikh sebelum :date.',
    'before_or_equal'      => ':attribute mesti tarikh sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => ':attribute mestilah antara :min dan :max.',
        'file'    => ':attribute mestilah antara :min dan :max kilobait.',
        'string'  => ':attribute mestilah antara :min dan :max aksara.',
        'array'   => ':attribute mestilah antara :min dan :max item.',
    ],
    'boolean'              => 'Ruangan :attribute mesti benar atau salah.',
    'confirmed'            => 'Pengesahan :attribute tidak sepadan.',
    'date'                 => ':attribute bukan tarikh yang sah.',
    'date_format'          => ':attribute tidak mengikut format :format.',
    'different'            => ':attribute dan :other mesti berlainan.',
    'digits'               => ':attribute mesti :digits digit.',
    'digits_between'       => ':attribute mestilah antara :min dan :max digit.',
    'dimensions'           => 'Dimensi imej :attribute tidak sah',
    'distinct'             => 'Ruangan :attribute ada nilai yang berulang.',
    'email'                => ':attribute tidak sah.',
    'ends_with'            => ':attribut tersebut mesti berakhir dengan salah satu yang disebutkan berikut: :nilai-nilai',
    'exists'               => ':attribute yang dipilih tidak sah.',
    'file'                 => ':attribute mesti fail yang sah.',
    'filled'               => ':attribute diperlukan.',
    'image'                => ':attribute mesti imej.',
    'in'                   => ':attribute yang dipilih tidak sah.',
    'in_array'             => 'Ruangan :attribute tidak wujud dalam :other.',
    'integer'              => ':attribute mesti integer.',
    'ip'                   => ':attribute mesti alamat IP yang sah.',
    'json'                 => ':attribute mesti rentetan JSON yang sah.',
    'max'                  => [
        'numeric' => ':attribute mesti tidak melebihi :max.',
        'file'    => ':attribute mesti tidak melebihi :max kilobait.',
        'string'  => ':attribute mesti tidak melebihi :max aksara.',
        'array'   => ':attribute mesti tidak melebihi :max item.',
    ],
    'mimes'                => ':attribute mesti fail type: :values.',
    'mimetypes'            => ':attribute mesti fail type: :values.',
    'min'                  => [
        'numeric' => ':attribute mesti sekurang-kurangnya :min.',
        'file'    => ':attribute mesti sekurang-kurangnya :min kilobait.',
        'string'  => ':attribute mesti sekurang-kurangnya :min aksara.',
        'array'   => 'Jumlah :attribute mesti sekurang-kurangnya :min perkara.',
    ],
    'not_in'               => ':attribute dipilih tidak sah.',
    'numeric'              => ':attribute mesti nombor.',
    'present'              => ':attribute mesti wujud.',
    'regex'                => 'Format :attribute tidak sah.',
    'required'             => 'Ruangan :attribute diperlukan.',
    'required_if'          => 'Ruangan :attribute diperlukan bila :other sama dengan :value.',
    'required_unless'      => 'Ruangan :attribute diperlukan sekiranya :other ada dalam :values.',
    'required_with'        => 'Ruangan :attribute diperlukan bila :values wujud.',
    'required_with_all'    => 'Ruangan :attribute diperlukan bila :values wujud.',
    'required_without'     => 'Ruangan :attribute diperlukan bila :values tidak wujud.',
    'required_without_all' => 'Ruangan :attribute diperlukan bila kesemua :values wujud.',
    'same'                 => 'Ruangan :attribute dan :other mesti sepadan.',
    'size'                 => [
        'numeric' => 'Saiz :attribute mesti :size.',
        'file'    => 'Saiz :attribute mesti :size kilobait.',
        'string'  => 'Saiz :attribute mesti :size aksara.',
        'array'   => 'Saiz :attribute mesti mengandungi :size perkara.',
    ],
    'string'               => ':attribute mesti aksara.',
    'timezone'             => ':attribute mesti zon masa yang sah.',
    'unique'               => ':attribute telah wujud.',
    'uploaded'             => ':attribute gagal dimuat naik.',
    'url'                  => ':attribute format tidak sah.',

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
            'rule-name' => 'mesej sendiri',
        ],
        'invalid_currency' => 'Kod :attribute tidak sah.',
        'invalid_amount'   => 'Amaun :attribute tidak sah.',
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
