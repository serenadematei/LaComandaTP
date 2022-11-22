<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './utils/AutentificadorJWT.php';




class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $perfil = $parametros['perfil'];
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->perfil = $perfil;
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito. Perfil:{$usr->perfil}"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

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
    $perfil = $parametros['perfil'];
    $usuario = $parametros['usuario'];
    $clave = $parametros['clave'];

    $user = Usuario::obtenerUsuario($parametros['usuario']);
    $payload = json_encode(array('status' => 'Invalid User'));
    
    if(!is_null($user)){


          if((password_verify($parametros['clave'],$user->clave) || $parametros['clave'] == $user->clave)){
            $userData = array(
                'id' => $user->id,
                'usuario' => $user->usuario,
                'perfil' => $user->perfil);
            $payload = json_encode(array('Token' => AutentificadorJWT::crearToken($userData), 'response' => 'OK', 'tipo:' => $user->perfil));
      }
        
    }
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
}
}
