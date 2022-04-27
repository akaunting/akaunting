<?php

return [

    'next'                  => 'הבא',
    'refresh'               => 'ריענון',

    'steps' => [
        'requirements'      => 'בבקשה, בקש מספק האחסון שאתה משתמש בו לתקן את השגיאות!',
        'language'          => 'שלב 1/3: בחירת שפה',
        'database'          => 'שלב 2/3: הגדרות מסד נתונים',
        'settings'          => 'שלב 3/3: הגדרת פרטי החברה והמנהל',
    ],

    'language' => [
        'select'            => 'בחירת שפה',
    ],

    'requirements' => [
        'enabled'           => ':feature צריך להיות פעיל!',
        'disabled'          => ':feature צריך להיות כבוי!',
        'extension'         => ':extension יש להתקין ולטעון את התוסף!',
        'directory'         => ':directory צריכה להיות עם הרשאות כתיבה!',
        'executable'        => 'קובץ ההפעלה של PHP CLI אינו מוגדר/אינו עובד או שהגרסה שלו לא :php_version או שהגרסה גבוהה יותר. בבקשה, תגדיר או תפנה לחברת האחסון שיגדירו PHP_BINARY או PHP_PATH את הגרסא ומשתני סביבה בצורה נכונה.',
    ],

    'database' => [
        'hostname'          => 'שם מארח',
        'username'          => 'שם משתמש',
        'password'          => 'סיסמה',
        'name'              => 'שם מסד נתונים',
    ],

    'settings' => [
        'company_name'      => 'שם החברה',
        'company_email'     => 'כתובת הדואר האלקטרוני של החברה',
        'admin_email'       => 'דוא"ל מנהל המערכת',
        'admin_password'    => 'סיסמת מנהל מערכת',
    ],

    'error' => [
        'php_version'       => 'שגיאה: יש להשתמש ב-PHP בגרסא :php_version או בגרסה גבוהה יותר.עבור הHTTP והCLI. ',
        'connection'        => 'שגיאה: לא היתה אפשרות להתחבר למסד הנתונים! בבקשה ודא כי הפרטים נכונים.',
    ],

];
