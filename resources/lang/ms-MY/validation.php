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
    'accepted_if' => ':attribute mesti diterima apabila :other ialah :value.',
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
    'declined' => ':attribute mesti ditolak.',
    'declined_if' => ':attribute mesti ditolak apabila :other ialah :value.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => ':attribute harus terdiri dari :digits angka.',
    'digits_between' => ':attribute harus terdiri dari :min sampai :max angka.',
    'dimensions' => ':attribute tidak memiliki dimensi gambar yang valid.',
    'distinct' => ':attribute memiliki nilai yang duplikat.',
    'doesnt_start_with' => ':attribute tidak boleh bermula dengan salah satu daripada yang berikut: :values.',
    'double' => ':attribute harus berupa angka desimal yang valid.',
    'email' => ':attribute mesti alamat e-mel yang sah.',
    'ends_with' => ':attribute harus diakhiri salah satu dari berikut: :values',
    'enum' => ':attribute yang dipilih tidak sah.',
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
    'in_detailed' => 'Nilai :attribute ":value" tidak valid. Diharapkan salah satu dari: :values',
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
    'mac_address' => ':attribute mesti alamat MAC yang sah.',
    'max' => [
        'array' => ':attribute tidak boleh mempunyai lebih daripada :max item.',
        'file' => ':attribute mesti tidak melebihi :max kilobait.',
        'numeric' => ':attribute mesti tidak melebihi :max.',
        'string' => ':attribute mesti tidak melebihi :max aksara.',
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
        'letters' => ':attribute mesti mengandungi sekurang-kurangnya satu huruf.',
        'mixed' => ':attribute mesti mengandungi sekurang-kurangnya satu huruf besar dan satu huruf kecil.',
        'numbers' => ':attribute mesti mengandungi sekurang-kurangnya satu nombor.',
        'symbols' => ':attribute mesti mengandungi sekurang-kurangnya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Sila pilih :attribute yang berbeza.',
    ],
    'present' => ':attribute wajib ada.',
    'prohibited' => 'Medan :attribute dilarang.',
    'prohibited_if' => 'Medan :attribute dilarang jika :other ialah :value.',
    'prohibited_unless' => 'Medan :attribute dilarang melainkan :other berada dalam :values.',
    'prohibits' => 'Medan :attribute melarang :other daripada wujud.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute wajib diisi.',
    'required_array_keys' => 'Medan :attribute mesti mengandungi entri untuk: :values.',
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
            'rule-name' => 'mesej-tersuai',
        ],
        'invalid_currency'      => 'Kod :attribute tidak sah.',
        'invalid_amount'        => 'Jumlah :attribute tidak sah.',
        'invalid_quantity'      => ':attribute bukan ungkapan matematik yang sah.',
        'invalid_extension'     => 'Sambungan fail tidak sah.',
        'invalid_dimension'     => 'Dimensi :attribute mesti maksimum :width x :height px.',
        'invalid_colour'        => 'Warna :attribute tidak sah.',
        'invalid_payment_method'=> 'Kaedah bayaran tidak sah.',
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
