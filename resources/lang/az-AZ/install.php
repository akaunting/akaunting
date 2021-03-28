<?php

return [

    'next'                  => 'İləri',
    'refresh'               => 'Yenilə',

    'steps' => [
        'requirements'      => 'Problemləri aradan qaldırmaq üçün hosting firması ilə əlaqə saxlayın!',
        'language'          => 'Adım 1/3 : Dil Seçimi',
        'database'          => 'Adım 2/3 : Verilənlər bazası parametrləri',
        'settings'          => 'Adım 3/3 : Şirkət və Menecer məlumatları',
    ],

    'language' => [
        'select'            => 'Dil Seçin',
    ],

    'requirements' => [
        'enabled'           => ':feature aktiv olmalıdır!',
        'disabled'          => ':feature deaktiv edilməlidir!',
        'extension'         => ':extension əlavəsi quraşdırılmaslıdır!',
        'directory'         => ':directory qovluq yazılabilir olmalıdır!',
        'executable'        => 'PHP CLI çalıştırıcısı tapılması vəya işlək deyil vəya versiyası :php_version və üstü deyil. Zəhmət olmazsa, hosting firmanızdan PHP_BINARY vəya PHP_PATH mühit dəyərlərinin düzgün tənzimləməsini istəyin.',
    ],

    'database' => [
        'hostname'          => 'Server',
        'username'          => 'İstifadçi adı',
        'password'          => 'Şifrə',
        'name'              => 'Verilənlər bazası',
    ],

    'settings' => [
        'company_name'      => 'Şirkət Adı',
        'company_email'     => 'Şirkət e-Poçtu',
        'admin_email'       => 'İnzibatçı e-Poçtu',
        'admin_password'    => 'İnzibatçı Şifresi',
    ],

    'error' => [
        'php_version'       => 'Xəta: HTTP ve CLI üçün PHP versiyası :php_version və üstü olmalı olduğunu hosting firmanıza bildirin.',
        'connection'        => 'Xəta: Verilənlər bazasına bağlana bilmədik! Zəhmət olmazsa verilənlər bazası məlumatlarını yoxlayın.',
    ],

];
