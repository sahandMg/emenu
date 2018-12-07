<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('vendor/mpdf/mpdf/src/Config/../../tmp'),
    'font_path'             => base_path('vendor/mpdf/mpdf/src/Config/../../ttfonts'),
    'font_data'             => [
        'byekan'            => [
                        'R'=>'Yekan.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
        ]
        ]
];
