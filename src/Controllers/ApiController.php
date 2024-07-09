<?php

namespace LanDao\LaravelCore\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use LanDao\LaravelCore\Traits\ApiResponse;

class ApiController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, ApiResponse;
}
