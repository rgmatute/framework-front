<?php

namespace App\Repositories\Usuarios;

use Illuminate\Support\Facades\DB;

class UserRepository {

    private $id;

    public function user(){
        return DB::table('users');
    }

    public function getAll($user){
		try {
			$response = DB::table('users')->get();
		} catch (\Exception $e) {
			throw new \Exception(' : ' . className($this) . '->getAll : ' . $e->getMessage());
		}
        
        return $response;
	}

	public function register($user){
		try {
            DB::beginTransaction();
			$this->id = DB::table('users')->insertGetId([
                'email' => $user['email'],
                'avatar' => $user['avatar'] ?? '',
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'phone' => $user['phone'],
                'address' => $user['address'] ?? '',
                'password' => $user['password'],
                'status' => $user['status'] ?? 1
            ]);
            DB::commit();
		} catch (\Exception $e) {
            DB::rollback();
			throw new \Exception(' : ' . className($this) . '->register : ' . $e->getMessage());
		}
        
        return $this->id;
    }
    
    public function update($user){
		try {
            DB::beginTransaction();
			DB::table('users')->where('id', $user['id'])->update([
                'email' => $user['email'],
                'avatar' => $user['avatar'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'phone' => $user['phone'],
                'address' => $user['address'],
                'password' => $user['password'],
                'status' => $user['status'] ?? 1
            ]);
            $this->id = $user['id'];
            DB::commit();
		} catch (\Exception $e) {
            DB::rollback();
			throw new \Exception(' : ' . className($this) . '->update : ' . $e->getMessage());
		}
        
        return $this->id;
    }

    public function updateStatus($user_id, $new_status){
        try{
        DB::beginTransaction();
			DB::table('users')->where('id', $user_id)->update([
                'status' => $new_status
            ]);
            DB::commit();
		} catch (\Exception $e) {
            DB::rollback();
			throw new \Exception(' : ' . className($this) . '->updateStatus : ' . $e->getMessage());
		}
    }

}