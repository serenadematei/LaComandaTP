<?php
require_once './models/Comanda.php';
require_once './models/GestorDeArchivos.php';
require_once './interfaces/IApiUsable.php'; 
require_once './utils/AutentificadorJWT.php';


class ComandaController extends Comanda 
{

    public static function ObtenerFotoMesa($request, $response, $args)
    {
       
        $parametros = $request->getParsedBody();
        $idMesa =  $parametros['idMesa'];
        $foto= $_FILES['foto']['name']; 

        try {
            if (isset($foto)) {
                $nombreArchivo = "/MesaNumero" . $idMesa . ".jpg";
                $foto = $nombreArchivo;
                $gestorArchivos = new GestorDeArchivos("../FotosMesas");
                $gestorArchivos->GuardarArchivoMesa($nombreArchivo);
            }
            Comanda::RelacionarFotoMesaConPedido($nombreArchivo);
            $payload = json_encode(array("mensaje" => "La imagen de la mesa fue relacionada con su comanda de manera exitosa"));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        
    }

    public function ListarPedidosPorEstadoYTipoEmpleado($request, $response, $args)
    {
      
 
            $parametros = $request->getQueryParams();
            $idComanda = $parametros['idComanda'];
            $perfilEmpleado = $parametros['perfilEmpleado'];
            $estadoPedido = $parametros['estadoPedido'];
           

            $comanda=Comanda::traerComandaPorId($idComanda);
            $codigoPedido = $comanda->codigoPedido;
            $productos = Producto::obtenerProductosDePedidosPorTipoEmpleado($perfilEmpleado, $estadoPedido,$codigoPedido);
            if (count($productos) == 0) {
                  
                    $payload = json_encode(array("mensaje" => "No hay pedidos ". $estadoPedido . " para empleados de tipo: ". $perfilEmpleado .
                  " en la comanda de la mesa numero " . $comanda->idMesa));
              
            } else 
            {
              
              $payload = json_encode("Productos en estado: " . $estadoPedido . " para los empleados de tipo: " . $perfilEmpleado .
              " en la comanda de la mesa numero " . $comanda->idMesa );
                
                foreach ($productos as $producto) {

                    echo ' ID producto: ' . $producto->id. PHP_EOL;
                    echo ' Nombre del producto: ' . $producto->descripcion . PHP_EOL;
                   
                }

                 

                  if($estadoPedido == "pendiente")
                  {
                  
                    Comanda::calcularTiempoFinalizacionPedidos();
                    Comanda::cambiarEstadoPedidosAEnPreparacion($codigoPedido);

                  }
                  if($estadoPedido == "En preparacion")
                  {
                    Comanda::cambiarEstadoPedidosAListoParaServir($codigoPedido);
                  }
                  
                  
             
            }


            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');

          
       
    }


    public function MostrarTiempoDemora($request, $response, $args)
    {
       $idMesa = $args['idMesa'];
       $codigoPedido = $args['codigoPedido'];
       $minutosDePreparacion = Producto::getMayorMinutosDePreparacion($codigoPedido,$idMesa);


       $payload = json_encode(array("Mensaje" => "El tiempo de demora para la mesa numero " . $idMesa . " es de: " . $minutosDePreparacion . " minutos" ));

       $response->getBody()->write($payload);
       return $response
         ->withHeader('Content-Type', 'application/json');
       
    }

    public function TraerTodosLosPedidosConTiempoDeDemora($request, $response, $args)
    {
        $pedidos = Pedido::obtenerTodosPorEstado("En preparacion");
        $productos = Producto::obtenerTodos();

       
        
        
        for($i = 0 ; $i < count($pedidos) ; $i++)
        {
          for($j=0; $j<count($productos); $j++)
          {
             if($pedidos[$i]->idProducto == $productos[$j]->id)
             {
                echo "El tiempo de demora del pedido numero " . $pedidos[$i]->id . " es de " . 
                $productos[$j]->minutosPreparacion . " minutos".PHP_EOL;;
             }
          }
          
        }
        

        $payload = json_encode(array("listaPedidos" => $pedidos));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ListarPedidosListosParaServir($request, $response, $args)
    {
       $pedidos = Pedido::obtenerTodosPorEstado("Listo para servir");


       for($i = 0; $i<count($pedidos) ; $i++)
       {
          $idMesa = $pedidos[$i]->idMesa;
          Mesa::cambiarEstadoMesa($idMesa, "Con cliente comiendo");
       }


       $payload = json_encode(array("Pedidos listos para servir" => $pedidos));

       $response->getBody()->write($payload);
       return $response
         ->withHeader('Content-Type', 'application/json');
       
    }

    public function Servir($request, $response, $args)
    {
       $idMesa = $args['idMesa'];
       $pedidos = Pedido::obtenerTodosPorIdMesa($idMesa);
       $mesa = Mesa::cambiarEstadoMesa($idMesa, "Con cliente comiendo");

       foreach($pedidos as $p)
       {
          $p->estado = "Servido";
       }


       $payload = json_encode(array("Mensaje" => "Los pedidos fueron servidos en la mesa correspondiente"));

       $response->getBody()->write($payload);
       return $response
         ->withHeader('Content-Type', 'application/json');
       
    }
 

    public function CobrarCuenta($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $idMesa =  $parametros['idMesa'];
      $codigoPedido = $parametros['codigoPedido'];

      $pedidos = Pedido::obtenerTodosPorCodigo($codigoPedido);
      $productosACobrar = array();

      Mesa::cambiarEstadoMesa($idMesa, "Con cliente pagando");

      for($i = 0; $i<count($pedidos) ; $i++)
      {
         if($pedidos[$i]->idMesa == $idMesa)
         {
            $producto = Producto::traerProductoPorId($pedidos[$i]->idProducto);
            array_push($productosACobrar,$producto);
            $pedidos[$i]->estado = "Pago";
         }
      }

      $costoTotal = 0;
      for($i = 0; $i<count($productosACobrar) ; $i++)
      {
         $costoTotal = $costoTotal + $productosACobrar[$i]->precio;
      }

      $payload = json_encode(array("Mesa cobrada con exito. El costo total de los pedidos de la mesa es de  "=> "$" .$costoTotal));

       $response->getBody()->write($payload);
       return $response
         ->withHeader('Content-Type', 'application/json');

    }

    public function CerrarMesa($request, $response, $args)
    {
       $parametros = $request->getParsedBody();
       $idMesa =  $parametros['idMesa'];
       $mesa = Mesa::cambiarEstadoMesa($idMesa, "Cerrada");

       $payload = json_encode(array("Mensaje" => "Se cerro la mesa numero $idMesa."));

       $response->getBody()->write($payload);
       return $response
         ->withHeader('Content-Type', 'application/json');      
    }
    
   
    
  
  

}

?>