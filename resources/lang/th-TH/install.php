<?php

return [

    'next'                  => 'ถัดไป',
    'refresh'               => 'โหลดใหม่',

    'steps' => [
        'requirements'      => 'Please, ask your hosting provider to fix the errors!',
        'language'          => 'ขั้นตอนที่ 1/3: เลือกภาษา',
        'database'          => 'ขั้นตอนที่ 2/3: การตั้งค่าฐานข้อมูล',
        'settings'          => 'ขั้นตอนที่ 3/3: รายละเอียดบริษัทและผู้ดูแลระบบ',
    ],

    'language' => [
        'select'            => 'เลือกภาษา',
    ],

    'requirements' => [
        'enabled'           => ':feature ที่ต้องการเปิดใช้งาน',
        'disabled'          => ':feature ที่ต้องการปิดใช้งาน',
        'extension'         => ':extension extension needs to be installed and loaded!',
        'directory'         => ':directory แฟ้มจำเป็นต้องเขียน',
    ],

    'database' => [
        'hostname'          => 'ชื่อโฮสต์',
        'username'          => 'ชื่อผู้ใช้งาน',
        'password'          => 'รหัสผ่าน',
        'name'              => 'ฐานข้อมูล',
    ],

    'settings' => [
        'company_name'      => 'ชื่อบริษัท',
        'company_email'     => 'อีเมลบริษัท',
        'admin_email'       => 'อีเมลผู้ดูแลระบบ',
        'admin_password'    => 'รหัสผ่านผู้ดูแลระบบ',
    ],

    'error' => [
        'connection'        => 'ข้อผิดพลาด: ไม่สามารถเชื่อมต่อไปยังฐานข้อมูล กรุณา ตรวจสอบรายละเอียดให้ถูกต้อง',
    ],

];
