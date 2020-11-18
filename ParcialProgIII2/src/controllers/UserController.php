<?php

namespace App\Controllers;
use App\Models\User;
use Auxiliar\UserAux;

class UserController{

    public function add($request, $response, $args) {
        
        $user = new User;
        
        $user->legajo = UserAux::getLegajo();
        $nombre = $request->getParsedBody()['nombre'];
        $email = $request->getParsedBody()['email'];
        $clave = $request->getParsedBody()['clave'];
        $tipo = $request->getParsedBody()['tipo'];
        
        $validar = UserAux::validarDatosUser($nombre,$email,$clave,$tipo);

        if($validar == true){

            $user->nombre = $nombre;
            $user->email = $email;
            $user->tipo = $tipo;
            $user->clave = $clave;

            $rta = $user->save();
            $rta = array("rta"=>$rta);
            $response->getBody()->write(json_encode($rta));
        } else {
            $response->getBody()->write(json_encode($validar));
        }
        
        return $response;
    }
}