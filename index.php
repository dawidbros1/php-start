<?php

declare (strict_types = 1);

ini_set("session.gc_maxlifetime", '31536000');
ini_set('session.cookie_lifetime', '31536000');
ini_set('session.gc_probability', '1');
ini_set('session.gc_divisor', '1');

session_start();

require_once 'src/Utils/debug.php';
require_once 'vendor/autoload.php';
require_once 'recaptchalib.php';

$config = require_once 'config/config.php';
$route = require_once 'routes/routes.php';

use App\Controller\Controller;
use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Helper\Request;

$request = new Request($_GET, $_POST, $_SERVER, $_FILES);

try {
    Controller::initConfiguration($config, $route);
    $type = $request->getParam('type', 'general');
    $controller = "\App\Controller\\" . ucfirst($type) . "Controller";
    (new $controller($request))->run();

} catch (ConfigurationException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo 'Problem z aplikacją, proszę spróbować za chwilę.';
} catch (AppException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>' . $e->getMessage() . '</h3>';
} catch (\Throwable $e) {
    echo '<h1>Wystąpił błąd w aplikacji </h1>';
    dump($e);
}
