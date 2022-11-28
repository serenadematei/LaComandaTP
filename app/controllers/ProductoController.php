<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';
require_once './utils/AutentificadorJWT.php';


class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        $minutosPreparacion = $parametros['minutosPreparacion'];
        $precio = $parametros['precio'];
        $tipoProducto = $parametros['tipoProducto'];

        $producto = new Producto();
        $producto->descripcion = $descripcion;
        $producto->minutosPreparacion = $minutosPreparacion;
        $producto->precio = $precio;
        $producto->tipoProducto = $tipoProducto;

        $producto->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito."));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
   
    public function BorrarUno($request, $response, $args)
    {
      
          $productoId = $args['productoId'];
          if (Producto::verificarId($productoId))
          {
            if (Producto::borrarProducto($productoId))
            {
              $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
            } 
          else
            {
              $payload = json_encode(array("mensaje" => "Error al borrar el producto"));        
            }
         } 
         else 
         {
          $payload = json_encode(array("mensaje" => "ID inexistente"));
         }
         $response->getBody()->write($payload);

         return $response->withHeader('Content-Type', 'application/json');
    }


    public function TraerUno($request, $response, $args)
    {
       
        return 0;
    }

   
    
    public function ModificarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $id = $args['productoId'];
      $producto= Producto::traerProductoPorId($id);
      
      
     if($producto!=null)
     {
      
        $producto->descripcion = $parametros['descripcion'];
        $producto->minutosPreparacion = $parametros['minutosPreparacion'];
        $producto->precio = $parametros['precio'];
        $producto->tipoProducto = $parametros['tipoProducto']; 
        $producto->modificarProducto();
        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

      }
      else
      {
        $payload = json_encode(array("mensaje" => "Id inexistente"));
      }

      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  } 

  public static function generarCSV($request, $response, $args)
  {
    $productos = Producto::obtenerTodos();
    $payload = json_encode($productos);

    header('Content-Type: application/csv; charset=UFT-8');
    header('Content-Disposition: attachment; filename=menu.csv');
    ob_end_clean();

   if(file_exists('./menu.csv'))
   {
      $data = json_decode($payload,true);
      $fp= fopen("menu.csv",'w');
      foreach($data as $row)
      {
        fputcsv($fp,$row);
      }
      fclose($fp);
   }
    readfile('./menu.csv');

    return $response->withHeader('Content-Type', 'application/csv');
  }


  public function CargarDatosDesdeCSV($request, $response, $args)
  {
    $gestorArchivo = new GestorDeArchivos();
    $payload = "El archivo no existe";

    $parametros = $request->getUploadedFiles();
    $archivo = $parametros['csv'];
    if(isset($archivo))
    {
     
      $nombreArchivo = $_FILES['csv']['name']; 
      if(explode('.',$nombreArchivo)[1] == 'csv')
      {
        $rutaArchivo = $gestorArchivo->MoverArchivoCSV($nombreArchivo);
        $payload = json_encode(array("Mensaje" => GestorDeArchivos::LeerProductosCSV($rutaArchivo)));

      }
      else
      {
        $payload = json_encode(array("Error" => "extension de archivo invalida"));
      }
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');

  }
}

    
   
 


