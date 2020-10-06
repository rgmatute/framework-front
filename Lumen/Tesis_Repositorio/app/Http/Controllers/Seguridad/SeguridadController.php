<?php

    namespace App\Http\Controllers\Seguridad;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Helpers\EstadoTransaccion;
    use App\AuthToken\JWToken;
    use App\BusinessLayer\Seguridad\Seguridad;
    use Validator;
    use App\BusinessLayer\Usuarios\User;
    use App\Repositories\Usuarios\RoleRepository;

    class SeguridadController extends Controller {
        
        private $et;
        private $user_id;
        private $role_id;

        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct(Request $request){
            $this->et       = new EstadoTransaccion();
            $userInfo = JWToken::userInfo($request);
            $this->user_id = $userInfo->useid ?? 0;
            $this->role_id = $userInfo->roleid ?? 0;
        }

        /**
        * @OA\Post(
        *  tags={"Seguridad"},
        *   path="/security/accounts/login",
        *   summary="Autenticarse para obtener el Token",
        *   @OA\Response(
        *     response=200,
        *     description="Informacion de Usuario Auntenticado",
        *     @OA\JsonContent(
        *       type="object",
        *       @OA\Property(property="code", type="integer"),
        *       @OA\Property(property="error", type="boolean", example=false),
        *       @OA\Property(property="message", type="string"),
        *       @OA\Property(property="data", type="object", @OA\Property(property="Token", type="string"), @OA\Property(property="Informacion", type="object")),
        *     ),
        *   ),
        *   @OA\Response(
        *     response="default",
        *     description="Formato de Error",
        *     @OA\JsonContent(
        *       type="object",
        *       @OA\Property(property="code", type="integer", example=-1),
        *       @OA\Property(property="error", type="boolean"),
        *       @OA\Property(property="message", type="object", @OA\Property(property="user", type="string")),
        *     ),
        *   ),
        *    @OA\RequestBody(
        *       @OA\JsonContent(
        *       required={"email","password"},
        *           type="object",
        *           @OA\Property(property="email", type="string",format="email"),
        *           @OA\Property(property="password", type="string")
        *       )
        *    ),

        * )
        */
        public function login(Request $request){
            try {
                $objSeguridad   = new Seguridad();
                $objJWToken     = new JWToken();
                $request = json_decode($request->getContent(), true);

                $this->et = $this->validarData($request);
            
                if($this->et->error) {
                    throw new \Exception("Error de validación de datos " . $this->et->message);
                }

                $email      = $request['email'];
                $password   = $request['password'];

                $this->et = $objSeguridad->userInfo($email,$password);

                if($this->et->error) {
                    // throw new \Exception('Error controlado. Generado desde un repository o una BLL');
                    throw new \Exception($this->et->message);
                }

                $userInfo = $this->et->data;
                $codUsuario = $userInfo->id;
                $codRole = $userInfo->role_id;

                $token = $objJWToken->generarToken($codUsuario, $codRole);

                unset($userInfo->id);
                unset($userInfo->password);
                unset($userInfo->role_id);

                $this->et->data =  [
                    'Token'       => $token,
                    'Informacion' => $userInfo
                ];

            } catch (\Exception $e) {
                $this->et->error = true;
                $this->et->code = -2;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->login : ' . $e->getMessage());
                if( !is_array($this->et->message) ) {
                    $this->et->message = [
                        'user' => $e->getMessage()//EstadoTransaccion::$procesoErroneo
                    ];
                }
            }
            return response()->json($this->et);
        }

        /**
        * @OA\Get(
        *  tags={"Seguridad"},
        *   path="/security/accounts/logout",
        *   summary="Cerrar Sesion",
        *   @OA\Response(
        *     response=200,
        *     description="Respuesta",
        *     @OA\JsonContent(
        *       type="object",
        *       @OA\Property(property="code", type="integer"),
        *       @OA\Property(property="error", type="boolean", example=false),
        *       @OA\Property(property="message", type="string"),
        *       @OA\Property(property="data", type="object", @OA\Property(property="Token", type="string"), @OA\Property(property="Informacion", type="object")),
        *     ),
        *   ),
        *   @OA\Response(
        *     response="default",
        *     description="Formato de Error",
        *     @OA\JsonContent(
        *       type="object",
        *       @OA\Property(property="code", type="integer", example=-1),
        *       @OA\Property(property="error", type="boolean"),
        *       @OA\Property(property="message", type="object", @OA\Property(property="user", type="string")),
        *     ),
        *   ),
        *   security={{ "api_key": {} }}
        * )
        */
        public function logout(Request $request){
            try {
                $obj_JWToken = new JWToken();
                $token = $request->header('Authorization');
                
                $obj_JWToken->destruirToken($token, null, 0);
                
                $this->et->message = 'Sesión cerrada exitósamente';
                unset($this->et->data);

            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                $messageLog = utf8_encode('Error: ' . className($this) . '->logout : ' . $e->getMessage());
                if( !is_array($this->et->message) ) {
                    $this->et->message = [
                        'user' => EstadoTransaccion::$procesoErroneo
                    ];
                }
            }
            return response()->json($this->et);
        }

        public function register(Request  $request){
            try {
                $user   = new User();

                $request = json_decode($request->getContent(), true);

                $this->et = $this->validarDataUser($request);
            
                if($this->et->error) {
                    throw new \Exception("Error de validación de datos");
                }

                $roleRepo = new RoleRepository();
                $roleByName = $roleRepo->getByName('ESTUDIANTE');

                if(!isset($roleByName->id)) {
                    throw new \Exception($this->et::$procesoErroneo);
                }

                $request['role_id'] = $roleByName->id;

                $this->et = $user->register($request);

                unset($this->et->data);

            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->register : ' . $e->getMessage());
                if( !is_array($this->et->message) ) {
                    $this->et->message = [
                        'user' => $e->getMessage()
                    ];
                }
            }

            return response()->json($this->et);
        }

        public function modificarCredencial(Request $request){
            try {
                $objSeguridad   = new Seguridad();

                $current_password = $request->current_password;
                $new_password = $request->new_password;

                $this->et = $objSeguridad->passwordUpdate($this->user_id, $current_password, $new_password);

                unset($this->et->data);

            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->modificarCredencial : ' . $e->getMessage());
                if( !is_array($this->et->message) ) {
                    $this->et->message = [
                        'user' => $e->getMessage()
                    ];
                }
            }

            return response()->json($this->et);
        }

        private function validarData($request){

            $this->et = new EstadoTransaccion();
    
            $reglas = [
                'email'         => 'required|max:50|min:10',
                'password'      => 'required|max:20|min:5'
            ];
    
            $validacion = Validator::make($request, $reglas,[
                'email.required'    =>  'email es requerido',
                'password.required' =>  'password es requerido',
                'email.min'         =>  'email, mínimo 10 caracteres',
                'email.max'         =>  'email, máximo 50 caracteres',
                'password.min'      =>  'mínimo 5 caracteres para la contraseña',
                'password.max'      =>  'máximo 20 caracteres para la contraseña'
            ]);
    
            $errores = '';
            if ($validacion->fails()){
                $this->et->error = true;
                $this->et->code = -1;
                foreach ($validacion->messages()->all() as $mensaje) {
                    if(empty($errores)){
                        $errores .= $mensaje;
                    }else{
                        $errores .= ' | ' .$mensaje;
                    }
                }
                $this->et->message = '[ '.$errores.' ]';
            }
            return $this->et;
        }

        private function validarDataUser($request){

            $this->et = new EstadoTransaccion();
    
            $reglas = [
                'email'         => 'required|max:50|min:10',
                'password'      => 'required|max:20|min:5',
                'firstname'     => 'required|max:100',
                'lastname'      => 'required|max:100',
                'phone'         => 'required|max:10'
            ];
    
            $validacion = Validator::make($request, $reglas, [
                'email.required'    =>  'email es requerido',
                'password.required' =>  'password es requerido',
                'email.min'         =>  'email, mínimo 10 caracteres',
                'email.max'         =>  'email, máximo 50 caracteres',
                'password.min'      =>  'mínimo 5 caracteres para la contraseña',
                'password.max'      =>  'máximo 20 caracteres para la contraseña',
                'firstname.required'    =>  'firstname es requerido',
                'lastname.required'     =>  'lastname es requerido',
                'phone.required'        =>  'phone es requerido',
                'phone.max'             =>  'phone , máximo 10 caracteres'
            ]);
    
            $errores = '';
            if ($validacion->fails()){
                $this->et->error = true;
                $this->et->code = -1;
                foreach ($validacion->messages()->all() as $mensaje) {
                    if(empty($errores)){
                        $errores .= $mensaje;
                    }else{
                        $errores .= ' | ' .$mensaje;
                    }
                }
                $this->et->message = '[ '.$errores.' ]';
            }
            return $this->et;
        }

    }
