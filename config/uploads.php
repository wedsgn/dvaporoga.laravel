<?php

return [
    'image' => [
        'max_kb' => 20 * 1024,
        'label' => '20 МБ',
        'mimes' => ['jpeg', 'jpg', 'png', 'gif', 'svg', 'webp'],
    ],

    'editor_image' => [
        'max_kb' => 5 * 1024,
        'label' => '5 МБ',
        'mimes' => ['jpeg', 'jpg', 'png', 'gif', 'webp'],
    ],

    'import' => [
        'max_kb' => 100 * 1024,
        'label' => '100 МБ',
        'mimes' => ['xlsx', 'xls', 'csv'],
    ],

    'php' => [
        'upload_max_filesize' => '100M',
        'post_max_size' => '128M',
        'post_max_label' => '128 МБ',
        'memory_limit' => '4096M',
        'max_file_uploads' => 50,
    ],
];
