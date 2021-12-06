<?php

declare (strict_types = 1);

session_start();

require_once 'src/Utils/debug.php';
require_once 'vendor/autoload.php';
$configuration = require_once 'config/config.php';
$routing = require_once 'routes/routes.php';
$rules = require_once 'rules/rules.php';

use App\Controller\AbstractController;
use App\Controller\AuthController;
use App\Controller\GeneralController;
use App\Controller\UserController;
use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Helper\Request;
use App\Validator\AbstractValidator;

$request = new Request($_GET, $_POST, $_SERVER);

try {
    AbstractController::initConfiguration($configuration, $routing);
    AbstractValidator::initConfiguration($rules);

    $type = $request->getParam('type', 'general');

    if ($type == "auth") {(new AuthController($request))->run();} //
    else if ($type == "user") {(new UserController($request))->run();} //
    else if ($type == "general") {(new GeneralController($request))->run();} //
    else {(new GeneralController($request))->run();} //

} catch (ConfigurationException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo 'Problem z aplikacją, proszę spróbować za chwilę.';
} catch (AppException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>' . $e->getMessage() . '</h3>';
} catch (\Throwable $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    dump($e);
}