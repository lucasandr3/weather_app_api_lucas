<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Weather API",
 *     version="1.0",
 *     description="Documentação da API do projeto Weather",
 *     @OA\Contact(
 *         name=" - Lucas Vieira",
 *         email="lucasvieiraandrade58@gmail.com"
 *     ),
 * ),
 * @OA\SecurityScheme(
 *       securityScheme="bearerAuth",
 *       type="http",
 *       scheme="bearer",
 *       bearerFormat="JWT"
 *  )
 */
abstract class Controller
{
    //
}
