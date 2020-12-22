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

    'accepted'             => 'Ви повинні прийняти :attribute.',
    'active_url'           => 'Поле :attribute не є правильним URL.',
    'after'                => 'Поле :attribute має містити дату не раніше :date.',
    'after_or_equal'       => 'Поле :attribute має містити дату не раніше або дорівнювати :date.',
    'alpha'                => 'Поле :attribute має містити лише літери.',
    'alpha_dash'           => 'Поле :attribute має містити лише літери, цифри та підкреслення.',
    'alpha_num'            => 'Поле :attribute має містити лише літери та цифри.',
    'array'                => 'Поле :attribute має бути масивом.',
    'before'               => 'Поле :attribute має містити дату не пізніше :date.',
    'before_or_equal'      => 'Поле :attribute має містити дату не пізніше або дорівнюватися :date.',
    'between'              => [
        'numeric' => 'Поле :attribute має бути між :min та :max.',
        'file'    => 'Розмір файлу в полі :attribute має бути не менше :min та не більше :max кілобайт.',
        'string'  => 'Текст в полі :attribute має бути не менше :min та не більше :max символів.',
        'array'   => 'Поле :attribute має містити від :min до :max елементів.',
    ],
    'boolean'              => 'Поле :attribute повинне містити логічний тип.',
    'confirmed'            => 'Поле :attribute не збігається з підтвердженням.',
    'date'                 => 'Поле :attribute не є датою.',
    'date_format'          => 'Поле :attribute не відповідає формату :format.',
    'different'            => 'Поля :attribute та :other повинні бути <strong>різними</strong>.',
    'digits'               => 'Довжина цифрового поля :attribute повинна дорівнювати :digits.',
    'digits_between'       => 'Довжина цифрового поля :attribute повинна бути від :min до :max.',
    'dimensions'           => 'Поле :attribute містіть неприпустимі розміри зображення.',
    'distinct'             => 'Поле :attribute містить значення, яке дублюється.',
    'email'                => 'Поле :attribute має містити коректну <strong>адресу електронної пошти</strong>.',
    'ends_with'            => ':attribute має закінчуватися в одному з наступних значень: :value',
    'exists'               => 'Вибране для :attribute значення не коректне.',
    'file'                 => 'Поле :attribute має бути <strong>файлом</strong>.',
    'filled'               => 'Поле :attribute повинне містити <strong>значення</strong>.',
    'image'                => 'Поле :attribute має бути <strong>малюнком</strong>.',
    'in'                   => 'Вибране для :attribute значення не коректне.',
    'in_array'             => 'Значення поля :attribute не міститься в :other.',
    'integer'              => 'Поле :attribute має бути <strong>цілим числом</strong>.',
    'ip'                   => 'Поле :attribute має містити IP адресу.',
    'json'                 => 'Дані поля :attribute мають бути в форматі JSON.',
    'max'                  => [
        'numeric' => 'Поле :attribute має бути не більше :max.',
        'file'    => 'Файл в полі :attribute має бути не більше :max кілобайт.',
        'string'  => 'Текст в полі :attribute повинен мати довжину не більшу за :max.',
        'array'   => 'Поле :attribute повинне містити не більше :max елементів.',
    ],
    'mimes'                => 'Поле :attribute повинне містити файл одного з типів: :values.',
    'mimetypes'            => 'Поле :attribute повинне містити файл одного з типів: :values.',
    'min'                  => [
        'numeric' => 'Поле :attribute повинне бути не менше :min.',
        'file'    => 'Розмір файлу в полі :attribute має бути не меншим :min кілобайт.',
        'string'  => 'Текст в полі :attribute повинен містити не менше :min символів.',
        'array'   => 'Поле :attribute повинне містити не менше :min елементів.',
    ],
    'not_in'               => 'Вибране для :attribute значення не коректне.',
    'numeric'              => 'Поле :attribute повинно містити число.',
    'present'              => 'Поле :attribute має бути <strong>присутнім</strong>.',
    'regex'                => 'Формат поля :attribute <strong>невірний</strong>.',
    'required'             => 'Поле :attribute <strong>необхідний</strong>.',
    'required_if'          => 'Поле :attribute є обов\'язковим для заповнення, коли :other є рівним :value.',
    'required_unless'      => 'Поле :attribute є обов\'язковим для заповнення, коли :other відрізняється від :values',
    'required_with'        => 'Поле :attribute є обов\'язковим для заповнення, коли :values вказано.',
    'required_with_all'    => 'Поле :attribute є обов\'язковим для заповнення, коли :values вказано.',
    'required_without'     => 'Поле :attribute є обов\'язковим для заповнення, коли :values не вказано.',
    'required_without_all' => 'Поле :attribute є обов\'язковим для заповнення, коли :values не вказано.',
    'same'                 => 'Поля :attribute та :other мають співпадати.',
    'size'                 => [
        'numeric' => 'Поле :attribute має бути довжини :size.',
        'file'    => 'Файл в полі :attribute має бути розміром :size кілобайт.',
        'string'  => 'Поле :attribute повинен містити <strong>:size символів</strong>символів.',
        'array'   => 'Поле :attribute повинне містити :size елементів.',
    ],
    'string'               => 'Поле :attribute має бути <strong>рядком</strong>.',
    'timezone'             => 'Поле :attribute повинне містити коректну часову зону.',
    'unique'               => ':attribute вже був <strong>заведений</strong>.',
    'uploaded'             => ':attribute <strong>не вдалося</strong> завантажити.',
    'url'                  => 'Формат поля :attribute <strong>невірний</strong>.',

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
            'rule-name' => 'спеціальне повідомлення',
        ],
        'invalid_currency' => 'Формат поля :attribute неправильний.',
        'invalid_amount'   => 'Вибране для :attribute значення не коректне.',
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
