<?php

return [

    'payment_received'      => 'Alınan Ödeme',
    'payment_made'          => 'Yapılan Ödeme',
    'paid_by'               => 'Ödeyen',
    'paid_to'               => 'Ödenen',
    'related_invoice'       => 'İlgili Fatura',
    'related_bill'          => 'İlgili Fatura',
    'recurring_income'      => 'Yinelenen Gelir',
    'recurring_expense'     => 'Yinelenen Gider',
    'included_tax'          => 'Dahil edilen vergi tutarı',
    'connected'             => 'Bağlantılı',

    'form_description' => [
        'general'           => 'Burada tarih, tutar, hesap, açıklama gibi işlemin genel bilgilerini girebilirsiniz.',
        'assign_income'     => 'Raporlarınızı daha detaylı hale getirmek için bir kategori ve müşteri seçin.',
        'assign_expense'    => 'Raporlarınızı daha detaylı hale getirmek için bir kategori ve tedarikçi seçin.',
        'other'             => 'İşlemi kayıtlarınızla bağlantılı tutmak için bir numara ve referans girin.',
    ],

    'slider' => [
        'create'            => ':date tarihinde :user bu işlemi oluşturdu',
        'attachments'       => 'Bu işleme eklenen dosyaları indir',
        'create_recurring'  => ':date tarihinde :user bu yinelenen şablonu oluşturdu',
        'schedule'          => ':date tarihinden itibaren her :interval :frequency tekrarla',
        'children'          => ':count işlemleri otomatik olarak oluşturuldu',
        'connect'           => 'Bu işlem :count işlemle bağlantılıdır',
        'transfer_headline' => '<div> <span class="font-bold">Gönderen: </span> :from_account </div> <div> <span class="font-bold">Alan: </span> :to_account </div>',
        'transfer_desc'     => 'Transfer :date tarihinde oluşturuldu',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Müşteriniz işlemi bu linkten görüntüleyebilir',
            'copy_link'     => 'Bağlantıyı kopyalayın ve müşterinizle paylaşın.',
        ],

        'expense' => [
            'show_link'     => 'Satıcınız işlemi bu bağlantıdan görüntüleyebilir',
            'copy_link'     => 'Bağlantıyı kopyalayın ve satıcınızla paylaşın.',
        ],
    ],

    'sticky' => [
        'description'       => 'Müşterinizin ödemenizin web sürümünü nasıl göreceğini önizleyebilirsiniz.',
    ],

];
