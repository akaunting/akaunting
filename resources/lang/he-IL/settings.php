<?php

return [

    'company' => [
        'name'              => 'שם',
        'email'             => 'דואר אלקטרוני',
        'phone'             => 'טלפון',
        'address'           => 'כתובת',
        'logo'              => 'לוגו',
    ],
    'localisation' => [
        'tab'               => 'לוקליזציה',
        'date' => [
            'format'        => 'פורמט תאריך',
            'separator'     => 'מפריד טקסט',
            'dash'          => 'מקף (-)',
            'dot'           => 'נקודה (.)',
            'comma'         => 'פסיק (,)',
            'slash'         => 'קו נטוי (/)',
            'space'         => 'רווח ( )',
        ],
        'timezone'          => 'איזור זמן',
        'percent' => [
            'title'         => 'אחוז (%) מיקום',
            'before'        => 'לפני המספר',
            'after'         => 'לאחר המספר',
        ],
    ],
    'invoice' => [
        'tab'               => 'חשבונית',
        'prefix'            => 'קידומת מספר',
        'digit'             => 'מספר ספרות',
        'next'              => 'המספר הבא',
        'logo'              => 'לוגו',
    ],
    'default' => [
        'tab'               => 'ברירת מחדל',
        'account'           => 'חשבון ברירת מחדל',
        'currency'          => 'מטבע ברירת מחדל',
        'tax'               => 'שיעור המס ברירת מחדל',
        'payment'           => 'שיטת התשלום המועדפת',
        'language'          => 'שפת ברירת מחדל',
    ],
    'email' => [
        'protocol'          => 'פרוטוקול',
        'php'               => 'PHP דואר',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP Port',
            'username'      => 'שם משתמש SMTP',
            'password'      => 'סיסמת SMTP',
            'encryption'    => 'SMTP אבטחה',
            'none'          => 'ללא',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail Path',
        'log'               => 'Log Emails',
    ],
    'scheduling' => [
        'tab'               => 'תזמון',
        'send_invoice'      => 'שלח חשבונית תזכורת',
        'invoice_days'      => 'שלח לאחר ימים',
        'send_bill'         => 'שלח תזכורת חשבונית',
        'bill_days'         => 'שלח לפני פירעון ימים',
        'cron_command'      => 'הפקודה Cron',
        'schedule_time'     => 'שעה ריצה',
    ],
    'appearance' => [
        'tab'               => 'המראה',
        'theme'             => 'ערכת עיצוב',
        'light'             => 'בהיר',
        'dark'              => 'כהה',
        'list_limit'        => 'תוצאות לעמוד',
        'use_gravatar'      => 'השתמש Gravatar',
    ],
    'system' => [
        'tab'               => 'מערכת',
        'session' => [
            'lifetime'      => 'משך החיים של ההפעלה (דקות)',
            'handler'       => 'Session Handler',
            'file'          => 'קובץ',
            'database'      => 'מסד נתונים',
        ],
        'file_size'         => 'גודל הקובץ מקסימלי (MB)',
        'file_types'        => 'סוגי קבצים מותרים',
    ],

];
