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

    'accepted' => ':attribute kabul edilmelidir.',
    'accepted_if' => ':other alanı :value değerine sahipken :attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL olmalıdır.',
    'after' => ':attribute şundan daha eski bir tarih olmalıdır :date.',
    'after_or_equal' => ':attribute tarihi eşit veya daha eski olmalıdır',
    'alpha' => ':attribute sadece harflerden oluşmalıdır.',
    'alpha_dash' => ':attribute sadece harfler, rakamlar ve tirelerden oluşmalıdır.',
    'alpha_num' => ':attribute sadece harfler ve rakamlar içermelidir.',
    'array' => ':attribute dizi olmalıdır.',
    'before' => ':attribute şundan daha önceki bir tarih olmalıdır :date.',
    'before_or_equal' => ':attribute tarihi eşit veya daha eski olmalıdır',
    'between' => [
        'array' => ':attribute :min - :max arasında nesneye sahip olmalıdır.',
        'file' => ':attribute :min - :max arasındaki kilobayt değeri olmalıdır.',
        'numeric' => ':attribute :min - :max arasında olmalıdır.',
        'string' => ':attribute :min - :max arasında karakterden oluşmalıdır.',
    ],
    'boolean' => ':attribute sadece doğru veya yanlış olmalıdır.',
    'confirmed' => ':attribute tekrarı eşleşmiyor.',
    'current_password' => 'Şifre yanlış.',
    'date' => ':attribute geçerli bir tarih olmalıdır.',
    'date_equals' => ':attribute, :date değerine eşit bir tarih olmalıdır.',
    'date_format' => ':attribute :format biçimi ile eşleşmiyor.',
    'declined' => ':attribute alanı reddedilmelidir.',
    'declined_if' => ':attribute alanı, :other alanı :value değerine sahipken reddedilmelidir.',
    'different' => ':attribute ile :other birbirinden farklı olmalıdır.',
    'digits' => ':attribute :digits rakam olmalıdır.',
    'digits_between' => ':attribute :min ile :max arasında rakam olmalıdır.',
    'dimensions' => ':attribute görsel ölçüleri geçersiz.',
    'distinct' => ':attribute alanı yinelenen bir değere sahip.',
    'doesnt_start_with' => ':attribute aşağıdakilerden biriyle başlamayabilir: :values.',
    'email' => ':attribute biçimi geçersiz.',
    'ends_with' => ':attribute şunlardan biriyle bitmelidir: :values',
    'enum' => 'Seçili :attribute geçersiz.',
    'exists' => 'Seçili :attribute geçersiz.',
    'file' => ':attribute dosya olmalıdır.',
    'filled' => ':attribute alanının doldurulması zorunludur.',
    'gt' => [
        'array' => ':attribute, :value öğelerinden daha fazlasına sahip olmalıdır.',
        'file' => ':attribute, :value kilobayttan büyük olmalıdır.',
        'numeric' => ':attribute, :value değerinden büyük olmalıdır.',
        'string' => ':attribute, :value karakterlerinden büyük olmalıdır.',
    ],
    'gte' => [
        'array' => ':attribute, :value öğelerine veya daha fazlasına sahip olmalıdır.',
        'file' => ':attribute, :value kilobayttan büyük veya ona eşit olmalıdır.',
        'numeric' => ':attribute, :value değerinden büyük veya ona eşit olmalıdır.',
        'string' => ':attribute, :value karakterlerinden büyük veya ona eşit olmalıdır.',
    ],
    'image' => ':attribute alanı resim dosyası olmalıdır.',
    'in' => ':attribute değeri geçersiz.',
    'in_array' => ':attribute alanı :other içinde mevcut değil.',
    'integer' => ':attribute tamsayı olmalıdır.',
    'ip' => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute geçerli bir JSON değişkeni olmalıdır.',
    'lt' => [
        'array' => ':attribute, :value öğelerinden daha azına sahip olmalıdır.',
        'file' => ':attribute, :value kilobayttan küçük olmalıdır.',
        'numeric' => ':attribute geçerli bir IPv6 adresi olmalıdır.',
        'string' => ':attribute, :value karakterlerinden daha az olmalıdır.',
    ],
    'lte' => [
        'array' => ':attribute, :value öğelerinden daha fazlasına sahip olmamalıdır.',
        'file' => ':attribute :value kilobayttan küçük veya ona eşit olmalıdır.',
        'numeric' => ':attribute, :value değerinden küçük veya ona eşit olmalıdır.',
        'string' => ':attribute, :value karakterlerinden küçük veya ona eşit olmalıdır.',
    ],
    'mac_address' => ':attribute geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => ':attribute değeri :max adedinden az nesneye sahip olmalıdır.',
        'file' => ':attribute değeri :max kilobayt değerinden küçük olmalıdır.',
        'numeric' => ':attribute değeri :max değerinden küçük olmalıdır.',
        'string' => ':attribute değeri :max karakter değerinden küçük olmalıdır.',
    ],
    'mimes' => ':attribute dosya biçimi :values olmalıdır.',
    'mimetypes' => ':attribute dosya biçimi :values olmalıdır.',
    'min' => [
        'array' => ':attribute en az :min nesneye sahip olmalıdır.',
        'file' => ':attribute değeri :min kilobayt değerinden büyük olmalıdır.',
        'numeric' => ':attribute değeri :min değerinden büyük olmalıdır.',
        'string' => ':attribute değeri :min karakter değerinden büyük olmalıdır.',
    ],
    'multiple_of' => ':attribute, :value\'nun katı olmalıdır.',
    'not_in' => 'Seçili :attribute geçersiz.',
    'not_regex' => ':attribute biçimi geçersiz.',
    'numeric' => ':attribute sayı olmalıdır.',
    'password' => [
        'letters' => ':attribute en az bir harf içermelidir.',
        'mixed' => ':attribute en az bir büyük harf ve bir küçük harf içermelidir.',
        'numbers' => ':attribute en az bir rakam içermelidir.',
        'symbols' => ':attribute en az bir özel karakter içermelidir.',
        'uncompromised' => 'Verilen :attribute bir veri sızıntısında görülmüş. Lütfen farklı bir :attribute seçin.',
    ],
    'present' => ':attribute alanı mevcut olmalıdır.',
    'prohibited' => ':attribute alanı yasaktır.',
    'prohibited_if' => ':attribute alanı :other :value olduğunda yasaktır.',
    'prohibited_unless' => ':attribute alanı, :other :values içinde olmadığı sürece yasaktır.',
    'prohibits' => ':attribute alanı, :other alanının mevcut olmasını yasaklar.',
    'regex' => ':attribute biçimi geçersiz.',
    'required' => ':attribute alanı gereklidir.',
    'required_array_keys' => ':attribute alanı :values değerlerinden birini içermelidir.',
    'required_if' => ':attribute alanı, :other :value değerine sahip olduğunda zorunludur.',
    'required_unless' => ':attribute alanı, :other :values değerine sahip olmadığı sürece zorunludur.',
    'required_with' => ':attribute alanı :values varken zorunludur.',
    'required_with_all' => ':attribute alanı herhangi bir :values değeri varken zorunludur.',
    'required_without' => ':attribute alanı :values yokken zorunludur.',
    'required_without_all' => ':attribute alanı :values değerlerinden herhangi biri yokken zorunludur.',
    'same' => ':attribute ile :other eşleşmelidir.',
    'size' => [
        'array' => ':attribute :size nesneye sahip olmalıdır.',
        'file' => ':attribute :size kilobyte olmalıdır.',
        'numeric' => ':attribute :size olmalıdır.',
        'string' => ':attribute :size karakter olmalıdır.',
    ],
    'starts_with' => ':attribute aşağıdakilerden biriyle başlamalıdır: :values.',
    'string' => ':attribute dizge olmalıdır.',
    'timezone' => ':attribute geçerli bir saat dilimi olmalıdır.',
    'unique' => ':attribute daha önceden kayıt edilmiş.',
    'uploaded' => ':attribute yüklemesi başarısız.',
    'url' => ':attribute biçimi geçersiz.',
    'uuid' => ':attribute geçerli bir UUID olmalıdır.',

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
            'rule-name' => 'Özel Mesaj',
        ],
        'invalid_currency'      => ':attribute geçersiz bir döviz kuru kodu.',
        'invalid_amount'        => 'Tutar :attribute geçersiz.',
        'invalid_extension'     => 'Dosyanın uzantısı geçersiz.',
        'invalid_dimension'     => ':attribute boyutları en fazla :width x :height px olmalıdır.',
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
