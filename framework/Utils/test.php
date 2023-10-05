<?php

use Phantom\RedirectToRoute;

return 0;

dump("TEST OPEN");

$redirectToRoute = new RedirectToRoute($route, 'authorization', [
   'email' => "test",
   'name' => "Adam"
]);

dump($redirectToRoute->redirect());

die();
