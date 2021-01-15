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
    'item_discount'         => 'Satır İndirimi',
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
    'mark_viewed'           => 'Görüntülendi İşaretle',
    'mark_cancelled'        => 'İptal Edildi İşaretle',
    'download_pdf'          => 'PDF İndir',
    'send_mail'             => 'Email Gönder',
    'all_invoices'          => 'Tüm faturaları görüntülemek için giriş yapın',
    'create_invoice'        => 'Fatura Oluştur',
    'send_invoice'          => 'Faturayı Gönder',
    'get_paid'              => 'Ödeme Al',
    'accept_payments'       => 'Online Tahsilat Al',

    'messages' => [
        'email_required'    => 'Bu müşteri için e-posta adresi yok!',
        'draft'             => 'Bu bir <b>TASLAK</b> faturadır ve gönderildikten sonra grafiklere yansıtılacaktır.',

        'status' => [
            'created'       => ':date tarihinde oluşturuldu',
            'viewed'        => 'Görüldü',
            'send' => [
                'draft'     => 'Gönderilmedi',
                'sent'      => ':date tarihinde gönderildi',
            ],
            'paid' => [
                'await'     => 'Ödeme bekliyor',
            ],
        ],
    ],

];
