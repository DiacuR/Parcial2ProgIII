<?php

namespace Auxiliar;
use App\Models\User;

class UserAux {

    public static function getLegajo(){
        
        $legajo = null;

        while(is_null($legajo)){

        $legajo = rand(100,10000);
        
        $valido = User::get()->where('legajo',$legajo)->first();

            if(is_null($valido)){
                return $legajo;
            } else {
                $legajo = null;
            }
        }
    }

    public static function validarDatosUser($nombre, $email, $clave, $tipo){

        $valido = User::get()->where('email',$email)->first();

        if(strpos($nombre, " ")){
            return array("rta:"=>"Nombre invalido");
        }
        if(!is_null($valido)){
            return array("rta:"=>"Email invalido");
        }
        if($clave->length<4){
            return array("rta:"=>"Clave invalido");
        }
        if($tipo != 'admin' || $tipo != 'profesor' || $tipo != 'alumno'){
            return array("rta:"=>"Tipo de usuario invalido");
        }

        return true;
    }
}