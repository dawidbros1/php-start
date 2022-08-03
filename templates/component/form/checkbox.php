<div class="form-check mt-2 <?=$params['class'] ?? ""?>">
    <input class="form-check-input" type="checkbox"
        id = "<?=$params['id'] ?? ""?>"
        name = "<?=$params['name']?>"
        <?php if ($params['checked'] ?? null == 1) {echo 'checked';}?>
    >
    <label class="form-check-label" for="<?=$params['id'] ?? ""?>">
        <?=$params['label']?>
    </label>
</div>
