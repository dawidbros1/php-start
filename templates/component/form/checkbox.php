<div class="form-check <?=$styles?>">
    <input class="form-check-input" type="checkbox" id = "<?=$params['id']?>" name = "<?=$params['name']?>"
        <?php if ($params['checked'] ?? null == 1) {echo 'checked';}?>
    >
    <label class="form-check-label" for="<?=$params['id'] ?? ""?>">
        <?=$params['label']?>
    </label>
</div>
