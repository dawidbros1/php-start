<?php

declare (strict_types = 1);

use App\Helper\Session;
?>

<?php $medium = $params['medium']?>

<div class = "container">
    <div id = "deleteBox">
        <div>Czy jesteś pewny, że chcesz usunąć medium społecznościowe: <?=$medium->name?></div>
    </div>
</div>
