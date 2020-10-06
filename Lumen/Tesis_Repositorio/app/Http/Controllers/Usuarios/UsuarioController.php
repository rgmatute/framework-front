<?php

    namespace App\Http\Controllers\Usuarios;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Helpers\EstadoTransaccion;
    use App\AuthToken\JWToken;
    use App\BusinessLayer\Usuarios\User;
    use Validator;
    // validar roles
    use App\Repositories\Usuarios\RoleRepository;

    class UsuarioController extends Controller {
        
        private $et;
        private $user_id;
        private $role_id;

        public function __construct(Request $request){
            $this->et       = new EstadoTransaccion();
            $userInfo = JWToken::userInfo($request);
            $this->user_id = $userInfo->useid ?? 0;
            $this->role_id = $userInfo->roleid ?? 0;
        }

        public function store(Request $request){
            try {
                $user   = new User();

                $request = json_decode($request->getContent(), true);

                $this->et = $this->validarData($request);
            
                if($this->et->error) {
                    throw new \Exception("Error de validación de datos");
                }

                $roleRepo = new RoleRepository();
                $roleById = $roleRepo->getById($this->role_id);
                $roleByName = $roleRepo->getByName('ESTUDIANTE');
                if(isset($roleById->name) && $roleById->name === 'ADMINISTRADOR'){
                    $request['role_id'] = $request['role_id'];
                }else{
                    $request['role_id'] = $roleByName->id ?? 3;
                }

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

        /**
        * @OA\Get(
        *  tags={"Mantenimiento"},
        *   path="/user",
        *   summary="Mostrar todos los usuarios",
        *   @OA\Response(
        *     response=200,
        *     description="Lista de Usuarios",
        *     @OA\JsonContent(
        *       type="object",
        *       @OA\Property(property="code", type="integer"),
        *       @OA\Property(property="error", type="boolean", example=false),
        *       @OA\Property(property="message", type="string"),
        *       @OA\Property(property="data", type="array", collectionFormat="multi",@OA\Items(type="object")),
        *     ),
        *   ),
        *   security={{ "api_key": {} }}
        * )
        */
        public function getAll(){
            try {
                $user   = new User();

                $this->et = $user->getAll();

            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->getAll : ' . $e->getMessage());
                if( !is_array($this->et->message) ) {
                    $this->et->message = [
                        'user' => $e->getMessage()
                    ];
                }
            }

            return response()->json($this->et);
        }

        public function getById($id){
            try {
                $user   = new User();

                $this->et = $user->getById($id);

            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->getById : ' . $e->getMessage());
                if( !is_array($this->et->message) ) {
                    $this->et->message = [
                        'user' => $e->getMessage()
                    ];
                }
            }

            return response()->json($this->et);
        }

        public function update(Request $request){
            try {
                $user   = new User();

                $request = json_decode($request->getContent(), true);

                $this->et = $this->validarData($request);
            
                if($this->et->error) {
                    throw new \Exception("Error de validación de datos");
                }

                $this->et = $user->update($request);

                unset($this->et->data);

            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->update : ' . $e->getMessage());
                if( !is_array($this->et->message) ) {
                    $this->et->message = [
                        'user' => $e->getMessage()
                    ];
                }
            }

            return response()->json($this->et);
        }

        
        public function delete($id){
            try {
                $user   = new User();

                $this->et = $user->changeStatus($id, 0);

                unset($this->et->data);
            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->delete : ' . $e->getMessage());
                if( !is_array($this->et->message) ) {
                    $this->et->message = [
                        'user' => $e->getMessage()
                    ];
                }
            }

            return response()->json($this->et);
        }

        public function activate($id){
            try {
                $user   = new User();

                $this->et = $user->changeStatus($id, 1);

                unset($this->et->data);
            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->delete : ' . $e->getMessage());
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
                'email'         => 'required|max:255',
                'password'      => 'required|max:20|min:5',
                'firstname'     => 'required|max:50',
                'lastname'      => 'required|max:50',
                'phone'         => 'required|max:15',
                'address'       => 'required|max:50',
                'status'        => 'required|max:1',
                'role_id'       => 'required|max:1'
            ];
    
            $validacion = Validator::make($request, $reglas);
    
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