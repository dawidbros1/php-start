<?php

declare (strict_types = 1);

use App\Helper\Session;

$mt = $params['mt'] ?? "mt-2";

?>

<div class="input-group <?=$mt?> <?=$params['class'] ?? ''?>">
    <?php if (array_key_exists('label', $params)): ?>
        <div class = "col-12"><label class="labels"><?=$params['label']?></label></div>
    <?php endif;?>

    <?php $array = ['type', 'name', 'placeholder', 'disabled', 'value']?>

    <span class="input-group-text bg-primary"></span>
    <input class="form-control"
        <?php foreach ($array as $name): ?>
            <?php if (array_key_exists($name, $params)): ?>
                <?=$name . "=" . "'" . $params[$name] . "'";?>
            <?php endif;?>
        <?php endforeach;?>
    >
    <span class="input-group-text bg-primary"></span>
</div>