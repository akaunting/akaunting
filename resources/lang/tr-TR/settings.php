<?php

return [

    'company' => [
        'description'                   => 'Şirketin ismini, adresini, vergi numrasını vs. değiştirin',
        'search_keywords'               => 'şirket, isim, e-posta, telefon, adres, ülke, vergi numarası, logo, şehir, kasaba, eyalet, il, posta kodu',
        'name'                          => 'Şirket İsmi',
        'email'                         => 'Şirket Emaili',
        'phone'                         => 'Telefon',
        'address'                       => 'Şirket Adresi',
        'edit_your_business_address'    => 'Şirket adresini giriniz',
        'logo'                          => 'Şirket Logosu',

        'form_description' => [
            'general'                   => 'Bu bilgiler oluşturduğunuz kayıtlarda görünür.',
            'address'                   => 'Adres, kestiğiniz faturalarda, faturalarda ve diğer kayıtlarda kullanılacaktır.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Mali yıl başlangıcını, saat dilimini, tarih biçimini vs. ayarlayın',
        'search_keywords'               => 'mali, yıl, başlangıç, ifade, saat, bölge, tarih, biçim, ayırıcı, indirim, yüzde',
        'financial_start'               => 'Mali Yıl Başlangıcı',
        'timezone'                      => 'Saat Dilimi',
        'financial_denote' => [
            'title'                     => 'Mali Yıl Gösterimi',
            'begins'                    => 'Yılın başlangıcı',
            'ends'                      => 'Yılın bitişi',
        ],
        'preferred_date'                => 'Tercih Tarihi',
        'date' => [
            'format'                    => 'Tarih Biçimi',
            'separator'                 => 'Tarih Ayracı',
            'dash'                      => 'Tire (-)',
            'dot'                       => 'Nokta (.)',
            'comma'                     => 'Virgül (,)',
            'slash'                     => 'Taksim (/)',
            'space'                     => 'Boşluk ( )',
        ],
        'percent' => [
            'title'                     => 'Yüzde (%) Konumu',
            'before'                    => 'Sayıdan Önce',
            'after'                     => 'Sayıdan Sonra',
        ],
        'discount_location' => [
            'name'                      => 'İndirim Konumu',
            'item'                      => 'Satırda',
            'total'                     => 'Toplamda',
            'both'                      => 'Satırda ve toplamda',
        ],

        'form_description' => [
            'fiscal'                    => 'Şirketinizin vergilendirme ve raporlama için kullandığı mali yıl dönemini ayarlayın.',
            'date'                      => 'Arayüzde her yerde görmek istediğiniz tarih biçimini seçin.',
            'other'                     => 'Vergiler için yüzde işaretinin görüntüleneceği yeri seçin.  Faturalar ve faturalar için kalemlerde ve toplamda indirimleri etkinleştirebilirsiniz.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Fatura numarasını, önekini, vadesini vs. özelleştirin',
        'search_keywords'               => 'özelleştir, fatura, numara, ön ek, basamak, sonraki, logo, ad, fiyat, miktar, şablon, başlık, alt başlık, alt bilgi, not, gizle, vade, renk, ödeme, şartlar, sütun',
        'prefix'                        => 'Numara Öneki',
        'digit'                         => 'Numara Rakam Sayısı',
        'next'                          => 'Sonraki Numara',
        'logo'                          => 'Logo',
        'custom'                        => 'Özel',
        'item_name'                     => 'Ürün adı',
        'item'                          => 'Ürünler',
        'product'                       => 'Ürünler',
        'service'                       => 'Hizmetler',
        'price_name'                    => 'Fiyat Adı',
        'price'                         => 'Fiyat',
        'rate'                          => 'Oran',
        'quantity_name'                 => 'Miktar Adı',
        'quantity'                      => 'Miktar',
        'payment_terms'                 => 'Ödeme Vadeleri',
        'title'                         => 'Başlık',
        'subheading'                    => 'Altbaşlık',
        'due_receipt'                   => 'Teslim alındığında vadeli',
        'due_days'                      => ':days vadeli',
        'choose_template'               => 'Fatura şablonu seçin',
        'default'                       => 'Varsayılan',
        'classic'                       => 'Klasik',
        'modern'                        => 'Modern',
        'hide' => [
            'item_name'                 => 'Ürün/Hizmet İsmini Gizle',
            'item_description'          => 'Ürün/Hizmet Açıklamasını Gizle',
            'quantity'                  => 'Miktarı Gizle',
            'price'                     => 'Fiyatı Gizle',
            'amount'                    => 'Tutarı Gizle',
        ],
        'column'                        => 'Sütun|Sütunlar',

        'form_description' => [
            'general'                   => 'Fatura numaralarınızı ve ödeme koşullarınızı biçimlendirmek için varsayılanları ayarlayın.',
            'template'                  => 'Faturalarınız için aşağıdaki şablonlardan birini seçin.',
            'default'                   => 'Faturalar için varsayılanları seçmek, başlıkları, alt başlıkları, notları ve alt bilgileri önceden dolduracaktır.  Böylece daha profesyonel görünmek için her seferinde faturaları düzenlemeniz gerekmez.',
            'column'                    => 'Fatura sütunlarının nasıl adlandırılacağını özelleştirin.  Kalem açıklamalarını ve miktarlarını satırlarda gizlemek isterseniz buradan değiştirebilirsiniz.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Aktarım şablonunu seçin',
        'second'                        => 'İkinci',
        'third'                         => 'Üçüncü',
    ],

    'default' => [
        'description'                   => 'Şirketinizin varsayılan hesap, para birimi, dil vs',
        'search_keywords'               => 'hesap, para birimi, dil, vergi, ödeme, yöntem, sayfalandırma',
        'list_limit'                    => 'Sayfa Başına Kayıt Sayısı',
        'use_gravatar'                  => 'Gravatar kullanın',
        'income_category'               => 'Gelir Kategorisi',
        'expense_category'              => 'Gider Kategorisi',

        'form_description' => [
            'general'                   => 'Kayıtları hızla oluşturmak için varsayılan hesabı, vergiyi ve ödeme yöntemini seçin.  Gösterge Tablosu ve Raporlar, varsayılan para birimi altında gösterilir.',
            'category'                  => 'Kayıt oluşturma işlemini hızlandırmak için varsayılan kategorileri seçin.',
            'other'                     => 'Şirket dilinin varsayılan ayarlarını ve sayfalandırmanın nasıl çalıştığını özelleştirin.',
        ],
    ],

    'email' => [
        'description'                   => 'E-posta şablonları ve gönderim protokolünü değiştirin',
        'search_keywords'               => 'e-posta, gönder, protokol, smtp, host, parola',
        'protocol'                      => 'Protokol',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP Host',
            'port'                      => 'SMTP Port',
            'username'                  => 'SMTP Kullanıcı Adı',
            'password'                  => 'SMTP Şifresi',
            'encryption'                => 'SMTP Güvenlik',
            'none'                      => 'Hiçbiri',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sendmail Dizini',
        'log'                           => 'E-mailleri logla',
        'email_service'                 => 'E-posta Hizmeti',
        'email_templates'               => 'E-posta Şablonları',

        'form_description' => [
            'general'                   => 'Ekibinize ve kullanıcılarınıza düzenli e-postalar gönderin.  Protokol ve SMTP ayarlarını yapabilirsiniz.',
        ],

        'templates' => [
            'description'               => 'E-posta şablonlarını değiştirin',
            'search_keywords'           => 'e-posta, şablon, konu, gövde, etiket',
            'subject'                   => 'Başlık',
            'body'                      => 'İçerik',
            'tags'                      => '<strong>Mevcut Etiketler:</strong> :tag_list',
            'invoice_new_customer'      => 'Yeni Fatura Şablonu (müşteriye gönderilen)',
            'invoice_remind_customer'   => 'Fatura Hatırlatma Şablonu (müşteriye gönderilen)',
            'invoice_remind_admin'      => 'Fatura Hatırlatma Şablonu (yöneticiye gönderilen)',
            'invoice_recur_customer'    => 'Tekrarlı Fatura Şablonu (müşteriye gönderilen)',
            'invoice_recur_admin'       => 'Tekrarlı Fatura Şablonu (yöneticiye gönderilen)',
            'invoice_view_admin'        => 'Fatura Görünüm Şablonu (yöneticiye gönderilir)',
            'invoice_payment_customer'  => 'Ödeme Alındı Şablonu (müşteriye gönderilen)',
            'invoice_payment_admin'     => 'Ödeme Alındı Şablonu (yöneticiye gönderilen)',
            'bill_remind_admin'         => 'Gider Faturası Hatırlatma Şablonu (yöneticiye gönderilen)',
            'bill_recur_admin'          => 'Tekrarlı Gider Faturası Şablonu (yöneticiye gönderilen)',
            'payment_received_customer' => 'Ödeme Alındı Şablonu (müşteriye gönderilen)',
            'payment_made_vendor'       => 'Ödeme Yapıldı Şablonu (müşteriye gönderilen)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Zamanlama',
        'description'                   => 'Otomatik hatırlatma ve tekrarlı işlemler için komut satırı',
        'search_keywords'               => 'otomatik, hatırlatma, yinelenen, cron, komut',
        'send_invoice'                  => 'Gelir Faturası Hatırlat',
        'invoice_days'                  => 'Vade Gününden Sonra Gönder',
        'send_bill'                     => 'Gider Faturası Hatırlat',
        'bill_days'                     => 'Vade Gününden Önce Gönder',
        'cron_command'                  => 'Cron Komutu',
        'command'                       => 'Komut',
        'schedule_time'                 => 'Çalışma Saati',

        'form_description' => [
            'invoice'                   => 'Vadesi geçtiğinde faturalarınız için etkinleştirin veya devre dışı bırakın ve hatırlatıcılar ayarlayın.',
            'bill'                      => 'Faturalarınız için vadesi dolmadan önce etkinleştirin veya devre dışı bırakın ve hatırlatıcılar ayarlayın.',
            'cron'                      => 'Sunucunuzun çalıştırması gereken cron komutunu kopyalayın.  Etkinliği tetiklemek için zamanı ayarlayın.',
        ]
    ],

    'categories' => [
        'description'                   => 'Sınırsız gelir, gider ve ürün kategorisi oluşturun',
        'search_keywords'               => 'kategori, gelir, gider, madde',
    ],

    'currencies' => [
        'description'                   => 'Para birimi oluşturup onların kurlarını ayarlayın',
        'search_keywords'               => 'varsayılan, para birimi, para birimleri, kod, oran, sembol, kesinlik, konum, ondalık, binler, işaret, ayırıcı',
    ],

    'taxes' => [
        'description'                   => 'Sabit, normal, dahil ve bileşik vergi sınıfları oluşturun',
        'search_keywords'               => 'vergi, oran, tür, sabit, dahil, bileşik, stopaj',
    ],

];
