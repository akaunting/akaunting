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

    'accepted'             => ':attribute qəbul edilməlidir',
    'active_url'           => ':attribute etibarlı bir URL olmalıdır.',
    'after'                => ':attribute :date tarixindən sonra olmalıdır',
    'after_or_equal'       => ':attribute :date tarixi ilə eyni və ya sonra olmalıdır',
    'alpha'                => ':attribute yalnız hərflərdən ibarət ola bilər',
    'alpha_dash'           => ':attribute yalnız hərf, rəqəm və tire simvolundan ibarət ola bilər',
    'alpha_num'            => ':attribute yalnız hərf və rəqəmlərdən ibarət ola bilər',
    'array'                => ':attribute massiv formatında olmalıdır',
    'before'               => ':attribute tarixindən əvvəl bir tarix olmalıdır :date.',
    'before_or_equal'      => ':attribute tarixə bərabər vəya daha əvvəl olmalıdır',
    'between'              => [
        'numeric' => ':attribute :min ilə :max arasında olmalıdır',
        'file'    => ':attribute :min ilə :max KB ölçüsü intervalında olmalıdır',
        'string'  => ':attribute :min - :max arasında simvollardan ibarət olmalıdır.',
        'array'   => ':attribute :min - :max arasında obyekt olmalıdır.',
    ],
    'boolean'              => ':attribute sadəcə doğru vəya səhv olmalıdır.',
    'confirmed'            => ':attribute təkrarlama uyğun gəlmir.',
    'date'                 => ':attribute etibarlı bir tarix olmalıdır.',
    'date_format'          => ':attribute :format formatına uyğun gəlmir.',
    'different'            => ':attribute ilə :other birbirindən fərqli olmalıdır.',
    'digits'               => ':attribute :digits rəqəm olmalıdır.',
    'digits_between'       => ':attribute :min ilə :max arasında rəqəm olmalıdır.',
    'dimensions'           => ':attribute vizual ölçüləri etibarsızdır.',
    'distinct'             => ':attribute sahənin təkrarlanan dəyəri var.',
    'email'                => ':attribute formatı etibarsızdır.',
    'ends_with'            => ':attribute bunlardan biri ilə bitməlidir: :values',
    'exists'               => 'Seçili :attribute etibarsızdır.',
    'file'                 => ':attribute fayl olmalıdır.',
    'filled'               => ':attribute sahənin doldurulması məcburidir.',
    'image'                => ':attribute sahə rəsm faylı olmalıdır.',
    'in'                   => ':attribute dəyəri etibarsızdır.',
    'in_array'             => ':attribute sahəni :other içində mövcud deyil.',
    'integer'              => ':attribute tam ədəd olmalıdır.',
    'ip'                   => ':attribute etibarlı bir IP ünvanl olmalıdır.',
    'json'                 => ':attribute etibarlı bir JSON dəyişən olmalıdır.',
    'max'                  => [
        'numeric' => ':attribute dəyəri :max dəyərindən kiçik olmalıdır.',
        'file'    => ':attribute dəyəri :max kilobayt dəyərindən kiçik olmalıdır.',
        'string'  => ':attribute dəyəri :max simvol dəyərindən kiçik olmalıdır.',
        'array'   => ':attribute dəyəri :max ədədindən daha az obyekt olmalıdır.',
    ],
    'mimes'                => ':attribute fayl formatı :values olmalıdır.',
    'mimetypes'            => ':attribute fayl formatı :values olmalıdır.',
    'min'                  => [
        'numeric' => ':attribute dəyəri :min dəyərindən büyük olmalıdır.',
        'file'    => ':attribute dəyəri :min kilobayt dəyərindən büyük olmalıdır.',
        'string'  => ':attribute dəyəri :min simvol dəyərindən büyük olmalıdır.',
        'array'   => ':attribute en az :min obyektə sahip olmalıdır.',
    ],
    'not_in'               => 'Seçili :attribute etibarsız.',
    'numeric'              => ' :attribute rəqəmlərdən ibarət olmalıdır',
    'present'              => ':attribute sahəsi mövcud olmalıdır.',
    'regex'                => ' :attribute formatı yanlışdır',
    'required'             => ' :attribute mütləqdir',
    'required_if'          => ' :attribute (:other :value ikən) mütləqdir',
    'required_unless'      => ':attribute sahəsi, :other :values dəyərinə sahip olmadığı təqdirdə məcburidir.',
    'required_with'        => ':attribute sahəsi :values varkən məcburidir.',
    'required_with_all'    => ':attribute sahəsi hərhansı bir :values dəyəri varkən məcburidir.',
    'required_without'     => ':attribute sahəsi :values olmadıqda məcburidir.',
    'required_without_all' => ':attribute sahəsi :values dəyərlərindən hərhansı biri olmadıqda məcburidir.',
    'same'                 => ':attribute ile :other uyğun olmalıdır.',
    'size'                 => [
        'numeric' => ':attribute :size olmalıdır.',
        'file'    => ':attribute :size kilobyte olmalıdır.',
        'string'  => ':attribute :size simvol olmalıdır.',
        'array'   => ':attribute :size obyektə sahip olmalıdır.',
    ],
    'string'               => ':attribute dizge olmalıdır.',
    'timezone'             => ':attribute etibarlı bir saat qurşağı olmalıdır.',
    'unique'               => ':attribute daha öncədən qeyd edilmiş.',
    'uploaded'             => ':attribute yükləməsi uğursuz.',
    'url'                  => ':attribute formatı etibarsız.',

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
            'rule-name'             => 'Şəxsi Mesaj',
        ],
        'invalid_currency'      => ':attribute etibarsız bir valyuta məzənnəsi kodu.',
        'invalid_amount'        => 'Məbləğ :attribute etibarsız.',
        'invalid_extension'     => 'faylnın uzantısı etibarsız.',
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
