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

    'accepted' => 'Data :attribute harus diterima.',
    'accepted_if' => 'Data :attribute harus diterima jika :other adalah :value.',
    'active_url' => 'Data :attribute harus berupa URL yang valid.',
    'after' => 'Data :attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => 'Data :attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => 'Data :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Data :attribute hanya boleh berisi huruf, angka, tanda pisah, dan garis bawah.',
    'alpha_num' => 'Data :attribute hanya boleh berisi huruf dan angka.',
    'array' => 'Data :attribute harus berupa array.',
    'ascii' => 'Data :attribute hanya boleh berisi karakter dan simbol alfanumerik byte tunggal.',
    'before' => 'Data :attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => 'Data :attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'diantara' => [
        'array' => 'Data :attribute harus memiliki item antara :min dan :max.',
        'file' => 'Data :attribute harus antara :min dan :max kilobyte.',
        'numeric' => 'Data :attribute harus antara :min dan :max.',
        'string' => 'Data :attribute harus antara :min dan :max karakter.',
    ],
    'boolean' => 'Data :attribute harus benar atau salah.',
    'confirmed' => 'Konfirmasi kolom :attribute tidak cocok.',
    'current_password' => 'Kata sandi salah.',
    'date' => 'Data :attribute harus berupa tanggal yang valid.',
    'date_equals' => 'Data :attribute harus berupa tanggal yang sama dengan tanggal :date.',
    'date_format' => 'Data :attribute harus cocok dengan format :format.',
    'decimal' => 'Data :attribute harus memiliki :decimal tempat desimal.',
    'declined' => 'Data :attribute harus ditolak.',
    'declined_if' => 'Data :attribute harus ditolak jika :other adalah :value.',
    'different' => 'Data :attribute dan :other harus berbeda.',
    'digits' => 'Data :attribute harus :digits angka.',
    'digits_between' => 'Data :attribute harus antara angkat :min dan :max.',
    'dimensions' => 'Data :attribute memiliki dimensi gambar yang tidak valid.',

    'distinct' => 'Data :attribute memiliki nilai duplikat.',
    'doesnt_end_with' => 'Data :attribute tidak boleh diakhiri dengan salah satu dari berikut: :values.',
    'doesnt_start_with' => 'Data :attribute tidak boleh dimulai dengan salah satu dari yang berikut: :values.',
    'email' => 'Data :attribute harus berupa alamat email yang valid.',
    'ends_with' => 'Data :attribute harus diakhiri dengan salah satu dari yang berikut: :values.',
    'enum' => ':attribute yang dipilih tidak valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'file' => 'Data :attribute harus berupa file.',
    'filled' => 'Data :attribute harus memiliki nilai.',
    'gt' => [
        'array' => 'Data :attribute harus memiliki lebih dari :value item.',
        'file' => 'Data :attribute harus lebih besar dari :value kilobyte.',
        'numeric' => 'Data :attribute harus lebih besar dari :value.',
        'string' => 'Data :attribute harus lebih besar dari :value karakter.',
    ],
    'gte' => [
        'array' => 'Data :attribute harus memiliki :value item atau lebih.',
        'file' => 'Data :attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'numeric' => 'Data :attribute harus lebih besar dari atau sama dengan :value.',
        'string' => 'Data :attribute harus lebih besar dari atau sama dengan :value karakter.',
    ],
    'image' => 'Data :attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'in_array' => 'Data :attribute harus ada di :other.',
    'integer' => 'Data :attribute harus berupa bilangan bulat.',
    'ip' => 'Data :attribute harus berupa alamat IP yang valid.',
    'ipv4' => 'Data :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => 'Data :attribute harus berupa alamat IPv6 yang valid.',
    'json' => 'Data :attribute harus berupa string JSON yang valid.',
    'lowercase' => 'Data :attribute harus berupa huruf kecil.',
    'lt' => [
        'array' => 'Data :attribute harus memiliki item kurang dari :value.',
        'file' => 'Data :attribute harus kurang dari :value kilobyte.',
        'numeric' => 'Data :attribute harus lebih kecil dari :value.',
        'string' => 'Data :attribute harus lebih kecil dari :value karakter.',
    ],
    'lte' => [
        'array' => 'Data :attribute tidak boleh memiliki lebih dari :value item.',
        'file' => 'Data :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'numeric' => 'Data :attribute harus kurang dari atau sama dengan :value.',
        'string' => 'Data :attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => 'Data :attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'array' => 'Data :attribute tidak boleh memiliki lebih dari :max item.',
        'file' => 'Data :attribute tidak boleh lebih besar dari :max kilobyte.',
        'numeric' => 'Data :attribute tidak boleh lebih besar dari :max.',
        'string' => 'Data :attribute tidak boleh lebih besar dari :max karakter.',
    ],
    'max_digits' => 'Data :attribute tidak boleh lebih dari :max digit.',
    'mimes' => 'Data :attribute harus berupa file bertipe: :values.',
    'mimetypes' => 'Data :attribute harus berupa file bertipe: :values.',
    'min' => [
        'array' => 'Data :attribute harus memiliki setidaknya :min item.',
        'file' => 'Data :attribute minimal harus :min kilobyte.',
        'numeric' => 'Data :attribute minimal harus :min.',
        'string' => 'Data :attribute minimal harus :min karakter.',
    ],
    'min_digits' => 'Data :attribute harus memiliki setidaknya :min digit.',
    'missing' => 'Data :attribute harus tidak ada.',
    'missing_if' => 'Data :attribute harus hilang ketika :other adalah :value.',
    'missing_unless' => 'Data :attribute harus hilang kecuali :other adalah :value.',
    'missing_with' => 'Data :attribute harus hilang saat :values ada.',
    'missing_with_all' => 'Data :attribute harus hilang saat :values ada.',
    'multiple_of' => 'Data :attribute harus merupakan kelipatan dari :value.',
    'not_in' => ':attribute yang dipilih tidak valid.',
    'not_regex' => 'Format data :attribute tidak valid.',
    'numeric' => 'Data :attribute harus berupa angka.',
    'password' => [
        'letters' => 'Nilai :attribute harus berisi setidaknya satu huruf.',
        'mixed' => 'Nilai :attribute harus berisi setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => 'Nilai :attribute harus berisi setidaknya satu angka.',
        'symbols' => 'Nilai :attribute harus berisi setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Harap pilih :atribut yang berbeda.',
    ],
    'present' => 'Nilai :attribute harus ada.',
    'prohibited' => 'Nilai :attribute dilarang.',
    'prohibited_if' => 'Nilai :attribute dilarang jika :other adalah :value.',
    'prohibited_unless' => 'Nilai :attribute dilarang kecuali :other ada di :values.',
    'prohibits' => 'Nilai :attribute melarang :other telah ada.',
    'regex' => 'Format data :attribute tidak valid.',
    'required' => 'Data :attribute wajib diisi.',
    'required_array_keys' => 'Data :attribute harus berisi entri for: :values.',
    'required_if' => 'Data :attribute diperlukan saat :other adalah :value.',
    'required_if_accepted' => 'Data :attribute diperlukan saat :other diterima.',
    'required_unless' => 'Data :attribute diperlukan kecuali :other ada di :values.',
    'required_with' => 'Kolom :attribute diperlukan saat :values ada.',
    'required_with_all' => 'Data :attribute diperlukan saat :values ada.',
    'required_without' => 'Data :attribute diperlukan saat :values tidak ada.',
    'required_without_all' => 'Data :attribute diperlukan jika tidak ada :values yang ada.',
    'same' => 'Data :attribute harus sesuai dengan :other.',
    'size' => [
        'array' => 'Data :attribute harus berisi :size item.',
        'file' => 'Data :attribute harus berukuran :size kilobytes.',
        'numeric' => 'Data :attribute harus berukuran :size.',
        'string' => 'Data :attribute harus berukuran :size karakter.',
    ],
    'starts_with' => 'Data :attribute harus dimulai dengan salah satu dari yang following: :values.',
    'string' => 'Data :attribute harus berupa string.',
    'timezone' => 'Data :attribute harus berupa zona waktu yang valid.',
    'unique' => ':attribute telah digunakan.',
    'uploaded' => ':attribute gagal diupload.',
    'uppercase' => 'Data :attribute harus huruf besar.',
    'url' => 'Data :attribute harus berupa URL yang valid.',
    'ulid' => 'Data :attribute harus berupa ULID yang valid.',
    'uuid' => 'Data :attribute harus berupa UUID yang valid.',

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
            'rule-name' => 'custom-message',
        ],
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
