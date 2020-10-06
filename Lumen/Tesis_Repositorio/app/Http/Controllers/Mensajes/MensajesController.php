<?php

    namespace App\Http\Controllers\Mensajes;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Helpers\EstadoTransaccion;
    use App\AuthToken\JWToken;
    use App\BusinessLayer\Mensajes\Mensajes;
    use Validator;

    class MensajesController extends Controller {
        
        private $et;
        private $user_id;
        private $role_id;

        public function __construct(Request $request){
            $this->et       = new EstadoTransaccion();
            $userInfo = JWToken::userInfo($request);
            $this->user_id = $userInfo->useid ?? 0;
            $this->role_id = $userInfo->roleid ?? 0;
        }

        public function getByFrom(){
            try {
                $mensajes   = new Mensajes();
                $this->et = $mensajes->getByFrom($this->user_id);
            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->getByFrom : ' . $e->getMessage());
                if( !is_array($this->et->message) ) {
                    $this->et->message = [
                        'user' => $e->getMessage()
                    ];
                }
            }
            return response()->json($this->et);
        }

        public function getByTo($to){
            try {
                $mensajes   = new Mensajes();
                $this->et = $mensajes->getByTo($to, $this->user_id);
            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->getByTo : ' . $e->getMessage());
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
                $mensajes   = new Mensajes();
                $request = json_decode($request->getContent(), true);
                $this->et = $this->validarData($request);

                if($this->et->error) {
                    throw new \Exception("Error de validación de datos " . $this->et->message);
                }

                $this->et = $mensajes->register($request, $this->user_id);
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
                $mensajes   = new Mensajes();
                $this->et = $mensajes->delete($id, $this->user_id);
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
                'from'  => 'required|min:1',
                'to'    => 'required',
                'text'  => 'required|min:1'
            ];
    
            $validacion = Validator::make($request, $reglas,[
                'from.required'        =>  'title requerido',
                'to.required'  =>  'Descripcion requerido',
                'text.required'        =>  'image requerido'
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