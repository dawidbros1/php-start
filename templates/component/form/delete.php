<div class="collapse border-top <?=$params['class'] ?? ""?>">
    <p class = "text-center fw-bold"> Czy jesteś pewien, że chcesz usunąć wybrany element? </p>

    <form class = "d-flex" action = "<?=$params['action'] ?? ""?>" method = "POST">
        <input type = "hidden" name = "id" value = "<?=$params['id'] ?? 0?>">
        <button data-bs-toggle="collapse" data-bs-target="<?=$params['target']?>" class="btn btn-success col-5 fw-bold" type = "button"> Nie </button>
        <button class="btn btn-danger col-5 offset-2 fw-bold" type = "submit"> Tak </button>
    </form>
</div>