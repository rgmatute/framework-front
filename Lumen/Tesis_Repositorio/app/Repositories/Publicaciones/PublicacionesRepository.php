<?php

namespace App\Repositories\Publicaciones;

use Illuminate\Support\Facades\DB;

class PublicacionesRepository {

    private $id;

    public function publicaciones(){
        return DB::table('publicaciones');
    }

    public function getAllApproved(){
        return $this->publicaciones()->where('status', 'A')->get();
    }

    public function getAll(){
        return $this->publicaciones()->get();
    }

    public function getById($id){
        return $this->publicaciones()->where('id', $id)->first();
    }

    public function register($publicacion, $userId){
        try {
            DB::beginTransaction();
			$this->id = $this->publicaciones()->insertGetId([
                'title'             => $publicacion['title'],
                'description'       => $publicacion['description'],
                'image'             => $publicacion['image'],
                'video'             => $publicacion['video'],
                'tool'              => $publicacion['tool'],
                'recommendation'    => $publicacion['recommendation'],
                'id_clasificacion_discapacidad' => $publicacion['id_clasificacion_discapacidad'],
                'id_tipo_discapacidad'          => $publicacion['id_tipo_discapacidad'],
                'user_id'           => $userId
            ]);
            DB::commit();
		} catch (\Exception $e) {
            DB::rollback();
			throw new \Exception(' : ' . className($this) . '->register : ' . $e->getMessage());
		}
        return [ 'id' => $this->id ];
    }

}