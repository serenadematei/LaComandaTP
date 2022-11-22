<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
require_once "./utils/AutentificadorJWT.php";

class VerificadorJWT{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
    
        $payload = "";
        $response = new Response();
        $token = "";
        $validToken = false;
        try{
            $header = $request->getHeaderLine("Authorization");
            if($header != null){
                $token = trim(explode("Bearer", $header)[1]);
            }
            AutentificadorJWT::VerificarToken($token);
            $validToken = true;
            if($validToken){
                $payload = json_encode(array("valid" => "Token Válido"));
                $response = $handler->handle($request);
            }
        }catch (\Throwable $e) {

            $payload = json_encode(array('error' => $e->getMessage()));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}