<button type="button" class="btn fw-bold d-grid text-light btn-primary <?=$styles?>">
    <div data-bs-toggle="collapse" data-bs-target=".btn1,.btn2,.<?=$params['target']?>" class="btn1 show">
        <?=$params['text'][0]?>
    </div>

    <div data-bs-toggle="collapse" data-bs-target=".btn2,.btn1,.<?=$params['target']?>" class="<?=$params['target']?> collapse btn2">
        <?=$params['text'][1]?>
    </div>
</button>