<button type="button" class="btn fw-bold d-grid text-light btn-primary <?=$styles?>" data-bs-toggle="collapse" data-bs-target=".btn1,.btn2,.<?=$params['target']?>">
    <div class="btn1 show">
        <?=$params['text'][0]?>
    </div>

    <div class = "btn2 collapse">
        <?=$params['text'][1]?>
    </div>
</button>