<?php

declare (strict_types = 1);

?>

<div class="input-group mt-2 <?=$params['class'] ?? ''?>">
    <?php if (array_key_exists('label', $params)): ?>
        <div class = "col-12"><label class="labels"><?=$params['label']?></label></div>
    <?php endif;?>

    <span class="input-group-text bg-primary"></span>
    <select class="form-select" name = "<?=$params['name']?>">
        <?php foreach ($params['options'] as $item): ?>
            <option value="<?=$item->id?>" <?=$params['search'] == $item->id ? "selected" : ""?>><?=$item->name?></option>
        <?php endforeach;?>
    </select>
    <span class="input-group-text bg-primary"></span>
</div>
