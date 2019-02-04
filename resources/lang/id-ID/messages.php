<?php

return [

    'success' => [
        'added'             => ':type ditambahkan!',
        'updated'           => ':type diperbaharui!',
        'deleted'           => ':type dihapus!',
        'duplicated'        => ':type duplikat!',
        'imported'          => ':type diimpor!',
        'enabled'           => ':type enabled!',
        'disabled'          => ':type disabled!',
    ],

    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'Kesalahan: Anda tidak diperbolehkan mengelola perusahaan ini!',
        'customer'          => 'Galat: Pengguna tidak dibuat! :name telah menggunakan alamat email ini.',
        'no_file'           => 'Kesalahan: Tidak ada file dipilih!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Galat: Token yang dimasukkan tidak sah!',
        'import_column'     => 'Error: :message Sheet name: :sheet. Line number: :line.',
        'import_sheet'      => 'Error: Sheet name is not valid. Please, check the sample file.',
    ],

    'warning' => [
        'deleted'           => 'PeringatanL: Anda tidak boleh menghapus <b>:name</b> karena memiliki :text terkaitan.',
        'disabled'          => 'Peringatan: Anda tidak boleh menonaktifkan<b>:name</b> karena memiliki :text terkaitan.',
        'disable_code'      => 'Warning: You are not allowed to disable or change the currency of <b>:name</b> because it has :text related.',
    ],

];
