<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class esSocioRegistradoMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler):Response
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();

        if (!empty($header)) { 
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->perfil == "socio" || $data->perfil == "Socio") {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "Solo los socios tienen acceso")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("Admin error" => "Necesita ingresar el token")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

}

?>