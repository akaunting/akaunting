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

    'accepted'             => ':attributeを承認してください。',
    'active_url'           => ':attributeは、有効なURLではありません。',
    'after'                => ':attributeには、:date以降の日付を指定してください。',
    'after_or_equal'       => ':attributeには、:date以降もしくは同日時を指定してください。',
    'alpha'                => ':attributeには、アルファベッドのみ使用できます。',
    'alpha_dash'           => ':attributeには、英数字(\'A-Z\',\'a-z\',\'0-9\')とハイフンと下線(\'-\',\'_\')が使用できます。',
    'alpha_num'            => ':attributeには、英数字(\'A-Z\',\'a-z\',\'0-9\')が使用できます。',
    'array'                => ':attributeには、配列を指定してください。',
    'before'               => ':attributeには、:date以前の日付を指定してください。',
    'before_or_equal'      => ':attributeには、:date以前もしくは同日時を指定してください。',
    'between'              => [
        'numeric' => ':attributeには、:minから、:maxまでの数字を指定してください。',
        'file'    => ':attributeには、:min KBから:max KBまでのサイズのファイルを指定してください。',
        'string'  => ':attributeは、:min文字から:max文字にしてください。',
        'array'   => ':attributeの項目は、:min個から:max個にしてください。',
    ],
    'boolean'              => ':attributeには、\'true\'か\'false\'を指定してください。',
    'confirmed'            => ':attributeと:attribute確認が一致しません。',
    'date'                 => ':attributeは、正しい日付ではありません。',
    'date_format'          => ':attributeの形式は、\':format\'と合いません。',
    'different'            => '：attributeと：otherは<strong>異なる</ strong>でなければなりません。',
    'digits'               => ':attributeは、:digits桁にしてください。',
    'digits_between'       => ':attributeは、:min桁から:max桁にしてください。',
    'dimensions'           => ':attributeは、正しい縦横比ではありません。',
    'distinct'             => ':attributeに重複した値があります。',
    'email'                => '：attributeは有効な<strong>メールアドレス</ strong>でなければなりません。',
    'ends_with'            => '：attributeは、次のいずれかで終了する必要があります：：values',
    'exists'               => '選択された:attributeは、有効ではありません。',
    'file'                 => '：attributeは<strong>ファイル</ strong>でなければなりません。',
    'filled'               => '：attributeフィールドには<strong>値</ strong>が必要です。',
    'image'                => '：attributeは<strong> image </ strong>でなければなりません。',
    'in'                   => '選択された:attributeは、有効ではありません。',
    'in_array'             => ':attributeは、:otherに存在しません。',
    'integer'              => '：attributeは<strong>整数</ strong>でなければなりません。',
    'ip'                   => ':attributeには、有効なIPアドレスを指定してください。',
    'json'                 => ':attributeには、有効なJSON文字列を指定してください。',
    'max'                  => [
        'numeric' => ':attributeには、:max以下の数字を指定してください。',
        'file'    => ':attributeには、:max KB以下のファイルを指定してください。',
        'string'  => ':attributeは、:max文字以下にしてください。',
        'array'   => ':attributeの項目は、:max個以下にしてください。',
    ],
    'mimes'                => ':attributeには、:valuesタイプのファイルを指定してください。',
    'mimetypes'            => ':attributeには、:valuesタイプのファイルを指定してください。',
    'min'                  => [
        'numeric' => ':attributeには、:min以上の数字を指定してください。',
        'file'    => ':attributeには、:min KB以上のファイルを指定してください。',
        'string'  => ':attributeは、:min文字以上にしてください。',
        'array'   => ':attributeの項目は、:max個以上にしてください。',
    ],
    'not_in'               => '選択された:attributeは、有効ではありません。',
    'numeric'              => ':attributeには、数字を指定してください。',
    'present'              => '：attributeフィールドは<strong> present </ strong>でなければなりません。',
    'regex'                => 'The：attribute形式は<strong>無効</ strong>です。',
    'required'             => 'The：attributeフィールドは<strong>必須</ strong>です。',
    'required_if'          => ':otherが:valueの場合、:attributeを指定してください。',
    'required_unless'      => ':otherが:value以外の場合、:attributeを指定してください。',
    'required_with'        => ':valuesが指定されている場合、:attributeも指定してください。',
    'required_with_all'    => ':valuesが全て指定されている場合、:attributeも指定してください。',
    'required_without'     => ':valuesが指定されていない場合、:attributeを指定してください。',
    'required_without_all' => ':valuesが全て指定されていない場合、:attributeを指定してください。',
    'same'                 => ':attributeと:otherが一致しません。',
    'size'                 => [
        'numeric' => ':attributeには、:sizeを指定してください。',
        'file'    => ':attributeには、:size KBのファイルを指定してください。',
        'string'  => '：attributeは<strong>：size characters </ strong>でなければなりません。',
        'array'   => ':attributeの項目は、:size個にしてください。',
    ],
    'string'               => '：attributeは<strong> string </ strong>でなければなりません。',
    'timezone'             => ':attributeには、有効なタイムゾーンを指定してください。',
    'unique'               => '：attributeは既に<strong>取得されています</ strong>。',
    'uploaded'             => '：attributeはアップロードに<strong>失敗しました</ strong>。',
    'url'                  => '：attribute形式は<strong>無効</ strong>です。',

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
            'rule-name'             => 'カスタム メッセージ',
        ],
        'invalid_currency'      => '：属性コードが無効です。',
        'invalid_amount'        => '総額：属性が無効です。',
        'invalid_extension'     => 'ファイル拡張子が無効です。',
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
