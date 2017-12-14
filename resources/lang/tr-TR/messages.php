<?php

return [

    'success' => [
        'added'             => ':type eklendi!',
        'updated'           => ':type güncellendi!',
        'deleted'           => ':type silindi!',
        'duplicated'        => ':type çoğaltıldı!',
        'imported'          => ':type içe aktarıldı!',
    ],
    'error' => [
        'payment_add'       => 'Hata: Ödeme eklenmedi. Ödeme tutarını kontrol ediniz.',
        'not_user_company'  => 'Hata: Bu şirketi yönetme yetkiniz yok!',
        'customer'          => 'Hata: :name bu email adresini kullanmaktadır.',
        'no_file'           => 'Hata: Dosya seçilmedi!',
    ],
    'warning' => [
        'deleted'           => 'Uyarı: <b>:name</b> silinemez çünkü :text ile ilişkilidir.',
        'disabled'          => 'Uyarı: <b>:name</b> devre dışı bırakılamaz çünkü :text ile ilişkilidir.',
    ],

];
