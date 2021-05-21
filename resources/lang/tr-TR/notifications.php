<?php

return [

    'whoops'              => 'Hay aksi!',
    'hello'               => 'Merhaba!',
    'salutation'          => 'Saygılar,<br> :company_name',
    'subcopy'             => '":text" butonuna tıklayamıyorsanız, aşağıdaki bağlantıyı kopyalayıp tarayıcıya yapıştırın: [:url](:url)',

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
            'subject'           => 'İçe aktarma tamamlandı',
            'description'       => 'İçe aktarma tamamlanmıştır ve kayıtlar panelinizde mevcuttur.',
        ],

        'failed' => [
            'subject'           => 'İçe aktarma başarısız oldu',
            'description'       => 'Aşağıdaki sorunlar nedeniyle dosya içe aktarılamıyor:',
        ],
    ],

    'export' => [

        'completed' => [
            'subject'           => 'Dışa aktarma hazır',
            'description'       => 'Dışa aktarma dosyası aşağıdaki bağlantıdan indirilmeye hazırdır:',
        ],

        'failed' => [
            'subject'           => 'Dışa aktarılamadı',
            'description'       => 'Aşağıdaki sorun nedeniyle dışa aktarma dosyası oluşturulamıyor:',
        ],

    ],

];
