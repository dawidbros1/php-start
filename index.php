<?php

declare (strict_types = 1);

ini_set("session.gc_maxlifetime", '31536000');
ini_set('session.cookie_lifetime', '31536000');
ini_set('session.gc_probability', '1');
ini_set('session.gc_divisor', '1');

session_start();

require_once 'framework/src/Utils/debug.php';
require_once 'framework/recaptchalib.php';
require_once 'vendor/autoload.php';

$config = require_once 'config/config.php';

$location = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'];
$folder = $config->get('project.folder');
if ($folder != "") {
    $location = $location . "/" . $folder;
}

$route = require_once 'routes/routes.php'; // variable $location is require

use Phantom\Controller\Controller;
use Phantom\Exception\AppException;
use Phantom\Exception\ConfigurationException;
use Phantom\Helper\Request;
use Phantom\View;

$request = new Request($_GET, $_POST, $_SERVER, $_FILES);

try {
    Controller::initConfiguration($config, $route);
    View::set(['location' => $location]);

    $type = $request->getParam('type', 'general');
    $phantom = "\Phantom\Controller\\" . ucfirst($type) . "Controller";
    $app = "\App\Controller\\" . ucfirst($type) . "Controller";

    if (class_exists($app)) {
        (new $app($request))->run();
    } else if (class_exists($phantom)) {
        (new $phantom($request))->run();
    } else {
        dump("TODO [index.php]: Controller [" . $type . "] doen't exists");
        //TODO Controller doen't exists
    }

} catch (ConfigurationException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo 'Problem z aplikacją, proszę spróbować za chwilę.';
} catch (AppException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>' . $e->getMessage() . '</h3>';
} catch (\Throwable$e) {
    echo '<h1>Wystąpił błąd w aplikacji </h1>';
    dump($e);
}
