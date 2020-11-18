<?php

namespace App\Controllers;
use App\Models\User;
use App\Models\Materia;
use App\Models\Inscripcion;
use JWT\Token;

class InscripcionController{

    public function inscripcion($request, $response, $args) {
        
        $id = $args['idMateria'] ?? null;
        $materia = Materia::find($id);

        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        $payload = Token::ValidateToken($token);

        $user = User::get()->where('legajo',$payload->legajo)->first();

        if(!is_null($user) && $user->tipo == 'alumno'){
            
            if($materia->cupos > 0){

                $inscripcion = new Inscripcion;
                $inscripcion->idAlumno = $user->id;
                $inscripcion->idMateria = $materia->idMateria;
                $materia->cupos -= 1;

                $materia->save();
                $rta = $inscripcion->save();
                $rta = array("rta"=>$rta);
                $response->getBody()->write(json_encode($rta));
            } else {
                $rta = array("rta"=>"Error. No quedan cupos en la clase");
                $response->getBody()->write(json_encode($rta));    
            }
        
        }else{
            $rta = array("rta"=>"Error");
            
            $response->getBody()->write(json_encode($rta));
        }

        return $response;
    }


    public function MostrarInscriptos($request, $response, $args) {
        
        $id = $args['idMateria'] ?? null;

        $inscripciones =Inscripcion::get()->where('idMateria',$id);

        $alumnos = array();

        for ($i=0; $i < count($inscripciones); $i++) { 

            if($inscripciones[$i]){

                $alumno =  User::get()->where('id', $inscripciones[$i]->idAlumno)->first();
                array_push($alumnos, $alumno->nombre);
            }
        }

        $response->getBody()->write(json_encode($alumnos));

        return $response;
    }
}