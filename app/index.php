<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);


//LA COMANDA

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Logger;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';


require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ComandaController.php';
require_once './middlewares/Loggin.php';
require_once './middlewares/VerificadorJWT.php';
require_once './middlewares/esUsuarioRegistradoMiddleware.php';
require_once './middlewares/esMozoMiddleware.php';
require_once './middlewares/esSocioRegistradoMiddleware.php';



// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();


// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes


//USUARIOS
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('/', \UsuarioController::class . ':TraerTodos');
    $group->post('/registrarse', \UsuarioController::class . ':CargarUno'); //dar de alta un empleado(registrarse)
    $group->post('/login', \UsuarioController::class . ':VerificarUsuario')->add(new LogginMiddleWare());  //iniciar sesion (verificarse)
  });

  //PRODUCTOS
  $app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->post('[/]', \ProductoController::class . ':CargarUno')->add(new esUsuarioRegistradoMiddleware());
  });


  //MESAS
  $app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos');
    $group->post('[/]', \MesaController::class . ':CargarUno')->add(new esUsuarioRegistradoMiddleware()); 
  });


  //PEDIDOS
  $app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->post('[/]', \PedidoController::class . ':CargarUno')->add(new esUsuarioRegistradoMiddleware());  
  });


  //CIRCUITO DEL PEDIDO (LUEGO DE CREARLO -PUNTO 1-)
  $app->group('/comandas', function (RouteCollectorProxy $group)
  {
    $group->post('/foto', \ComandaController::class . ':ObtenerFotoMesa')->add(new esMozoMiddleware()); //PUNTO 2
    $group->get('/listarPendientes', \ComandaController::class . ':ListarPedidosPorEstadoYTipoEmpleado'); //PUNTO 3
    $group->get('/demora/{idMesa}/{codigoPedido}', \ComandaController::class . ':MostrarTiempoDemora'); //PUNTO 4
    $group->get('[/]', \ComandaController::class . ':TraerTodosLosPedidosConTiempoDeDemora')->add(new esSocioRegistradoMiddleware());//PUNTO 5
    $group->get('/listarEnPreparacion', \ComandaController::class . ':ListarPedidosPorEstadoYTipoEmpleado'); //PUNTO 6
    $group->get('/servir/{idMesa}', \ComandaController::class . ':Servir')->add(new esMozoMiddleware()); //PUNTO 7
    $group->get('/listarMesas', \MesaController::class . ':TraerTodos')->add(new esSocioRegistradoMiddleware()); //PUNTO 8
    $group->post('/cobrarCuenta', \ComandaController::class . ':CobrarCuenta')->add(new esMozoMiddleware()); //PUNTO 9
    $group->post('/cerrarMesa', \ComandaController::class . ':CerrarMesa')->add(new esSocioRegistradoMiddleware()); //PUNTO 10
    
  })->add(new esUsuarioRegistradoMiddleware());  


   $app->run();
   
?>








