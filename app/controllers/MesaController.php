<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php'; 
require_once './utils/AutentificadorJWT.php';


class MesaController extends Mesa 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];

        $mesa = new Mesa();
        $mesa->estado = $estado;
        

        $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito."));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ReestablecerDisponibilidad($request, $response, $args)
    {
        Mesa::reestablecerDisponibilidadMesas();

        $payload = json_encode(array("Mensaje" => "Se cambio el estado de todas las mesas a DISPONIBLE"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


   
  

}

?>