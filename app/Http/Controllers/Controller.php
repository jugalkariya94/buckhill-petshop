<?php

namespace App\Http\Controllers;
/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="Buckhill Petshop API",
 *     version="1.0.0",
 *     description="API implementation Buckhill petshop project",
 *     @OA\Contact(
 *       email="jugalkariya@gmail.com",
 *       name="Jugal Kariya"
 *     )
 *   ),
 *   @OA\Components(
 *     @OA\SecurityScheme(
 *       securityScheme="bearerAuth",
 *       type="http",
 *       scheme="bearer",
 *       bearerFormat="JWT"
 *     )
 *   )
 * )
 */
abstract class Controller
{
    //
}
