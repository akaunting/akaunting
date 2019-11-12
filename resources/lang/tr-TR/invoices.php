<?php

return [

    'invoice_number'        => 'Fatura Numarası',
    'invoice_date'          => 'Fatura Tarihi',
    'total_price'           => 'Toplam Tutar',
    'due_date'              => 'Vade Tarihi',
    'order_number'          => 'Sipariş Numarası',
    'bill_to'               => 'Faturalanacak Kişi/Kurum',

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
    'paid'                  => 'Ödenmiş',
    'histories'             => 'Geçmiş',
    'payments'              => 'Ödemeler',
    'add_payment'           => 'Ödeme Ekle',
    'mark_paid'             => 'Ödendi İşaretle',
    'mark_sent'             => 'Gönderildi İşaretle',
    'download_pdf'          => 'PDF İndir',
    'send_mail'             => 'Email Gönder',
    'all_invoices'          => 'Tüm faturaları görüntülemek için giriş yapın',
    'create_invoice'        => 'Fatura Oluştur',
    'send_invoice'          => 'Faturayı Gönder',
    'get_paid'              => 'Ödeme Al',
    'accept_payments'       => 'Online Tahsilat Al',

    'status' => [
        'draft'             => 'Taslak',
        'sent'              => 'Gönderilen',
        'viewed'            => 'Görüldü',
        'approved'          => 'Onaylandı',
        'partial'           => 'Kısmi',
        'paid'              => 'Ödenmiş',
    ],

    'messages' => [
        'email_sent'        => 'Fatura emaili başarı ile gönderildi!',
        'marked_sent'       => 'Fatura başarıyla gönderilmiş olarak işaretlendi!',
        'email_required'    => 'Bu müşteri için e-posta adresi yok!',
        'draft'             => 'Bu bir <b>TASLAK</b> faturadır ve gönderildikten sonra grafiklere yansıtılacaktır.',

        'status' => [
            'created'       => ':date tarihinde oluşturuldu',
            'send' => [
                'draft'     => 'Gönderilmedi',
                'sent'      => ':date tarihinde gönderildi',
            ],
            'paid' => [
                'await'     => 'Ödeme bekliyor',
            ],
        ],
    ],

    'notification' => [
        'message'           => ':amount tutarında faturayı :customer ödemediği için bu iletiyi almaktasınız.',
        'button'            => 'Şimdi Öde',
    ],

];
