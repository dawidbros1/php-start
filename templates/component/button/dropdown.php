<button type="button" class="btn fw-bold d-grid mt-2
    <?=$params['btn-color'] ?? "btn-danger"?>
    <?=$params['col'] ?? "col-3"?>
    <?=$params['offset'] ?? "offset-1"?>
    <?=$params['text-color'] ?? "text-white"?>
    <?=$params['class'] ?? ''?>"

    data-bs-toggle="collapse" data-bs-target="<?=$params['target']?>" aria-expanded="false">
    <?=$params['text'] ?? 'USUŃ'?>
</button>