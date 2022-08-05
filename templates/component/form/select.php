<?php

declare (strict_types = 1);

$property = $params['show'];
$value = $params['value'] ?? "id";
$key = key($params['selected']);
$search = $params['selected'][$key];
?>

<div class="d-flex flex-wrap <?=$styles?>">
    <div class = "col-12"><label class="labels"><?=$params['label']?></label></div>

    <div class = "d-flex col-12">
        <span class="input-group-text bg-primary"></span>
            <select class="form-select" name = "<?=$params['name']?>">
                <?php foreach ($params['options'] as $item): ?>
                    <option value="<?=$item->$value?>" <?=$search == $item->$key ? "selected" : ""?>><?=$item->$property?></option>
                <?php endforeach;?>
            </select>
        <span class="input-group-text bg-primary"></span>
    </div>
</div>
