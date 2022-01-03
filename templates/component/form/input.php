<?php

declare (strict_types = 1);

use App\Helper\Session;

?>

<div class="<?=$params['class'] ?? ''?>">

    <?php if ($params['label'] ?? null == true): ?>
        <div class = "col-12"><label class="labels"><?=$params['label']?></label></div>
    <?php endif;?>

    <input type="<?=$params['type'] ?? "text"?>" name="<?=$params['name']?>" class="form-control" placeholder="<?=$params['placeholder'] ?? ""?>"
        value="<?=$params['value'] ?? ""?>">
</div>
