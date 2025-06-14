<?php

namespace App\Service;

use App\Service\FileService;

class ServiceProvider {

    public static function getFileService(){
        return new FileService();
    }
}
