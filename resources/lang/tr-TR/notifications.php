<?php

return [

    'whoops'              => 'Hay aksi!',
    'hello'               => 'Merhaba!',
    'salutation'          => 'Saygılar,<br> :company_name',
    'subcopy'             => '":text" butonuna tıklayamıyorsanız, aşağıdaki bağlantıyı kopyalayıp tarayıcıya yapıştırın: [:url](:url)',
    'mark_read'           => 'Okundu İşaretle',
    'mark_read_all'       => 'Hepsini Okundu İşaretle',
    'empty'               => 'Woohoo, hiç bildirim yok!',
    'new_apps'            => 'Yeni Uygulama|Yeni Uygulamalar',

    'update' => [

        'mail' => [

            'title'         => '⚠️ :domain güncelleme başarısız oldu',
            'description'   => ':alias güncellemesi :current_version sürümünden :new_version sürümüne <strong>:step</strong> adımında şu hata mesajıyla başarısız oldu: :error_message',

        ],

        'slack' => [

            'description'   => ':domain güncelleme başarısız oldu',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'İndirme hazır',
            'description'   => 'Dosya aşağıdaki bağlantıdan indirilmeye hazır:',

        ],

        'failed' => [

            'title'         => 'İndirme başarısız oldu',
            'description'   => 'Aşağıdaki sorun nedeniyle dosya oluşturulamıyor:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'İçeri aktarma tamamlandı',
            'description'   => 'İçeri aktarma tamamlanmıştır ve kayıtlar panelinizde mevcuttur.',

        ],

        'failed' => [

            'title'         => 'İçeri aktarma başarısız oldu',
            'description'   => 'Aşağıdaki sorunlar nedeniyle dosya içeri aktarılamıyor:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Dışarı aktarma hazır',
            'description'   => 'Dışarı aktarma dosyası aşağıdaki bağlantıdan indirilmeye hazırdır:',

        ],

        'failed' => [

            'title'         => 'Dışa aktarma başarısız oldu',
            'description'   => 'Aşağıdaki sorun nedeniyle dışarı aktarma dosyası oluşturulamıyor:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Geçersiz :type E-posta',
            'description'   => ':email e-posta adresi geçersiz olarak bildirildi ve kişi devre dışı bırakıldı. Lütfen aşağıdaki hata mesajını kontrol edin ve e-posta adresini düzeltin:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'İndirme hazır',
            'description'   => '<strong>:type</strong> dosyanız <a href=":url" target="_blank"><strong>indirilmeye</strong></a> hazır.',

        ],

        'download_failed' => [

            'title'         => 'İndirme başarısız oldu',
            'description'   => 'Çeşitli sorunlar nedeniyle dosya oluşturulamadı. Detaylar için e-postanızı kontrol edin.',

        ],

        'export_completed' => [

            'title'         => 'Dışarı aktarma hazır',
            'description'   => '<strong>:type</strong> dışa aktarma dosyanız <a href=":url" target="_blank"><strong>indirilmeye</strong></a> hazır.',

        ],

        'export_failed' => [

            'title'         => 'Dışa aktarma başarısız oldu',
            'description'   => 'Çeşitli sorunlar nedeniyle dışa aktarma dosyası oluşturulamadı. Detaylar için e-postanızı kontrol edin.',

        ],

        'import_completed' => [

            'title'         => 'İçeri aktarma tamamlandı',
            'description'   => '<strong>:type</strong> için <strong>:count</strong> veri başarıyla içe aktarıldı.',

        ],

        'import_failed' => [

            'title'         => 'İçe aktarma başarısız oldu',
            'description'   => 'Çeşitli sorunlar nedeniyle dosya içe aktarılamadı. Detaylar için e-postanızı kontrol edin.',

        ],

        'new_apps' => [

            'title'         => 'Yeni Uygulama',
            'description'   => '<strong>:name</strong> uygulaması yayınlandı. Detayları görmek için <a href=":url">buraya tıklayabilirsiniz</a>.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Yeni Fatura',
            'description'   => '<strong>:invoice_number</strong> fatura oluşturuldu. Detayları görmek ve ödeme yapmak için <a href=":invoice_portal_link">buraya tıklayabilirsiniz</a>.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Gecikmiş Fatura',
            'description'   => '<strong>:invoice_number</strong> faturasının son ödeme tarihi <strong>:invoice_due_date</strong> idi. Detayları görmek ve ödeme yapmak için <a href=":invoice_portal_link">buraya tıklayabilirsiniz</a>.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Gecikmiş Fatura',
            'description'   => '<strong>:invoice_number</strong> faturasının son ödeme tarihi <strong>:invoice_due_date</strong> idi. Detayları görmek için <a href=":invoice_admin_link">buraya tıklayabilirsiniz</a>.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Yeni Tekrarlanan Fatura',
            'description'   => 'Tekrarlanan döngünüze göre <strong>:invoice_number</strong> fatura oluşturuldu. Detayları görmek ve ödeme yapmak için <a href=":invoice_portal_link">buraya tıklayabilirsiniz</a>.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Yeni Tekrarlanan Fatura',
            'description'   => '<strong>:customer_name</strong> tekrarlanan döngüsüne göre <strong>:invoice_number</strong> fatura oluşturuldu. Detayları görmek için <a href=":invoice_admin_link">buraya tıklayabilirsiniz</a>.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Fatura Görüntülendi',
            'description'   => '<strong>:customer_name</strong>, <strong>:invoice_number</strong> faturasını görüntüledi. Detayları görmek için <a href=":invoice_admin_link">buraya tıklayabilirsiniz</a>.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Ödeme Alındı',
            'description'   => '<strong>:invoice_number</strong> faturası için ödemeniz için teşekkür ederiz. Detayları görmek için <a href=":invoice_portal_link">buraya tıklayabilirsiniz</a>.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Ödeme Alındı',
            'description'   => '<strong>:invoice_number</strong> faturası için ödemeniz için teşekkür ederiz. Detayları görmek için <a href=":invoice_portal_link">buraya tıklayabilirsiniz</a>.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Ödeme Alındı',
            'description'   => ':customer_name, <strong>:invoice_number</strong> faturası için ödeme yaptı. Detayları görmek için <a href=":invoice_admin_link">buraya tıklayabilirsiniz</a>.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Gecikmiş Gider Faturası',
            'description'   => '<strong>:bill_number</strong> faturasının son ödeme tarihi <strong>:bill_due_date</strong> idi. Detayları görmek için <a href=":bill_admin_link">buraya tıklayabilirsiniz</a>.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Yeni Tekrarlanan Gider Faturası',
            'description'   => '<strong>:vendor_name</strong> tekrarlanan döngüsüne göre <strong>:bill_number</strong> fatura oluşturuldu. Detayları görmek için <a href=":bill_admin_link">buraya tıklayabilirsiniz</a>.',

        ],

        'invalid_email' => [

            'title'         => 'Geçersiz :type E-posta',
            'description'   => '<strong>:email</strong> e-posta adresi geçersiz olarak bildirildi ve kişi devre dışı bırakıldı. Lütfen e-posta adresini kontrol edip düzeltin.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type bu bildirimi okuyun!',
        'mark_read_all'         => ':type tüm bildirimleri oku!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Firefox İkon Yapılandırması',
            'description'  => '<span class="font-medium">Eğer ikonlarınız görünmüyorsa lütfen;</span> <br /> <span class="font-medium">Sayfaların kendi yazı tiplerini seçmesine izin verin, yukarıdaki seçimleriniz yerine</span> <br /><br /> <span class="font-bold">Ayarlar (Tercihler) > Yazı Tipleri > Gelişmiş</span>',

        ],

    ],

];
