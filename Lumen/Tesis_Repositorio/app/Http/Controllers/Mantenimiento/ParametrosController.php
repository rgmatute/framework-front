<?php

    namespace App\Http\Controllers\Mantenimiento;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Helpers\EstadoTransaccion;
    use App\AuthToken\JWToken;
    use Validator;
    use Illuminate\Support\Facades\DB;

    class ParametrosController extends Controller {
        
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
                $this->et->data = DB::table('parametros')->where('status', 1)->get();
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

        public function getAllInactive(){
            try {
                $this->et->data = DB::table('parametros')->where('status', 0)->get();
            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->getAllInactive : ' . $e->getMessage());
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
                $this->et->data = DB::table('parametros')->where('id', $id)->get();
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

        public function getByCode($code){
            try {
                $this->et->data = DB::table('parametros')->where('status',1)->where('codigo', $code)->get();
            } catch (\Exception $e) {
                $this->et->code = -1;
                $this->et->error = true;
                unset($this->et->data);
                $messageLog = utf8_encode('Error: ' . className($this) . '->getByCode : ' . $e->getMessage());
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
                if(isset($request['id'])){
                    DB::table('parametros')->where(['id' => $request['id'],'status' => 1])->update([
                        'codigo'    =>  $request['codigo'],
                        'valor'     =>  $request['valor'],
                        'status'     =>  $request['status'] ?? 1
                    ]);
                    unset($this->et->data);
                }else{
                    $id = DB::table('parametros')->insertGetId([
                        'codigo'    =>  $request['codigo'],
                        'valor'     =>  $request['valor'],
                        'status'     =>  $request['status'] ?? 1
                    ]);
                    $this->et->data = [ 'id' => $id ];
                }

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

        public function delete($id){
            try {
                DB::beginTransaction();
                DB::table('parametros')->where(['id' => $id,'status' => 1])->update([ 
                    'status'    =>  0 
                ]);
                DB::commit();
                unset($this->et->data);
            } catch (\Exception $e) {
                DB::rollback();
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
                'codigo'  => 'required|min:1',
                'valor'  => 'required|min:1'
            ];
    
            $validacion = Validator::make($request, $reglas,[
                'codigo.required'        =>  'codigo requerido',
                'valor.required'        =>  'valor requerido'
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