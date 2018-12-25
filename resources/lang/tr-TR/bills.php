<?php

return [

    'bill_number'           => 'Fatura Numarası',
    'bill_date'             => 'Fatura Tarihi',
    'total_price'           => 'Toplam Tutar',
    'due_date'              => 'Vade Tarihi',
    'order_number'          => 'Sipariş Numarası',
    'bill_from'             => 'Faturayı Kesen',

    'quantity'              => 'Adet',
    'price'                 => 'Fiyat',
    'sub_total'             => 'Ara Toplam',
    'discount'              => 'İndirim',
    'tax_total'             => 'Vergi Toplamı',
    'total'                 => 'Toplam',

    'item_name'             => 'Öğe Adı | Öğe Adları',

    'show_discount'         => '%:discount İndirim',
    'add_discount'          => 'İndirim Ekle',
    'discount_desc'         => 'ara toplam üzerinden',

    'payment_due'           => 'Son Ödeme Tarihi',
    'amount_due'            => 'Ödenecek Miktar',
    'paid'                  => 'Ödenmiş',
    'histories'             => 'Geçmiş',
    'payments'              => 'Ödemeler',
    'add_payment'           => 'Ödeme Ekle',
    'mark_received'         => 'Teslim Alındı İşaretle',
    'download_pdf'          => 'PDF İndir',
    'send_mail'             => 'Email Gönder',
    'create_bill'           => 'Fatura Oluştur',
    'receive_bill'          => 'Faturayı Al',
    'make_payment'          => 'Ödeme Yap',

    'status' => [
        'draft'             => 'Taslak',
        'received'          => 'Teslim Alındı',
        'partial'           => 'Kısmi',
        'paid'              => 'Ödenmiş',
    ],

    'messages' => [
        'received'          => 'Fatura başarıyla teslim alındı olarak işaretlendi!',
        'draft'             => 'Bu bir <b>TASLAK</b> faturadır ve alındıktan sonra grafiklere yansıtılacaktır.',

        'status' => [
            'created'       => ':date tarihinde oluşturuldu',
            'receive' => [
                'draft'     => 'Gönderilmedi',
                'received'  => ':date tarihinde alındı',
            ],
            'paid' => [
                'await'     => 'Bekleyen Ödeme',
            ],
        ],
    ],

];
