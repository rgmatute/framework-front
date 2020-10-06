<?php

namespace App\BusinessLayer\Mensajes;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Mensajes\MensajesRepository;

class Mensajes {

    public function getByFrom($userId){
        try {
	        $et = new EstadoTransaccion();
            $mensajesRepo = new MensajesRepository();
            $et->data = $mensajesRepo->getByFrom($userId);
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function getByTo($to, $userId){
        try {
	        $et = new EstadoTransaccion();
            $mensajesRepo = new MensajesRepository();
            $et->data = $mensajesRepo->getByTo($to, $userId);
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function register($mensaje, $userId){
        try {
	        $et = new EstadoTransaccion();
            $mensajesRepo = new MensajesRepository();
            $et->data = $mensajesRepo->register($mensaje, $userId);
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function delete($id, $userId){
        try {
	        $et = new EstadoTransaccion();
            $mensajesRepo = new MensajesRepository();
            $et->data = $mensajesRepo->mensajes()->where('from',$userId)->delete($id);
            unset($et->data);
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

}


