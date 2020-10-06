<?php
    namespace App\Http\Middleware;
    use App\AuthToken\JWToken;
    use App\Helpers\EstadoTransaccion;
    use Closure;

    class ValidarToken{
        public function handle($request, Closure $next){
            $obj_JWToken = new JWToken;
            $et = new EstadoTransaccion();
            $token = $request->header('Authorization');
            if (!$token) {
                $et->error = true;
                $et->code = -1;
                $et->message = [
                    //'user' => 'No se ha enviado ningún token'
                    'user' => 'No se encuentra autenticado'
                ];
                unset($et->data);
            } else {
                $datos = $obj_JWToken->validarToken($token);
                if ($datos['valido']){
                    return $next($request);
                    #->header('Token',$datos['nuevoToken']);
                } else {
                    $et->error = true;
                    $et->code = -2;
                    $et->message = [
                        //'user' => 'El token enviado no es valido'
                        'user' => 'Su sesion ha caducado'
                    ];
                    unset($et->data);
                }
            }
            return response()->json($et);
        }
    }
?>