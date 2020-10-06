<?php
	namespace App\AuthToken;
	use Lcobucci\JWT\Builder;
	use Lcobucci\JWT\ValidationData;
	use Lcobucci\JWT\Signer\Hmac\Sha256;
	use Lcobucci\JWT\Parser;
	use Illuminate\Support\Facades\Cache;

	class JWToken{

		protected $key = 'wlWlFIHcioMnyYvUMkbbYWrJHXCZTw8k';    # key para el firmado del token
	    protected $minutosValidez = 1440;                       # tiempo en minutos de validez del token
	    protected $emisorToken = 'http://www.google.com';

	    public function destruirToken($token, $codUsuario, $tiempo = 60){
	        Cache::put($token, (string)$codUsuario, $tiempo);
	    }

		public function generarToken($codUsuario, $codRole){
	    // public function generarToken($codEmpresa, $codUsuario, $codPersona){
			$signer = new Sha256();
	        $builderToken = new Builder();
	        $time = time();
	        $token = $builderToken->setIssuer($this->emisorToken) 	# Configura el emisor (iss claim)
	            ->setAudience($codUsuario) # Configura el receptor (aud claim)
	            ->setId(md5($codUsuario . $time)) # Configura el id (jti claim), replica como item del header, ID unico del token. md5(cod_usuario + iat claim)
	            ->setIssuedAt($time) # Configura el datetime en que el token fue emitido (iat claim)
	            ->setNotBefore($time) # Configura el datetime en que el token puede ser usado (nbf claim)
	            ->setExpiration($time + $this->minutosValidez*60) # Configura el datetime de expiracion del token (exp claim)
				// ->set('data', ['comid' => $codEmpresa, 'useid' => $codUsuario, 'codper' => $codPersona]) # Configura un nuevo claim, llamado "data", recordar que esto no va cifrado, ser cuidadoso con lo que se envía
				->set('data', ['useid' => $codUsuario, 'roleid' => $codRole]) # Configura un nuevo claim, llamado "data", recordar que esto no va cifrado, ser cuidadoso con lo que se envía
	            ->sign($signer, $this->key) # crea una firma usando $key como llave
	            ->getToken(); # Pide el token generado
	        $token->getHeaders(); # Obtiene el header del token
			$token->getClaims(); # Obtiene el claim del token
			if (!Cache::has((string)$token)) {
				Cache::put((string)$token, (string)$codUsuario, ($this->minutosValidez*60));
			}
	        return (string)$token;
		}

	    private function renovarToken($token, $codUsuario, $codRole){
		// private function renovarToken($token, $codEmpresa, $codUsuario, $codPersona){
	        $this->destruirToken($token, $codUsuario);
	        $nuevoToken = $this->generarToken($codUsuario,$codRole);
	        
	        return $nuevoToken;
	    }

	    static function userInfo($request){
	        $parser 	= new Parser();
			$strToken 	= $request->header('Authorization');
			if(!empty($strToken)){
				$token 		= $parser->parse($strToken); # Parsea desde una string
				$token->getHeaders(); 	# Obtiene todo el header del token
				$token->getClaims(); 	# Obtiene todos los claims del token
				$userData 	= $token->getClaim('data');
			}

	        return $userData ?? [];
	    }

	    public function validarToken($token){
	        $informacion	= [];
	        $parser 		= new Parser();
	        $signer 		= new Sha256();
	        $data 			= new ValidationData(); # Valida basado en la fecha y hora actual (iat, nbf and exp)
	        try {
				$strToken 	= (string)$token;
				/*\print_r($strToken);
				echo "<br/>";*/
				/*Cache::put($token,'rgmatute',0);
				\print_r(Cache::get($token));
				die();*/
	            if (!Cache::has($strToken)) {
	                return $informacion['valido'] = false;
	            }
	            $token 		= $parser->parse($strToken); # Parsea desde una string
	            $token->getHeaders();	# Obtiene todo el header del token
	            $token->getClaims(); 	# Obtiene todos los claims del token
	            $userData 	= $token->getClaim('data');
	            $iatData 	= $token->getClaim('iat');
	            $data->setIssuer($this->emisorToken);
	            $data->setAudience($userData->useid);
	            $data->setId(md5($userData->useid . $iatData));
	            if($token->validate($data) and $token->verify($signer, $this->key)){ # Si token es válido
	            	///////////////
	                #$nuevoToken = $this->renovarToken($strToken, $userData->comid, $userData->useid, $userData->codper);
	                #$informacion['nuevoToken'] = $nuevoToken;
	                //////////////
					$informacion['valido'] 		= true;
	                $informacion['userInfo'] 	= $token->getClaim('data');
	            }else{	# Token no es válido
	            	$informacion['valido'] 		= false;
	            }
	        }catch (\Exception $e){
	            $informacion['valido'] 		= false;
	        }

	        return $informacion;
	    }
	}
?>