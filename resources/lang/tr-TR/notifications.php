<?php

return [

    'whoops'              => 'Hay aksi!',
    'hello'               => 'Merhaba!',
    'salutation'          => 'Saygılar,<br> :company_name',
    'subcopy'             => '":text" butonuna tıklayamıyorsanız, aşağıdaki bağlantıyı kopyalayıp tarayıcıya yapıştırın: [:url](:url)',
    'reads'               => 'Okunma|Okunma',
    'read_all'            => 'Hepsini Oku',
    'mark_read'           => 'Okundu İşaretle',
    'mark_read_all'       => 'Hepsini Okundu İşaretle',
    'new_apps'            => 'Yeni Uygulama|Yeni Uygulamalar',
    'upcoming_bills'      => 'Yaklaşan Faturalar',
    'recurring_invoices'  => 'Tekrarlanan Faturalar',
    'recurring_bills'     => 'Tekrarlanan Faturalar',

    'update' => [

        'mail' => [

            'subject' => '⚠️ :domain güncellemesi başarısız oldu',
            'message' => ':alias için :current_version sürümden :new_version sürümüne güncellemesi <strong>:step</strong> aşamasında şu hata ile başarısız oldu: :error_message',

        ],

        'slack' => [

            'message' => ':domain güncellemesi başarısız oldu',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'İçeri aktarma tamamlandı',
            'description'       => 'İçeri aktarma tamamlanmıştır ve kayıtlar panelinizde mevcuttur.',

        ],

        'failed' => [

            'subject'           => 'İçeri aktarma başarısız oldu',
            'description'       => 'Aşağıdaki sorunlar nedeniyle dosya içeri aktarılamıyor:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Dışarı aktarma hazır',
            'description'       => 'Dışarı aktarma dosyası aşağıdaki bağlantıdan indirilmeye hazırdır:',

        ],

        'failed' => [

            'subject'           => 'Dışarı aktarılamadı',
            'description'       => 'Aşağıdaki sorun nedeniyle dışarı aktarma dosyası oluşturulamıyor:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type bu bildirimi okuyun!',
        'mark_read_all'         => ':type tüm bildirimleri oku!',
        'new_app'               => ':type uygulaması yayınlandı.',
        'export'                => '<b>:type</b> dışa aktarma dosyanız <a href=":url" target="_blank"><b>indirilmeye</b></a> hazır.',
        'import'                => '<b>:type</b> satırlı <b>:count</b> verileriniz başarıyla içe aktarıldı.',

    ],
];
