<?php

    if(! function_exists('className')){
        function className($class=null){
            if(!empty($class)){
                $ruta=get_class($class);
                $array=explode("\\",$ruta);
                $nombre=$array[count($array)-1];
                return $nombre;
            }else{
                return false;
            }
            
        }
    }

?>