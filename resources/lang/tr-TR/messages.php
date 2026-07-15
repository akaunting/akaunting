<?php

return [

    'success' => [
        'added'             => ':type eklendi!',
        'created'            => ':type oluşturuldu!',
        'updated'           => ':type güncellendi!',
        'deleted'           => ':type silindi!',
        'duplicated'        => ':type çoğaltıldı!',
        'imported'          => ':type içe aktarıldı!',
        'import_queued'     => ':type içe aktarma planlandı! Tamamlandığında bir e-posta alacaksınız.',
        'exported'          => ':type dışa aktarıldı!',
        'export_queued'     => ':type dışa aktarımı planlandı! İndirmeye hazır olduğunda bir e-posta alacaksınız.',
        'download_queued'   => 'Mevcut sayfanın :type indirmesi planlandı! İndirmeye hazır olduğunda bir e-posta alacaksınız.',
        'enabled'           => ':type etkinleştirildi!',
        'disabled'          => ':type devre dışı bırakıldı!',
        'connected'         => ':type bağlandı!',
        'invited'           => ':type davet edildi!',
        'ended'             => ':type sonlandırıldı!',

        'clear_all'         => 'Harika!  Tüm :type öğelerinizi temizlediniz.',
    ],

    'error' => [
        'over_payment'      => 'Hata: Ödeme Eklenmedi! Girdiğiniz :amount toplamı geçiyor',
        'not_user_company'  => 'Hata: Bu şirketi yönetme yetkiniz yok!',
        'customer'          => 'Hata: Kullanıcı oluşturulamadı. :name bu e-posta adresini kullanmaktadır.',
        'no_file'           => 'Hata: Dosya seçilmedi!',
        'last_category'     => 'Hata: Son :type kategorisini silemezsiniz!',
        'transfer_category' => 'Hata: Transfer <b>:type</b> kategorisi silinemez!',
        'change_type'       => 'Hata: Tür değiştirilemez çünkü :text ilişki mevcut!',
        'invalid_apikey'    => 'Hata: Girdiğiniz API Anahtarı geçersiz!',
        'empty_apikey'      => 'Hata: API Anahtarınızı girmediniz! API Anahtarınızı girmek için <a href=":url" class="font-bold underline underline-offset-4">buraya tıklayın</a>.',
        'import_column'     => 'Hata: :message Sütun adı: :column. Satır numarası: :line.',
        'import_sheet'      => 'Hata: Sayfa adı geçersiz. Lütfen örnek dosyayı kontrol edin.',
        'same_amount'       => 'Hata: Bölünmüş toplam tutarı tam olarak :transaction toplamına eşit olmalıdır: :amount',
        'over_match'        => 'Hata: :type bağlanamadı! Girdiğiniz tutar ödeme toplamını geçemez: :amount',
    ],

    'warning' => [
        'deleted'           => 'Uyarı: <b>:name</b> silinemez çünkü :text ile ilişkilidir.',
        'disabled'          => 'Uyarı: <b>:name</b> devre dışı bırakılamaz çünkü :text ile ilişkilidir.',
        'reconciled_tran'   => 'Uyarı: İşlem değiştirilemez/silinemez çünkü mutabakatı yapıldı.',
        'reconciled_doc'    => 'Uyarı: :type değiştirilemez/silinemez çünkü mutabakatı yapılmış işlemleri var.',
        'disable_code'      => 'Uyarı: <b>:name</b> devre dışı bırakılamaz veya kur değiştirilemez çünkü :text ile ilişkilidir.',
        'payment_cancel'    => 'Uyarı: :method ödemesini iptal ettiniz!',
        'missing_transfer'  => 'Uyarı: Bu işlemle ilgili transfer eksik. Bu işlemi silmeyi düşünmelisiniz.',
        'connect_tax'       => 'Uyarı: Bu :type bir vergi tutarı içeriyor. :type eklenen vergiler bağlanamadığından, vergi topama eklenecek ve buna göre hesaplanacaktır.',
        'contact_change'    => 'Uyarı: Halihazırda gönderilmiş, alınmış veya ödenmiş bir :type üzerindeki kişiyi değiştirmenize izin verilmez!',
    ],

];
