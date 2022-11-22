<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';
require_once './utils/AutentificadorJWT.php';

//mpc

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
  

    public function TraerUno($request, $response, $args)
    {
       
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['usuario'];
        $clave = $parametros['clave'];
        $usuario = new Usuario();
        $usuario->id = $args['usuarioId'];
        $usuario->nombre= $nombre;
        $usuario->clave = $clave;

        $usuario->ModificarUsuario();

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
    public function BorrarUno($request, $response, $args)
    {
      
          $parametros = $request->getParsedBody();

          $usuarioId = $args['usuarioId'];
    if (Usuario::verificarId($usuarioId)) {
      if (Usuario::borrarUsuario($usuarioId)) {
        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
      } else {
        $payload = json_encode(array("mensaje" => "Error al borrar el usuario"));        
      }
    } else {
      $payload = json_encode(array("mensaje" => "ID inexistente"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
    }

  

  public function VerificarUsuario($request, $response, $args){

    $parametros = $request->getParsedBody();
    $mail = $parametros['mail'];
    $tipo = $parametros['tipo'];
    $clave = $parametros['clave'];

    $user = Usuario::obtenerUsuario($parametros['mail']);
    $payload = json_encode(array('status' => 'Invalid User'));
    
    if(!is_null($user)){


          if((password_verify($parametros['clave'],$user->clave) || $parametros['clave'] == $user->clave)){
            $userData = array(
                'id' => $user->id,
                'mail' => $user->mail,
                'tipo' => $user->tipo);
            $payload = json_encode(array('Token' => AutentificadorJWT::crearToken($userData), 'response' => 'OK', 'tipo:' => $user->tipo));
      }
        
    }
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
}
}
