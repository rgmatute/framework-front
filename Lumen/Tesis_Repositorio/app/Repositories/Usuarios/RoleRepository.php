<?php

namespace App\Repositories\Usuarios;

use Illuminate\Support\Facades\DB;

class RoleRepository {

    private $id;

    public function roles(){
        return DB::table('roles');
    }

    public function getById($id){
        return $this->roles()->where('id', $id)->first();
    }

    public function getByName($name){
        return $this->roles()->where('name', $name)->first();
    }

    public function insert_users_roles($user_id, $role_id){
        try {
            DB::beginTransaction();
			$this->id = DB::table('users_roles')->insertGetId(['user_id' => $user_id, 'role_id' => $role_id ]);
            DB::commit();
		} catch (\Exception $e) {
            DB::rollback();
			throw new \Exception(' : ' . className($this) . '->register : ' . $e->getMessage());
		}
        return $this->id;
    }

    public function update_users_roles($id, $user_id, $role_id){
        try {
            DB::beginTransaction();
			DB::table('users_roles')->where('id',$id)->update(['user_id' => $user_id, 'role_id' => $role_id ]);
            DB::commit();
		} catch (\Exception $e) {
            DB::rollback();
			throw new \Exception(' : ' . className($this) . '->register : ' . $e->getMessage());
		}
    }

    public function users_roles(){
        return DB::table('users_roles');
    }

}