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

    'accepted'             => ':Attribut maydonini qabul qilishingiz kerak.',
    'active_url'           => ':Attribut maydoniga noto‘g‘ri URL kiritildi.',
    'after'                => ':Attribut maydonida sana :sanadan keyingi bo‘lishi kerak.',
    'after_or_equal'       => ':Attribut maydonida sana :sanaga teng yoki undan keyingi bo‘lishi kerak.',
    'alpha'                => ':Attribut maydoni faqat harflarni qabul qilishi mumkin.',
    'alpha_dash'           => ':Attribut maydoni faqat harflar, sonlar va chiziqlarni qabul qilishi mumkin.',
    'alpha_num'            => ':Attribut maydoni faqat harflar va sonlarni qabul qilishi mumkin.',
    'array'                => ': Atributi qator bo\'lishi kerak.',
    'before'               => ': Atributi avvalgi sana bo\'lishi kerak: sanadan oldin.',
    'before_or_equal'      => ':Attribut maydonida sana :date ga teng yoki undan oldin bo‘lishi kerak.',
    'between'              => [
        'numeric' => ':Attribut maydonining qiymati :min va :max orasida bo‘lishi kerak.',
        'file'    => ':Attribut maydonidagi faylning hajmi :min va :max kilobayt orasida bo‘lishi kerak.',
        'string'  => ':Attribut maydonidagi belgilar soni :min va :max orasida bo‘lishi kerak.',
        'array'   => ':Attribut maydonida elementlar soni :min va :max orasida bo‘lishi kerak.',
    ],
    'boolean'              => ':Attribut maydoni faqat mantiqiy qiymatni qabul qiladi.',
    'confirmed'            => ':Attribut maydoni tasdiqlanmadi.',
    'date'                 => ':Attribut sana maydoniga noto‘g‘ri qiymat kiritildi.',
    'date_format'          => ':Attribut maydoni :format formatga mos kelmadi.',
    'different'            => 'The :Atribut va: boshqalar :har xil bo\'lishi kerak <strong>different</strong>.',
    'digits'               => ':Attribute raqamli maydon uzunligi :digits bo‘lishi kerak.',
    'digits_between'       => ':Attribut raqamli maydon uzunligi :min va :max orasida bo‘lishi kerak.',
    'dimensions'           => ':Attribut maydonidagi tasvir to‘g‘ri kelmaydigan o‘lchamlarga ega.',
    'distinct'             => ':Attribut maydoni takrorlanuvchi qiymatlardan iborat.',
    'email'                => ':Atributi to\'g\'ri <strong>elektron pochta manzili bo\'lishi kerak</strong>.',
    'ends_with'            => ':attribut quyidagilarda biri bilan tugashi kerak: :values',
    'exists'               => ':Attribute maydoni uchun tanlangan qiymat noto‘g‘ri.',
    'file'                 => ':Attribute maydoni fayl turida bo‘lishi kerak.',
    'filled'               => ':Attribute maydoni to‘ldirilishi shart.',
    'image'                => ':Attribute maydoni tasvir turida bo‘lishi kerak.',
    'in'                   => ':Attribute maydoni uchun tanlangan qiymat xato.',
    'in_array'             => ':Attribute maydonining qiymati :other da mavjud emas.',
    'integer'              => ':Attribute maydoni butun son bo‘lishi kerak.',
    'ip'                   => ':Attribute maydoni haqiyqiy IP manzil bo‘lishi kerak.',
    'json'                 => ':Attribute maydoni JSON qator (string) bo‘lishi kerak.',
    'max'                  => [
        'numeric' => ':Attribute maydoni qiymati :max dan oshmasligi kerak.',
        'file'    => ':Attribute maydonidagi faylning hajmi :max kilobaytdan oshmasligi kerak.',
        'string'  => ':Attribute maydonidagi belgilar soni :max tadan oshmasligi kerak.',
        'array'   => ':Attribute maydonidagi elmentlar soni :max tadan oshmasligi kerak.',
    ],
    'mimes'                => ':Attribute maydonidagi fayl so‘ngida keltirilgan turlardan birida bo‘lishi kerak: :values.',
    'mimetypes'            => ':Attribute maydonidagi fayl so‘ngida keltirilgan turlardan birida bo‘lishi kerak: :values.',
    'min'                  => [
        'numeric' => ':Attribute maydoni qiymati :min dan kam bo‘lmasligi kerak.',
        'file'    => ':Attribute maydonidagi faylning hajmi :min kilobaytdan kam bo‘lmasligi kerak.',
        'string'  => ':Attribute maydonidagi belgilar soni :min tadan kam bo‘lmasligi kerak.',
        'array'   => ':Attribute maydonidagi elmentlar soni :min tadan kam bo‘lmasligi kerak.',
    ],
    'not_in'               => ':Attribute maydoni uchun tanlangan qiymat xato.',
    'numeric'              => ':Attribute son bo‘lishi kerak.',
    'present'              => ':Attribute <strong>ko‘rsatilishi</strong> kerak.',
    'regex'                => ':Attribute maydoni xato formatda.',
    'required'             => ':Attribute maydoni to‘ldirilishi shart.',
    'required_if'          => ':Attribute maydoni to‘ldirilishi shart, qachonki :other maydoni :value ga teng bo‘lsa.',
    'required_unless'      => ':Attribute maydoni to‘ldirilishi shart, qachonki :other maydoni :values ga teng bo‘lmasa.',
    'required_with'        => ':Attribute maydoni to‘ldirilishi shart, qachonki :values ko‘rsatilgan bo‘lsa.',
    'required_with_all'    => ':Attribute maydoni to‘ldirilishi shart, qachonki :values ko‘rsatilgan bo‘lsa.',
    'required_without'     => ':Attribute maydoni to‘ldirilishi shart, qachonki :values ko‘rsatilmagan bo‘lsa.',
    'required_without_all' => ':Attribute maydoni to‘ldirilishi shart, qachonki :values lardan hech biri ko‘rsatilmagan bo‘lsa.',
    'same'                 => ':Attribute maydonining qiymati :other bilan bir xil bo‘lishi kerak.',
    'size'                 => [
        'numeric' => ':Attribute maydoni qiymati :size ga teng bo‘lishi kerak.',
        'file'    => ':Attribute maydonidagi faylning hajmi :size kilobaytga teng bo‘lishi kerak.',
        'string'  => ':Attribute maydonidagi belgilar soni :size ga teng bo‘lishi kerak.',
        'array'   => ':Attribute maydonidagi elmentlar soni :size ga teng bo‘lishi kerak.',
    ],
    'string'               => ':Attribute maydoni qator (string) bo‘lishi kerak.',
    'timezone'             => ':Attribute maydonining qiymati mavjud vaqt mintaqasi bo‘lishi kerak.',
    'unique'               => ':Attribute maydonining bunday qiymati mavjud (kiritlgan).',
    'uploaded'             => ':Attribute maydonini yuklash muvaffaqiyatli amalga oshmadi.',
    'url'                  => ':Attribute maydoni noto‘g‘ri formatga ega.',

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
            'rule-name' => 'maxsus-xabar',
        ],
        'invalid_currency' => ':attribute kodi xato berilgan.',
        'invalid_amount'   => ':attribute miqdori xato berilgan.',
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
