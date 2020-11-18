<?php

namespace App\Controllers;
use App\Models\Materia;

class MateriaController{

    public function add($request, $response, $args) {
        
        $Materia = new Materia;

        $Materia->materia = $request->getParsedBody()['materia'];
        $Materia->cuatrimestre = $request->getParsedBody()['cuatrimestre'];
        $Materia->cupos = $request->getParsedBody()['cupos'];

        $rta = $Materia->save();
        $rta = array("rta"=>$rta);
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getMaterias($request, $response, $args){
        
        $materias = Materia::get();
        $listaMaterias = array();
        for ($i=0; $i < count($materias); $i++) { 

            if($materias[$i]){
                
                $materia =  Materia::get()->where('id', $materias[$i]->idAlumno)->first();
                array_push($listaMaterias, $materia->nombre);
            }

        }
        $response->getBody()->write(json_encode($listaMaterias));
        return $response;
    }
}