<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Lang;
use App\Http\Controllers\BaseController;

class MenuController extends BaseController
{
    public function listTranslation()
    {
        return response()->json(Lang::get('*'));
    }
}
