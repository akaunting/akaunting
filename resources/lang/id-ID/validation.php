<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute harus diterima.',
    'accepted_if' => 'Atribut :attribute harus diterima ketika atribute :other memiliki nilai :value.',
    'active_url' => ':attribute bukan URL yang valid.',
    'after' => ':attribute harus berisi tanggal setelah :date.',
    'after_or_equal' => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'array' => ':attribute harus berisi sebuah array.',
    'before' => ':attribute harus berisi tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => ':attribute harus memiliki antara :min sampai :max item.',
        'file' => ':attribute harus berukuran antara :min sampai :max kilobita.',
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'string' => ':attribute harus berisi antara :min sampai :max karakter.',
    ],
    'boolean' => ':attribute harus bernilai true atau false',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi tidak benar.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_equals' => ':attribute harus berupa sebuah tanggal yang sama dengan :date.',
    'date_format' => ':attribute tidak cocok dengan format :format.',
    'declined' => 'Atribut :attribute harus di tolak',
    'declined_if' => 'Atribut :attribute harus ditolak ketika atribut :other memiliki nilai :value.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => ':attribute harus terdiri dari :digits angka.',
    'digits_between' => ':attribute harus terdiri dari :min sampai :max angka.',
    'dimensions' => ':attribute tidak memiliki dimensi gambar yang valid.',
    'distinct' => ':attribute memiliki nilai yang duplikat.',
    'doesnt_start_with' => 'Atribute :attribute tidak boleh dimulai dengan salah satu nilai dari berikut ini: :values.',
    'email' => ':attribute harus berupa alamat surel yang valid.',
    'ends_with' => ':attribute harus diakhiri salah satu dari berikut: :values',
    'enum' => 'Atribut :attribute tidak valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'file' => ':attribute harus berupa sebuah berkas.',
    'filled' => ':attribute harus memiliki nilai.',
    'gt' => [
        'array' => ':attribute harus memiliki lebih dari :value item.',
        'file' => ':attribute harus lebih besar dari :value kilobytes.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string' => ':attribute harus lebih besar dari :value karakter.',
    ],
    'gte' => [
        'array' => ':attribute harus memiliki :value item atau lebih.',
        'file' => ':attribute harus lebih besar dari atau sama dengan :value kilobytes.',
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'string' => ':attribute harus lebih besar dari atau sama dengan :value karakter.',
    ],
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'in_array' => ':attribute tidak ada di dalam :other.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'ip' => ':attribute harus berupa alamat IP yang valid.',
    'ipv4' => ':attribute harus berupa sebuah alamat IPv4 yang valid.',
    'ipv6' => ':attribute harus berupa sebuah alamat IPv6 yang valid.',
    'json' => ':attribute harus berupa JSON string yang valid.',
    'lt' => [
        'array' => ':attribute harus kurang dari :value item.',
        'file' => ':attribute harus kurang dari :value kilobytes.',
        'numeric' => ':attribute harus kurang dari :value.',
        'string' => ':attribute harus kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => ':attribute tidak boleh memiliki lebih dari :value item.',
        'file' => ':attribute harus kurang dari atau sama dengan :value kilobytes.',
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'string' => ':attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => 'Atribut :attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'array' => ':attribute harus tidak memiliki lebih dari :max item.',
        'file' => ':attribute maksimal berukuran :max kilobita.',
        'numeric' => ':attribute maskimal bernilai :max.',
        'string' => ':attribute maskimal berisi :max karakter.',
    ],
    'mimes' => ':attribute harus berupa berkas berjenis: :values.',
    'mimetypes' => ':attribute harus berupa berkas berjenis: :values.',
    'min' => [
        'array' => ':attribute harus memiliki setidaknya :min item.',
        'file' => ':attribute minimal berukuran :min kilobita.',
        'numeric' => ':attribute minimal bernilai :min.',
        'string' => ':attribute minimal berisi :min karakter.',
    ],
    'multiple_of' => ':attribute harus berupa sebuah penggandaan dari: :value.',
    'not_in' => ':attribute yang dipilih tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'password' => [
        'letters' => 'Atribut :attribute harus berisi setidaknya 1 huruf.',
        'mixed' => 'Atribut :attribute harus berisi setidaknya 1 huruf besar dan 1 huruf kecil.',
        'numbers' => 'Atribut :attribute harus berisi setidaknya 1 angka.',
        'symbols' => 'Atribut :attribute harus berisi setidaknya 1 simbol.',
        'uncompromised' => ':attributre yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present' => ':attribute wajib ada.',
    'prohibited' => 'Bidang :attribute dilarang.',
    'prohibited_if' => 'Bidang :attribute dilarang bila :other adalah :value.',
    'prohibited_unless' => 'Bidang :attribute dilarang kecuali :other di dalam :values.',
    'prohibits' => 'Atribut :attribute melarang atribut :other untuk muncul.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute wajib diisi.',
    'required_array_keys' => 'Atribut :attribute mesti berisi nilai-nilai untuk :values.',
    'required_if' => ':attribute wajib diisi bila :other adalah :value.',
    'required_unless' => ':attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with' => ':attribute wajib diisi bila terdapat :values.',
    'required_with_all' => ':attribute wajib diisi bila terdapat :values.',
    'required_without' => ':attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => ':attribute wajib diisi bila sama sekali tidak terdapat :values.',
    'same' => ':attribute dan :other harus sama.',
    'size' => [
        'array' => ':attribute harus memiliki :size item.',
        'file' => ':attribute harus berukuran :size kilobyte.',
        'numeric' => ':attribute harus berukuran :size.',
        'string' => ':attribute harus berukuran :size karakter.',
    ],
    'starts_with' => ':attribute harus dimulai dengan salah satu dari berikut: :values.',
    'string' => ':attribute harus berupa string.',
    'timezone' => ':attribute harus berisi zona waktu yang valid.',
    'unique' => ':attribute sudah ada sebelumnya.',
    'uploaded' => ':attribute gagal diunggah.',
    'url' => 'Format :attribute tidak valid.',
    'uuid' => ':attribute harus berupa sebuah UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'kustomisasi-pesan',
        ],
        'invalid_currency'      => ':attribute kode tidak valid.',
        'invalid_amount'        => 'Jumlah :attribute tidak valid.',
        'invalid_extension'     => 'Ekstensi file tidak valid',
        'invalid_dimension'     => 'Maksimal dimensi atribut :attribute adalah :width x :height px.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
