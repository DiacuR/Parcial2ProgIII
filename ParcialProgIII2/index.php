<?php

require_once "./vendor/autoload.php";

use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Exception\NotFoundException;
use Slim\Routing\RouteCollectorProxy;       //Group
use Slim\Middleware\ErrorMiddleware;
use Slim\Factory\AppFactory;

use Config\DataBase;
use App\Controllers\LoginController;
use App\Controllers\UserController;
use App\Controllers\MateriaController;
use App\Controllers\NotaController;
use App\Controllers\InscripcionController;
use App\Middlewares\JsonMiddleware;
use App\Middlewares\AuthMiddleware;



$app = AppFactory::create();
new DataBase;
$app->setBasePath("/ProgramacionIII/ParcialProgIII2");


$app->post('/users', UserController::class . ":add");

$app->post("/login", LoginController::class . ":login");

$app->group("/notas", function (RouteCollectorProxy $group){

    $group->put("/{idMateria}", NotaController::class . ":AsignarNota")->add(new AuthMiddleware(array('profesor')));

    $group->get('/{idMateria}', NotaController::class . ":getMateriasById");
});

$app->group('/inscripcion', function (RouteCollectorProxy $group) {
    
    $group->post("/{idMateria}", InscripcionController::class . ":inscripcion")->add(new AuthMiddleware(array('alumno')));

    $group->get("/{idMateria}", InscripcionController::class . ":MostrarInscriptos")->add(new AuthMiddleware(array('profesor','admin')));

});

$app->group('/materia', function (RouteCollectorProxy $group) {

    $group->post('[/]', MateriaController::class . ":add")->add(new AuthMiddleware(array('admin')));

    $group->get('[/]', MateriaController::class . ":getMaterias");

});

$app->add(new JsonMiddleware);

$app->addBodyParsingMiddleware();

$app->run();