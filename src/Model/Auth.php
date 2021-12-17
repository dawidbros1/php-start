<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;

class Auth
{
    public static function isBusyEmail($email, ?array $emails)
    {
        if ($emails != null) {
            if ($busy = in_array($email, $emails)) {
                Session::set("error:email:unique", "Podany adres email jest zajęty");
            }
        }

        return $busy ?? false;
    }
}
