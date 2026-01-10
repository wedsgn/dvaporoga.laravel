<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FormatData;
use App\Services\UploadFiles;

class BaseController extends Controller
{
    public $upload_service;
    public $format_data_service;

    public function __construct(UploadFiles $upload_service, FormatData  $format_data_service)
    {
        $this->upload_service = $upload_service;
        $this->format_data_service = $format_data_service;
    }

}
