<?php
    namespace App\Helpers;

    class EstadoTransaccion{
        public $code                        = 0;
        public $error                       = false;
        public $message                     = "";
        public $data;
        public static $noExistenDatos       = "No existen datos con el criterio seleccionado";
        public static $procesoExitoso       = "Proceso ejecutado exitosamente";
        public static $procesoErroneo       = "Hubo un error. Intente de nuevo por favor";
        public static $loginError           = "Hubo un error. Intente de nuevo por favor (No existe el Usuario con los criterios enviados)";
        public static $registroYaExiste     = "No se puede crear, registro ya existe";
        public static $registroNotUpdate    = "No se realizaron cambios en el registro seleccionado ó no existe.";
        public static $operacionNoPermitida = 'Operación no permitida';

        function __construct() {
            $this->message  = EstadoTransaccion::$procesoExitoso;
            return $this;
        }
    }
?>