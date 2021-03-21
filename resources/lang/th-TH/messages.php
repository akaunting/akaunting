<?php

return [

    'success' => [
        'added'             => ':type เพิ่มแล้ว!',
        'updated'           => ':type อัพเดทแล้ว!',
        'deleted'           => ':type ลบแล้ว!',
        'duplicated'        => ':type ทำซ้ำแล้ว!',
        'imported'          => ':type นำเข้าแล้ว!',
        'enabled'           => ':type enabled!',
        'disabled'          => ':type disabled!',
    ],

    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'ข้อผิดพลาด: คุณไม่สามารถจัดการบริษัทนี้!',
        'customer'          => 'ข้อผิดพลาด: ผู้ใช้ยังไม่ได้สร้าง! :name ใช้อีเมลนี้แล้ว',
        'no_file'           => 'ข้อผิดพลาด: ไม่ได้เลือกไฟล์!',
        'last_category'     => 'ข้อผิดพลาด: ไม่สามารถลบหมวด :type ล่าสุด!',
        'invalid_apikey'     => 'ข้อผิดพลาด: โทเค็นที่ป้อนไม่ถูกต้อง!',
        'import_column'     => 'Error: :message Sheet name: :sheet. Line number: :line.',
        'import_sheet'      => 'Error: Sheet name is not valid. Please, check the sample file.',
    ],

    'warning' => [
        'deleted'           => 'คำเตือน: คุณไม่ได้รับอนุญาตให้ลบ <b>:name</b> เนื่องจากมี :text ที่เกี่ยวข้อง',
        'disabled'          => 'คำเตือน: คุณไม่ได้รับอนุญาตให้ปิดใช้งาน <b>:name</b> เนื่องจากมี :text ที่เกี่ยวข้อง',
        'disable_code'      => 'Warning: You are not allowed to disable or change the currency of <b>:name</b> because it has :text related.',
    ],

];
