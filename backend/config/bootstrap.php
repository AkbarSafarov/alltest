<?php

Yii::$container->set('yii\bootstrap\ActiveField', [
    'options' => ['class' => 'mb-3 col-sm-6'],
    'labelOptions' => ['class' => 'form-label'],
    'checkboxTemplate' => '<div class="form-check mb-2 form-check-primary">{input}{label}{hint}{error}</div>',
    'radioTemplate' => '<div class="form-check mb-2 form-check-primary">{input}{label}{hint}{error}</div>',
]);

Yii::$container->set('yii\widgets\LinkPager', [
    'options' => ['class' => 'pagination'],
    'linkContainerOptions' => ['class' => 'page-item'],
    'linkOptions' => ['class' => 'page-link'],
    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
    'firstPageLabel' => '<i class="fas fa-angle-double-left"></i>',
    'prevPageLabel' => '<i class="fas fa-angle-left"></i>',
    'nextPageLabel' => '<i class="fas fa-angle-right"></i>',
    'lastPageLabel' => '<i class="fas fa-angle-double-right"></i>',
]);

Yii::$container->set('yii\bootstrap\Modal', [
    'headerOptions' => ['class' => 'modal-header flex-row-reverse'],
    'closeButton' => [
        'class' => 'close float-right',
    ],
]);

$font_formats = [
    'Andale Mono' => 'andale mono,times',
    'Arial' => 'arial,helvetica,sans-serif',
    'Arial Black' => 'arial black,avant garde',
    'Book Antiqua' => 'book antiqua,palatino',
    'Georgia' => 'georgia,palatino',
    'Helvetica' => 'helvetica',
    'Montserrat' => 'Montserrat,sans-serif',
    'Open Sans' => 'Open Sans,sans-serif',
    'Raleway' => 'Raleway,sans-serif',
    'Roboto' => 'Roboto,sans-serif',
    'Rubik' => 'Rubik,sans-serif',
    'Ubuntu' => 'Ubuntu,sans-serif',
    'Tahoma' => 'tahoma,arial,helvetica,sans-serif',
    'Terminal' => 'terminal,monaco',
    'Times New Roman' => 'times new roman,times',
    'Trebuchet MS' => 'trebuchet ms,geneva',
    'Verdana' => 'verdana,geneva',
];

Yii::$container->set('alexantr\tinymce\TinyMCE', [
    'clientOptions' => [
        'height' => '300px',
        'relative_urls' => false,
        'convert_urls' => false,
        'paste_data_images' => true,
        
        'force_p_newlines' => false,
        'forced_root_block' => false,
        'table_style_by_css' => true,
        
        'fontsize_formats' => '6px 8px 10px 12px 14px 16px 18px 24px 30px 36px 48px',
        'font_formats' => implode(null, array_map(fn($value, $key) => "$key=$value;", $font_formats, array_keys($font_formats))),
        'content_style' => "@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;600;700;800&family=Raleway:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&family=Rubik:wght@300;400;500;600;700;800;900&family=Ubuntu:wght@300;400;500;700&display=swap'); body {font-family: Montserrat;}",
        
        'textcolor_map' => [
            'DCC4FF', "Main light purple",
            'E9C9F2', "Main light pink",
            'C7DEFF', "Main light blue",
            '4262C2', "Main blue",
            'FFFFFF', "White",
            'FFFFFF', "White",
            'FFFFFF', "White",
            'FFFFFF', "White",
            
            '000000', "Black",
            '993300', "Burnt orange",
            '333300', "Dark olive",
            '003300', "Dark green",
            '003366', "Dark azure",
            '000080', "Navy Blue",
            '333399', "Indigo",
            '333333', "Very dark gray",
            '800000', "Maroon",
            'FF6600', "Orange",
            '808000', "Olive",
            '008000', "Green",
            '008080', "Teal",
            '0000FF', "Blue",
            '666699', "Grayish blue",
            '808080', "Gray",
            'FF0000', "Red",
            'FF9900', "Amber",
            '99CC00', "Yellow green",
            '339966', "Sea green",
            '33CCCC', "Turquoise",
            '3366FF', "Royal blue",
            '800080', "Purple",
            '999999', "Medium gray",
            'FF00FF', "Magenta",
            'FFCC00', "Gold",
            'FFFF00', "Yellow",
            '00FF00', "Lime",
            '00FFFF', "Aqua",
            '00CCFF', "Sky blue",
            'FF99CC', "Pink",
        ],
        
        'plugins' => [
            'advlist autolink lists link charmap print anchor',
            'searchreplace visualblocks code fullscreen',
            'table contextmenu paste textcolor colorpicker advcode',
            'media image formula',
        ],
        'menu' => [],
        'toolbar' => [
            implode(' | ', [
                'undo redo', 'cut copy paste',
                'fontselect', 'fontsizeselect', 'styleselect',
                'removeformat bold italic underline strikethrough superscript subscript',
                'forecolor backcolor',
                'formula',
            ]),
            implode(' | ', [
                'outdent indent',
                'alignleft aligncenter alignright alignjustify',
                'bullist numlist table',
                'link anchor image media', 'charmap',
                'searchreplace visualblocks code fullscreen',
            ]),
        ],
    ],
]);
