<?php

declare (strict_types = 1);

use App\Helper\Session;

if ($message = Session::getNextClear('success')) {
    echo '<div class="alert alert-success py-2 text-center">
        ' . $message . '
    </div>
  ';
}

if ($message = Session::getNextClear('error')) {
    echo '<div class="alert alert-danger py-2 text-center">
        ' . $message . '
    </div>
  ';
}
