<?php

declare (strict_types = 1);

$name = $params['show'];
$value = $params['value'] ?? "id";
$key = key($params['selected']);
$search = $params['selected'][$key];

?>

<div class="d-flex flex-wrap <?=$params['class'] ?? ''?> <?=$params['col'] ?? 'col-12'?>">
    <?php if (array_key_exists('label', $params)): ?>
        <div class = "col-12"><label class="labels"><?=$params['label']?></label></div>
    <?php endif;?>

    <div class = "d-flex col-12">
        <span class="input-group-text bg-primary"></span>
            <select class="form-select" name = "<?=$params['name']?>">
                <?php foreach ($params['options'] as $item): ?>
                    <option value="<?=$item->$value?>" <?=$search == $item->$key ? "selected" : ""?>><?=$item->$name?></option>
                <?php endforeach;?>
            </select>
        <span class="input-group-text bg-primary"></span>
    </div>
</div>
