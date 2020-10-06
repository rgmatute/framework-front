<?php

namespace App\BusinessLayer\Usuarios;

use App\Helpers\EstadoTransaccion;
use App\Repositories\Usuarios\UserRepository;
use App\Repositories\Usuarios\RoleRepository;
use Illuminate\Support\Facades\Hash;

class User {

	public function register($user_data) {
        try {
	        $et = new EstadoTransaccion();
            $userRepo = new UserRepository();

            $user = $userRepo->user()->where('email', $user_data['email'])->first();

            if(isset($user->id)){
                throw new \Exception("Ya existe el correo ingresado.");
            }
            
            $user_data['password']  = Hash::make($user_data['password']);
            $user_id = $userRepo->register($user_data);

            $user = $userRepo->user()->where('id', $user_id)->first();

            if(isset($user->id)){
                $roleRepo = new RoleRepository();
                $role_id = $roleRepo->insert_users_roles($user_id, $user_data['role_id']);
            }
            
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            // throw new \Exception(' : ' . className($this) . '->login : ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function getAll(){
        try {
	        $et = new EstadoTransaccion();
            $userRepo = new UserRepository();

            $et->data = $userRepo->user()->select(['id','email','avatar','firstname','lastname','phone','address','status','created_at','updated_at'])->get();

        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            // throw new \Exception(' : ' . className($this) . '->login : ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function getById($id){
        try {
	        $et = new EstadoTransaccion();
            $userRepo = new UserRepository();

            $et->data = $userRepo->user()->where('id',$id)->select(['id','email','avatar','firstname','lastname','phone','address','status','created_at','updated_at'])->get();

        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            // throw new \Exception(' : ' . className($this) . '->login : ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function update($user_data){
        try {
	        $et = new EstadoTransaccion();
            $userRepo = new UserRepository();

            $user_data['password'] = Hash::make($user_data['password']);
            $userid = $userRepo->update($user_data);

            $roleRepo = new RoleRepository();

            $role = $roleRepo->roles()->where('id',$user_data['role_id'])->first();
            if(isset($role->id)){
                $user_role = $roleRepo->users_roles()
                ->where('user_id',$userid)
                ->join('users','users.id','=','users_roles.user_id')
                ->where('users.id',$userid)
                ->join('roles','roles.id','=','users_roles.role_id')
                ->select('users_roles.id')->first();
                if(isset($user_role->id)){
                    $roleRepo->update_users_roles($user_role->id, $userid, $user_data['role_id']);   
                }
            }
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            // throw new \Exception(' : ' . className($this) . '->update : ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
        return $et;
    }

    public function changeStatus($id, $status) {
        try {
	        $et = new EstadoTransaccion();
            $userRepo = new UserRepository();
            $userRepo->updateStatus($id, $status);
        } catch (\Exception $e) {
            $et->code = -1;
            $et->error = true;
            // throw new \Exception(' : ' . className($this) . '->delete : ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
        return $et;
    }
}