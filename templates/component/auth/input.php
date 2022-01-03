<?php

declare (strict_types = 1);

use App\Helper\Session;

?>

<div class="input-group <?=$params['class'] ?? ''?>">
    <span class="input-group-text bg-primary"></span>

    <input type="<?=$params['type']?>" name="<?=$params['name']?>" class="form-control" placeholder="<?=$params['placeholder'] ?? ""?>"
        value="<?=$params['value'] ?? ""?>">
</div>