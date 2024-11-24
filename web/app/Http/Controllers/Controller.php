<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="AparcApp API Documentation",
 *    version="1.0.0",
 * )
 * @OA\Tag(
 *     name="Parking",
 *     description="Operations about parking"
 *  ),
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
