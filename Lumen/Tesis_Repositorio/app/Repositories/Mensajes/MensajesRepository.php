<?php

namespace App\Repositories\Mensajes;

use Illuminate\Support\Facades\DB;

class MensajesRepository {

    private $id;

    public function mensajes(){
        return DB::table('mensajes');
    }

    public function getByFrom($userId){
        return $this->mensajes()->where('from', $userId)->get();
    }

    public function getByTo($to, $userId){
        return $this->mensajes()->where('to', $to)->where('from', $userId)->get();
    }

    public function register($message, $userId){
        try {
            DB::beginTransaction();
			$this->id = $this->mensajes()->insertGetId([
                'from'  => $userId,
                'to'    => $message['to'],
                'text'  => $message['text']
            ]);
            DB::commit();
		} catch (\Exception $e) {
            DB::rollback();
			throw new \Exception(' : ' . className($this) . '->register : ' . $e->getMessage());
		}
        return [ 'id' => $this->id ];
    }

}