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

    'accepted'             => 'שדה :attribute חייב להיות מסומן.',
    'active_url'           => 'שדה :attribute הוא לא כתובת אתר תקנית.',
    'after'                => 'שדה :attribute חייב להיות תאריך אחרי :date.',
    'after_or_equal'       => 'שדה :attribute חייב להיות תאריך מאוחר או שווה ל :date.',
    'alpha'                => 'שדה :attribute יכול להכיל אותיות בלבד.',
    'alpha_dash'           => 'שדה :attribute יכול להכיל אותיות, מספרים ומקפים בלבד.',
    'alpha_num'            => 'שדה :attribute יכול להכיל אותיות ומספרים בלבד.',
    'array'                => 'שדה :attribute חייב להיות מערך.',
    'before'               => 'שדה :attribute חייב להיות תאריך לפני :date.',
    'before_or_equal'      => 'שדה :attribute חייב להיות תאריך מוקדם או שווה ל :date.',
    'between'              => [
        'numeric' => 'שדה :attribute חייב להיות בין :min ל-:max.',
        'file'    => 'שדה :attribute חייב להיות בין :min ל-:max קילובייטים.',
        'string'  => 'שדה :attribute חייב להיות בין :min ל-:max תווים.',
        'array'   => 'שדה :attribute חייב להיות בין :min ל-:max פריטים.',
    ],
    'boolean'              => 'שדה :attribute חייב להיות אמת או שקר.',
    'confirmed'            => 'שדה האישור של :attribute לא תואם.',
    'date'                 => 'שדה :attribute אינו תאריך תקני.',
    'date_format'          => 'שדה :attribute לא תואם את הפורמט :format.',
    'different'            => 'שדה :attribute ושדה :other חייבים להיות שונים.',
    'digits'               => 'שדה :attribute חייב להיות בעל :digits ספרות.',
    'digits_between'       => 'שדה :attribute חייב להיות בין :min ו-:max ספרות.',
    'dimensions'           => 'שדה :attribute מימדי התמונה לא תקינים',
    'distinct'             => 'שדה :attribute קיים ערך כפול.',
    'email'                => 'שדה :attribute חייב להיות כתובת אימייל תקנית.',
    'exists'               => 'בחירת ה-:attribute אינה תקפה.',
    'file'                 => 'שדה :attribute חייב להיות קובץ.',
    'filled'               => 'שדה :attribute הוא חובה.',
    'image'                => 'שדה :attribute חייב להיות תמונה.',
    'in'                   => 'בחירת ה-:attribute אינה תקפה.',
    'in_array'             => 'שדה :attribute לא קיים ב:other.',
    'integer'              => 'שדה :attribute חייב להיות מספר שלם.',
    'ip'                   => 'שדה :attribute חייב להיות כתובת IP תקנית.',
    'json'                 => 'שדה :attribute חייב להיות מחרוזת JSON תקנית.',
    'max'                  => [
        'numeric' => 'שדה :attribute אינו יכול להיות גדול מ-:max.',
        'file'    => 'שדה :attribute לא יכול להיות גדול מ-:max קילובייטים.',
        'string'  => 'שדה :attribute לא יכול להיות גדול מ-:max characters.',
        'array'   => 'שדה :attribute לא יכול להכיל יותר מ-:max פריטים.',
    ],
    'mimes'                => 'שדה :attribute צריך להיות קובץ מסוג: :values.',
    'mimetypes'            => 'שדה :attribute צריך להיות קובץ מסוג: :values.',
    'min'                  => [
        'numeric' => 'שדה :attribute חייב להיות לפחות :min.',
        'file'    => 'שדה :attribute חייב להיות לפחות :min קילובייטים.',
        'string'  => 'שדה :attribute חייב להיות לפחות :min תווים.',
        'array'   => 'שדה :attribute חייב להיות לפחות :min פריטים.',
    ],
    'not_in'               => 'בחירת ה-:attribute אינה תקפה.',
    'numeric'              => 'שדה :attribute חייב להיות מספר.',
    'present'              => 'שדה :attribute חייב להיות קיים.',
    'regex'                => 'שדה :attribute בעל פורמט שאינו תקין.',
    'required'             => 'שדה :attribute הוא חובה.',
    'required_if'          => 'שדה :attribute נחוץ כאשר :other הוא :value.',
    'required_unless'      => 'שדה :attribute נחוץ אלא אם כן :other הוא בין :values.',
    'required_with'        => 'שדה :attribute נחוץ כאשר :values נמצא.',
    'required_with_all'    => 'שדה :attribute נחוץ כאשר :values נמצא.',
    'required_without'     => 'שדה :attribute נחוץ כאשר :values לא בנמצא.',
    'required_without_all' => 'שדה :attribute נחוץ כאשר אף אחד מ-:values נמצאים.',
    'same'                 => 'שדה :attribute ו-:other חייבים להיות זהים.',
    'size'                 => [
        'numeric' => 'שדה :attribute חייב להיות :size.',
        'file'    => 'שדה :attribute חייב להיות :size קילובייטים.',
        'string'  => 'שדה :attribute חייב להיות :size תווים.',
        'array'   => 'שדה :attribute חייב להכיל :size פריטים.',
    ],
    'string'               => 'שדה :attribute חייב להיות מחרוזת.',
    'timezone'             => 'שדה :attribute חייב להיות איזור תקני.',
    'unique'               => 'שדה :attribute כבר תפוס.',
    'uploaded'             => 'שדה :attribute ארעה שגיאה בעת ההעלאה.',
    'url'                  => 'שדה :attribute בעל פורמט שאינו תקין.',

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
            'rule-name' => 'הודעה מותאמת אישית',
        ],
        'invalid_currency' => 'The :attribute code is invalid.',
        'invalid_amount'   => 'The amount :attribute is invalid.',
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
