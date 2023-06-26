<?php

return [
    'input_types' => [
        'text_input' => 'Текстовое поле',
        'text_area' => 'Текстовая область',
        'checkbox' => 'Чекбокс',
        'select' => 'Выбор из списка',
        'select2_ajax' => 'Выбор из списка (AJAX)',
        'elfinder' => 'Файловый менеджер',
        'tinymce' => 'Текстовый редактор',
        'files' => 'Файлы',
        'groups' => 'Группы',
    ],
    
    'extensions' => [
        'application' => ['doc', 'docx', 'rtf', 'xls', 'xlsx', 'pdf'], 
        'audio' => ['mp3', 'ogg'],
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg+xml'],
        'text' => ['txt', 'csv'],
        'video' => ['mp4', 'flv'],
    ],
    
    'socials' => [
        'instagram' => 'instagram',
        'facebook' => 'facebook',
        'youtube' => 'youtube-fill',
        'telegram' => 'telegram',
    ],
    
    'payment_connections' => [
        'paycom' => [
            'merchant_id' => '60ec737cee7a276dd137cd46',
            'secret_tk' => 'E8t285@juXRtTD9hH7zfUO&oQpdGjwKA0bek',
            'secret_pk' => '&JC1mHpsC1ZgiJcy4JAp%QrO3ScMyNxUdCer',
        ],
        'click' => [
            'merchant_id' => '13513',
            'service_id' => '18965',
            'user_id' => '21248',
            'secret_key' => 'oDhXPjrppE5vbIW',
        ],
    ],
    
    'date_formats' => [
        'datetime' => [
            'formats' => [
                'afterFind' => 'd.m.Y H:i',
                'beforeSave' => 'Y-m-d H:i:s',
                'beforeSearch' => 'Y-m-d',
                'afterSearch' => 'd.m.Y',
            ],
            'attributes' => [
                'created_at', 'updated_at', 'published_at_from', 'published_at_to', 'datetime',
                'last_visit', 'last_activity', 'demo_datetime_to',
            ],
        ],
        'date' => [
            'formats' => [
                'afterFind' => 'd.m.Y',
                'beforeSave' => 'Y-m-d',
                'beforeSearch' => 'Y-m-d',
                'afterSearch' => 'd.m.Y',
            ],
            'attributes' => [
                'date', 'date_from', 'date_to',
                'status_activation_date', 'birth_date',
            ],
        ],
        'time' => [
            'formats' => [
                'afterFind' => 'H:i',
                'beforeSave' => 'H:i:s',
                'beforeSearch' => 'H:i',
                'afterSearch' => 'H:i',
            ],
            'attributes' => [
                'time',
            ],
        ],
        'month' => [
            'formats' => [
                'afterFind' => 'm.Y',
                'beforeSave' => 'Y-m',
                'beforeSearch' => 'Y-m',
                'afterSearch' => 'm.Y',
            ],
            'attributes' => [
                'month',
            ],
        ],
    ],
];
