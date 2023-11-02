<?php

return [

    'code'                  => '货币代码',
    'rate'                  => '货币汇率',
    'default'               => '默认货币',
    'decimal_mark'          => '小数点',
    'thousands_separator'   => '千位分隔符',
    'precision'             => '精确度',
    'conversion'            => '货币转换: :price (:currency_code) 为 :currency_rate',
    'symbol' => [
        'symbol'            => '货币符号',
        'position'          => '货币符号位置',
        'before'            => '金额前',
        'after'             => '在金额之后',
    ],

    'form_description' => [
        'general'           => '仪表盘和统计报告均采用默认货币。对于其它货币，弱势货币的汇率必须低于1，而强势货币的汇率必须高于1。',
    ],

    'no_currency'           => '未设置货币种类',
    'create_currency'       => '创建一个新的货币种类，如果不合适，后续可以在设置中修改',
    'new_currency'          => '新建货币',

];
