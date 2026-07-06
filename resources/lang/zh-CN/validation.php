<?php

return [

    /*
|--------------------------------------------------------------------------
|Validation Language Lines
|--------------------------------------------------------------------------
|
|The following language lines contain the default error messages used by
|the validator class. Some of these rules have multiple versions such
|as the size rules. Feel free to tweak each of these messages here.
|
    */

    'accepted' => '您必须接受 :attribute。',
    'accepted_if' => '当 :other 为 :value 时，您必须接受 :attribute。',
    'active_url' => ':attribute 不是有效的 URL。',
    'after' => ':attribute 必须是 :date 之后的日期。',
    'after_or_equal' => ':attribute 必须是 :date 或之后的日期。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、数字、短划线和下划线。',
    'alpha_num' => ':attribute 只能包含字母和数字。',
    'array' => ':attribute 必须是数组。',
    'before' => ':attribute 必须是 :date 之前的日期。',
    'before_or_equal' => ':attribute 必须是 :date 或之前的日期。',
    'between' => [
        'array' => ':attribute 必须有 :min 到 :max 个元素。',
        'file' => ':attribute 必须在 :min 到 :max KB 之间。',
        'numeric' => ':attribute 必须在 :min 到 :max 之间。',
        'string' => ':attribute 必须在 :min 到 :max 个字符之间。',
    ],
    'boolean' => ':attribute 字段必须为 true 或 false。',
    'confirmed' => ':attribute 确认值不匹配。',
    'current_password' => '密码不正确。',
    'date' => ':attribute 不是有效的日期。',
    'date_equals' => ':attribute 必须等于 :date。',
    'date_format' => ':attribute 不符合 :format 的格式。',
    'declined' => ':attribute 必须被拒绝。',
    'declined_if' => '当 :other 为 :value 时，:attribute 必须被拒绝。',
    'different' => ':attribute 和 :other 必须不同。',
    'digits' => ':attribute 必须是 :digits 位数字。',
    'digits_between' => ':attribute 必须在 :min 到 :max 位数字之间。',
    'dimensions' => ':attribute 的图片尺寸无效。',
    'distinct' => ':attribute 字段有重复值。',
    'doesnt_start_with' => ':attribute 不能以下列任意一项开头： :values。',
    'double' => ':attribute 必须是有效的双精度数。',
    'email' => ':attribute 必须是有效的邮箱地址。',
    'ends_with' => ':attribute 必须以下列任意一项结尾： :values。',
    'enum' => '所选择的 :attribute 无效。',
    'exists' => '所选择的 :attribute 无效。',
    'file' => ':attribute 必须是文件。',
    'filled' => ':attribute 字段必须具有值。',
    'gt' => [
        'array' => ':attribute 必须有超过 :value 个元素。',
        'file' => ':attribute 必须大于 :value KB。',
        'numeric' => ':attribute 必须大于 :value。',
        'string' => ':attribute 必须多于 :value 个字符。',
    ],
    'gte' => [
        'array' => ':attribute 必须有 :value 个或更多元素。',
        'file' => ':attribute 必须大于或等于 :value KB。',
        'numeric' => ':attribute 必须大于或等于 :value。',
        'string' => ':attribute 必须多于或等于 :value 个字符。',
    ],
    'image' => ':attribute 必须是图片。',
    'in' => '所选择的 :attribute 无效。',
    'in_array' => ':attribute 字段在 :other 中不存在。',
    'in_detailed' => ':attribute 的值 ":value" 无效。应为以下之一： :values',
    'integer' => ':attribute 必须是整数。',
    'ip' => ':attribute 必须是有效的 IP 地址。',
    'ipv4' => ':attribute 必须是有效的 IPv4 地址。',
    'ipv6' => ':attribute 必须是有效的 IPv6 地址。',
    'json' => ':attribute 必须是有效的 JSON 字符串。',
    'lt' => [
        'array' => ':attribute 必须少于 :value 个元素。',
        'file' => ':attribute 必须小于 :value KB。',
        'numeric' => ':attribute 必须小于 :value。',
        'string' => ':attribute 必须少于 :value 个字符。',
    ],
    'lte' => [
        'array' => ':attribute 不能超过 :value 个元素。',
        'file' => ':attribute 必须小于或等于 :value KB。',
        'numeric' => ':attribute 必须小于或等于 :value。',
        'string' => ':attribute 必须少于或等于 :value 个字符。',
    ],
    'mac_address' => ':attribute 必须是有效的 MAC 地址。',
    'max' => [
        'array' => ':attribute 不能超过 :max 个元素。',
        'file' => ':attribute 不能大于 :max KB。',
        'numeric' => ':attribute 不能大于 :max。',
        'string' => ':attribute 不能多于 :max 个字符。',
    ],
    'mimes' => ':attribute 必须是类型为 :values 的文件。',
    'mimetypes' => ':attribute 必须是类型为 :values 的文件。',
    'min' => [
        'array' => ':attribute 必须至少有 :min 个元素。',
        'file' => ':attribute 必须至少为 :min KB。',
        'numeric' => ':attribute 必须至少为 :min。',
        'string' => ':attribute 必须至少为 :min 个字符。',
    ],
    'multiple_of' => ':attribute 必须是 :value 的倍数。',
    'not_in' => '所选择的 :attribute 无效。',
    'not_regex' => ':attribute 的格式无效。',
    'numeric' => ':attribute 必须是数字。',
    'password' => [
        'letters' => ':attribute 必须至少包含一个字母。',
        'mixed' => ':attribute 必须至少包含一个大写和一个小写字母。',
        'numbers' => ':attribute 必须至少包含一个数字。',
        'symbols' => ':attribute 必须至少包含一个符号。',
        'uncompromised' => '所给的 :attribute 已出现在数据泄露中。请选择不同的 :attribute。',
    ],
    'present' => ':attribute 字段必须存在。',
    'prohibited' => ':attribute 字段被禁止。',
    'prohibited_if' => '当 :other 为 :value 时，:attribute 字段被禁止。',
    'prohibited_unless' => '除非 :other 在 :values 中，否则 :attribute 字段被禁止。',
    'prohibits' => ':attribute 字段禁止 :other 出现。',
    'regex' => ':attribute 的格式无效。',
    'required' => ':attribute 字段是必填项。',
    'required_array_keys' => ':attribute 字段必须包含以下条目： :values。',
    'required_if' => '当 :other 为 :value 时，:attribute 字段是必填项。',
    'required_unless' => '除非 :other 在 :values 中，否则 :attribute 字段是必填项。',
    'required_with' => '当 :values 存在时，:attribute 字段是必填项。',
    'required_with_all' => '当 :values 都存在时，:attribute 字段是必填项。',
    'required_without' => '当 :values 不存在时，:attribute 字段是必填项。',
    'required_without_all' => '当 :values 都不存在时，:attribute 字段是必填项。',
    'same' => ':attribute 和 :other 必须匹配。',
    'size' => [
        'array' => ':attribute 必须包含 :size 个元素。',
        'file' => ':attribute 必须为 :size KB。',
        'numeric' => ':attribute 必须为 :size。',
        'string' => ':attribute 必须为 :size 个字符。',
    ],
    'starts_with' => ':attribute 必须以下列任意一项开头： :values。',
    'string' => ':attribute 必须是字符串。',
    'timezone' => ':attribute 必须是有效的时区。',
    'unique' => ':attribute 已被占用。',
    'uploaded' => ':attribute 上传失败。',
    'url' => ':attribute 必须是有效的 URL。',
    'uuid' => ':attribute 必须是有效的 UUID。',

    /*
|--------------------------------------------------------------------------
|Custom Validation Language Lines
|--------------------------------------------------------------------------
|
|Here you may specify custom validation messages for attributes using the
|convention "attribute.rule" to name the lines. This makes it quick to
|specify a specific custom language line for a given attribute rule.
|
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => '自定义消息',
        ],
        'invalid_currency'      => ':attribute 代码无效。',
        'invalid_amount'        => '金额 :attribute 无效。',
        'invalid_quantity'      => ':attribute 不是有效的数学表达式。',
        'invalid_extension'     => '文件扩展名无效。',
        'invalid_dimension'     => ':attribute 的尺寸不得超过 :width x :height px。',
        'invalid_colour'        => ':attribute 的颜色无效。',
        'invalid_payment_method'=> '付款方式无效。',
    ],

    /*
|--------------------------------------------------------------------------
|Custom Validation Attributes
|--------------------------------------------------------------------------
|
|The following language lines are used to swap our attribute placeholder
|with something more reader friendly such as "E-Mail Address" instead
|of "email". This simply helps us make our message more expressive.
|
    */

    'attributes' => [],

];
