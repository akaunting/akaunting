<?php

return [

    'bill_number'           => 'Satın Alma Fatura Numarası',
    'bill_date'             => 'Satın Alma Fatura Tarihi',
    'bill_amount'           => 'Satın Alma Fatura Tutarı',
    'total_price'           => 'Toplam Tutar',
    'due_date'              => 'Vade Tarihi',
    'order_number'          => 'Sipariş Numarası',
    'bill_from'             => 'Faturayı Kesen',

    'quantity'              => 'Adet',
    'price'                 => 'Fiyat',
    'sub_total'             => 'Ara Toplam',
    'discount'              => 'İndirim',
    'item_discount'         => 'Satır İndirimi',
    'tax_total'             => 'Vergi Toplamı',
    'total'                 => 'Toplam',

    'item_name'             => 'Öğe Adı|Öğe Adları',
    'recurring_bills'       => 'Yinelenen Satın Alma Faturası|Yinelenen Satın Alma Faturaları',

    'show_discount'         => '%:discount İndirim',
    'add_discount'          => 'İndirim Ekle',
    'discount_desc'         => 'ara toplam üzerinden',

    'payment_made'          => 'Yapılan ödeme',
    'payment_due'           => 'Son Ödeme Tarihi',
    'amount_due'            => 'Ödenecek Miktar',
    'paid'                  => 'Ödenmiş',
    'histories'             => 'Geçmiş',
    'payments'              => 'Ödemeler',
    'add_payment'           => 'Ödeme Ekle',
    'mark_paid'             => 'Ödendi Olarak İşaretle',
    'mark_received'         => 'Alındı Olarak İşaretle',
    'mark_cancelled'        => 'İptal Edildi Olarak İşaretle',
    'download_pdf'          => 'PDF İndir',
    'send_mail'             => 'Email Gönder',
    'create_bill'           => 'Satın Alma Faturası Oluştur',
    'receive_bill'          => 'Faturayı Al',
    'make_payment'          => 'Ödeme Yap',

    'form_description' => [
        'billing'           => 'Faturalama detayları satın alma faturanızda görünür. Fatura Tarihi kontrol panelinde ve raporlarda kullanılır. Vade Tarihi olarak ödemeyi beklediğiniz tarihi seçin.',
    ],

    'messages' => [
        'draft'             => 'Bu bir <b>TASLAK</b> faturadır ve alındıktan sonra grafiklere yansıtılacaktır.',

        'status' => [
            'created'       => ':date tarihinde oluşturuldu',
            'receive' => [
                'draft'     => 'Alınamadı',
                'received'  => ':date tarihinde alındı',
            ],
            'paid' => [
                'await'     => 'Bekleyen Ödeme',
            ],
        ],
    ],

];
