<?php

declare (strict_types = 1);

session_start();

require_once 'src/Utils/debug.php';
require_once 'vendor/autoload.php';
$configuration = require_once 'config/config.php';

use App\Controller\AbstractController;
use App\Controller\AuthController;
use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Helper\Request;

$request = new Request($_GET, $_POST, $_SERVER);

try {
    AbstractController::initConfiguration($configuration);

    // Jakiś IF jaki kontroler wybrać

    (new AuthController($request))->run();
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