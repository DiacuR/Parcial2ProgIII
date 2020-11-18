<?php

namespace App\Controllers;
use App\Models\User;
use JWT\Token;

class LoginController{ 

    public function login($request, $response, $args) {
        
        $clave = $request->getParsedBody()['clave'];
        $email = $request->getParsedBody()['email'];
        
        $user = User::where('email',$email)->where('clave',$clave)->first();

        if(!is_null($user)){
            $rta = Token::getToken($user)?? '';
        } else {
            $rta = 'Error. El email/legajo no son correctos';
        }
        
        $response->getBody()->write(json_encode($rta));

        return $response;
    }

}