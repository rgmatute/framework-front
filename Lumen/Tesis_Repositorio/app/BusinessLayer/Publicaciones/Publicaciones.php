<?php

namespace App\BusinessLayer\Publicaciones;

use App\Helpers\EstadoTransaccion;
// use App\Repositories\Seguridad\SeguridadRepository;
use App\Repositories\Publicaciones\PublicacionesRepository;
use Illuminate\Support\Facades\Hash;

class Publicaciones {

    public function getAllApproved(){
        try {
	        $et = new EstadoTransaccion();
            $publicacionesRepo = new PublicacionesRepository();
            $et->data = $publicacionesRepo->getAllApproved();
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function getById($id){
        try {
	        $et = new EstadoTransaccion();
            $publicacionesRepo = new PublicacionesRepository();
            $et->data = $publicacionesRepo->getById($id);
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function register($publicacion, $userId){
        try {
	        $et = new EstadoTransaccion();
            $publicacionesRepo = new PublicacionesRepository();
            $et->data = $publicacionesRepo->register($publicacion,$userId);
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function delete($id){
        try {
	        $et = new EstadoTransaccion();
            $publicacionesRepo = new PublicacionesRepository();
            $et->data = $publicacionesRepo->publicaciones()->delete($id);
            unset($et->data);
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

}


