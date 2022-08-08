<?php

declare (strict_types = 1);

namespace App\Helper;

class CheckBox
{
    public static function get($checkbox)
    {
        if ($checkbox) {return 1;} else {return 0;}
    }
}
