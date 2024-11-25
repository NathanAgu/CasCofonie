<?php
namespace Signature;

use Signature\Controller\ErrorController;
use Signature\Controller\SecurityController;
use Signature\Model\User;
use Signature\Service\Container;
use Signature\Service\Router;
use Signature\Service\Security;

require '../vendor/autoload.php';

$container = new Container();

$firewallDefintions = [
    '/admin' => [User::ROLE_ADMIN],
    '/' => [User::ROLE_USER],
];

$uri = $_SERVER['REQUEST_URI'];

$security = new Security($container, $firewallDefintions);
$security->check($uri);

$routeDefintions = [
    // 'path/to/find' => ['ControllerClass', 'controllerMethod'],
    '/register' => [SecurityController::class, 'register'],
    '/login' => [SecurityController::class, 'login'],
    '/logout' => [SecurityController::class, 'logout'],
];

$router = new Router($container, $routeDefintions);

if ($callable = $router->find($uri)) {
    $callable();
} else {
    $controller = new ErrorController($container);
    return $controller->notFound();
}
exit;