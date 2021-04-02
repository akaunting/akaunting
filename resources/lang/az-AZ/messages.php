<?php

return [

    'success' => [
        'added'             => ':type əlavə edildi!',
        'updated'           => ':type yeniləndi!',
        'deleted'           => ':type silindi!',
        'duplicated'        => ':type dublikat edildi!',
        'imported'          => ':type idxal edildi!',
        'exported'          => ':type ixrac edildi!',
        'enabled'           => ':type aktiv edildi!',
        'disabled'          => ':type deaktiv edildi!',
    ],

    'error' => [
        'over_payment'      => 'Xəta: Ödəniş əlavə edilmədi! Daxil etdiyiniz :amount cəmi keçir.',
        'not_user_company'  => 'Xəta: Bu şirkəti idarə etmə icazəniz yoxdur!',
        'customer'          => 'Xəta: İstifadəçi yaradılmadı. :name bu e-poçt ünvanı istifadə edilir.',
        'no_file'           => 'Xəta: Fayl seçilmədi!',
        'last_category'     => 'Xəta: Son :type kateqoriyasını silə bilməzsiniz!',
        'change_type'       => 'Xəta: Növ dəyişdirilə bilməz çünki :text əlaqə mövcuddur!',
        'invalid_apikey'    => 'Xəta: Daxil etdiyiniz API açar qüvvədə deyil!',
        'import_column'     => 'Xəta: :message Səhifə adı: :sheet. Sətir nömrəsi: :line.',
        'import_sheet'      => 'Xəta: Səyfə adı qüvvədə deyil. Zəhmət olmazsa, nümunə sənədinə baxın.',
    ],

    'warning' => [
        'deleted'           => 'Xəbərdarlıq: <b>:name</b> silinə bilməz çünki :text ile əlaqəlidir.',
        'disabled'          => 'Xəbərdarlıq: <b>:name</b> deaktiv edilə bilməz çünki :text ilə əlaqəlidir.',
        'reconciled_tran'   => 'Xəbərdarlıq: Əməliyyat razılaşdırılmış olunduğu üçün dəyişdirilə / silinə bilməz.',
        'reconciled_doc'    => 'Xəbərdarlıq: :type razılaşdırılmış əməliyyatlar apardığı üçün dəyişdirilə / silinə bilməz.',
        'disable_code'      => 'Xəbərdarlıq: <b>:name</b> deaktiv edilə vəya valyuta dəyişdirilə bilməz çünki :text ilə əlaqəlidir.',
        'payment_cancel'    => 'Xəbərdarlıq: :method ödənişini ləğv etdiniz!',
    ],

];
