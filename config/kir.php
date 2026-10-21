<?php

return [
    /*
    | Column names must match the existing public.datapengujian table.
    | Confirm the chassis column name before enabling lookup in production.
    */
    'table' => env('KIR_TABLE', 'datapengujian'),
    'columns' => [
        'idx' => env('KIR_COL_ID', 'idx'),
        'test_number' => env('KIR_COL_TEST_NUMBER', 'nouji'),
        'chassis' => env('KIR_COL_CHASSIS', 'nomorrangka'),
        'vehicle_name' => env('KIR_COL_NAME', 'nama'),
        'vehicle_type' => env('KIR_COL_TYPE', 'jenis'),
        'brand' => env('KIR_COL_BRAND', 'merk'),
        'model' => env('KIR_COL_MODEL', 'tipe'),
        'year' => env('KIR_COL_YEAR', 'tahunpembuatan'),
        'color' => env('KIR_COL_COLOR', 'warna'),
        'test_date' => env('KIR_COL_TEST_DATE', 'tanggalpengujian'),
        'valid_until' => env('KIR_COL_VALID_UNTIL', 'masaberlaku'),
        'publication_status' => env('KIR_COL_PUBLICATION_STATUS', 'statuspenerbitan'),
    ],
];
