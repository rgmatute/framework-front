<?php

namespace App\BusinessLayer\Seguridad;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Seguridad\SeguridadRepository;
use App\Repositories\Usuarios\UserRepository;
use Illuminate\Support\Facades\Hash;

class Seguridad {

	public function userInfo($email, $password) {
        try {
	        $et = new EstadoTransaccion();
            $seguridadRepo = new SeguridadRepository();
            
            $response = $seguridadRepo->login($email);

            if(count($response) > 0){
                if(password_verify($password, $response[0]->password)){
                    $et->data = $response[0];
                }else{
                    $et->error = true;
                    $et->message = 'Credenciales inválidas';
                }
            }else{
                throw new \Exception('No existe el usuario con el correo '.$email);
            }
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            // throw new \Exception(' : ' . className($this) . '->userInfo : ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function passwordUpdate($user_id, $current_password, $new_password){
        try {
            $et = new EstadoTransaccion();
            
            $userRepo = new UserRepository();

            $user = $userRepo->user()->where('id', $user_id)->first();
            $plain_password = $new_password;

            if(Hash::check($current_password, $user->password)){
                $new_password = Hash::make( $new_password );
                $seguridadRepo = new SeguridadRepository();

                $seguridadRepo->passwordUpdate($user_id, $new_password);

                $user = $userRepo->user()->where('id', $user_id)->first();
                
                if(Hash::check($plain_password, $user->password)){
                    // $et->message = "Contraseña actualizada correctamente.";
                }else{
                    throw new \Exception('COntraseña no se pudo actualizar.');
                }
            }else{
                throw new \Exception('Contraseña no coincide.');
            }
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            // throw new \Exception(' : ' . className($this) . '->passwordUpdate : ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
        return $et;
    }
}