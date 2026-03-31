<?php

namespace App\Common\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;

class ServerTimeController
{
    public function getServerTime()
    {
        return ApiResponse::success([
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'datetime' => date('Y-m-d H:i:s'),
            'timestamp' => time(),
        ]);
    }
}
