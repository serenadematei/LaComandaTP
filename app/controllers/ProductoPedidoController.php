<?php
require_once './models/ProductoPedido.php';
require_once './interfaces/IApiUsable.php'; 
require_once './utils/AutentificadorJWT.php';


class ProductoPedidoController extends ProductoPedido
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $idProducto = $parametros['$idProducto'];
        $idPedido = $parametros['$idPedido'];

        $ProductoPedido = new ProductoPedido();
        $ProductoPedido->idProducto= $idProducto;
        $ProductoPedido->idPedido= $idPedido;
        

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


}

?>