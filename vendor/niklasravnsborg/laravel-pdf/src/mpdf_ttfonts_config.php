<?php

use Illuminate\Support\Facades\Config;
define('_MPDF_SYSTEM_TTFONTS', Config::get('pdf.font_path'));
$this->fontdata = array_merge($this->fontdata, Config::get('pdf.font_data'));

