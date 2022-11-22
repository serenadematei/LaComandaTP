<?php
require_once './models/Pedido.php';
require_once './models/Comanda.php';
require_once './interfaces/IApiUsable.php';
require_once './utils/AutentificadorJWT.php';


class PedidoController extends Pedido 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombreCliente = $parametros['nombreCliente'];
        $idProducto= $parametros['idProducto'];
        $idMesa = $parametros['idMesa'];
        $codigoPedido = $parametros['codigoPedido'];

        $pedido = new Pedido();
        $pedido->estado = "Pendiente";
        $pedido->nombreCliente = $nombreCliente;
        $pedido->idMesa = $idMesa;
        $pedido->codigoPedido = $codigoPedido;
        $pedido->idProducto = $idProducto;
       
        
       
        
        
        if(Mesa::VerificarIdYDisponibilidad($idMesa) && Producto::VerificarId($idProducto))
        {
         
          $producto = Producto::traerProductoPorId($idProducto);
          $idEmpleado = Usuario::designarEmpleado($idProducto);
          Mesa::cambiarEstadoMesa($idMesa,"Con cliente esperando pedido");

          
          

          
        
          if(!Comanda::verificarExistencia($pedido->codigoPedido)) //SI NO EXISTE LA COMANDA, LA AGREGO
          {

            $idMozo = Usuario::designarMozo();
            $idSocio = Usuario::designarSocio();

            $comanda = new Comanda();
            $comanda->idMesa = $idMesa;
            $comanda->idSocio = $idSocio;
            $comanda->idMozo = $idMozo;
            $comanda->codigoPedido = $codigoPedido;
            $comanda->crearComanda();

            
          }
          else
          {
              $comanda = Comanda::traerComandaPorCodigoPedido($codigoPedido); //SI YA EXISTE, TOMO EL ID SOCIO Y ID MOZO DE DICHA COMANDA

              $idMozo = $comanda->idMozo;
              $idSocio = $comanda->idSocio;
          }

          
          $pedido->idMozo = $idMozo;
          $pedido->idSocio = $idSocio;
          $pedido->idEmpleado = $idEmpleado;
         
      
          $pedido->crearPedido();

          $payload = json_encode(array("mensaje" => "Pedido creado con exito."));
          ProductoPedido::crearProductoPedido($pedido->codigoPedido,$pedido->idProducto, $pedido->idEmpleado);

         
         
        }
        else
        {
          $payload = json_encode(array("mensaje" => "Error: la mesa no esta disponible, o no existe producto con ese ID."));
        }
       

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
        
        
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
   
    

    public function TraerUno($request, $response, $args)
    {
       /*
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
          */
    }



}

?>