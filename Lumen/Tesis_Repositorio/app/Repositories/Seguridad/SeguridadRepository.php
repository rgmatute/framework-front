<?php

namespace App\Repositories\Seguridad;

use Illuminate\Support\Facades\DB;

class SeguridadRepository {

	public function login($email){
		try {
			$response = DB::select('SELECT u.*, r.`name` as role_name, r.id as role_id FROM users u JOIN users_roles ur ON ur.user_id=u.id JOIN roles r ON r.id=ur.role_id WHERE u.status=1 AND email=?;', [
				$email
			]);
		} catch (\Exception $e) {
			throw new \Exception(' : ' . className($this) . '->login : ' . $e->getMessage());
		}
        
        return $response;
	}

	public function passwordUpdate($user_id, $new_password){
		try {
			DB::table('users')->where('id',$user_id)->update([
				'password' => $new_password
			]);
		} catch (\Exception $e) {
			throw new \Exception(' : ' . className($this) . '->passwordUpdate : ' . $e->getMessage());
		}
	}

}