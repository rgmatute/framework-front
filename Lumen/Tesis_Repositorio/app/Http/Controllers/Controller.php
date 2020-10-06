<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
   

     /**
     * @OA\Swagger(
     *     basePath="/repository/api/v1",
     *     schemes={"http"},
     *     @OA\Info(
     *         version="1.0.0",
     *          title="Repositorio de ayuda didactica UG - API",
     *         @OA\Contact(
     *          email="rgmatute91@gmail.com",
     *          name="Soporte"
     *         ),
     *     )
     * )
     */

    /**
    *  @OA\Server(
    *   url="http://localhost/repository/api/v1",
    *   description="Local"
    *  )
    *  @OA\Server(
    *   url="https://app-repository.azurewebsites.net/api/v1",
    *   description="Cloud"
    *  )
    */

    /**
    * @OA\SecurityScheme(
    *       type="apiKey",
    *       securityScheme="api_key",
    *       in="header",
    *       name="Authorization",
    * )
    */
}
