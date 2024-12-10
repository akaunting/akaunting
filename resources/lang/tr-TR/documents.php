<?php

return [

    'edit_columns'              => 'Sütünları Düzenle',
    'empty_items'               => 'Herhangi bir ürün/hizmet eklemediniz.',
    'grand_total'               => 'Genel Toplam',
    'accept_payment_online'     => 'Çevrimiçi Ödemeleri Kabul Et',
    'transaction'               => ':account kullanılarak :amount tutarında bir ödeme yapıldı.',
    'billing'                   => 'Faturalama',
    'advanced'                  => 'Gelişmiş',

    'item_price_hidden'         => 'Bu sütun :type belgenizde gizlenmiştir.',

    'actions' => [
        'cancel'                => 'İptal',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Siz</b> bu faturayı şöyle işaretlediniz',
        'services'              => 'Hizmetler',
        'another_item'          => 'Başka Bir Öğe',
        'another_description'   => 've başka bir açıklama',
        'more_item'             => '+:count öğe daha',
    ],

    'statuses' => [
        'draft'                 => 'Taslak',
        'sent'                  => 'Gönderildi',
        'expired'               => 'Süresi Doldu',
        'viewed'                => 'Görüldü',
        'approved'              => 'Onaylandı',
        'received'              => 'Alındı',
        'refused'               => 'Reddedildi',
        'restored'              => 'Geri Yüklendi',
        'reversed'              => 'Çevrildi',
        'partial'               => 'Kısmi Ödeme',
        'paid'                  => 'Ödendi',
        'pending'               => 'Bekliyor',
        'invoiced'              => 'Faturalandı',
        'overdue'               => 'Gecikmiş',
        'unpaid'                => 'Ödenmemiş',
        'cancelled'             => 'İptal Edildi',
        'voided'                => 'Feshedildi',
        'completed'             => 'Tamamlandı',
        'shipped'               => 'Kargolandı',
        'refunded'              => 'İade Edildi',
        'failed'                => 'Başarısız Oldu',
        'denied'                => 'Reddedildi',
        'processed'             => 'İşlendi',
        'open'                  => 'Açık',
        'closed'                => 'Kapandı',
        'billed'                => 'Faturalandı',
        'delivered'             => 'Teslim Edildi',
        'returned'              => 'Geri Çevrildi',
        'drawn'                 => 'Geri Çekildi',
        'not_billed'            => 'Faturalanmadı',
        'issued'                => 'İşlendi',
        'not_invoiced'          => 'Faturalanmadı',
        'confirmed'             => 'Onaylandı',
        'not_confirmed'         => 'Onaylanmadı',
        'active'                => 'Aktif',
        'ended'                 => 'Sonlandı',
    ],

    'form_description' => [
        'companies'             => 'Şirketinizin adresini, logosunu ve diğer bilgilerini değiştirin.',
        'billing'               => 'Faturalama detayları belgenizde görünür.',
        'advanced'              => 'Kategori seçin, alt bilgiyi ekleyin veya düzenleyin ve :type belgenize ekler ekleyin.',
        'attachment'            => 'Bu :type belgesine eklenmiş dosyaları indirin',
    ],

    'slider' => [
        'create'            => ':user, bu :type belgesini :date tarihinde oluşturdu',
        'create_recurring'  => ':user, bu yinelenen şablonu :date tarihinde oluşturdu',
        'send'              => ':user, bu :type belgesini :date tarihinde gönderdi',
        'schedule'          => ':date tarihinden itibaren her :interval :frequency tekrarla',
        'children'          => ':count adet :type otomatik olarak oluşturuldu',
        'cancel'            => ':user, bu :type belgesini :date tarihinde iptal etti',
    ],

    'messages' => [
        'email_sent'            => ':type e-postası gönderildi!',
        'restored'              => ':type geri yüklendi!',
        'marked_as'             => ':type :status olarak işaretlendi!',
        'marked_sent'           => ':type gönderildi olarak işaretlendi!',
        'marked_paid'           => ':type ödendi olarak işaretlendi!',
        'marked_viewed'         => ':type görüldü olarak işaretlendi!',
        'marked_cancelled'      => ':type iptal edildi olarak işaretlendi!',
        'marked_received'       => ':type alındı olarak işaretlendi!',
    ],

    'recurring' => [
        'auto_generated'        => 'Otomatik oluşturuldu',

        'tooltip' => [
            'document_date'     => ':type tarihi, :type programı ve sıklığına göre otomatik olarak atanacaktır.',
            'document_number'   => ':type numarası, her yinelenen :type oluşturulduğunda otomatik olarak atanacaktır.',
        ],
    ],

    'empty_attachments'         => 'Bu :type belgesine eklenmiş dosya bulunmamaktadır.',
];
