<?php

return [

    'success' => [
        'added'             => ':type ditambahkan!',
        'updated'           => ':type diperbaharui!',
        'deleted'           => ':type dihapus!',
        'duplicated'        => ':type duplikat!',
        'imported'          => ':type diimpor!',
    ],
    'error' => [
        'over_payment'      => 'Error: Payment not added! Amount passes the total.',
        'not_user_company'  => 'Kesalahan: Anda tidak diperbolehkan mengelola perusahaan ini!',
        'customer'          => 'Error: User not created! :name already uses this email address.',
        'no_file'           => 'Kesalahan: Tidak ada file dipilih!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
    ],
    'warning' => [
        'deleted'           => 'PeringatanL: Anda tidak boleh menghapus <b>:name</b> karena memiliki :text terkaitan.',
        'disabled'          => 'Peringatan: Anda tidak boleh menonaktifkan<b>:name</b> karena memiliki :text terkaitan.',
    ],

];
