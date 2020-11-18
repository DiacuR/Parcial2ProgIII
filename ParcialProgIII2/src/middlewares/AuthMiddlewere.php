<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Res;
use Slim\Psr7\Response;
use App\Models\User;
use JWT\Token;

class AuthMiddleware{

    public $_AuthUsers;

    public function __construct( $AuthUser ) { $this->_AuthUsers = $AuthUser; }

    public function __invoke($request, $handler)
    {
        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        
        $payload = Token::ValidateToken($token);

        if(is_null($payload)){

            $response = new Response();
            $rta = array("rta"=>"Token Invalido");
            $response->getBody()->write(json_encode($rta));

            return $response->withStatus(403);

        } else {

            $user = User::where('legajo',$payload->legajo)->get()->first();
            $res = new Response();
            $rta = null;

            foreach ($this->_AuthUsers as $tipoUser) {
                
                if($user->tipo == $tipoUser){

                    $response = $handler->handle($request);
                    $body = (string) $response->getBody();

                    $res->getBody()->write(json_encode($body));
                    break;
                } else {
                    $rta = array("rta","Error. No tiene los permisos necesarios.");
                }
            }
            
            if(!is_null($rta)){
                $res->getBody()->write(json_encode($rta));
            }
        }
        return $res;
    }
}