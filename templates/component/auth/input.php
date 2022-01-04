<?php

declare (strict_types = 1);

use App\Helper\Session;

?>

<div class="input-group <?=$params['class'] ?? ''?>">

    <?php if (array_key_exists('label', $params)): ?>
        <div class = "col-12"><label class="labels"><?=$params['label']?></label></div>
    <?php endif;?>

    <?php if (array_key_exists('prefix', $params)): ?>
        <span class="input-group-text bg-primary"></span>
    <?php endif;?>

    <input type="<?=$params['type']?>" name="<?=$params['name']?>" class="form-control" placeholder="<?=$params['placeholder'] ?? ""?>"
        value="<?=$params['value'] ?? ""?>">
</div>