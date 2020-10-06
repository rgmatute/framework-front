<?php

    namespace App\Http\Controllers\Publicaciones;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Helpers\EstadoTransaccion;
    use App\AuthToken\JWToken;
    use App\BusinessLayer\Publicaciones\Publicaciones;
    use Validator;

    class PublicacionesController extends Controller {
        
        private $et;
        private $user_id;
        private $role_id;

        public function __construct(Request $request){
            $this->et       = new EstadoTransaccion();
            $userInfo = JWToken::userInfo($request);
            $this->user_id = $userInfo->useid ?? 0;
            $this->role_id = $userInfo->roleid ?? 0;
        }

        public function getAllApproved(){
            try {
                $publicaciones   = new Publicaciones();
                $this->et = $publicaciones->getAllApproved();
            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->getAllApproved : ' . $e->getMessage());
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
                $publicaciones   = new Publicaciones();
                $this->et = $publicaciones->getAll();
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

        public function store(Request $request){
            try {
                $publicaciones   = new Publicaciones();
                $request = json_decode($request->getContent(), true);
                $this->et = $this->validarData($request);

                if($this->et->error) {
                    throw new \Exception("Error de validación de datos " . $this->et->message);
                }

                $this->et = $publicaciones->register($request, $this->user_id);
            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->store : ' . $e->getMessage());
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
                $publicaciones   = new Publicaciones();
                $this->et = $publicaciones->delete($id);
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
                'title'             => 'required|min:1',
                'description'       => 'required',
                'image'             => 'required',
                'video'             => 'required',
                'tool'              => 'required',
                'recommendation'    => 'required',
                'id_clasificacion_discapacidad' => 'required',
                'id_tipo_discapacidad'          => 'required',
                'id_discapacidad'   => 'required'
            ];
    
            $validacion = Validator::make($request, $reglas,[
                'title.required'        =>  'title requerido',
                'description.required'  =>  'Descripcion requerido',
                'image.required'        =>  'image requerido',
                'video.required'        =>  'video requerido',
                'tool.required'         =>  'tool requerido',
                'recommendation.required'  =>  'recommendation requerido',
                'id_clasificacion_discapacidad.required'  =>  'id_clasificacion_discapacidad requerido',
                'id_tipo_discapacidad.required'     =>  'id_tipo_discapacidad requerido',
                'id_discapacidad.required'          =>  'id_discapacidad requerido'
            ]);
    
            $errores = '';
            if ($validacion->fails()){
                $this->et->error = true;
                $this->et->code = -1;
                foreach ($validacion->messages()->all() as $key => $mensaje) {
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