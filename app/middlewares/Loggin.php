<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Usuario.php';
//VALIDO EL TOKEN

class LogginMiddleware
{
  public function __invoke(Request $request, RequestHandler $handler): Response
  {
    $response = new Response();

    $parametros = $request->getParsedBody();
  
    if (isset($parametros['clave']) && isset($parametros['usuario']) && isset($parametros['perfil'])) {
     
      if ($parametros['clave'] == "" || $parametros['usuario'] == "" || $parametros['perfil'] == "")
      {
        $response->getBody()->write("Error: hay campos vacios");
      }
      else
      {
        
        $response = $handler->handle($request);
      }
    } else {
      
      $response->getBody()->write("Error: faltan enviar campos");
    }
    return $response;
  }
}
?>