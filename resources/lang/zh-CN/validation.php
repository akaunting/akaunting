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

    'accepted'             => '必须接受 :attribute。',
    'active_url'           => ':attribute 并非一个有效的网址。',
    'after'                => ':attribute 必须要晚于 :date。',
    'after_or_equal'       => ':attribute 必须要等于 :date 或更晚',
    'alpha'                => ':attribute 只能以字母组成。',
    'alpha_dash'           => ':attribute 只能以字母、数字及斜线组成。',
    'alpha_num'            => ':attribute 只能以字母及数字组成。',
    'array'                => ':attribute 必须为数组。',
    'before'               => ':attribute 必须要早于 :date。',
    'before_or_equal'      => ':attribute 必须要等于 :date 或更早。',
    'between'              => [
        'numeric' => ':attribute 必须介于 :min 至 :max 之间。',
        'file'    => ':attribute 必须介于 :min 至 :max kb 之间。 ',
        'string'  => ':attribute 必须介于 :min 至 :max 个字符之间。',
        'array'   => ':attribute: 必须有 :min - :max 个元素。',
    ],
    'boolean'              => ':attribute 必须为布尔值（是、否/真、假）。',
    'confirmed'            => ':attribute 确认值的输入不一致。',
    'date'                 => ':attribute 并非一个有效日期。',
    'date_format'          => ':attribute 不符合 :format 的格式。',
    'different'            => ':attribute 与 :other 必须不同。',
    'digits'               => ':attribute 必须是 :digits 位数字。',
    'digits_between'       => ':attribute 必须介于 :min 至 :max 位数字。',
    'dimensions'           => ':attribute 图片尺寸不正确。',
    'distinct'             => ':attribute 已存在。',
    'email'                => ':attribute 必须是有效的邮箱。',
    'exists'               => '所选择的 :attribute 选项无效。',
    'file'                 => ':attribute 必须是文件。',
    'filled'               => ':attribute 不能留空。',
    'image'                => ':attribute 必须是图片。',
    'in'                   => '所选择的 :attribute 选项无效。',
    'in_array'             => ':attribute 没有在 :other 中。',
    'integer'              => ':attribute 必须是整数。',
    'ip'                   => ':attribute 必须是有效的 IP 地址。',
    'json'                 => ':attribute 必须是正确的 JSON 字符串。',
    'max'                  => [
        'numeric' => ':attribute 不能大于 :max。',
        'file'    => ':attribute 不能大于 :max kb。',
        'string'  => ':attribute 不能多于 :max 个字符。',
        'array'   => ':attribute 最多有 :max 个元素。',
    ],
    'mimes'                => ':attribute 必须为 :values 的文件。',
    'mimetypes'            => ':attribute 必须为 :values 的文件。',
    'min'                  => [
        'numeric' => ':attribute 不能小于 :min。',
        'file'    => ':attribute 不能小于 :min kb。',
        'string'  => ':attribute 不能小于 :min 个字符。',
        'array'   => ':attribute 至少有 :min 个元素。',
    ],
    'not_in'               => '所选择的 :attribute 选项无效。',
    'numeric'              => ':attribute 必须为数字。',
    'present'              => ':attribute 必须存在。',
    'regex'                => ':attribute 的格式错误。',
    'required'             => ':attribute 不能留空。',
    'required_if'          => '当 :other 是 :value 时 :attribute 不能留空。',
    'required_unless'      => '当 :other 不是 :value 时 :attribute 不能留空。',
    'required_with'        => '当 :values 出现时 :attribute 不能留空。',
    'required_with_all'    => '当 :values 出现时 :attribute 不能為空。',
    'required_without'     => '当 :values 留空时 :attribute field 不能留空。',
    'required_without_all' => '当 :values 都不出现时 :attribute 不能留空。',
    'same'                 => ':attribute 与 :other 必须相同。',
    'size'                 => [
        'numeric' => ':attribute 的大小必须是 :size。',
        'file'    => ':attribute 的大小必须是 :size kb。',
        'string'  => ':attribute 必须是 :size 个字符。',
        'array'   => ':attribute 必须是 :size 个元素。',
    ],
    'string'               => ':attribute 必须是字符串。',
    'timezone'             => ':attribute 必须是争取的时区值。',
    'unique'               => ':attribute 已经存在。',
    'uploaded'             => ':attribute 上传失败。',
    'url'                  => ':attribute 的格式错误。',

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
            'rule-name' => '自定信息',
        ],
        'invalid_currency' => ':attribute code 无效.',
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
