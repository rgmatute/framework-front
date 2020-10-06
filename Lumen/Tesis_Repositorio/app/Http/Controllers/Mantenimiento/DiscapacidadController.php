<?php

    namespace App\Http\Controllers\Mantenimiento;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Helpers\EstadoTransaccion;
    use App\AuthToken\JWToken;
    use Validator;
    use Illuminate\Support\Facades\DB;

    class DiscapacidadController extends Controller {
        
        private $et;
        private $user_id;
        private $role_id;

        public function __construct(Request $request){
            $this->et       = new EstadoTransaccion();
            $userInfo = JWToken::userInfo($request);
            $this->user_id = $userInfo->useid ?? 0;
            $this->role_id = $userInfo->roleid ?? 0;
        }

        public function getAll(){
            try {
                $this->et->data = DB::table('discapacidad')->whereNotNull('clasificacion_discapacidad_id')->get();
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
                $this->et->data = DB::table('discapacidad')->where('id', $id)->get();
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

        public function getByClasificacionDiscapacidad($idClasificacion){
            try {
                $this->et->data = DB::table('discapacidad')->where('clasificacion_discapacidad_id', $idClasificacion)->get();
            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->getByClasificacionDiscapacidad : ' . $e->getMessage());
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
                $request = json_decode($request->getContent()??'', true);
                $this->et = $this->validarData($request);

                if($this->et->error) {
                    throw new \Exception("Error de validación de datos " . $this->et->message);
                }
                DB::beginTransaction();
                $id = DB::table('discapacidad')->insertGetId([
                    'name'                          =>  $request['name'],
                    'clasificacion_discapacidad_id' =>  $request['clasificacion_discapacidad_id'],
                    'description'                   =>  $request['description']??''
                ]);
                $this->et->data = [ 'id' => $id ];
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
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

        public function update(Request $request){
            try {
                $request = json_decode($request->getContent()??'', true);
                $this->et = $this->validarData($request);

                if($this->et->error) {
                    throw new \Exception("Error de validación de datos " . $this->et->message);
                }
                if(!isset($request['id']) || empty($request['id'])) {
                    throw new \Exception("Falta el código del registro seleccionado.");
                }
                DB::beginTransaction();
                $filaAfectada = DB::table('discapacidad')->where('id', $request['id'])->update([
                    'name'                          =>  $request['name'],
                    'clasificacion_discapacidad_id' =>  $request['clasificacion_discapacidad_id'],
                    'description'                   =>  $request['description']??''
                ]);
                DB::commit();
                unset($this->et->data);
                if($filaAfectada === 0){
                    throw new \Exception($this->et::$registroNotUpdate);
                }
            } catch (\Exception $e) {
                DB::rollback();
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
                DB::table('discapacidad')->delete($id);
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
                'name'                          => 'required|min:1',
                'clasificacion_discapacidad_id' => 'required|numeric|min:1'
            ];
    
            $validacion = Validator::make($request, $reglas,[
                'name.required'                             =>  'name requerido',
                'clasificacion_discapacidad_id.required'    =>  'clasificacion_discapacidad_id requerido',
                'clasificacion_discapacidad_id.numeric'     =>  'solo se permite numerico'
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