<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Traits\ApiResponse;

abstract class BaseController extends Controller
{
    use ApiResponse;
}
