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
    'home'          => true,

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
    | Set strategy
    |--------------------------------------------------------------------------
    |
    | This option will determine the strategy used to determine the back url.
    | It can be 'session' (default) or 'referer'
    |
    */
    'back'          => 'session',

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
    'date'          => false,

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
    'flags'         => ['width' => '22px', 'ul_class' => '', 'li_class' => '', 'img_class' => ''],

    /*
    |--------------------------------------------------------------------------
    | Language code mode
    |--------------------------------------------------------------------------
    |
    | This option indicates the language code and name to be used, short/long
    | and english/native.
    | Short: language code (en)
    | Long: languagecode-COUNTRYCODE (en-GB)
    |
    */
    'mode'          => ['code' => 'short', 'name' => 'native'],

    /*
    |--------------------------------------------------------------------------
    | Allowed languages
    |--------------------------------------------------------------------------
    |
    | This options indicates the language allowed languages.
    |
    */
    'allowed'       => ['en', 'es', 'fr', 'de', 'it'],

    /*
    |--------------------------------------------------------------------------
    | All Languages
    |--------------------------------------------------------------------------
    |
    | This option indicates the language codes and names.
    |
    */
    'all' => [
        ['short' => 'ar',       'long' => 'ar-SA',      'direction' => 'rtl',       'english' => 'Arabic',              'native' => 'العربية'],
        ['short' => 'az',       'long' => 'az-AZ',      'direction' => 'ltr',       'english' => 'Azerbaijani',         'native' => 'Azərbaycan'],
        ['short' => 'bg',       'long' => 'bg-BG',      'direction' => 'ltr',       'english' => 'Bulgarian',           'native' => 'български'],
        ['short' => 'bn',       'long' => 'bn-BD',      'direction' => 'ltr',       'english' => 'Bengali',             'native' => 'বাংলা'],
        ['short' => 'bs',       'long' => 'bs-BA',      'direction' => 'ltr',       'english' => 'Bosnian',             'native' => 'Bosanski'],
        ['short' => 'ca',       'long' => 'ca-ES',      'direction' => 'ltr',       'english' => 'Catalan',             'native' => 'Català'],
        ['short' => 'cn',       'long' => 'zh-CN',      'direction' => 'ltr',       'english' => 'Chinese (S)',         'native' => '简体中文'],
        ['short' => 'cs',       'long' => 'cs-CZ',      'direction' => 'ltr',       'english' => 'Czech',               'native' => 'Čeština'],
        ['short' => 'da',       'long' => 'da-DK',      'direction' => 'ltr',       'english' => 'Danish',              'native' => 'Dansk'],
        ['short' => 'de',       'long' => 'de-DE',      'direction' => 'ltr',       'english' => 'German',              'native' => 'Deutsch'],
        ['short' => 'de',       'long' => 'de-AT',      'direction' => 'ltr',       'english' => 'Austrian',            'native' => 'Österreichisches Deutsch'],
        ['short' => 'fi',       'long' => 'fi-FI',      'direction' => 'ltr',       'english' => 'Finnish',             'native' => 'Suomi'],
        ['short' => 'fr',       'long' => 'fr-FR',      'direction' => 'ltr',       'english' => 'French',              'native' => 'Français'],
        ['short' => 'ea',       'long' => 'es-AR',      'direction' => 'ltr',       'english' => 'Spanish (Argentina)', 'native' => 'Español de Argentina'],
        ['short' => 'el',       'long' => 'el-GR',      'direction' => 'ltr',       'english' => 'Greek',               'native' => 'Ελληνικά'],
        ['short' => 'en',       'long' => 'en-AU',      'direction' => 'ltr',       'english' => 'English (AU)',        'native' => 'English (AU)'],
        ['short' => 'en',       'long' => 'en-CA',      'direction' => 'ltr',       'english' => 'English (CA)',        'native' => 'English (CA)'],
        ['short' => 'en',       'long' => 'en-GB',      'direction' => 'ltr',       'english' => 'English (GB)',        'native' => 'English (GB)'],
        ['short' => 'en',       'long' => 'en-US',      'direction' => 'ltr',       'english' => 'English (US)',        'native' => 'English (US)'],
        ['short' => 'es',       'long' => 'es-ES',      'direction' => 'ltr',       'english' => 'Spanish',             'native' => 'Español'],
        ['short' => 'et',       'long' => 'et-EE',      'direction' => 'ltr',       'english' => 'Estonian',            'native' => 'Eesti'],
        ['short' => 'he',       'long' => 'he-IL',      'direction' => 'rtl',       'english' => 'Hebrew',              'native' => 'עִבְרִית'],
        ['short' => 'hi',       'long' => 'hi-IN',      'direction' => 'ltr',       'english' => 'Hindi',               'native' => 'हिन्दी'],
        ['short' => 'hr',       'long' => 'hr-HR',      'direction' => 'ltr',       'english' => 'Croatian',            'native' => 'Hrvatski'],
        ['short' => 'hu',       'long' => 'hu-HU',      'direction' => 'ltr',       'english' => 'Hungarian',           'native' => 'Magyar'],
        ['short' => 'hy',       'long' => 'hy-AM',      'direction' => 'ltr',       'english' => 'Armenian',            'native' => 'Հայերեն',],
        ['short' => 'id',       'long' => 'id-ID',      'direction' => 'ltr',       'english' => 'Indonesian',          'native' => 'Bahasa Indonesia'],
        ['short' => 'is',       'long' => 'is-IS',      'direction' => 'ltr',       'english' => 'Icelandic',           'native' => 'Íslenska'],
        ['short' => 'it',       'long' => 'it-IT',      'direction' => 'ltr',       'english' => 'Italian',             'native' => 'Italiano'],
        ['short' => 'ir',       'long' => 'fa-IR',      'direction' => 'rtl',       'english' => 'Persian',             'native' => 'فارسی'],
        ['short' => 'jp',       'long' => 'ja-JP',      'direction' => 'ltr',       'english' => 'Japanese',            'native' => '日本語'],
        ['short' => 'ka',       'long' => 'ka-GE',      'direction' => 'ltr',       'english' => 'Georgian',            'native' => 'ქართული'],
        ['short' => 'ko',       'long' => 'ko-KR',      'direction' => 'ltr',       'english' => 'Korean',              'native' => '한국어'],
        ['short' => 'lt',       'long' => 'lt-LT',      'direction' => 'ltr',       'english' => 'Lithuanian',          'native' => 'Lietuvių'],
        ['short' => 'lv',       'long' => 'lv-LV',      'direction' => 'ltr',       'english' => 'Latvian',             'native' => 'Latviešu valoda'],
        ['short' => 'mk',       'long' => 'mk-MK',      'direction' => 'ltr',       'english' => 'Macedonian',          'native' => 'Македонски јазик'],
        ['short' => 'ms',       'long' => 'ms-MY',      'direction' => 'ltr',       'english' => 'Malay',               'native' => 'Bahasa Melayu'],
        ['short' => 'mx',       'long' => 'es-MX',      'direction' => 'ltr',       'english' => 'Mexico',              'native' => 'Español de México'],
        ['short' => 'nb',       'long' => 'nb-NO',      'direction' => 'ltr',       'english' => 'Norwegian',           'native' => 'Norsk Bokmål'],
        ['short' => 'ne',       'long' => 'ne-NP',      'direction' => 'ltr',       'english' => 'Nepali',              'native' => 'नेपाली'],
        ['short' => 'nl',       'long' => 'nl-NL',      'direction' => 'ltr',       'english' => 'Dutch',               'native' => 'Nederlands'],
        ['short' => 'pl',       'long' => 'pl-PL',      'direction' => 'ltr',       'english' => 'Polish',              'native' => 'Polski'],
        ['short' => 'pt-BR',    'long' => 'pt-BR',      'direction' => 'ltr',       'english' => 'Brazilian',           'native' => 'Português do Brasil'],
        ['short' => 'pt',       'long' => 'pt-PT',      'direction' => 'ltr',       'english' => 'Portuguese',          'native' => 'Português'],
        ['short' => 'ro',       'long' => 'ro-RO',      'direction' => 'ltr',       'english' => 'Romanian',            'native' => 'Română'],
        ['short' => 'ru',       'long' => 'ru-RU',      'direction' => 'ltr',       'english' => 'Russian',             'native' => 'Русский'],
        ['short' => 'sr',       'long' => 'sr-RS',      'direction' => 'ltr',       'english' => 'Serbian (Cyrillic)',  'native' => 'Српски језик'],
        ['short' => 'sr',       'long' => 'sr-CS',      'direction' => 'ltr',       'english' => 'Serbian (Latin)',     'native' => 'Српски језик'],
        ['short' => 'sq',       'long' => 'sq-AL',      'direction' => 'ltr',       'english' => 'Albanian',            'native' => 'Shqip'],
        ['short' => 'sk',       'long' => 'sk-SK',      'direction' => 'ltr',       'english' => 'Slovak',              'native' => 'Slovenčina'],
        ['short' => 'sl',       'long' => 'sl-SI',      'direction' => 'ltr',       'english' => 'Slovenian',           'native' => 'Slovenščina'],
        ['short' => 'sv',       'long' => 'sv-SE',      'direction' => 'ltr',       'english' => 'Swedish',             'native' => 'Svenska'],
        ['short' => 'th',       'long' => 'th-TH',      'direction' => 'ltr',       'english' => 'Thai',                'native' => 'ไทย'],
        ['short' => 'tr',       'long' => 'tr-TR',      'direction' => 'ltr',       'english' => 'Turkish',             'native' => 'Türkçe'],
        ['short' => 'tw',       'long' => 'zh-TW',      'direction' => 'ltr',       'english' => 'Chinese (T)',         'native' => '繁體中文'],
        ['short' => 'uk',       'long' => 'uk-UA',      'direction' => 'ltr',       'english' => 'Ukrainian',           'native' => 'Українська'],
        ['short' => 'ur',       'long' => 'ur-PK',      'direction' => 'rtl',       'english' => 'Urdu (Pakistan)',     'native' => 'اردو'],
        ['short' => 'uz',       'long' => 'uz-UZ',      'direction' => 'ltr',       'english' => 'Uzbek',               'native' => 'O\'zbek'],
        ['short' => 'vi',       'long' => 'vi-VN',      'direction' => 'ltr',       'english' => 'Vietnamese',          'native' => 'Tiếng Việt'],
    ],
];
