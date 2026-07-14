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

    'accepted'             => 'Поле :attribute должно быть принято.',
    'accepted_if'          => 'Поле :attribute должно быть принято, когда :other равно :value.',
    'active_url'           => 'Поле :attribute содержит недействительный URL.',
    'after'                => 'В поле :attribute должна быть дата после :date.',
    'after_or_equal'       => 'В поле :attribute должна быть дата после или равная :date.',
    'alpha'                => 'Поле :attribute может содержать только буквы.',
    'alpha_dash'           => 'Поле :attribute может содержать только буквы, цифры, дефисы и подчёркивания.',
    'alpha_num'            => 'Поле :attribute может содержать только буквы и цифры.',
    'array'                => 'Поле :attribute должно быть массивом.',
    'before'               => 'В поле :attribute должна быть дата до :date.',
    'before_or_equal'      => 'В поле :attribute должна быть дата до или равная :date.',
    'between'              => [
        'array'   => 'Количество элементов в поле :attribute должно быть между :min и :max.',
        'file'    => 'Размер файла в поле :attribute должен быть между :min и :max Кб.',
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'string'  => 'Количество символов в поле :attribute должно быть между :min и :max.',
    ],
    'boolean'              => 'Поле :attribute должно иметь значение true или false.',
    'confirmed'            => 'Поле :attribute не совпадает с подтверждением.',
    'current_password'     => 'Пароль неверный.',
    'date'                 => 'Поле :attribute не является действительной датой.',
    'date_equals'          => 'Поле :attribute должно быть датой, равной :date.',
    'date_format'          => 'Поле :attribute не соответствует формату :format.',
    'declined'             => 'Поле :attribute должно быть отклонено.',
    'declined_if'          => 'Поле :attribute должно быть отклонено, когда :other равно :value.',
    'different'            => 'Поля :attribute и :other должны быть разными.',
    'digits'               => 'Длина цифрового поля :attribute должна быть :digits.',
    'digits_between'       => 'Длина цифрового поля :attribute должна быть между :min и :max.',
    'dimensions'           => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct'             => 'Поле :attribute содержит повторяющееся значение.',
    'doesnt_start_with'    => 'Поле :attribute не должно начинаться с одного из следующих значений: :values.',
    'double'               => 'Поле :attribute должно быть действительным числом двойной точности.',
    'email'                => 'Поле :attribute должно быть действительным адресом эл. почты.',
    'ends_with'            => 'Поле :attribute должно заканчиваться одним из следующих значений: :values.',
    'enum'                 => 'Выбранное значение :attribute недействительно.',
    'exists'               => 'Выбранное значение для :attribute некорректно.',
    'file'                 => 'Поле :attribute должно быть файлом.',
    'filled'               => 'Поле :attribute должно иметь значение.',
    'gt'                   => [
        'array'   => 'Поле :attribute должно содержать больше :value элементов.',
        'file'    => 'Размер файла в поле :attribute должен быть больше :value Кб.',
        'numeric' => 'Поле :attribute должно быть больше :value.',
        'string'  => 'Количество символов в поле :attribute должно быть больше :value.',
    ],
    'gte'                  => [
        'array'   => 'Поле :attribute должно содержать :value или более элементов.',
        'file'    => 'Размер файла в поле :attribute должен быть больше или равен :value Кб.',
        'numeric' => 'Поле :attribute должно быть больше или равно :value.',
        'string'  => 'Количество символов в поле :attribute должно быть больше или равно :value.',
    ],
    'image'                => 'Поле :attribute должно быть изображением.',
    'in'                   => 'Выбранное значение для :attribute неверно.',
    'in_array'             => 'Поле :attribute не существует в :other.',
    'in_detailed'          => 'Значение :attribute ":value" недействительно. Ожидалось одно из: :values',
    'integer'              => 'Поле :attribute должно быть целым числом.',
    'ip'                   => 'Поле :attribute должно быть действительным IP-адресом.',
    'ipv4'                 => 'Поле :attribute должно быть действительным IPv4-адресом.',
    'ipv6'                 => 'Поле :attribute должно быть действительным IPv6-адресом.',
    'json'                 => 'Поле :attribute должно быть действительной строкой JSON.',
    'lt'                   => [
        'array'   => 'Поле :attribute должно содержать меньше :value элементов.',
        'file'    => 'Размер файла в поле :attribute должен быть меньше :value Кб.',
        'numeric' => 'Поле :attribute должно быть меньше :value.',
        'string'  => 'Количество символов в поле :attribute должно быть меньше :value.',
    ],
    'lte'                  => [
        'array'   => 'Поле :attribute не должно содержать больше :value элементов.',
        'file'    => 'Размер файла в поле :attribute должен быть меньше или равен :value Кб.',
        'numeric' => 'Поле :attribute должно быть меньше или равно :value.',
        'string'  => 'Количество символов в поле :attribute должно быть меньше или равно :value.',
    ],
    'mac_address'          => 'Поле :attribute должно быть действительным MAC-адресом.',
    'max'                  => [
        'array'   => 'Поле :attribute не должно содержать больше :max элементов.',
        'file'    => 'Размер файла в поле :attribute не может быть больше :max Кб.',
        'numeric' => 'Поле :attribute не может быть больше :max.',
        'string'  => 'Количество символов в поле :attribute не может превышать :max.',
    ],
    'mimes'                => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'mimetypes'            => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'min'                  => [
        'array'   => 'Поле :attribute должно содержать не менее :min элементов.',
        'file'    => 'Размер файла в поле :attribute должен быть не менее :min Кб.',
        'numeric' => 'Поле :attribute должно быть не меньше :min.',
        'string'  => 'Количество символов в поле :attribute должно быть не менее :min.',
    ],
    'multiple_of'          => 'Поле :attribute должно быть кратным :value.',
    'not_in'               => 'Выбранное значение :attribute неверно.',
    'not_regex'            => 'Формат поля :attribute является недопустимым.',
    'numeric'              => 'Поле :attribute должно быть числом.',
    'password'             => [
        'letters'       => 'Поле :attribute должно содержать хотя бы одну букву.',
        'mixed'          => 'Поле :attribute должно содержать хотя бы одну прописную и одну строчную букву.',
        'numbers'        => 'Поле :attribute должно содержать хотя бы одну цифру.',
        'symbols'        => 'Поле :attribute должно содержать хотя бы один специальный символ.',
        'uncompromised'  => 'Указанное :attribute обнаружено в утечке данных. Пожалуйста, выберите другое :attribute.',
    ],
    'present'              => 'Поле :attribute должно присутствовать.',
    'prohibited'           => 'Поле :attribute запрещено.',
    'prohibited_if'        => 'Поле :attribute запрещено, когда :other равно :value.',
    'prohibited_unless'    => 'Поле :attribute запрещено, если только :other не входит в :values.',
    'prohibits'            => 'Поле :attribute запрещает присутствие :other.',
    'regex'                => 'Формат поля :attribute является недопустимым.',
    'required'             => 'Поле :attribute обязательно для заполнения.',
    'required_array_keys'  => 'Поле :attribute должно содержать записи для: :values.',
    'required_if'          => 'Поле :attribute обязательно для заполнения, когда :other равно :value.',
    'required_unless'      => 'Поле :attribute обязательно для заполнения, если только :other не входит в :values.',
    'required_with'        => 'Поле :attribute обязательно для заполнения, когда :values указано.',
    'required_with_all'    => 'Поле :attribute обязательно для заполнения, когда все :values указаны.',
    'required_without'     => 'Поле :attribute обязательно для заполнения, когда :values не указано.',
    'required_without_all' => 'Поле :attribute обязательно для заполнения, когда ни одно из :values не указано.',
    'same'                 => 'Значение :attribute должно совпадать с :other.',
    'size'                 => [
        'array'   => 'Количество элементов в поле :attribute должно быть равным :size.',
        'file'    => 'Размер файла в поле :attribute должен быть равен :size Кб.',
        'numeric' => 'Поле :attribute должно быть равным :size.',
        'string'  => 'Поле :attribute должно содержать :size символов.',
    ],
    'starts_with'          => 'Поле :attribute должно начинаться с одного из следующих значений: :values.',
    'string'               => 'Поле :attribute должно быть строкой.',
    'timezone'             => 'Поле :attribute должно быть действительным часовым поясом.',
    'unique'               => 'Значение :attribute уже занято.',
    'uploaded'             => 'Не удалось загрузить содержимое :attribute.',
    'url'                  => 'Поле :attribute должно быть действительным URL.',
    'uuid'                 => 'Поле :attribute должно быть действительным UUID.',

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
            'rule-name'             => 'Настраиваемое сообщение',
        ],
        'invalid_currency'      => 'Код :attribute неверен.',
        'invalid_amount'        => 'Значение :attribute неверно.',
        'invalid_quantity'      => 'Значение :attribute не является действительным математическим выражением.',
        'invalid_extension'     => 'Недопустимое расширение файла.',
        'invalid_dimension'     => 'Размеры :attribute должны быть не более :width x :height пикселей.',
        'invalid_colour'        => 'Цвет :attribute недействителен.',
        'invalid_payment_method'=> 'Способ оплаты недействителен.',
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
