<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable All Language Routes
    |--------------------------------------------------------------------------
    |
    | This option enable language route.
    |
    */
    'route'         => true,

    /*
    |--------------------------------------------------------------------------
    | Enable Language Home Route
    |--------------------------------------------------------------------------
    |
    | This option enable language route to set language and return
    | to url('/')
    |
    */
    'home'          => false,

    /*
    |--------------------------------------------------------------------------
    | Add Language Code
    |--------------------------------------------------------------------------
    |
    | This option will add the language code to the redirected url
    |
    */
    'url'          => false,

    /*
    |--------------------------------------------------------------------------
    | Carbon Language
    |--------------------------------------------------------------------------
    |
    | This option the language of carbon library.
    |
    */
    'carbon'        => true,

    /*
    |--------------------------------------------------------------------------
    | Date Language
    |--------------------------------------------------------------------------
    |
    | This option the language of jenssegers/date library.
    |
    */
    'date'          => true,

    /*
    |--------------------------------------------------------------------------
    | Auto Change Language
    |--------------------------------------------------------------------------
    |
    | This option allows to change website language to user's
    | browser language.
    |
    */
    'auto'          => true,

    /*
    |--------------------------------------------------------------------------
    | Routes Prefix
    |--------------------------------------------------------------------------
    |
    | This option indicates the prefix for language routes.
    |
    */
    'prefix'        => 'languages',

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | This option indicates the middleware to change language.
    |
    */
    'middleware'    => 'Akaunting\Language\Middleware\SetLocale',

    /*
    |--------------------------------------------------------------------------
    | Controller
    |--------------------------------------------------------------------------
    |
    | This option indicates the controller to be used.
    |
    */
    'controller'    => 'Akaunting\Language\Controllers\Language',

    /*
    |--------------------------------------------------------------------------
    | Flags
    |--------------------------------------------------------------------------
    |
    | This option indicates the flags features.
    |
    */
    'flags'         => ['width' => '22px', 'ul_class' => 'menu', 'li_class' => '', 'img_class' => ''],

    /*
    |--------------------------------------------------------------------------
    | Language code mode
    |--------------------------------------------------------------------------
    |
    | This option indicates the language code to be used, short or long
    |
    */
    'mode'          => ['code' => 'long', 'name' => 'native'],

    /*
    |--------------------------------------------------------------------------
    | Allowed languages
    |--------------------------------------------------------------------------
    |
    | This options indicates the allowed languages.
    |
    */
    'allowed'       => ['ar-SA', 'bg-BG', 'cs-CZ', 'da-DK', 'de-DE', 'el-GR', 'en-GB', 'es-ES', 'es-MX', 'fa-IR', 'fr-FR', 'he-IL', 'hi-IN', 'hr-HR', 'id-ID', 'is-IS', 'it-IT', 'ja-JP', 'ka-GE', 'ko-KR', 'lt-LT', 'lv-LV', 'mk-MK', 'ms-MY', 'nb-NO', 'nl-NL', 'pt-BR', 'pt-PT', 'ro-RO', 'ru-RU', 'sk-SK', 'sr-RS', 'sq-AL', 'sv-SE', 'th-TH', 'tr-TR', 'uk-UA', 'ur-PK', 'uz-UZ',  'vi-VN', 'zh-CN', 'zh-TW'],

    /*
    |--------------------------------------------------------------------------
    | All languages
    |--------------------------------------------------------------------------
    |
    | This option indicates the language codes and names.
    |
    */
    'all' => [
        ['short' => 'ar',       'long' => 'ar-SA',      'english' => 'Arabic',              'native' => 'العربية'],
        ['short' => 'bg',       'long' => 'bg-BG',      'english' => 'Bulgarian',           'native' => 'български'],
        ['short' => 'bn',       'long' => 'bn-BD',      'english' => 'Bengali',             'native' => 'বাংলা'],
        ['short' => 'cn',       'long' => 'zh-CN',      'english' => 'Chinese (S)',         'native' => '简体中文'],
        ['short' => 'cs',       'long' => 'cs-CZ',      'english' => 'Czech',               'native' => 'Čeština'],
        ['short' => 'da',       'long' => 'da-DK',      'english' => 'Danish',              'native' => 'Dansk'],
        ['short' => 'de',       'long' => 'de-DE',      'english' => 'German',              'native' => 'Deutsch'],
        ['short' => 'de',       'long' => 'de-AT',      'english' => 'Austrian',            'native' => 'Österreichisches Deutsch'],
        ['short' => 'fi',       'long' => 'fi-FI',      'english' => 'Finnish',             'native' => 'Suomi'],
        ['short' => 'fr',       'long' => 'fr-FR',      'english' => 'French',              'native' => 'Français'],
        ['short' => 'el',       'long' => 'el-GR',      'english' => 'Greek',               'native' => 'Ελληνικά'],
        ['short' => 'en',       'long' => 'en-AU',      'english' => 'English (AU)',        'native' => 'English (AU)'],
        ['short' => 'en',       'long' => 'en-CA',      'english' => 'English (CA)',        'native' => 'English (CA)'],
        ['short' => 'en',       'long' => 'en-GB',      'english' => 'English (GB)',        'native' => 'English (GB)'],
        ['short' => 'en',       'long' => 'en-US',      'english' => 'English (US)',        'native' => 'English (US)'],
        ['short' => 'es',       'long' => 'es-ES',      'english' => 'Spanish',             'native' => 'Español'],
        ['short' => 'et',       'long' => 'et-EE',      'english' => 'Estonian',            'native' => 'Eesti'],
        ['short' => 'he',       'long' => 'he-IL',      'english' => 'Hebrew',              'native' => 'עִבְרִית'],
        ['short' => 'hi',       'long' => 'hi-IN',      'english' => 'Hindi',               'native' => 'हिन्दी'],
        ['short' => 'hr',       'long' => 'hr-HR',      'english' => 'Croatian',            'native' => 'Hrvatski'],
        ['short' => 'hu',       'long' => 'hu-HU',      'english' => 'Hungarian',           'native' => 'Magyar'],
        ['short' => 'hy',       'long' => 'hy-AM',      'english' => 'Armenian',            'native' => 'Հայերեն'],
        ['short' => 'id',       'long' => 'id-ID',      'english' => 'Indonesian',          'native' => 'Bahasa Indonesia'],
        ['short' => 'is',       'long' => 'is-IS',      'english' => 'Icelandic',           'native' => 'Íslenska'],
        ['short' => 'it',       'long' => 'it-IT',      'english' => 'Italian',             'native' => 'Italiano'],
        ['short' => 'ir',       'long' => 'fa-IR',      'english' => 'Persian',             'native' => 'فارسی'],
        ['short' => 'jp',       'long' => 'ja-JP',      'english' => 'Japanese',            'native' => '日本語'],
        ['short' => 'ka',       'long' => 'ka-GE',      'english' => 'Georgian',            'native' => 'ქართული'],
        ['short' => 'ko',       'long' => 'ko-KR',      'english' => 'Korean',              'native' => '한국어'],
        ['short' => 'lt',       'long' => 'lt-LT',      'english' => 'Lithuanian',          'native' => 'Lietuvių'],
        ['short' => 'lv',       'long' => 'lv-LV',      'english' => 'Latvian',             'native' => 'Latviešu valoda'],
        ['short' => 'mk',       'long' => 'mk-MK',      'english' => 'Macedonian',          'native' => 'Македонски јазик'],
        ['short' => 'ms',       'long' => 'ms-MY',      'english' => 'Malay',               'native' => 'Bahasa Melayu'],
        ['short' => 'mx',       'long' => 'es-MX',      'english' => 'Mexico',              'native' => 'Español de México'],
        ['short' => 'nb',       'long' => 'nb-NO',      'english' => 'Norwegian',           'native' => 'Norsk Bokmål'],
        ['short' => 'ne',       'long' => 'ne-NP',      'english' => 'Nepali',              'native' => 'नेपाली'],
        ['short' => 'nl',       'long' => 'nl-NL',      'english' => 'Dutch',               'native' => 'Nederlands'],
        ['short' => 'pl',       'long' => 'pl-PL',      'english' => 'Polish',              'native' => 'Polski'],
        ['short' => 'pt-BR',    'long' => 'pt-BR',      'english' => 'Brazilian',           'native' => 'Português do Brasil'],
        ['short' => 'pt',       'long' => 'pt-PT',      'english' => 'Portuguese',          'native' => 'Português'],
        ['short' => 'ro',       'long' => 'ro-RO',      'english' => 'Romanian',            'native' => 'Română'],
        ['short' => 'ru',       'long' => 'ru-RU',      'english' => 'Russian',             'native' => 'Русский'],
        ['short' => 'sr',       'long' => 'sr-RS',      'english' => 'Serbian (Cyrillic)',  'native' => 'Српски језик'],
        ['short' => 'sr',       'long' => 'sr-CS',      'english' => 'Serbian (Latin)',     'native' => 'Српски језик'],
        ['short' => 'sq',       'long' => 'sq-AL',      'english' => 'Albanian',            'native' => 'Shqip'],
        ['short' => 'sk',       'long' => 'sk-SK',      'english' => 'Slovak',              'native' => 'Slovenčina'],
        ['short' => 'sl',       'long' => 'sl-SL',      'english' => 'Slovenian',           'native' => 'Slovenščina'],
        ['short' => 'sv',       'long' => 'sv-SE',      'english' => 'Swedish',             'native' => 'Svenska'],
        ['short' => 'th',       'long' => 'th-TH',      'english' => 'Thai',                'native' => 'ไทย'],
        ['short' => 'tr',       'long' => 'tr-TR',      'english' => 'Turkish',             'native' => 'Türkçe'],
        ['short' => 'tw',       'long' => 'zh-TW',      'english' => 'Chinese (T)',         'native' => '繁體中文'],
        ['short' => 'uk',       'long' => 'uk-UA',      'english' => 'Ukrainian',           'native' => 'Українська'],
        ['short' => 'ur',       'long' => 'ur-PK',      'english' => 'Urdu (Pakistan)',     'native' => 'اردو'],
        ['short' => 'uz',       'long' => 'uz-UZ',      'english' => 'Uzbek',               'native' => 'O‘zbekcha'],
        ['short' => 'vi',       'long' => 'vi-VN',      'english' => 'Vietnamese',          'native' => 'Tiếng Việt'],
    ],

];
