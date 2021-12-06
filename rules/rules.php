<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Model\Rules;

$rules = new Rules();
$rules->create('username', ['min' => 6, "max" => 16]);
$rules->create('password', ['min' => 6, "max" => 16]);

return $rules->get();