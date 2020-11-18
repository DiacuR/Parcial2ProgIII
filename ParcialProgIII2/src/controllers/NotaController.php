<?php

namespace App\Controllers;
use App\Models\User;
use App\Models\Materia;
use App\Models\Inscripcion;
use JWT\Token;

class NotaController{

    public function AsignarNota($request, $response, $args) {
        
        $idAlumno = $request->getParsedBody()['idAlumno'];
        $nota = $request->getParsedBody()['nota'];
        //$idProfesor = $request->getParsedBody()['idProfesor'];

        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        $payload = Token::ValidateToken($token);

        $materia = Materia::find($args['idMateria']);

        $profesor = User::get()->where("legajo",$payload->legajo)->first();

        

        $inscripcion = Inscripcion::get()->where('idAlumno', $idAlumno)
                                        ->where("idMateria",$materia->idMateria)
                                        ->where("idProfesor", $profesor->id)
                                        ->first();
            
            if(!is_null($inscripcion)){
                $inscripcion->idProfesor = $profesor->id;
                $inscripcion->nota = $nota;

                $rta = $inscripcion->save();
            }
            else {

                $rta = array("rta" =>"Error. El id no corresponde a niguno de sus alumnos");
            }
        
        
        $response->getBody()->write(json_encode($rta));
    
        return $response;
    }


    public function getMateriasById($request, $response, $args) {

        $id = $args['idMateria'];

        $inscripciones = Inscripcion::get()->where('idMateria',$id);

        $notas= array();

        foreach($inscripciones as $inscripcion){ 

            if($inscripcion){
                $nota = $inscripcion->nota;
                
                array_push($notas,$nota);
            }
        }

        $response->getBody()->write(json_encode($notas));

        return $response;
    }
}